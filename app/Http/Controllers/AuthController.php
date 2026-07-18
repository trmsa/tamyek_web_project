<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function verify_sms(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:11|starts_with:09'
        ]);

        $mobile = $request->mobile;
        $user = User::where('mobile', $mobile)->first();
        if ($user) {
            $verify_sms = $user->verify_sms;
            $time = time();
            if (isset($verify_sms['EXPIRE']) && $verify_sms['EXPIRE'] > $time) {
                $until = $verify_sms['EXPIRE'] - $time;
                session()->flash('message_danger', "برای ارسال مجدد کد تایید، $until ثانیه دیگر اقدام کنید.");
                return back();
            }
        }
        $code = rand(10000, 999999);
        $sms_params = [
            'CODE' => $code,
        ];
        Helper::sms($sms_params, $mobile, config('tamyek.sms_template.verify'));
        $sms_params['EXPIRE'] = time() + config('tamyek.expire_time_otp') + 5;
        User::updateOrCreate(
            ['mobile' => $mobile],
            [
                'verify_sms' => $sms_params
            ]
        );
        session()->flash('message_success', 'کد تایید ارسال شد');
        session()->flash('sended', $mobile);
        return back();
    }

    public function check_verify(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:11|starts_with:09',
            'code' => 'required|numeric'
        ]);
        $mobile = $request->mobile;
        $user = User::where('mobile', $mobile)->first();
        $code = (int) $request->code;
        $expire = (int) $user->verify_sms['EXPIRE'];
        $main_code = (int) $user->verify_sms['CODE'];
        if ($expire >= time() && $code === $main_code) {
            Auth::login($user);
            User::where('id', $user->id)->update([
                'verify_sms' => null
            ]);
            session()->flash('message_success', 'با موفقیت وارد شدید');
        } else {
            session()->flash('message_danger', 'کد تایید منقضی یا اشتباه است');
            session()->flash('sended', $mobile);
            return back();
        }

        return to_route('home');
    }

    public function logout()
    {
        Auth::logout();
        return to_route('home');
    }
}
