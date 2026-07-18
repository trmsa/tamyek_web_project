@php
    use App\Helpers\Helper;
@endphp
@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('nuts') }}" />
    <meta name="description" content="تو تام یک میتونی آجیل با هر ترکیب و قیمتی که دلت بخواد بسازی">
    <meta property="og:title" content="آجیل با ترکیب دلخواه" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ route('nuts') }}" />
    <meta property="og:description" content="تو تام یک میتونی آجیل با هر ترکیب و قیمتی که دلت بخواد بسازی" />
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent
@endsection
@section('title', 'آجیل با ترکیب دلخواه')
@section('content')
    <div class="container-xl">
        <h1 class="nuts-title mt-5 text-center">ترکیب آجیل دلخواه</h1>
        <p class="text mt-3 text-center">از میان گزینه‌های زیر انتخاب کن و ترکیب خودتو بساز!</p>
        @auth
            <input type="hidden" id="base_postal_price" value="{{ $postal_info['postal_price'] }}">
            <input type="hidden" id="many_weight_price" value="{{ $postal_info['many_weight_price'] }}">
            <input type="hidden" id="min_price_free_postal" value="{{ $postal_info['min_price_free_postal'] }}">
            <input type="hidden" id="self_city" value="{{ $postal_info['self_city'] }}">
        @endauth
        <form action="{{ route('pay_nuts') }}" method="POST" id="nuts_pay_form">
            @csrf
            <div class="row mt-5 mx-0">
                @foreach ($combinations as $combination)
                    @php
                        $unit_fa = $combination->unit == 'kg' ? 'کیلوگرم' : 'گرم';
                    @endphp
                    <div class="col-6 col-sm-4 p-1">
                        <div class="bg-nuts-box rounded-4 py-3">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-11 col-md-7">
                                    <a href="{{ route('products.show', ['id' => $combination->id]) }}" class="link">
                                        <h2 class="nuts-text">{{ $combination->name }}</h2>
                                    </a>
                                    @if ($combination->inventory > 0)
                                        <p class="nuts-mini-text mt-3">
                                            {{ Helper::fa_digits(number_format($combination->payable_price())) }}
                                            تومان/{{ $unit_fa }}</p>
                                    @else
                                        <p class="nuts-mini-text mt-3 text-danger">ناموجود</p>
                                    @endif
                                </div>
                                <div class="col-8 col-md-4">
                                    <img src="{{ $combination->images[0] }}" alt="{{ $combination->name }}"
                                        class="w-100 rounded-pill">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <input @disabled($combination->inventory <= 0) type="text" inputmode="numeric"
                                    name="weights[{{ $combination->id }}]" min="0"
                                    class="form-control nuts-weight-input hidden-arrow"
                                    id="nuts_weight_input_{{ $combination->id }}" data-c_name="{{ $combination->name }}"
                                    data-c_id="{{ $combination->id }}" data-c_price="{{ $combination->payable_price() }}"
                                    data-c_main_unit="{{ $combination->unit }}" placeholder="مقدار">

                                <select name="units[{{ $combination->id }}]" @disabled($combination->inventory <= 0)
                                    class="form-control nuts-unit-input" id="nuts_unit_input_{{ $combination->id }}"
                                    data-c_id="{{ $combination->id }}">
                                    <option value="g">گرم</option>
                                    <option value="kg">کیلوگرم</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row mt-5">
                <p class="title text-center">نمایش ترکیب</p>
                <ul class="user-combinashions-list mt-3 text"></ul>
                <div class="mt-4 d-flex justify-content-center">
                    <p class="text">هزینه محصولات:</p>
                    <p class="text me-2" id="final_price_products"></p>
                </div>
                <div class="d-flex justify-content-center">
                    <p class="text">هزینه پست:</p>
                    <p class="text me-2" id="final_post_price"></p>
                </div>
                <div class="d-flex justify-content-center">
                    <p class="text">قابل پرداخت:</p>
                    <p class="text me-2 text-success" id="final_price"></p>
                </div>
                <div class="d-flex justify-content-center align-items-center text mb-4">
                    <label for="refah" class="gateway refah-gateway p-1 ms-3 border border-success">
                        <img src="/images/icons/refah.webp" class="w-100 h-100" alt="درگاه پرداخت بانک رفاه">
                        <input type="radio" id="refah" name="gateway" value="refah" class="d-none gateway-pay"
                            checked>
                    </label>
                    <label for="zarinpal" class="gateway zarinpal-gateway p-1">
                        <img src="/images/icons/zarinpal.webp" class="w-100 h-100" alt="درگاه پرداخت زرین پال">
                        <input type="radio" id="zarinpal" name="gateway" value="zarinpal" class="d-none gateway-pay">
                    </label>
                </div>
                <div class="mt-3 text-center">
                    <button class="btn btn-success" id="nuts_pay_btn">پرداخت</button>
                </div>
            </div>
        </form>
    </div>
@endsection
