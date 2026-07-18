@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('bestselling') }}" />
    @if (isset($product[3]->name))
        <meta name="description"
            content="پرفروش‌ترین محصولات تام یک شامل: {{ $products[0]->name }}، {{ $products[1]->name }}، {{ $products[2]->name }}، {{ $products[3]->name }}، ...">
    @endif
    <meta property="og:title" content="پرفروش‌ترین محصولات" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ route('bestselling') }}" />
    @if (isset($product[3]->name))
        <meta property="og:description"
            content="پرفروش‌ترین محصولات تام یک شامل: {{ $products[0]->name }}، {{ $products[1]->name }}، {{ $products[2]->name }}، {{ $products[3]->name }}، ..." />
    @endif
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent
@endsection
@section('title', 'پرفروش‌ترین محصولات تام یک')
@section('content')
    <div class="container-xl">
        <div class="row">
            <h1 class="title mb-4">پرفروش‌‌های تام یک</h1>
            @foreach ($products as $product)
                @component('components.product', ['product' => $product])
                @endcomponent
            @endforeach

        </div>
    </div>
@endsection
