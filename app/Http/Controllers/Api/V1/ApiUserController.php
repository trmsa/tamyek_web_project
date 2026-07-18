<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\City;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Province;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApiUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provinces = Cache::remember('provinces', 30 * 24 * 60 * 60, function () {
            return Province::all();
        });
        $cities = Cache::remember('cities', 30 * 24 * 60 * 60, function () {
            return City::all();
        });
        $year = Helper::fa_date('%Y');

        return response()->json([
            'message' => 'success',
            'body' => [
                'user' => $user,
                'provinces' => $provinces,
                'cities' => $cities,
                'year' => $year
            ]
        ]);
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
            $message = 'mobile_changed';
        } else {
            $message = 'success';
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

        return response()->json([
            'message' => $message
        ]);
    }

    public function repeat_send_otp()
    {
        $user = Auth::user();
        $mobile = $user->verify_sms['NEW_MOBILE'];
        if ($mobile) {
            $code = rand(10000, 999999);
            $sms_params = [
                'CODE' => $code,
            ];
            Helper::sms($sms_params, $mobile, config('tamyek.sms_template.verify'));
            $sms_params['EXPIRE'] = time() + config('tamyek.expire_time_otp') + 5;
            $sms_params['CODE'] = $code;
            $sms_params['NEW_MOBILE'] = $mobile;
            User::where('id', $user->id)->update([
                'verify_sms' => $sms_params
            ]);
            $message = 'success';
        } else {
            $message = 'error';
        }

        return response()->json([
            'message' => $message
        ]);
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
            $message = 'success';
        } else {
            $message = 'expire';
        }

        return response()->json([
            'message' => $message
        ]);
    }

    public function count_cart()
    {
        $user = Auth::user();

        return response()->json([
            'count_cart' => $user->shoping_cart ? count($user->shoping_cart) : '0'
        ]);
    }

    public function add_cart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
        ]);
        $user = Auth::user();
        $product_id = $request->product_id;
        $shoping_cart = $user->shoping_cart ? $user->shoping_cart : [];
        $exist = in_array($product_id, $shoping_cart);
        if ($exist) {
            return response()->json(['message' => 'exists']);
        } else {
            array_push($shoping_cart, $product_id);
            User::where('id', $user->id)->update([
                'shoping_cart' => array_values($shoping_cart),
            ]);
        }

        return response()->json([
            'message' => 'success',
            'count_cart' => count($shoping_cart)
        ]);
    }

    public function favorits()
    {
        $user = Auth::user();
        $user_favorits = $user->favorits ? $user->favorits : [];
        $products = Product::whereIn('id', $user_favorits)->get();

        return response()->json([
            'message' => 'success',
            'favorits' => $products
        ]);
    }

    public function add_favorits($id)
    {
        $user = Auth::user();
        $user_favorits = $user->favorits ? $user->favorits : [];
        $exist = array_search($id, $user_favorits);
        if ($exist === false) {
            array_push($user_favorits, $id);
            $message = 'added';
        } else {
            unset($user_favorits[$exist]);
            $message = 'removed';
        }
        User::where('id', $user->id)->update([
            'favorits' => array_values($user_favorits),
        ]);
        $user = User::find($user->id);

        return response()->json([
            'message' => $message,
            'user' => $user
        ]);
    }

    public function remove_favorits($id)
    {
        $user = Auth::user();
        $user_favorits = $user->favorits ? $user->favorits : [];
        $exist = array_search($id, $user_favorits);
        unset($user_favorits[$exist]);
        User::where('id', $user->id)->update([
            'favorits' => array_values($user_favorits),
        ]);

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function records()
    {
        $user = Auth::user();
        $records = Transaction::where('user_id', $user->id)->where('status', 1)->whereNotNull('date_payment')->select('id', 'transaction_id', 'amount', 'final_price_products', 'postal_price', 'date_payment', 'date_send', 'shipment_code')->get();

        return response()->json([
            'message' => 'success',
            'records' => $records
        ]);
    }

    public function show_record(Request $request)
    {
        $request->validate([
            't_id' => 'required|numeric'
        ]);
        $user_id = Auth::user()->id;
        $record = Transaction::where('user_id', $user_id)->where('id', $request->t_id)->select('id', 'transaction_id', 'products')->first();

        return response()->json([
            'message' => 'success',
            'record' => $record
        ]);
    }

    public function shopping_cart()
    {
        $user = Auth::user();
        $products_id = $user->shoping_cart ? $user->shoping_cart : [];
        $products = Product::whereIn('id', $products_id)->get();
        $postal_info = Helper::calc_base_postal_price($user);
        if (!$user->name || !$user->city_id || !$user->address || !$user->postal_code || !$user->plaque) {
            $message = 'uncomplate';
        } else {
            $message = 'success';
        }

        return response()->json([
            'message' => $message,
            'body' => [
                'products' => $products,
                'postal_info' => $postal_info,
                'user' => $user
            ]
        ]);
    }

    public function remove_shopping_cart($id)
    {
        $user = Auth::user();
        $shoping_cart = $user->shoping_cart ? $user->shoping_cart : [];
        $key = array_search($id, $shoping_cart);
        unset($shoping_cart[$key]);
        User::where('id', $user->id)->update([
            'shoping_cart' => array_values($shoping_cart),
        ]);

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function check_transaction(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|numeric'
        ]);
        $user = Auth::user();
        $success = Transaction::where('transaction_id', $request->transaction_id)->where('user_id', $user->id)->where('status', 1)->whereNotNull('date_payment')->exists();

        return response()->json([
            'message' => $success ? 'success' : 'faild'
        ]);
    }

    public function create_pay_token(Request $request)
    {
        $request->validate([
            'products_count.*' => 'required|numeric|min:1',
            'products_weight.*' => 'nullable|numeric|gt:0',
            'send_way' => 'nullable|string|in:barbary,bus,tipax,post',
            'send_description' => 'nullable|string|max:250',
            'gateway' => 'nullable|string',
        ]);

        $user = Auth::user();
        $gateway = $request->gateway ?? 'refah';

        if (!$user->name || !$user->city_id || !$user->address || !$user->postal_code) {

            return response()->json([
                'message' => 'error',
                'error' => 'اطلاعات شما ناقص است لطفا ابتدا، از قسمت پروفایل، اطلاعات خود را تکمیل نمایید'
            ]);
        }

        $products_count = json_decode($request->products_count, true);
        $products_weight = $request->products_weight ? json_decode($request->products_weight, true) : [];
        $calculated_products_data = Helper::calc_products_sales_data($products_count, $products_weight);

        if ($calculated_products_data['message'] === 'error') {
            return response()->json([
                'message' => 'error',
                'error' => $calculated_products_data['error']
            ]);
        }

        $final_weight_in_g = $calculated_products_data['final_weight_in_g'];
        $final_price_products = $calculated_products_data['final_price_products'];
        $products_salesed = $calculated_products_data['products_salesed'];
        $is_omde = $calculated_products_data['is_omde'];
        $postal_data = [
            'final_weight_in_g' => $final_weight_in_g,
            'final_price_products' => $final_price_products,
            'is_omde' => $is_omde,
            'send_way' => $request->send_way
        ];
        $postal_price = Helper::calc_final_post_price($user, $postal_data);
        $amount = $final_price_products + $postal_price;
        $transaction_id = rand(1000000, 999999999);
        $tu_token = Str::random(40);
        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'final_price_products' => $final_price_products,
            'postal_price' => $postal_price,
            'gateway' => $gateway,
            'ip' => $request->ip(),
            'products' => $products_salesed,
            'transaction_id' => $transaction_id,
            'tu_token' => $tu_token,
            'origin' => 'app',
            'send_way' => $request->send_way,
            'send_description' => $request->send_description
        ]);

        return response()->json([
            'message' => 'success',
            'token' => $tu_token
        ]);
    }

    public function create_nuts_pay_token(Request $request)
    {
        $request->validate([
            'weights.*' => 'nullable|numeric|gt:0',
            'units.*' => 'required|string|in:kg,g',
            'gateway' => 'required|string'
        ]);
        $weights = json_decode($request->weights, true);
        $weights = array_filter($weights);
        $units = json_decode($request->units, true);
        $gateway = $request->gateway ?? 'refah';

        $user = Auth::user();
        if (!$user->name || !$user->city_id || !$user->address || !$user->postal_code) {
            return response()->json([
                'message' => 'error',
                'error' => 'اطلاعات شما ناقص است لطفا ابتدا، از قسمت پروفایل، اطلاعات خود را تکمیل نمایید'
            ]);
        }
        $calculated_products_data = Helper::calc_nuts_sales_data($weights, $units);

        if ($calculated_products_data['message'] === 'error') {
            return response()->json([
                'message' => 'error',
                'error' => $calculated_products_data['error']
            ]);
        }

        $final_weight_in_g = $calculated_products_data['final_weight_in_g'];
        $final_price_products = $calculated_products_data['final_price_products'];
        $products_salesed = $calculated_products_data['products_salesed'];

        $postal_data = [
            'final_weight_in_g' => $final_weight_in_g,
            'final_price_products' => $final_price_products,
            'is_omde' => false
        ];
        $postal_price = Helper::calc_final_post_price($user, $postal_data);
        $amount = $final_price_products + $postal_price;
        $transaction_id = rand(1000000, 999999999);
        $tu_token = Str::random(40);
        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'final_price_products' => $final_price_products,
            'postal_price' => $postal_price,
            'gateway' => $gateway,
            'ip' => $request->ip(),
            'products' => $products_salesed,
            'transaction_id' => $transaction_id,
            'tu_token' => $tu_token,
            'origin' => 'app',
            'type' => 'nuts'
        ]);

        return response()->json([
            'message' => 'success',
            'token' => $tu_token
        ]);
    }
}
