<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function tiket()
    {
        $user = Auth::user();
        $tikets = Tiket::where('user_id', $user->id)->where('delete_user', 0)->orderBy('created_at', 'asc')->get();

        return view('tiket', compact('user', 'tikets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:10000',
            'attachment' => 'nullable|mimes:png,jpg,jpeg,pdf|max:2048'
        ]);

        $user = Auth::user();
        $path = null;

        if($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('uploads/tikets');
        }

        Tiket::create([
            'user_id' => $user->id,
            'owner' => 'user',
            'message' => $request->message,
            'attachment' => $path,
        ]);

        session()->flash('success_message', 'پیام شما با موفقیت ارسال شد. در اولین فرصت پاسخ شما را خواهیم داد');

        return back();
    }

    public function delete()
    {
        $user = Auth::user();
        $tikets = Tiket::where('user_id', $user->id)->where('read', 1)->get();
        foreach($tikets as $tiket) {
            if($tiket->attachment) {
                Storage::delete($tiket->attachment);
            }
        }
        Tiket::where('user_id', $user->id)->delete();

        session()->flash('message_success', 'گفتگوی شما با موفقیت حذف شد.');
        return back();
    }
}
