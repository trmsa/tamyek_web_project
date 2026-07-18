<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Nutrient;
use App\Models\Pnutrient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApiProductController extends Controller
{
    public function category()
    {
        $user = Auth::user();
        $categories = Category::all();
        return response()->json([
            'message' => 'success',
            'categories' => $categories,
            'count_cart' => $user->shoping_cart ? count($user->shoping_cart) : '0'
        ]);
    }

    public function get_product($id)
    {
        $product = Product::find($id);
        $relateds = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->inRandomOrder()->take(20)->get();
        $comments = Comment::where('product_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->with('user:id,name')->paginate(10);
        $pnutrients = Pnutrient::where('product_id', $id)->get();
        return response()->json([
            'message' => 'success',
            'body' => [
                'product' => $product,
                'relateds' => $relateds,
                'comments' => $comments,
                'pnutrients' => $pnutrients,
                'user' => Auth::user(),
            ]
        ]);
    }

    public function names()
    {
        $products_name = Product::select('name')->orderByRaw("length(name) asc")->pluck('name');
        $nutrients = Cache::remember('nutrients', 30 * 24 * 60 * 60, function () {
            return Nutrient::all();
        });

        return response()->json([
            'message' => 'success',
            'body' => [
                'products_name' => $products_name,
                'nutrients' => $nutrients
            ]
        ]);
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
        if ($request->base == 'nutrient') {
            $nutrients_id = $request->nutrients_id ? $request->nutrients_id : [];

            if (count($nutrients_id)) {
                $products_id = Pnutrient::whereIn('nutrient_id', $nutrients_id)->select('product_id')->groupBy('product_id')->havingRaw('COUNT(DISTINCT nutrient_id) = ?', [count($nutrients_id)])->pluck('product_id');
                $products = Product::whereIn('id', $products_id)->select('id', 'name', 'images')->get();
            } else {
                return response()->json([
                    'message' => 'not selected nutrient'
                ], 201);
            }
        } else {


            $products = Product::where('name', 'like', "%$word%")->select('id', 'name', 'images')->orderByRaw("length(name) asc")->get();
        }
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'not found'
            ], 201);
        }

        return response()->json([
            'message' => 'success',
            'products' => $products
        ]);
    }

    public function discounts()
    {
        $discounts = Product::whereIn('discount_type', ['public_percent', 'public_constant'])->where('discount_expire', '>=', now())->where('discount_begin', '<=', now())->where('discounted_price', '>', 0)->get();

        return response()->json([
            'message' => 'success',
            'discounts' => $discounts
        ]);
    }

    public function bestsellings()
    {
        $bestsellings = Product::orderBy('sales_count', 'desc')->take(50)->get();

        return response()->json([
            'message' => 'success',
            'bestsellings' => $bestsellings
        ]);
    }

    public function populars()
    {
        $populars = Product::orderBy('likes', 'desc')->take(50)->get();

        return response()->json([
            'message' => 'success',
            'populars' => $populars
        ]);
    }

    public function all()
    {
        $products = Product::all();

        return response()->json([
            'message' => 'success',
            'products' => $products
        ]);
    }

    public function most_visiteds()
    {
        $most_visiteds = Product::orderBy('created_at', 'desc')->take(50)->get();

        return response()->json([
            'message' => 'success',
            'most_visiteds' => $most_visiteds
        ]);
    }

    public function category_products($id)
    {
        $category = Category::find($id);
        $category_products = Product::where('category_id', $id)->get();

        return response()->json([
            'message' => 'success',
            'body' => [
                'category' => $category,
                'category_products' => $category_products
            ]
        ]);
    }

    public function send_comment(Request $request)
    {
        $request->validate([
            'like' => 'required|numeric|min:1|max:5',
            'product_id' => 'required|numeric',
            'text' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $products_id = Transaction::where('user_id', $user->id)->where('status', 1)->select('products')->pluck('products')->collapse()->pluck('product_id');
        $is_buyed = $products_id->contains($request->product_id);
        if (!$is_buyed) {
            $message = 'not buyed';
        } else {
            $user_like = (int) $request->like;
            Comment::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'text' => $request->text,
                'like' => $user_like
            ]);
            $message = 'success';
        }

        return response()->json([
            'message' => $message
        ]);
    }

    public function comments($id)
    {
        $product = Product::find($id);
        $comments = Comment::where('product_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->with('user:id,name')->paginate(20);

        return response()->json([
            'message' => 'success',
            'body' => [
                'product' => $product,
                'comments' => $comments
            ]
        ]);
    }
}
