@php
    use App\Helpers\Helper;
@endphp
@extends('_master')
@section('meta')
    <meta name="description" content="{{ $product->meta_description }}">
    <meta name="keywords" content="{{ implode(', ', $product->keywords) }}" />
    <link rel="canonical" href="{{ route('products.show', ['id' => $product->id]) }}" />
    <meta property="og:type" content="product" />
    <meta property="og:title" content="{{ $product->name }}" />
    <meta property="og:description" content="{{ $product->meta_description }}" />
    <meta property="og:url" content="{{ route('products.show', ['id' => $product->id]) }}" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:image" content="{{ config('app.url') . $product->images[0] }}" />
    <script type="application/ld+json">
            {
                "@context": "https://schema.org/",
                "@type": "Product",
                "name": "{{ $product->name }}",
                "image": "{{ config('app.url') . $product->images[0] }}",
                "description": "{{ $product->meta_description }}",
                "brand": {
                    "@type": "Brand",
                    "name": "{{ Config('app.name') }}"
                },
                "sku": "{{ $product->id }}",
                "offers": {
                    "@type": "Offer",
                    "url": "{{ route('products.show', ['id' => $product->id]) }}",
                    "priceCurrency": "IRR",
                    "price": "{{ $product->payable_price() * 10 }}"
                }
            }
            </script>
    @component('components.schema-sitename')
    @endcomponent
