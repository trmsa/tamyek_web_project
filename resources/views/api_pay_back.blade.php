@extends('_master')
@section('title', 'تام یک - نتیجه تراکنش')
@section('meta')

    <meta name="robots", content="nofollow,noindex">

@endsection
@section('content')
    <div class="container-xl h-75vh d-flex justify-content-center align-items-center">
        @if (isset($data['ref_id']))
            <div class="row">
                <p class="title mb-3 text-center text-success">{{ $data['message'] }}</p>
                <p class="mb-2 text-center">مبلغ پرداختی: {{ number_format($data['amount']) }} تومان</p>
                <p class="mb-2 text-center">کد پیگیری: {{ $data['ref_id'] }}</p>
                <p class="mb-2 text-center">شماره تراکنش: {{ $data['transaction_id'] }}</p>
                <div class="text-center">
                    <a href="return://IhbwKh2NzDgEvQ368ya91Xk1?transaction={{ $data['transaction_id'] }}"
                        class="btn btn-success">بازگشت به برنامه</a>
                </div>
            </div>
        @else
            <div class="row">
                <p class="title mb-3 text-center text-danger">{{ $data['message'] }}</p>
                @if (isset($data['error_message']) && isset($data['error_code']))
                    <p class="mb-2 text-center text-danger">علت خطا: {{ $data['error_message'] }}</p>
                    <p class="mb-2 text-center text-danger">کد خطا: {{ $data['error_code'] }}</p>
                @endif
                <div class="text-center">
                    <a href="return://IhbwKh2NzDgEvQ368ya91Xk1?transaction=error" class="btn btn-success">بازگشت به
                        برنامه</a>
                </div>
            </div>
        @endif
    </div>
@endsection
