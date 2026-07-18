@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('search') }}" />
    <meta name="description"
        content="در تام یک می‌توانید محصول مورد نظر خود را براساس مغذی‌ها جستجو و پیدا کنید و میزان مغذی‌های موجود در هر محصول را ببینید.">
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    @component('components.schema-sitename')
    @endcomponent
@endsection
@section('title', 'جستجو در تام یک')
@section('content')
    <div class="container-xl">
        <div class="row">
            <h1 class="title text-center">جستجو در محصولات تام یک</h1>
            @component('components.search', [
                'products_name' => $products_name,
                'nutrients' => $nutrients,
                'base' => $base ?? null,
                'word' => $word ?? null,
                'nutrients_id' => $nutrients_id ?? null,
            ])
            @endcomponent
        </div>
        @if (isset($base))
            <div id="{{ $base }}_search_result">
                <div class="row align-items-center justify-content-center my-4">
                    <p class="text text-center">نتایج جستجو:</p>
                    @if ($products && $products->isNotEmpty())
                        @foreach ($products as $product)
                            <div class="col-4 col-sm-2 col-xl-1">
                                <a href="{{ route('products.show', ['id' => $product->id]) }}"
                                    class="small-product-link position-relative d-block mx-auto text-center">
                                    <img src="{{ $product->images[0] }}" alt="{{ $product->name }}"
                                        title="{{ $product->name }}" class="rounded-pill shadow small-product-image">
                                    <h2 class="text-center mt-2 product-title">{{ $product->name }}</h2>
                                </a>
                            </div>
                        @endforeach
                    @elseif ($base == 'name')
                        <p class="text-center titr text-danger">متاسفانه محصول مورد نظر شما یافت نشد.</p>
                    @else
                        <p class="text-center titr text-danger">متاسفانه محصولی با مغذی‌های انتخاب شده، یافت نشد.</p>
                    @endif
                </div>
                @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="row" dir="ltr">
                        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        @endif
    @endsection