@endsection
@section('title', "خرید و قیمت $product->name در آجیل و خشکبار تام یک")
@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8 col-lg-6 mx-auto mb-4">
                <img class="product-product-image rounded-4 shadow" src="{{ $product->images[0] }}"
                    alt="{{ $product->name }}" title="{{ $product->name }}">
                <div class="position-relative">
                    <div class="product-product-images-box shadow mt-5 d-flex align-items-center rounded-4">
                        @foreach ($product->images as $image)
                            <img src="{{ $image }}" alt="{{ $product->name }}" title="{{ $product->name }}"
                                class="product-product-small-image rounded-pill mx-2">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-3">
                <h1 class="title">{{ $product->name }}</h1>

                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $product->id }}" name="product_id">
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
                        @if ($product->type == 'omde' && $product->min_order)
                            <p class="text text-danger mb-0">حداقل سفارش: {{ $product->min_order }} کیلوگرم</p>
                        @endif
                        @if ($product->discount() == 'public_percent' || $product->discount() == 'public_constant')
                            <div class="text text-decoration-line-through text-danger">
                                <span class="ms-1">قیمت: </span>
                                <span id="product_main_price"
                                    data-main_price="{{ $product->price }}">{{ number_format($product->price) }}
                                    تومان</span>
                            </div>
                            <div class="text text-success">
                                <span class="ms-1">تخفیف: </span>
                                <span id="total_discount">{{ number_format($product->price - $product->discounted_price) }}
                                    تومان</span>
                            </div>
                        @endif
                        <div class="text price-box">
                            <span class="ms-1">قیمت: </span>
                            <span id="product_price" class="text-success"
                                data-price="{{ $product->payable_price() }}">{{ number_format($product->payable_price()) }}
                                تومان</span>
                        </div>
                        <div class="col-md-6">
                            @if ($product->type == 'khorde')
                                @if ($product->unit == 'kg')
                                    <label class="text" for="product_weight">وزن</label>
                                    <select class="text form-select mb-2" id="product_weight" name="product_weight">
                                        <option value="1" selected>یک کیلوگرم</option>
                                        @if ($product->min_order <= 500)
                                            <option value="0.5">نیم کیلوگرم</option>
                                        @endif
                                        @if ($product->min_order <= 250)
                                            <option value="0.25">250 گرم</option>
                                        @endif
                                    </select>
                                @elseif ($product->unit == 'g')
                                    <label class="text" for="product_weight">وزن</label>
                                    <select class="text form-select mb-2" id="product_weight" name="product_weight">
                                        <option value="1" selected>1 گرم</option>
                                        <option value="5">5 گرم</option>
                                        <option value="10">10 گرم</option>
                                        <option value="50">50 گرم</option>
                                        <option value="100">100 گرم</option>
                                    </select>
                                @endif
                                <label class="text" for="product_count">تعداد</label>
                                <div class="position-relative">
                                    <input type="number" id="product_count" min="1" value="1"
                                        name="product_count" class="form-control text hidden-arrow">
                                    <div class="d-flex justify-content-between align-items-center change-count-box">
                                        <button type="button" class="increase ms-2"
                                            id="increase_product_count_btn">+</button>
                                        <button type="button" class="decrease" id="decrease_product_count_btn">-</button>
                                    </div>
                                </div>
                            @elseif ($product->type == 'omde')
                                <input type="hidden" name="product_count" id="product_count" value="1">
                                <input type="hidden" id="omde_prices" value="{{ $product->other }}">
                                <label class="text" for="product_weight">وزن (کیلوگرم)</label>
                                <input type="number" min="1" name="product_weight" id="product_weight"
                                    class="form-control hidden-arrow" placeholder="وزن را به کیلوگرم وارد کنید">

                            @endif

                        </div>
                        @if ($product->type == 'omde' && $product->other != null)
                            @php
                                $weight_price = json_decode($product->other, true);
                                ksort($weight_price);
                            @endphp
                            <ul class="mt-3">
                                <li>
                                    <span class="custom-list-style-dark omde-list-style bg-success"
                                        id="omde_list_style_0"></span>
                                    <span>بیشتر از {{ $product->min_order }} کیلوگرم با
                                        قیمت
                                        {{ number_format($product->price) }}
                                        تومان</span>
                                </li>
                                @foreach ($weight_price as $weight_omde => $price_omde)
                                    <li>
                                        <span class="custom-list-style-dark omde-list-style"
                                            id="omde_list_style_{{ $weight_omde }}"></span>
                                        <span>بیشتر از {{ $weight_omde }} کیلوگرم با قیمت
                                            {{ number_format($price_omde) }}
                                            تومان</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <button class="btn btn-success mt-4 ms-2">افزودن به سبد خرید</button>
                        <a href="{{ route('cart.index') }}" class="btn btn-success mt-4">مشاهده سبد خرید</a>
                    @else
                        <p class="text-danger mt-4 text">ناموجود</p>
                    @endif
                </form>
                <div class="mt-5 d-flex align-items-center">
                    <div class="position-relative text-center ms-4">
                        <img src="/images/icons/share.webp" alt="اشتراک" class="share-btn icon">
                        <p class="mini-text mt-2">اشتراک</p>
                    </div>
                    <div class="text-center">
                        @auth
                            <a href="{{ route('favorits.update', ['product_id' => $product->id]) }}"
                                class="d-inline-block favorite-btn icon @if (in_array($product->id, auth()->user()->favorits ?? [])) active @endif"></a>
                        @else
                            <span class="d-inline-block favorite-btn icon"></span>
                        @endauth
                        <p class="mini-text mt-2">علاقه‌مندی‌ها</p>
                    </div>
                    @if (isset($product->links['instageram']))
                        <div class="text-center me-4">
                            <div>
                                @if (isset($product->links['instageram']))
                                    <a href="{{ $product->links['instageram'] }}"><img
                                            src="/images/icons/instageram.webp" class="icon" alt="اینستاگرام"></a>
                                @endif
                                @if (isset($product->links['telegram']))
                                    <a href="{{ $product->links['telegram'] }}" class="mx-3"><img
                                            src="/images/icons/telegram.webp" class="icon" alt="تلگرام"></a>
                                @endif
                                @if (isset($product->links['rubika']))
                                    <a href="{{ $product->links['rubika'] }}"><img src="/images/icons/rubika.webp"
                                            class="icon" alt="روبیکا"></a>
                                @endif
                            </div>
                            <p class="mini-text mt-2">مشاهده فیلم محصول در:</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="row mt-5">
            <p class="title">توضیحات</p>
            @php
                $texts = explode('**', $product->description);
            @endphp
            @foreach ($texts as $text)
                <p class="titr text-justify">{{ trim($text) }}</p>
            @endforeach
        </div>
        @if (count($product_nutrients))
            <div class="row mt-5">
                <div class="shadow p-3 rounded-5 bg-light">
                    <h2 class="text mt-3">جدول ارزش غذایی در هر 100 گرم {{ $product->name }}</h2>
                    <div class="row">
                        @foreach ($product_nutrients as $nutrient)
                            <div class="col-md-6 my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text">{{ $nutrient->nutrient_name }}</span>
                                    <span class="text">{{ $nutrient->amount }}</span>
                                </div>
                                <div class="progress position-relative" role="progressbar">
                                    @if (round($nutrient->percent) > 0)
                                        <span
                                            class="position-absolute end-0 start-0 text-center text-black">{{ round($nutrient->percent) }}%</span>
                                    @endif
                                    <div class="progress-bar" data-progress="{{ round($nutrient->percent) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-danger mini-text mt-3 mb-0">* درصدها، نشان دهنده میزان پوشش نیاز یک انسان بالغ به هر
                        مغذی، در طول روز است.</p>
                </div>
            </div>
        @endif
        @if (count($product_relateds))
            @component('components.related_products', ['relateds' => $product_relateds])
            @endcomponent
        @endif
        @if (count($article_relateds))
            @component('components.related_articles', ['relateds' => $article_relateds])
            @endcomponent
        @endif
        <div class="row mt-3">
            <p class="title mt-5">نظرات</p>
            <div class="shadow rounded-5">
                <div class="text-start m-3">
                    <button class="btn btn-success dropdown-toggle add-comment-btn"
                        value="{{ Auth::check() ? true : false }}">ثبت نظر</button>
                </div>
                <div class="m-3 hidden add-comment-box">
                    <p class="text">افزودن نظر</p>
                    <div class="text-center" dir="ltr">
                        <button value="1" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="2" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="3" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="4" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="5" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                    </div>
                    <p class="text-center mt-1">امتیاز به محصول</p>
                    <form action="{{ route('comments.store', ['product_id' => $product->id]) }}" method="POST"
                        id="comment_form">
                        @csrf
                        <input type="hidden" value="0" name="like" id="like">
                        <textarea name="text" id="comment_text" class="w-100 p-3 border-none text rounded-3 bg-light" rows="5"
                            placeholder="نظر خود را بنویسید..."></textarea>
                        <div class="text-start mt-2">
                            <button class="btn btn-success" id="send_comment_btn">ارسال</button>
                        </div>
                    </form>
                </div>
                @if ($product->likes_count > 0)
                    <div class="m-3">
                        <p class="text mb-0">میانگین امتیاز: {{ $product->likes }} از 5</p>
                        <p class="text">تعداد نظرات: {{ $product->likes_count }}</p>
                    </div>
                    @foreach ($comments as $comment)
                        <div class="rounded-4 p-3 m-3 question-text w-75">
                            <div>
                                <img src="{{ $comment->user->avatar ?? '/images/icons/user.webp' }}" alt="کاربر"
                                    class="product-comment-avatar rounded-pill">
                                <span class="text mx-2">{{ $comment->user->name }}</span>
                                <span
                                    class="text">{{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($comment->created_at), true) }}</span>
                            </div>
                            <span class="user-like-box my-2" dir="ltr">
                                <div class="star star-sm @if ($comment->like >= 1) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 2) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 3) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 4) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 5) active @endif"></div>
                            </span>
                            <p class="mb-0">{{ $comment->text }}</p>
                        </div>
                        @if ($comment->answer)
                            <div class="rounded-4 p-3 m-3 answer-text w-75 me-auto">
                                <span class="text text-success">پاسخ پشتیبانی</span>
                                <p class="mb-0 mt-2">{{ $comment->answer }}</p>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p class="text m-3">تاکنون هیچ نظری برای این محصول ثبت نشده است.</p>
                @endif

                <div dir="ltr">
                    {{ $comments->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
