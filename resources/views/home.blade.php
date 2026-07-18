@extends('_master')

@section('meta')

    <link rel="canonical" href="{{ route('home') }}" />
    <meta name="description"
        content="تام یک، فروشگاه اینترنتی آجیل و خشکبار تازه؛ با جستجوی هوشمند، جدول ارزش غذایی، ترکیب دلخواه آجیل و خرید خرده و عمده با قیمت مناسب.">
    <meta name="keywords"
        content="{{ $str_keywords }} ،تام یک، آجیل و خشکبار، مواد غذایی سالم، خوراکی سالم، حبوبات، میوه خشک، چیپس میوه" />
    <meta property="og:title" content="آجیل و خشکبار تام یک" />
    <meta property="og:local" content="fa_IR" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ Config('app.url') }}" />
    <meta property="og:description"
        content="تام یک، فروشگاه اینترنتی آجیل و خشکبار تازه؛ با جستجوی هوشمند، جدول ارزش غذایی، ترکیب دلخواه آجیل و خرید خرده و عمده با قیمت مناسب." />
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent
@endsection
@section('title', 'آجیل و خشکبار تام یک')
@section('content')
    <div class="container-xl">
        <h1 class="big-title text-success text-center">آجیل و خشکبار تام یک</h1>
        @if (count($slide_images))
            <div class="row mt-4">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators mb-0">
                        @foreach ($slide_images as $key => $image)
                            <button type="button" data-bs-target="#carouselExampleIndicators"
                                data-bs-slide-to="{{ $key }}"
                                @if ($key == 0) class="active" aria-current="true" @endif
                                aria-label="Slide {{ $key + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach ($slide_images as $key => $image)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ $image }}" class="d-block w-100 rounded-4" alt="تام یک">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">قبلی</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">بعدی</span>
                    </button>
                </div>
            </div>
        @endif
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-success ms-4">مشاهده محصولات</a>
            <a href="{{ route('nuts') }}" class="btn btn-success">ساخت آجیل دلخواه</a>
        </div>
        <div class="mt-4">
            <p class="title mb-2">دسته‌بندی محصولات</p>
            <div class="row align-items-center">
                @foreach ($categories as $category)
                    <div class="col-4 col-md-3 col-lg-2">
                        <a href="{{ route('products_category', ['id' => $category->id]) }}"
                            class="home-category-link d-block mx-auto">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" title="{{ $category->name }}"
                                class="w-100 rounded-3 shadow category-image" width="400" height="400">
                            <h2 class="text-center mt-2 product-title">{{ $category->name }}</h2>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @if ($amazing_discounts->isNotEmpty())
            <div class="my-5">
                <p class="title mb-2">تخفیفات شگفت‌انگیز</p>
                <div class="row align-items-center">
                    @foreach ($amazing_discounts as $product)
                        @component('components.product', ['product' => $product])
                        @endcomponent
                    @endforeach
                </div>
                <div class="text-start">
                    <a href="{{ route('discounts') }}" class="btn btn-success text">مشاهده بیشتر</a>
                </div>
            </div>
        @endif
        <div class="my-5">
            <p class="title mb-2">محبوب‌ترین محصولات</p>
            <div class="row align-items-center">
                @foreach ($pupolar as $product)
                    @component('components.product', ['product' => $product])
                    @endcomponent
                @endforeach
            </div>
            <div class="text-start">
                <a href="{{ route('pupolar') }}" class="btn btn-success text">مشاهده بیشتر</a>
            </div>
        </div>
        <div class="my-5">
            <p class="title mb-2">پرفروش‌ترین محصولات</p>
            <div class="row align-items-center">
                @foreach ($bestselling as $product)
                    @component('components.product', ['product' => $product])
                    @endcomponent
                @endforeach
            </div>
            <div class="text-start">
                <a href="{{ route('bestselling') }}" class="btn btn-success text">مشاهده بیشتر</a>
            </div>
        </div>
        <div class="my-5">
            <p class="title mb-2">جدیدترین محصولات</p>
            <div class="row align-items-center">
                @foreach ($most_visited as $product)
                    @component('components.product', ['product' => $product])
                    @endcomponent
                @endforeach
            </div>
            <div class="text-start">
                <a href="{{ route('most_visited') }}" class="btn btn-success text">مشاهده بیشتر</a>
            </div>
        </div>
    </div>
@endsection
