@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('discounts') }}" />
    @if (isset($products[2]))
        <meta name="description"
            content="محصولات تخفیف دار تام یک شامل: {{ $products[0]->name }}، {{ $products[1]->name }}، {{ $products[2]->name }} و ... است">
    @endif
    <meta property="og:title" content="تخفیف‌های تام یک" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ route('discounts') }}" />
    @if (isset($products[2]))
        <meta property="og:description"
            content="محصولات تخفیف‌دار تام یک شامل: {{ $products[0]->name }}، {{ $products[1]->name }}، {{ $products[2]->name }} و ... است" />
    @endif
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent
@endsection
@section('title', 'تخفیف‌های تام یک')
@section('content')
    <div class="container-xl">
        <div class="row">
            <h1 class="title mb-4">محصولات تخفیف‌دار تام یک</h1>
            @foreach ($products as $product)
                @component('components.product', ['product' => $product])
                @endcomponent
            @endforeach
        </div>
    </div>
@endsection
