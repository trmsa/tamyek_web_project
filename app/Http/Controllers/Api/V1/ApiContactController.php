<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tiket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ApiContactController extends Controller
{
    public function tiket()
    {
        $user = Auth::user();
        $tikets = Tiket::where('user_id', $user->id)->where('delete_user', 0)->orderBy('created_at', 'asc')->get(['id', 'owner', 'message', 'attachment', 'read', 'created_at']);

        return response()->json([
            'message' => 'success',
            'tikets' => $tikets
        ]);
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

        return response()->json([
            'message' => 'success'
        ]);
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

        return response()->json([
            'message' => 'success'
        ]);
    }
}
