<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use App\Models\Acomment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string',
            'order_by' => 'nullable|string',
            'word' => 'nullable|string',
        ]);
        $word = $request->word;
        $articles = Article::whereNotNull('published');
        if($request->category) {
            $articles = $articles->where('category', $request->category);
        }
        if($word) {
            $articles = $articles->where(function($q) use($word) {
                $q->where('title', 'like', "%$word%")->orWhere('content', 'like', "%$word%")->orderByRaw("CASE WHEN title='$word' then 1 else 2 end asc")->orderByRaw("CASE WHEN title like '%$word%' then 1 else 2 end asc");
            });
        }
        if($request->order_by == 'asc') {
            $articles = $articles->orderBy('published', 'asc');
        }elseif(!$word) {
            $articles = $articles->orderBy('published', 'desc');
        }

        $articles = $articles->paginate(50);
        $categories = Category::select('id', 'name')->get();

        return view('articles.index', compact('articles', 'categories'));
    }

    public function show($id)
    {
        $article = Article::where('id', $id)->whereNotNull('published')->firstOrFail();
        $article->increment('view_count');
        $categories = Category::select('id', 'name')->get();
        $comments = Acomment::where('article_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(20);
        if($article->category == 'public') {
            $products_related = Product::inRandomOrder()->take(20)->get();
        }else {
            $products_related = Product::where('category_id', $article->category)->inRandomOrder()->take(20)->get();
        }
        $articles_related = Article::where('category', $article->category)->where('id', '!=', $id)->inRandomOrder()->take(20)->get();
        return view('articles.show', compact('article', 'categories', 'comments', 'products_related', 'articles_related'));
    }

    public function comments(Request $request, $article_id)
    {
        $request->validate([
            'like' => 'required|numeric|min:1|max:5',
            'text' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $user_like = (int) $request->like;
        Acomment::create([
            'user_id' => $user->id,
            'article_id' => $request->article_id,
            'text' => $request->text,
            'like' => $user_like
        ]);

        session()->flash('message_success', 'نظر شما با موفقیت ذخیره شد و بعد از تایید منتشر می گردد');
        return back();
    }
}
