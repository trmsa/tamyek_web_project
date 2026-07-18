<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Province;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AdminSalesController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'transaction_id' => 'nullable|string',
            'mobile' => 'nullable|numeric|digits:11|starts_with:09',
            'send' => 'nullable|numeric'
        ]);
        $send = $request->send ?? 0;
        $transaction_id = $request->transaction_id;
        $mobile = $request->mobile;
        $sales = Transaction::where('status', 1)->whereNotNull('date_payment');
        if($transaction_id) {
            $sales = $sales->where('transaction_id', $transaction_id);
        }
        if($mobile) {
            $user_id = User::where('mobile', $mobile)->first('id')->id ?? null;
            $sales = $sales->where('user_id', $user_id);
        }
        if($send == 1) {
            $sales = $sales->whereNotNull('date_send');
        }else {
            $sales = $sales->whereNull('date_send');
        }

        $sales = $sales->orderBy('date_payment', 'desc')->paginate(50);
        $users_id = $sales->pluck('user_id')->unique();
        $users = User::find($users_id);
        $cities_id = $users->pluck('city_id')->unique();
        $cities = City::find($cities_id);
        $provinces = Province::all();

        return view('admin.sales.index', compact('sales', 'users', 'cities', 'provinces', 'send', 'mobile', 'transaction_id'));
    }

    public function show($id)
    {
        $sales = Transaction::where('id', $id)->where('status', 1)->whereNotNull('date_payment')->first();
        $user = User::findOrFail($sales->user_id);
        $city = City::find($user->city_id);
        $province = Province::find($user->province_id);

        return view('admin.sales.show', compact('user', 'sales', 'city', 'province'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|numeric',
            'send_date_y' => 'required|numeric',
            'send_date_m' => 'required|numeric|min:1|max:12',
            'send_date_d' => 'required|numeric|min:1|max:31',
            'shipment_code' => 'nullable|numeric',
            'user_id' => 'required|numeric',
        ]);
        $id = $request->sales_id;
        $y = $request->send_date_y;
        $m = $request->send_date_m;
        $d = $request->send_date_d;
        $shipment_code = $request->shipment_code;
        $date_send = Helper::jalali_to_gregorian($y, $m, $d, '-');

        Transaction::where('id', $id)->where('status', 1)->whereNotNull('date_payment')->update([
            'date_send' => $date_send,
            'shipment_code' => $shipment_code
        ]);
        $user = User::find($request->user_id);
        $sms_params = [
            'NAME' => $user->name,
            'SHIP' => $shipment_code,
            'DATE' => "$y/$m/$d"
        ];
        Helper::sms($sms_params, $user->mobile, config('tamyek.sms_template.send_products'));
        session()->flash('message_success', 'اطلاعات ارسال محصول با موفقیت ذخیره گردید');

        return back();
    }
}
