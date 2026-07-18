@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('category') }}" />
    @if (isset($categories[3]))
        <meta name="description"
            content="دسته‌بندی محصولات تام یک شامل: {{ $categories[0]->name }}، {{ $categories[1]->name }}، {{ $categories[2]->name }}، {{ $categories[3]->name }}، ...">
    @endif
    <meta property="og:title" content="دسته‌بندی محصولات" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ route('category') }}" />
    @if (isset($categories[3]))
        <meta property="og:description"
            content="دسته‌بندی محصولات تام یک شامل: {{ $categories[0]->name }}، {{ $categories[1]->name }}، {{ $categories[2]->name }}، {{ $categories[3]->name }}، ..." />
    @endif
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent
@endsection
@section('title', 'دسته‌بندی‌ها')
@section('content')
    <div class="container-xl">
        <div class="row">
            <h1 class="title mb-4">دسته‌بندی محصولات تام یک</h1>
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
@endsection
