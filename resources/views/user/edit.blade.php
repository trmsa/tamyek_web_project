@extends('user.layout')
@section('meta')

    <meta name="robots", content="nofollow,noindex">

@endsection
@section('title', 'ویرایش اطلاعات کاربر')
@section('content_user')

    <form action="{{ route('user.update') }}" method="post" class="@if (session()->has('changed_mobile')) d-none @endif">
        @csrf
        <div class="row shadow rounded-4 p-3 justify-content-between">
            <p class="title mb-4">اصلاح مشخصات کاربر</p>
            <div class="col-md-5 mb-3">
                <label class="form-label">نام‌ و نام‌خانوادگی</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required
                    placeholder="نام‌ و نام‌خانوادگی">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">شماره تلفن همراه</label>
                <input type="text" name="mobile" value="{{ $user->mobile }}" class="form-control hidden-arrow" required>
            </div>
            {{-- <div class="col-md-5 mb-3">
                <label class="form-label">کدملی (اختیاری)</label>
                <input type="number" name="national_code" value="{{ $user->national_code }}" class="form-control hidden-arrow" placeholder="کدملی">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">تاریخ تولد (اختیاری)</label>
                <div class="row">
                    <div class="col-4">
                        <select name="birth_date_d" class="form-select">
                            <option value=""></option>
                            @for ($i = 1; $i < 32; $i++)
                            <option value="{{ $i }}" @selected($user->birth_date && $user->birth_date['d'] == $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="birth_date_m" class="form-select">
                            <option value=""></option>
                            <option value="1" @selected($user->birth_date && $user->birth_date['m'] == '1')>فروردین</option>
                            <option value="2" @selected($user->birth_date && $user->birth_date['m'] == '2')>اردیبهشت</option>
                            <option value="3" @selected($user->birth_date && $user->birth_date['m'] == '3')>خرداد</option>
                            <option value="4" @selected($user->birth_date && $user->birth_date['m'] == '4')>تیر</option>
                            <option value="5" @selected($user->birth_date && $user->birth_date['m'] == '5')>مرداد</option>
                            <option value="6" @selected($user->birth_date && $user->birth_date['m'] == '6')>شهریور</option>
                            <option value="7" @selected($user->birth_date && $user->birth_date['m'] == '7')>مهر</option>
                            <option value="8" @selected($user->birth_date && $user->birth_date['m'] == '8')>ابان</option>
                            <option value="9" @selected($user->birth_date && $user->birth_date['m'] == '9')>آذر</option>
                            <option value="10" @selected($user->birth_date && $user->birth_date['m'] == '10')>دی</option>
                            <option value="11" @selected($user->birth_date && $user->birth_date['m'] == '11')>بهمن</option>
                            <option value="12" @selected($user->birth_date && $user->birth_date['m'] == '12')>اسفند</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="birth_date_y" class="form-select">
                            <option value=""></option>
                            @for ($i = Helper::fa_date('%y'); $i > 1300; $i--)
                            <option value="{{ $i }}" @selected($user->birth_date && $user->birth_date['y'] == $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-5 mb-3">
                <label class="form-label">استان</label>
                <select name="province_id" id="province" class="form-select">
                    <option value="">انتخاب کنید</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}" @selected($user->province_id === $province->id)>{{ $province->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">شهر</label>
                <select name="city_id" id="city" class="form-select">
                    <option value="">انتخاب کنید</option>
                    @php
                        $user_province_cities = $cities->where('province_id', $user->province_id);
                    @endphp
                    @foreach ($user_province_cities as $city)
                        <option value="{{ $city->id }}" @selected($user->city_id === $city->id)>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">آدرس</label>
                <input type="text" name="address" value="{{ $user->address }}" class="form-control" required
                    placeholder="آدرس">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">کدپستی</label>
                <input type="text" name="postal_code" value="{{ $user->postal_code }}" class="form-control" required
                    placeholder="کدپستی">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">پلاک</label>
                <input type="text" name="plaque" value="{{ $user->plaque }}" class="form-control hidden-arrow" required
                    placeholder="پلاک">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">ایمیل (اختیاری)</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="ایمیل">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">شماره تلفن ثابت (اختیاری)</label>
                <input type="number" name="phone" value="{{ $user->phone }}" class="form-control hidden-arrow"
                    placeholder="شماره تلفن ثابت">
            </div>
            <div class="text-start">
                <button class="btn btn-success">ذخیره</button>
            </div>
        </div>
    </form>
    <div class="row justify-content-center">
        <div class="col-11 col-md-6 shadow position-relative rounded-5 p-4 text-center @if (!session()->has('changed_mobile')) d-none @endif"
            id="vreify_sms_box">
            <img src="/images/logo/logo.png" alt="تام یک">
            <span class="text position-absolute d-block text-danger expire-time-box">تا انقضای کد: <span class="text"
                    id="expire_time">180</span></span>
            <form action="{{ route('user.update_mobile') }}" method="POST">
                @csrf
                <input type="number" name="code" class="form-control mb-3 hidden-arrow p-3" required
                    placeholder="کد دریافتی را وارد کنید">
                <button class="btn btn-success">ارسال</button>
            </form>
        </div>
    </div>
    <select id="all_cities" class="d-none">
        @foreach ($cities as $city)
            <option class="city-province-{{ $city->province_id }}" value="{{ $city->id }}">{{ $city->name }}
            </option>
        @endforeach
    </select>
@endsection
