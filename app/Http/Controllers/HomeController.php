<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        $slide_images = Slider::where('for', 'web')->select('image')->pluck('image');

        $pupolar = $products->sortByDesc('likes')->take(12);
        $bestselling = $products->sortByDesc('sales_count')->take(12);
        $most_visited = $products->sortByDesc('created_at')->take(12);
        $amazing_discounts = $products->whereIn('discount_type', ['public_percent', 'public_constant'])->where('discount_expire', '>=', now())->where('discount_begin', '<=', now())->where('discounted_price', '>', 0)->take(12);
        $str_keywords = $categories->pluck('name')->concat($products->pluck('name'));
        $str_keywords = $str_keywords->implode(', ');

        return view('home', compact('categories', 'pupolar', 'bestselling', 'most_visited', 'amazing_discounts', 'str_keywords', 'slide_images'));
    }

    public function rules()
    {
        return view('rules');
    }

    public function about()
    {
        return view('about');
    }
}
