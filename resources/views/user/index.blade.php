@extends('user.layout')
@section('meta')

    <meta name="robots", content="nofollow,noindex">

@endsection
@section('title', 'تام یک - اطلاعات کاربر')
@section('content_user')

    <div class="row shadow rounded-4 p-3 justify-content-between">
        <p class="title mb-4">مشخصات کاربر</p>
        <div class="col-md-5 mb-3">
            <label class="form-label">نام‌ و نام‌خانوادگی</label>
            <input value="{{ $user->name }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">شماره تلفن همراه</label>
            <input value="{{ $user->mobile }}" class="form-control" disabled>
        </div>
        {{-- <div class="col-md-5 mb-3">
            <label class="form-label">کدملی</label>
            <input value="{{ $user->national_code }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">تاریخ تولد</label>
            <div class="row">
                <div class="col-4">
                    <select class="form-select" disabled>
                        <option>{{ $user->birth_date && $user->birth_date['d'] ? $user->birth_date['d'] : '' }}</option>
                    </select>
                </div>
                <div class="col-4">
                    <select class="form-select" disabled>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '')></option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '1')>فروردین</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '2')>اردیبهشت</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '3')>خرداد</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '4')>تیر</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '5')>مرداد</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '6')>شهریور</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '7')>مهر</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '8')>ابان</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '9')>آذر</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '10')>دی</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '11')>بهمن</option>
                        <option @selected($user->birth_date && $user->birth_date['m'] == '12')>اسفند</option>
                    </select>
                </div>
                <div class="col-4">
                    <select class="form-select" disabled>
                        <option>{{ $user->birth_date && $user->birth_date['y'] ? $user->birth_date['y'] : '' }}</option>
                    </select>
                </div>
            </div>
        </div> --}}
        <div class="col-md-5 mb-3">
            <label class="form-label">استان</label>
            <input value="{{ $user->province ? $user->province->name : '' }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">شهر</label>
            <input value="{{ $user->city ? $user->city->name : '' }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">آدرس</label>
            <input value="{{ $user->address }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">کدپستی</label>
            <input value="{{ $user->postal_code }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">پلاک</label>
            <input value="{{ $user->plaque }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">ایمیل</label>
            <input value="{{ $user->email }}" class="form-control" disabled>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">شماره تلفن ثابت</label>
            <input value="{{ $user->phone }}" class="form-control" disabled>
        </div>
    </div>

@endsection
