<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\User;
use App\Models\Version;
use App\Models\Province;
use App\Models\Usession;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(50);
        $users_id = $users->pluck('id');
        $cities_id = $users->pluck('city_id');
        $sales = Transaction::whereIn('user_id', $users_id)->where('status', 1)->whereNotNull('date_payment')->select('user_id', 'final_price_products')->get();
        $cities = Cache::remember('cities', 30 * 24 * 60 * 60, function () {
            return City::all();
        });
        $cities = $cities->whereIn('id', $cities_id);
        $provinces = Cache::remember('provinces', 30 * 24 * 60 * 60, function () {
            return Province::all();
        });

        return view('admin.users.index', compact('users', 'sales', 'cities', 'provinces'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $provinces = Cache::remember('provinces', 30 * 24 * 60 * 60, function () {
            return Province::all();
        });
        $cities = Cache::remember('cities', 30 * 24 * 60 * 60, function () {
            return City::all();
        });

        return view('admin.users.edit', compact('user', 'provinces', 'cities'));
    }

    public function update(Request $request, $id)
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

        $user = User::findOrFail($id);
        $birth_date = [
            'y' => $request->birth_date_y,
            'm' => $request->birth_date_m,
            'd' => $request->birth_date_d,
        ];

        User::where('id', $user->id)->update([
            'name' => $request->name,
            'national_code' => $request->national_code,
            'birth_date' => $birth_date,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'plaque' => $request->plaque,
            'phone' => $request->phone,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);
        session()->flash('message_success', 'اطلاعات کاربر با موفقیت ذخیره شد');

        return back();
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();
        session()->flash('message_success', 'کاربر با موفقیت حذف شد');

        return back();
    }

    public function search(Request $request)
    {
        $request->validate([
            'mobile' => 'nullable|numeric'
        ]);

        $mobile = $request->mobile;
        if ($mobile) {
            $users = User::where('mobile', 'like', "%$mobile%")->paginate(50);
        } else {
            $users = User::orderBy('created_at', 'desc')->paginate(50);
        }

        $users_id = $users->pluck('id');
        $cities_id = $users->pluck('city_id');
        $sales = Transaction::whereIn('user_id', $users_id)->where('status', 1)->whereNotNull('date_payment')->select('final_price_products')->get();
        $cities = Cache::remember('cities', 30 * 24 * 60 * 60, function () {
            return City::all();
        });
        $cities = $cities->whereIn('id', $cities_id);
        $provinces_id = $cities->pluck('province_id');
        $provinces = Cache::remember('provinces', 30 * 24 * 60 * 60, function () {
            return Province::all();
        });
        $provinces = $provinces->whereIn('id', $provinces_id);

        return view('admin.users.index', compact('users', 'sales', 'cities', 'provinces', 'mobile'));
    }

    public function records($id)
    {
        $user = User::findOrFail($id);
        $records = Transaction::where('user_id', $user->id)->where('status', 1)->whereNotNull('date_payment')->orderBy('date_payment', 'desc')->get();

        return view('admin.users.records', compact('user', 'records'));
    }

    public function record_products($id)
    {
        $record = Transaction::where('id', $id)->where('status', 1)->whereNotNull('date_payment')->first();
        $user = [];
        if ($record) {
            $user = User::findOrFail($record->user_id);
        }

        return view('admin.users.record_products', compact('user', 'record'));
    }

    public function sessions(Request $request)
    {
        $request->validate([
            'filtter' => 'nullable|string'
        ]);

        $filter = $request->filter == 'user' ? 'user_id' : 'last_activity';
        $order = $request->order_by == 'asc' ? 'asc' : 'desc';
        $sessions = Usession::select('user_id', 'ip_address', 'last_activity', 'user_agent')->orderBy($filter, $order)->paginate(100);
        $users_id = $sessions->collect()->pluck('user_id')->values();
        $users = User::whereIn('id', $users_id)->select('id', 'name', 'mobile')->get();

        return view('admin.sessions.index', compact('sessions', 'users'));
    }

    public function tokens(Request $request)
    {
        $request->validate([
            'filtter' => 'nullable|string'
        ]);

        $filter = $request->filter == 'user' ? 'tokenable_id' : 'last_used_at';
        $order = $request->order_by == 'asc' ? 'asc' : 'desc';
        $tokens = DB::table('personal_access_tokens')->select('tokenable_id', 'last_used_at', 'expires_at')->orderBy($filter, $order)->paginate(100);
        $users_id = $tokens->getCollection()->pluck('tokenable_id')->values();
        $users = User::whereIn('id', $users_id)->select('id', 'name', 'mobile')->get();

        return view('admin.sessions.tokens', compact('tokens', 'users'));
    }

    public function delete_session(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'last_activity' => 'required|numeric'
        ]);

        Usession::where('user_id', $request->user_id)->where('last_activity', $request->last_activity)->delete();
        session()->flash('message_success', 'جلسه با موفقیت حذف شد.');

        return back();
    }

    public function delete_token(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'last_used_at' => 'nullable|string'
        ]);

        if ($request->last_used_at) {
            $token = DB::table('personal_access_tokens')->where('tokenable_id', $request->user_id)->where('last_used_at', $request->last_used_at)->delete();
        } else {
            $token = DB::table('personal_access_tokens')->where('tokenable_id', $request->user_id)->where('last_used_at', null)->delete();
        }
        session()->flash('message_success', "$token جلسه با موفقیت حذف شد.");

        return back();
    }

    public function clear_trash()
    {
        $trns = Transaction::where('status', 0)->whereNull('date_payment')->where('created_at', '<=', date('Y-m-d H:i:s', strtotime('-20 minutes')))->delete();
        $sessions = Usession::whereNull('user_id')->where('last_activity', '<', strtotime('-4 hours'))->delete();
        session()->flash('message_success', "$trns رکورد خرید ناموفق و $sessions رکورد سشن حذف شد.");
        return back();
    }

    public function clear_tokens()
    {
        $tokens = DB::table('personal_access_tokens')->where('last_used_at', '<', date('Y-m-d H:i:s', strtotime('-1 year')))->delete();
        session()->flash('message_success', "$tokens رکورد توکن حذف شد.");
        return back();
    }


    public function download_file(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        return Storage::download($request->path);
    }

    public function app_version()
    {
        $version = Version::first();

        return view('admin.version.index', compact('version'));
    }

    public function change_app_version(Request $request)
    {
        $request->validate([
            'app_version' => 'nullable|string',
            'is_force' => 'nullable|boolean',
            'message' => 'nullable|string'
        ]);

        Version::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'app_version' => $request->app_version,
                'is_force' => $request->is_force,
                'message' => $request->message
            ]
        );

        session()->flash('message_success', 'ورژن با موفقیت به روزرسانی شد.');
        return back();
    }
}
