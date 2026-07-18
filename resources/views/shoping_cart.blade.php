@php
    $final_price = 0;
    $is_omde = false;
@endphp
@extends('user.layout')
@section('meta')

    <meta name="robots", content="nofollow,noindex">

@endsection
@section('title', 'سبد خرید تام یک')
@section('content_user')
    <div class="container-xl">
        <p class="title">محصولات موجود در سبد خرید شما</p>
        <form action="{{ route('pay') }}" method="POST">
            <div class="row my-3 justify-content-between">
                @if (count($products))
                    @csrf
                    @foreach ($products as $product)
                        @php
                            $final_price += $product->inventory > 0 ? $product->payable_price() : 0;
                            if ($product->type == 'omde') {
                                $is_omde = true;
                            }
                        @endphp
                        <div class="col-md-6 p-4">
                            <div class="row custom-light rounded-3 position-relative">
                                <div class="position-absolute top-5 w-fit d-flex align-items-center">
                                    <a href="{{ route('cart.delete', ['product_id' => $product->id]) }}"
                                        class="btn btn-outline-danger btn-sm">✕</a>
                                    <a href="{{ route('favorits.update', ['product_id' => $product->id]) }}"
                                        class="favorite-btn icon me-2 @if (in_array($product->id, auth()->user()->favorits ?? [])) active @endif"></a>
                                </div>

                                <div class="col-5 mt-5">
                                    <a href="{{ route('products.show', ['id' => $product->id]) }}" class="d-block my-2">
                                        <img src="{{ $product->images[0] }}" alt="{{ $product->name }}"
                                            title="{{ $product->name }}" class="w-100 h-100 rounded-3">
                                    </a>
                                </div>
                                <div class="col-7 d-flex flex-column">
                                    <a href="{{ route('products.show', ['id' => $product->id]) }}" class="link">
                                        <h2 class="titr mb-2">{{ $product->name }}</h2>
                                    </a>
                                    @if ($product->inventory > 0)
                                        @if ($product->unit != 'kg' && $product->unit != 'g')
                                            @php
                                                $pack = explode('_', $product->unit);
                                                $weight_pack = $pack[0];
                                                $unit_pack = $pack[1] == 'kg' ? 'کیلویی' : 'گرمی';
                                            @endphp
                                            <p class="text mb-1">{{ $weight_pack }}{{ $unit_pack }}</p>
                                        @endif
                                        @if ($product->unit == 'g' && $product->min_order)
                                            <p class="text text-danger mb-0">حداقل سفارش: {{ $product->min_order }} گرم</p>
                                        @endif
                                        @if ($product->type == 'omde')
                                            <p class="text text-danger mb-0">حداقل سفارش: {{ $product->min_order }} کیلوگرم
                                            </p>
                                        @endif
                                        @if ($product->discount() == 'public_percent' || $product->discount() == 'public_constant')
                                            <div class="text text-decoration-line-through text-danger">
                                                <span class="cart-box-titr">قیمت: </span>
                                                <span id="cart_product_main_price_{{ $product->id }}"
                                                    data-price="{{ $product->price }}">{{ number_format($product->price) }}
                                                    تومان</span>
                                            </div>
                                            <div class="text text-success">
                                                <span class="cart-box-titr">تخفیف: </span>
                                                <span id="total_discount_product_{{ $product->id }}"
                                                    data-total_discount_product="{{ $product->discounted_price }}"
                                                    class="total-discount-product">{{ number_format($product->price - $product->discounted_price) }}
                                                    تومان</span>
                                            </div>
                                        @endif
                                        <div class="text text-success">
                                            <span class="cart-box-titr">قیمت: </span>
                                            <span id="total_price_product_{{ $product->id }}"
                                                data-total_price_product="{{ $product->payable_price() }}"
                                                class="total-price-product">{{ number_format($product->payable_price()) }}
                                                تومان</span>
                                        </div>
                                        <span id="cart_product_price_{{ $product->id }}"
                                            data-price="{{ $product->payable_price() }}" class="d-none"></span>
                                        <div
                                            class="text mb-2 d-flex align-items-center {{ $product->type == 'omde' ? 'd-none' : '' }}">
                                            <span class="cart-box-titr">تعداد: </span>
                                            <div class="position-relative w-100">
                                                <input type="number" data-product_id="{{ $product->id }}"
                                                    data-product_type="{{ $product->type }}"
                                                    data-product_unit="{{ $product->unit }}" min="1" value="1"
                                                    name="products_count[{{ $product->id }}]"
                                                    id="cart_product_count_{{ $product->id }}"
                                                    class="form-control flex-grow-1 cart-product-count hidden-arrow">
                                                <div
                                                    class="d-flex justify-content-between align-items-center change-count-box">
                                                    <button type="button" class="increase increase-product-count-cart ms-2"
                                                        data-product_id="{{ $product->id }}">+</button>
                                                    <button type="button" class="decrease decrease-product-count-cart"
                                                        data-product_id="{{ $product->id }}">-</button>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($product->type == 'khorde')
                                            @if ($product->unit == 'kg')
                                                <div class="text mb-2 d-flex align-items-center">
                                                    <label class="cart-box-titr">وزن: </label>
                                                    <select
                                                        class="form-select text mx-auto flex-grow-1 cart-product-weight d-inline"
                                                        id="cart_product_weight_{{ $product->id }}"
                                                        name="products_weight[{{ $product->id }}]"
                                                        data-product_id="{{ $product->id }}">
                                                        <option value="1">1 کیلوگرم</option>
                                                        @if ($product->min_order <= 500)
                                                            <option value="0.5">نیم کیلوگرم</option>
                                                        @endif
                                                        @if ($product->min_order <= 250)
                                                            <option value="0.25">250 گرم</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            @elseif($product->unit == 'g')
                                                <div class="text mb-1 d-flex align-items-center">
                                                    <label class="cart-box-titr">وزن: </label>
                                                    <select class="form-select text mx-auto flex-grow-1 cart-product-weight"
                                                        id="cart_product_weight_{{ $product->id }}"
                                                        name="products_weight[{{ $product->id }}]"
                                                        data-product_id="{{ $product->id }}">
                                                        <option value="1">1 گرم</option>
                                                        <option value="5">5 گرم</option>
                                                        <option value="10">10 گرم</option>
                                                        <option value="50">50 گرم</option>
                                                        <option value="100">100 گرم</option>
                                                    </select>
                                                </div>
                                            @endif
                                        @elseif($product->type == 'omde')
                                            <input type="hidden" id="omde_prices_{{ $product->id }}"
                                                value="{{ $product->other }}">
                                            <label class="text" for="product_weight">وزن (کیلوگرم)</label>
                                            <input type="number" min="{{ $product->min_order }}"
                                                name="products_weight[{{ $product->id }}]"
                                                id="cart_product_weight_{{ $product->id }}"
                                                data-product_id="{{ $product->id }}" value="{{ $product->min_order }}"
                                                class="form-control cart-product-weight hidden-arrow"
                                                placeholder="وزن را به کیلوگرم وارد کنید">
                                        @endif
                                    @else
                                        <p class="titr text-center mt-5 text-danger">ناموجود</p>
                                        <input type="hidden" value="1" name="products_count[{{ $product->id }}]">
                                        <input type="hidden" value="1"
                                            name="products_weight[{{ $product->id }}]">
                                    @endif


                                </div>
                                @if ($product->type == 'omde' && $product->other != null)
                                    @php
                                        $weight_price = json_decode($product->other, true);
                                        ksort($weight_price);
                                    @endphp
                                    <ul class="mb-0">
                                        <li class="mb-2">
                                            <span class="custom-list-style-dark omde-list-style bg-success"
                                                id="omde_list_style_0"></span>
                                            <span class="mini-text">بیشتر از {{ $product->min_order }} کیلوگرم با
                                                قیمت
                                                {{ number_format($product->price) }}
                                                تومان</span>
                                        </li>
                                        @foreach ($weight_price as $weight_omde => $price_omde)
                                            <li class="mb-2">
                                                <span class="custom-list-style-dark omde-list-style"
                                                    id="omde_list_style_{{ $weight_omde }}"></span>
                                                <span class="mini-text">بیشتر از {{ $weight_omde }} کیلوگرم با
                                                    قیمت
                                                    {{ number_format($price_omde) }}
                                                    تومان</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if ($is_omde)
                        <div class="row justify-content-between">
                            <div class="col-md-5 mt-3">
                                <label for="send_way">انتخاب روش ارسال</label>
                                <select name="send_way" id="send_way" class="form-control">
                                    <option value="barbary">باربری- پس‌کرایه (برعهده خریدار)</option>
                                    <option value="bus">اتوبوسرانی- پس‌کرایه (برعهده خریدار)</option>
                                    <option value="tipax">تیپاکس- پس‌کرایه (برعهده خریدار)</option>
                                    <option value="post">پست</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="send_description">توضیحات</label>
                                <textarea name="send_description" id="send_description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    @endif
                    <div class="text text-center mt-4">
                        <span>جمع تخفیف:</span>
                        <span class="text-success me-3 title" id="cart_discount_products"></span>
                    </div>
                    <div class="text-center text">
                        <span>هزینه پست:</span>
                        <span class="text-success me-3 titr" id="post_price"
                            data-base_post_price="{{ $postal_info['postal_price'] }}"
                            data-many_weight_price="{{ $postal_info['many_weight_price'] }}"
                            data-self_city="{{ $postal_info['self_city'] }}"
                            data-min_price_free_postal="{{ $postal_info['min_price_free_postal'] }}"></span>
                    </div>
                    <div class="text-center text mb-3">
                        <span>مبلغ قابل پرداخت:</span>
                        <span class="cart-final-price text-success me-3 title">{{ number_format($final_price) }}
                            تومان</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center text mb-4">
                        <label for="refah" class="gateway refah-gateway p-1 ms-3 border border-success">
                            <img src="/images/icons/refah.webp" class="w-100 h-100" alt="درگاه پرداخت بانک رفاه">
                            <input type="radio" id="refah" name="gateway" value="refah"
                                class="d-none gateway-pay" checked>
                        </label>
                        <label for="zarinpal" class="gateway zarinpal-gateway p-1">
                            <img src="/images/icons/zarinpal.webp" class="w-100 h-100" alt="درگاه پرداخت زرین پال">
                            <input type="radio" id="zarinpal" name="gateway" value="zarinpal"
                                class="d-none gateway-pay">
                        </label>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-success">پرداخت</button>
                    </div>
                @else
                    <p class="title text-center text-danger">سبد خرید شما خالی است.</p>
                @endif
            </div>
        </form>
    </div>
@endsection
