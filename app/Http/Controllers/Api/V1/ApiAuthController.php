<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiAuthController extends Controller
{
    public function send_otp(Request $request)
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
                return response()->json([
                    'message' => 'exist',
                    'until' => $until
                ]);
            }
        }
        $code = rand(10000, 999999);
        $sms_params = [
            'CODE' => $code,
        ];
        Helper::sms($sms_params, $mobile, config('tamyek.sms_template.verify'));
        $sms_params['EXPIRE'] = time() + (int) config('tamyek.expire_time_otp') + 5;
        User::updateOrCreate(
            ['mobile' => $mobile],
            [
                'verify_sms' => $sms_params
            ]
        );

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function check_otp(Request $request)
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

            User::where('id', $user->id)->update([
                'verify_sms' => null,
                'auth_api_pay' => Str::random(60)
            ]);

            return response()->json([
                'message' => 'success',
                'body' => [
                    'token' => $user->createToken('tamyek_app')->plainTextToken,
                    'user' => $user
                ]
            ]);
        } else {
            return response()->json([
                'message' => 'failed',
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
