<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Postal;
use App\Models\Product;
use App\Models\Province;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with(['province', 'city'])->findOrFail(Auth::user()->id);

        return view('user.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $provinces = Cache::remember('provinces', 30 * 24 * 60 * 60, function () {
            return Province::all();
        });
        $cities = Cache::remember('cities', 30 * 24 * 60 * 60, function () {
            return City::all();
        });

        return view('user.edit', compact('user', 'provinces', 'cities'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'mobile' => 'required|numeric|digits:11|starts_with:09',
            'national_code' => 'nullable|numeric|digits:10',
            'birth_date_y' => 'nullable|numeric',
            'birth_date_m' => 'nullable|numeric',
            'birth_date_d' => 'nullable|numeric',
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'address' => 'required|string|',
            'postal_code' => 'required|string',
            'plaque' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric'
        ]);

        $user = Auth::user();
        $birth_date = [
            'y' => $request->birth_date_y,
            'm' => $request->birth_date_m,
            'd' => $request->birth_date_d,
        ];

        $sms_params = null;
        if ($user->mobile !== $request->mobile) {
            $code = rand(10000, 999999);
            $sms_params = [
                'CODE' => $code,
            ];
            Helper::sms($sms_params, $request->mobile, config('tamyek.sms_template.verify'));
            $sms_params['EXPIRE'] = time() + config('tamyek.expire_time_otp') + 5;
            $sms_params['NEW_MOBILE'] = $request->mobile;
            session()->flash('changed_mobile', '1');
            session()->flash('message_success', 'اطلاعات شما با موفقیت ذخیره شد. برای تغییر شماره تلفن همراه، کد تایید پیامک شده را وارد نمایید.');
        } else {
            session()->flash('message_success', 'اطلاعات شما با موفقیت ذخیره شد');
        }

        User::where('id', $user->id)->update([
            'name' => $request->name,
            'national_code' => $request->national_code,
            'birth_date' => $birth_date,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'plaque' => $request->plaque,
            'email' => $request->email,
            'phone' => $request->phone,
            'verify_sms' => $sms_params,
        ]);

        return back();
    }

    public function update_mobile(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $user = Auth::user();
        $code = (int) $request->code;
        $main_code = (int) $user->verify_sms['CODE'];
        $expire = (int) $user->verify_sms['EXPIRE'];
        $mobile = $user->verify_sms['NEW_MOBILE'];

        if ($code === $main_code && $expire >= time()) {
            User::where('id', $user->id)->update([
                'mobile' => $mobile,
                'verify_sms' => null
            ]);
            session()->flash('message_success', 'شماره تلفن همراه شما با موفقیت تغییر یافت');
        } else {
            session()->flash('message_danger', 'کد تایید منقضی یا اشتباه است');
        }

        return back();
    }

    public function show_shoping_cart()
    {
        $user = Auth::user();
        $products_id = $user->shoping_cart ? $user->shoping_cart : [];
        $products = Product::whereIn('id', $products_id)->get();
        $postal_info = Helper::calc_base_postal_price($user);
        if (!$user->name || !$user->city_id || !$user->address || !$user->postal_code) {
            session()->flash('message_danger', 'اطلاعات شما ناقص است لطفا ابتدا اطلاعات خود را تکمیل نمایید');
        }

        return view('shoping_cart', compact('products', 'postal_info'));
    }

    public function add_shoping_cart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
        ]);
        $user = Auth::user();
        $product_id = $request->product_id;
        $shoping_cart = $user->shoping_cart ? $user->shoping_cart : [];
        $exist = in_array($product_id, $shoping_cart);
        if ($exist) {
            session()->flash('message_success', 'این محصول را قبلا به سبد خریدتان اضافه نموده‌اید');
        } else {
            array_push($shoping_cart, $product_id);
            User::where('id', $user->id)->update([
                'shoping_cart' => array_values($shoping_cart),
            ]);
            session()->flash('message_success', 'به سبد خرید اضافه شد');
        }

        return back();
    }

    public function delete_shoping_cart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
        ]);
        $user = Auth::user();
        $product_id = $request->product_id;
        $shoping_cart = $user->shoping_cart ? $user->shoping_cart : [];
        $key = array_search($product_id, $shoping_cart);
        unset($shoping_cart[$key]);
        User::where('id', $user->id)->update([
            'shoping_cart' => array_values($shoping_cart),
        ]);
        session()->flash('message_success', 'محصول مورد نظر از سبد خرید شما حذف شد');

        return back();
    }

    public function favorits()
    {
        $user = Auth::user();
        $products_id = $user->favorits ? $user->favorits : [];
        $products = Product::whereIn('id', $products_id)->get();

        return view('favorits', compact('products'));
    }

    public function update_favorits(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
        ]);

        $product_id = $request->product_id;
        $user = Auth::user();
        $user_favorits = $user->favorits ? $user->favorits : [];
        $exist = array_search($product_id, $user_favorits);

        if ($exist === false) {
            array_push($user_favorits, $product_id);
        } else {
            unset($user_favorits[$exist]);
        }

        User::where('id', $user->id)->update([
            'favorits' => array_values($user_favorits),
        ]);

        return back();
    }

    public function records()
    {
        $user = Auth::user();
        $records = Transaction::where('user_id', $user->id)->where('status', 1)->whereNotNull('date_payment')->select('id', 'transaction_id', 'amount', 'final_price_products', 'postal_price', 'date_payment', 'date_send', 'shipment_code')->get();

        return view('user.records', compact('records'));
    }

    public function show_record(Request $request)
    {
        $request->validate([
            't_id' => 'required|numeric'
        ]);
        $user_id = Auth::user()->id;
        $record = Transaction::where('user_id', $user_id)->where('id', $request->t_id)->select('id', 'transaction_id', 'products')->first();

        return view('user.show_record', compact('record'));
    }
}
