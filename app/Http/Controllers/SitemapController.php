<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        return response()->view('sitemap.index')->header('Content-Type', 'text/xml');
    }

    public function statics()
    {
        return response()->view('sitemap.statics')->header('Content-Type', 'text/xml');
    }

    public function products()
    {
        $products = Product::select('id', 'name', 'images', 'updated_at')->get();
        return response()->view('sitemap.products', compact('products'))->header('Content-Type', 'text/xml');
    }

    public function category()
    {
        $categories = Category::all();
        return response()->view('sitemap.category', compact('categories'))->header('Content-Type', 'text/xml');
    }

    public function articles()
    {
        $articles = Article::select('id', 'title', 'images', 'updated_at')->get();
        return response()->view('sitemap.articles', compact('articles'))->header('Content-Type', 'text/xml');
    }
}
