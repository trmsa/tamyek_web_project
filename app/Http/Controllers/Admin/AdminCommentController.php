<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $comments = new Comment();
        if($request->status === '0') {
            $comments = $comments->where('status', 0);
        }elseif($request->status === '1') {
            $comments = $comments->where('status', 1);
        }
        if($request->product_id) {
            $comments = $comments->where('product_id', $request->product_id);
        }

        $comments = $comments->orderBy('created_at', 'desc')->paginate(50);
        $products = Product::select('id', 'name')->orderBy('category_id', 'asc')->get();

        return view('admin.comments.index', compact('comments', 'products'));
    }

    public function show($id)
    {
        $comment = Comment::findOrFail($id);
        $product = Product::findOrFail($comment->product_id);

        return view('admin.comments.show', compact('comment', 'product'));
    }

    public function confirm($id)
    {
        $comment = Comment::findorFail($id);
        $product = Product::findOrFail($comment->product_id);
        if($comment->status == 0) {
            Comment::where('id', $id)->update([
                'status' => 1
            ]);
            $old_likes_count = (int) $product->likes_count;
            $old_likes = (double) $product->likes;
            $new_likes_count = $old_likes_count + 1;
            $new_likes = round(($old_likes * $old_likes_count + (int) $comment->like) / $new_likes_count, 1);

            Product::where('id', $product->id)->update([
                'likes' => $new_likes,
                'likes_count' => $new_likes_count
            ]);
        }

        session()->flash('message_success', 'کامنت مورد نظر تایید شد.');
        return back();
    }

    public function answer(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string'
        ]);
        $comment = Comment::findorFail($id);
        $product = Product::findOrFail($comment->product_id);
        if($comment->status == 0) {
            Comment::where('id', $id)->update([
                'status' => 1,
                'answer' => $request->answer
            ]);
            $old_likes_count = (int) $product->likes_count;
            $old_likes = (double) $product->likes;
            $new_likes_count = $old_likes_count + 1;
            $new_likes = round(($old_likes * $old_likes_count + (int) $comment->like) / $new_likes_count, 1);

            Product::where('id', $product->id)->update([
                'likes' => $new_likes,
                'likes_count' => $new_likes_count
            ]);
        }else {
            Comment::where('id', $id)->update([
                'answer' => $request->answer
            ]);
        }

        session()->flash('message_success', 'پاسخ شما ثبت و کامنت مورد نظر تایید شد.');
        return back();
    }

    public function delete($id)
    {
        Comment::where('id', $id)->delete();
        session()->flash('success_message', 'کامنت مورد نظر با موفقیت حذف شد.');
        
        return back();
    }
}
