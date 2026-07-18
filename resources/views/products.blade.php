@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ url()->full() }}" />
    <meta name="description"
        content="تام یک، فروشگاه اینترنتی آجیل و خشکبار تازه؛ با جستجوی هوشمند، جدول ارزش غذایی، ترکیب دلخواه آجیل و خرید خرده و عمده با قیمت مناسب.">
    <meta property="og:title" content="محصولات تام یک" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ url()->full() }}" />
    <meta property="og:description"
        content="تام یک، فروشگاه اینترنتی آجیل و خشکبار تازه؛ با جستجوی هوشمند، جدول ارزش غذایی، ترکیب دلخواه آجیل و خرید خرده و عمده با قیمت مناسب." />
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent

@endsection
@section('title', 'محصولات تام یک')
@section('content')
    <div class="container-xl">
        <div class="row">
            <div><button class="btn btn-light" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"><img
                        src="/images/icons/content.webp" alt="دسته‌بندی" class="mini-icon"> دسته بندی</button></div>

            <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasScrolling"
                aria-labelledby="offcanvasScrollingLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasScrollingLabel">دسته بندی محصولات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul>
                        @foreach ($categories as $category)
                            <li class="p-2 rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                                <a href="{{ route('products_category', ['id' => $category->id]) }}" class="d-block link">
                                    <h1 class="titr m-0">{{ $category->name }}</h1>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <h1 class="title text-center">محصولات تام یک</h1>
            @component('components.search', ['products_name' => $products_name, 'nutrients' => $nutrients])
            @endcomponent
        </div>
        <div class="row">
            @foreach ($product_categories as $category)
                <div class="mt-4 products-category-products">
                    <h1 class="title mb-2">{{ $category->name }}</h1>
                    <div class="row align-items-center">
                        @foreach ($products as $product)
                            @if ($category->id == $product->category_id)
                                @component('components.product', ['product' => $product])
                                @endcomponent
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
