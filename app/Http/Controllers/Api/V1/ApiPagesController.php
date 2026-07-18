<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Slider;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Version;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiPagesController extends Controller
{
    public function home()
    {
        $categories = Category::all();
        $products = Product::all();
        $slideImages = Slider::where('for', 'mobile')->select('image')->pluck('image');

        $pupolar = $products->sortByDesc('likes')->take(12)->values();
        $bestselling = $products->sortByDesc('sales_count')->take(12)->values();
        $most_visited = $products->sortByDesc('created_at')->take(12)->values();
        $amazing_discounts = $products->whereIn('discount_type', ['public_percent', 'public_constant'])->where('discount_expire', '>=', now())->where('discount_begin', '<=', now())->where('discounted_price', '>', 0)->take(12)->values();

        return response()->json([
            'message' => 'success',
            'body' => [
                'categories' => $categories,
                'pupolar' => $pupolar,
                'bestselling' => $bestselling,
                'most_visited' => $most_visited,
                'amazing_discounts' => $amazing_discounts,
                'slideImages' => $slideImages,
            ]
        ]);
    }

    public function nuts()
    {
        $combinations = Product::where('other', 'nuts')->get();
        $postal_info = Helper::calc_base_postal_price(Auth::user());

        return response()->json([
            'message' => 'success',
            'body' => [
                'combinations' => $combinations,
                'postal_info' => $postal_info,
                'auth_api_pay' => Auth::user()->auth_api_pay
            ]
        ]);
    }

    public function app_version()
    {
        $version = Version::first();

        return response()->json([
            'message' => 'success',
            'version' => $version
        ]);
    }
}
