@extends('admin.layout')
@section('title', 'آمار')
@section('admin_content')
    <p class="title p-3">آمار‌ها</p>
    <p class="titr mb-2 p-3">آمار‌ کاربران</p>
    <div class="d-flex flex-wrap">
        <div class="ms-4">
            <a href="{{ route('admin.facts.users.city') }}" class="btn btn-success">شهرهای کاربران</a>
        </div>
        <div class="ms-4">
            <a href="{{ route('admin.facts.users.sales') }}" class="btn btn-secondary">خرید کاربران</a>
        </div>
    </div>
    <p class="titr mt-4 mb-2 p-3">آمار‌ محصولات</p>
    <div class="d-flex flex-wrap">
        <div class="ms-4">
            <a href="{{ route('admin.facts.products.inventory') }}" class="btn btn-success">موجودی محصولات</a>
        </div>
        <div class="ms-4">
            <a href="{{ route('admin.facts.products.sales') }}" class="btn btn-secondary">پرفروش‌ترین محصولات</a>
        </div>
        <div class="ms-4">
            <a href="{{ route('admin.facts.products.likes') }}" class="btn btn-warning">محبوب‌ترین محصولات</a>
        </div>
    </div>
    <p class="titr mt-4 mb-2 p-3">آمار‌ فروش‌ها</p>
    <div class="d-flex flex-wrap">
        <div class="ms-4">
            <a href="{{ route('admin.facts.sales.index') }}" class="btn btn-primary">آمار فروش‌ها</a>
        </div>
    </div>
@endsection
