<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Nutrient;
use App\Models\Pnutrient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        $products_name = Product::select('name')->orderByRaw("length(name) asc")->pluck('name');
        $products = Product::orderBy('category_id', 'asc')->paginate(50);
        $categories_id = $products->pluck('category_id')->unique();
        $product_categories = $categories->whereIn('id', $categories_id);
        $nutrients = Cache::remember('nutrients', 30 * 24 * 60 * 60, function () {
            return Nutrient::all();
        });
        return view('products', compact('categories', 'products', 'products_name', 'product_categories', 'nutrients'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product_relateds = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->inRandomOrder()->take(20)->get();
        $article_relateds = Article::where('category', $product->category_id)->inRandomOrder()->take(20)->get();
        $comments = Comment::where('product_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(20);
        $product_nutrients = Pnutrient::where('product_id', $id)->get();

        return view('product', compact('product', 'product_relateds', 'comments', 'product_nutrients', 'article_relateds'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'word' => 'nullable|string',
            'base' => 'nullable|string',
            'nutrients_id' => 'nullable|array',
            'nutrients_id.*' => 'required|numeric'
        ]);
        $word = $request->word;
        $base = $request->base;
        $nutrients_id = $request->nutrients_id;
        $products = null;
        if ($base == 'nutrient') {
            if ($nutrients_id) {
                $products_id = Pnutrient::whereIn('nutrient_id', $nutrients_id)->select('product_id')->groupBy('product_id')->havingRaw('COUNT(DISTINCT nutrient_id) = ?', [count($nutrients_id)])->pluck('product_id');
                $products = Product::whereIn('id', $products_id)->paginate(12);
            } else {
                session()->flash('message_danger', 'مغذی مورد نظر خود را انتخاب نمایید');
            }
        } elseif ($word) {
            $products = Product::select('id', 'name', 'images')->where('name', 'like', "%$word%")->orderByRaw("length(name) asc")->paginate(12);
        }

        $products_name = Product::select('name')->orderByRaw("length(name) asc")->pluck('name');
        $nutrients = Cache::remember('nutrients', 30 * 24 * 60 * 60, function () {
            return Nutrient::all();
        });

        return view('search', compact('products', 'products_name', 'base', 'word', 'nutrients_id', 'nutrients'));
    }

    public function discounts()
    {
        $products = Product::whereIn('discount_type', ['public_percent', 'public_constant'])->where('discount_expire', '>=', now())->where('discounted_price', '>', 0)->orderByRaw("`price` - `discounted_price` DESC")->get();

        return view('discounts', compact('products'));
    }

    public function category()
    {
        $categories = Category::get();

        return view('category', compact('categories'));
    }

    public function products_category($id)
    {
        $products = Product::where('category_id', $id)->get();
        $categories = Category::get();
        $products_name = Product::select('name')->orderByRaw("length(name) asc")->pluck('name');
        $product_category = $categories->firstWhere('id', $id);
        $nutrients = Cache::remember('nutrients', 30 * 24 * 60 * 60, function () {
            return Nutrient::all();
        });

        return view('products_category', compact('categories', 'products', 'products_name', 'product_category', 'nutrients'));
    }

    public function pupolar()
    {
        $products = Product::orderBy('likes', 'desc')->limit(50)->get();

        return view('pupolar', compact('products'));
    }

    public function bestselling()
    {
        $products = Product::orderBy('sales_count', 'desc')->limit(50)->get();

        return view('bestselling', compact('products'));
    }

    public function most_visited()
    {
        $products = Product::orderBy('created_at', 'desc')->limit(50)->get();

        return view('most_visited', compact('products'));
    }

    public function nuts()
    {
        $combinations = Product::where('other', 'nuts')->get();
        $postal_info = null;
        if (Auth::check()) {
            $postal_info = Helper::calc_base_postal_price(Auth::user());
        }

        return view('nuts', compact('combinations', 'postal_info'));
    }
}
