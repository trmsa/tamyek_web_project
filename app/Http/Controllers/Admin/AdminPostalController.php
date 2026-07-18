<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Postal;
use App\Models\Province;
use Illuminate\Http\Request;

class AdminPostalController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        $cities = City::all();
        $postals = Postal::all();

        return view('admin.postal.index', compact('provinces', 'cities', 'postals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'base_price' => 'required|numeric',
            'many_weight' => 'required|numeric',
            'min_price_free_postal' => 'nullable|numeric',
            'nears_price' => 'nullable|array',
            'nears_price.*' => 'required|numeric',
            'big_cities_price' => 'nullable|array',
            'big_cities_price.*' => 'required|numeric',
            'self_province_price' => 'nullable|array',
            'self_province_price.*' => 'required|numeric',
            'self_city_price' => 'nullable|array',
            'self_city_price.*' => 'required|numeric'
        ]);
        $self_province_id = array_keys($request->self_province_price)[0];
        $self_province_price = array_values($request->self_province_price)[0];
        $self_city_id = array_keys($request->self_city_price)[0];
        $self_city_price = array_values($request->self_city_price)[0];
        Postal::truncate();
        Postal::create([
            'type' => 'base',
            'province_price' => $request->base_price
        ]);
        Postal::create([
            'type' => 'many_weight',
            'province_price' => $request->many_weight
        ]);
        if($request->min_price_free_postal > 0) {
            Postal::create([
                'type' => 'min_price_free_postal',
                'province_price' => $request->min_price_free_postal
            ]);
        }
        Postal::create([
            'type' => 'self_province',
            'province_id' => $self_province_id,
            'province_price' => $self_province_price
        ]);
        Postal::create([
            'type' => 'self_city',
            'city_id' => $self_city_id,
            'city_price' => $self_city_price
        ]);

        foreach($request->big_cities_price as $key => $price) {
            Postal::create([
                'type' => 'big_city',
                'city_id' => $key,
                'city_price' => $price
            ]);
        }

        foreach($request->nears_price as $key => $price) {
            Postal::create([
                'type' => 'near',
                'province_id' => $key,
                'province_price' => $price
            ]);
        }

        session()->flash('message_success', 'هزینه‌های پستی با موفقیت ویرایش شد');
        return back();
    }
}
