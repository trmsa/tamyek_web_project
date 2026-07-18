@extends('admin.layout')
@section('title', 'داشبورد')
@section('admin_content')
    <div class="row">
        <p class="title my-4">خلاصه آمار</p>
        <div class="col-md-3 p-4">
            <div class="rounded-4 bg-primary p-2 text-light">
                <p class="titr">کل خرید‌ها:</p>
                <p class="titr text-start mb-0">{{ number_format($all_sales_count) }}</p>
            </div>
        </div>
        <div class="col-md-3 p-4">
            <div class="rounded-4 bg-success p-2 text-light">
                <p class="titr">خرید‌های جدید:</p>
                <p class="titr text-start mb-0">{{ number_format($new_sales_count) }}</p>
            </div>
        </div>
        <div class="col-md-3 p-4">
            <div class="rounded-4 bg-info p-2 text-light">
                <p class="titr">جمع درآمد:</p>
                <p class="titr text-start mb-0">{{ number_format($total_price_sales) }} تومان</p>
            </div>
        </div>
        <div class="col-md-3 p-4">
            <div class="rounded-4 bg-secondary p-2 text-light">
                <p class="titr">تعداد کاربران:</p>
                <p class="titr text-start mb-0">{{ number_format($users_count) }} نفر</p>
            </div>
        </div>
        <div class="col-md-3 p-4">
            <div class="rounded-4 bg-warning p-2 text-light">
                <p class="titr">تیکت‌های جدید:</p>
                <p class="titr text-start mb-0">{{ number_format($new_tikets) }}</p>
            </div>
        </div>
        <div class="col-md-3 p-4">
            <div class="rounded-4 bg-danger p-2 text-light">
                <p class="titr">نظرات جدید:</p>
                <p class="titr text-start mb-0">{{ number_format($new_comments) }}</p>
            </div>
        </div>
    </div>
@endsection
