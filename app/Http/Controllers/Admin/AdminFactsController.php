<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\City;
use App\Models\User;
use App\Models\Province;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class AdminFactsController extends Controller
{
    public function index()
    {
        return view('admin.facts.index');
    }

    public function users_city(Request $request)
    {
        $provinces_count = null;
        $cities_count = null;
        if($request->province == 'desc') {
            $provinces_count = User::selectRaw("COUNT(province_id) AS province_count, province_id")->orderBy('province_count', 'desc')->groupBy('province_id')->get();
        }elseif($request->province == 'asc') {
            $provinces_count = User::selectRaw("COUNT(province_id) AS province_count, province_id")->orderBy('province_count', 'asc')->groupBy('province_id')->get();
        }elseif($request->city == 'desc') {
            $cities_count = User::selectRaw("COUNT(city_id) AS city_count, city_id")->orderBy('city_count', 'desc')->groupBy('city_id')->get();
        }elseif($request->city == 'asc') {
            $cities_count = User::selectRaw("COUNT(city_id) AS city_count, city_id")->orderBy('city_count', 'asc')->groupBy('city_id')->get();
        }
        $provinces = Province::all();
        $cities = City::all();

        return view('admin.facts.users_city', compact('provinces_count', 'cities_count', 'provinces', 'cities'));
    }

    public function users_sales(Request $request)
    {
        $sales = Transaction::where('status', 1)->whereNotNull('date_payment')->get();
        $users_id = $sales->pluck('user_id')->unique();
        $users = User::find($users_id);
        foreach($users as $user) {
            $user_buies = $sales->where('user_id', $user->id);
            $user->buies_count = $user_buies->count();
            $user->total_price_buies = $user_buies->sum('final_price_products');
        }
        if($request->filter == 'count_asc') {
            $users = $users->sortBy('buies_count');
        }elseif($request->filter == 'price_asc') {
            $users = $users->sortBy('total_price_buies');
        }elseif($request->filter == 'price_desc') {
            $users = $users->sortByDesc('total_price_buies');
        }else {
            $users = $users->sortByDesc('buies_count');

        }
        $cities_id = $users->pluck('city_id')->unique();
        $provinces = Province::all();
        $cities = City::find($cities_id);

        return view('admin.facts.users_sales', compact('users', 'provinces', 'cities'));
    }

    public function sales_index(Request $request)
    {
        $request->validate([
            'begin_date_d' => 'nullable|numeric|min:1|max:31',
            'begin_date_m' => 'nullable|numeric|min:1|max:12',
            'begin_date_y' => 'nullable|numeric',
            'end_date_d' => 'nullable|numeric|min:1|max:31',
            'end_date_m' => 'nullable|numeric|min:1|max:12',
            'end_date_y' => 'nullable|numeric',
        ]);
        $sales = Transaction::where('status', 1)->whereNotNull('date_payment');
        $users_sales = Transaction::where('status', 1)->whereNotNull('date_payment');
        if($request->begin_date_y && $request->begin_date_m && $request->begin_date_d) {
            $date_begin = Helper::jalali_to_gregorian($request->begin_date_y, $request->begin_date_m, $request->begin_date_d, '-')." 00:00:00";
            $sales = $sales->where('date_payment', '>=', $date_begin);
            $users_sales = $users_sales->where('date_payment', '>=', $date_begin);
        }
        if($request->end_date_y && $request->end_date_m && $request->end_date_d) {
            $date_end = Helper::jalali_to_gregorian($request->end_date_y, $request->end_date_m, $request->end_date_d, '-')." 23:59:59";
            $sales = $sales->where('date_payment', '<=', $date_end);
            $users_sales = $users_sales->where('date_payment', '<=', $date_end);
        }
        $sales = $sales->selectRaw('SUM(final_price_products) as total_products, SUM(amount) as total_amount, SUM(postal_price) as total_postal')->first();
        $users_sales = $users_sales->orderBy('date_payment', 'asc')->get();
        $users_id = $users_sales->pluck('user_id')->unique();
        $users = User::find($users_id);
        return view('admin.facts.sales_index', compact('sales', 'users_sales', 'users'));
    }

    public function products_inventory(Request $request)
    {
        $products = Product::select('id', 'name', 'inventory', 'unit');

        if($request->filter == 'desc') {
            $products = $products->orderBy('inventory', 'desc');
        }else {
            $products = $products->orderBy('inventory', 'asc');
        }

        $products = $products->get();
        $finished = $products->where('inventory', '<=', 0)->count();
        $finishing = $products->whereBetween('inventory', [0, 20])->count();

        return view('admin.facts.products_inventory', compact('products', 'finished', 'finishing'));
    }

    public function products_sales(Request $request)
    {
        $products = new Product();

        if($request->filter == 'count_asc') {
            $products = $products->orderBy('sales_count', 'asc');
        }elseif($request->filter == 'price_asc') {
            $products = $products->orderBy('total_price_sales', 'asc');
        }elseif($request->filter == 'price_desc') {
            $products = $products->orderBy('total_price_sales', 'desc');
        }else {
            $products = $products->orderBy('sales_count', 'desc');
        }

        $products = $products->get();
        return view('admin.facts.products_sales', compact('products'));
    }

    public function products_likes(Request $request)
    {
        $products = Product::select('id', 'name', 'likes', 'likes_count');

        if($request->filter == 'likes_asc') {
            $products = $products->orderBy('likes', 'asc');
        }elseif($request->filter == 'likes_count_asc') {
            $products = $products->orderBy('likes_count', 'asc');
        }elseif($request->filter == 'likes_count_desc') {
            $products = $products->orderBy('likes_count', 'desc');
        }else {
            $products = $products->orderBy('likes', 'desc');
        }

        $products = $products->get();
        return view('admin.facts.products_likes', compact('products'));
    }
}
