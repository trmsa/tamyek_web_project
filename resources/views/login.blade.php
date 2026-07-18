@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('login') }}" />
    <meta name="description" content="جهت ورود به سایت شماره تلفن همراه خود را وارد نمایید.">
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    @component('components.schema-sitename')
    @endcomponent

@endsection
@section('title', 'ورود')
@section('content')
    <div class="container-xl">
        <div class="row justify-content-center">
            {{-- mobile number --}}
            <div class="col-11 col-md-6 shadow rounded-5 p-4 text-center @if (session()->has('sended')) d-none @endif">
                <img src="/images/logo/logo.png" class="logo-lg" alt="تام یک" title="تام یک">
                <form action="{{ route('verify_sms') }}" method="POST" id="send_otp_form">
                    @csrf
                    <input type="number" name="mobile" value="{{ old('mobile') }}"
                        class="form-control mt-5 hidden-arrow p-3" required placeholder="شماره تلفن همراه خود را وارد کنید">
                    <div class="d-flex mt-3 mb-4">
                        <input class="ms-2 form-check-input" type="checkbox" id="accept_rules">
                        <a href="{{ route('rules') }}">قوانین و مقررات را خوانده‌ام و می‌پذیرم</a>
                    </div>
                    <button class="btn btn-success" id="send_otp_btn">دریافت کد</button>
                </form>
            </div>
            {{-- verify code --}}
            <div class="col-11 col-md-6 shadow position-relative rounded-5 p-4 text-center @if (!session()->has('sended')) d-none @endif"
                id="vreify_sms_box">
                <img src="/images/logo/logo.png" class="logo-lg" alt="تام یک" title="تام یک">
                <p class="text mt-2 mb-0">{{ session('sended') }}</p>
                <a href="{{ route('login') }}" class="d-block mb-5">ویرایش شماره</a>
                <span class="text position-absolute d-block text-danger expire-time-box">تا انقضای کد: <span class="text"
                        id="expire_time">02:00</span></span>
                <form action="{{ route('verify_sms') }}" method="POST" class="hidden" id="again_form">
                    @csrf
                    <input type="hidden" name="mobile" id="again_mobile">
                    <button class="btn btn-light btn-sm my-1">دریافت مجدد کد</button>
                </form>
                <form action="{{ route('check_verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mobile" id="first_mobile" value="{{ session('sended') }}">
                    <input type="number" name="code" class="form-control mb-3 hidden-arrow p-3" required
                        placeholder="کد دریافتی را وارد کنید">
                    <button class="btn btn-success">ورود</button>
                </form>
            </div>
        </div>
    </div>
@endsection
