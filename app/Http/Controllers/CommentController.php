<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'like' => 'required|numeric|min:1|max:5',
            'text' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $products_id = Transaction::where('user_id', $user->id)->where('status', 1)->select('products')->pluck('products')->collapse()->pluck('product_id');
        $is_buyed = $products_id->contains($product_id);
        if(!$is_buyed) {
            session()->flash('message_danger', 'به دلیل اینکه هنوز این محصول را خرید نکرده‌اید، امکان ثبت نظر وجود ندارد.');
            return back();
        }

        $user_like = (int) $request->like;
        Comment::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
            'text' => $request->text,
            'like' => $user_like
        ]);

        session()->flash('message_success', 'نظر شما با موفقیت ذخیره شد و بعد از تایید منتشر می گردد');
        return back();
    }
}
