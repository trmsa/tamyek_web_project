<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Tiket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminTiketController extends Controller
{
    public function index()
    {
        $tikets = Tiket::where('delete_user', 0)->where('delete_admin', 0)->select('user_id', 'owner', 'read')->orderBy('created_at', 'desc')->get();
        $users = [];
        if($tikets->isNotEmpty()) {
            $users_id = $tikets->pluck('user_id')->unique();
            $sorted_users_id = $tikets->where('owner', 'user')->where('read', 0)->pluck('user_id')->unique()->toArray();
            $users = User::whereIn('id', $users_id)->get();
            if(count($sorted_users_id) > 0) {
                $users = $users->sortBy(
                    fn($user) => in_array($user->id, $sorted_users_id)
                        ? array_search($user->id, $sorted_users_id)
                        : count($sorted_users_id) + $user->id
                );
                $users = $users->values();
            }
        }

        return view('admin.tikets.index', compact('tikets', 'users'));
    }

    public function show($id) {
        $user = User::findOrFail($id);
        $tikets = Tiket::where('user_id', $id)->where('delete_user', 0)->where('delete_admin', 0)->orderBy('created_at', 'asc')->get();
        Tiket::where('user_id', $id)->where('delete_user', 0)->where('delete_admin', 0)->update([
            'read' => 1
        ]);
        $attachments = $tikets->whereNotNull('attachment');

        return view('admin.tikets.show', compact('user', 'tikets', 'attachments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:10000',
            'user_id' => 'required|numeric',
        ]);

        Tiket::create([
            'user_id' => $request->user_id,
            'owner' => 'admin',
            'message' => $request->message,
        ]);

        session()->flash('success_message', 'پیام با موفقیت ارسال شد.');

        return back();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $tikets = Tiket::where('user_id', $user->id)->where('read', 1)->get();
        foreach($tikets as $tiket) {
            if($tiket->attachment) {
                Storage::delete([$tiket->attachment]);
            }
        }
        Tiket::where('user_id', $user->id)->where('read', 1)->where('delete_user', 1)->delete();
        Tiket::where('user_id', $user->id)->where('read', 1)->where('delete_user', 0)->update([
            'delete_admin' => 1
        ]);

        session()->flash('message_success', 'گفتگوی با موفقیت حذف شد.');
        return back();
    }
}
