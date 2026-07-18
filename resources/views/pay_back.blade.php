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
                <p class="text-center">شماره تراکنش: {{ $data['transaction_id'] }}</p>

            </div>
        @else
            <div class="row">
                <p class="title mb-3 text-center text-danger">{{ $data['message'] }}</p>
                <p class="mb-2 text-center">علت خطا: {{ $data['error_message'] }}</p>
                <p class="text-center">کد خطا: {{ $data['error_code'] }}</p>

            </div>
        @endif
    </div>
@endsection
