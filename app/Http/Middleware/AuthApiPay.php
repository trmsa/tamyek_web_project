<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApiPay
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_check = false;
        if(strlen($request->auth_api_pay) > 10) {
            $user_check = User::where('auth_api_pay', $request->auth_api_pay)->exists();
        }
        if($request->pay_api_key === config('tamyek.pay_api_key') && $user_check) {
            return $next($request);
        }else {
            abort(403);
        }
    }
}
