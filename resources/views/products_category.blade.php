@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('products_category', ['id' => $product_category->id]) }}" />
    {{-- <meta name="description" content="{{ $product_category->meta_description }}"> --}}
    <meta property="og:title" content="{{ $product_category->name }}" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ route('products_category', ['id' => $product_category->id]) }}" />
    <meta property="og:description" content="{{ $product_category->meta_description }}" />
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent

@endsection
@section('title', $product_category->name)
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
                                    <h2 class="titr m-0">{{ $category->name }}</h2>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <h1 class="title text-center">محصولات دسته {{ $product_category->name }}</h1>
            @component('components.search', ['products_name' => $products_name, 'nutrients' => $nutrients])
            @endcomponent
        </div>
        <div class="row">
            <div class="mt-4 products-category-products">
                <div class="row align-items-center">
                    @foreach ($products as $product)
                        @component('components.product', ['product' => $product])
                        @endcomponent
                    @endforeach
                </div>
            </div>
        </div>
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
