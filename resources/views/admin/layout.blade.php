@extends('_master')
@section('meta')
    <meta name="robots", content="nofollow,noindex">
@endsection
@section('adminjs')
    @vite('resources/js/admin.js')
@endsection
@section('content')
    <div class="container-xl">
        <div class="row">
            <div>
                <button class="btn btn-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
                    aria-controls="offcanvasScrolling">
                    <img src="/images/icons/content.webp" alt="فهرست" class="mini-icon">
                    فهرست
                </button>
            </div>

            <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasScrolling"
                aria-labelledby="offcanvasScrollingLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title ms-auto" id="offcanvasScrollingLabel">منوی مدیریت</h5>
                    <button type="button" class="btn-close ms-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.home') }}" class="link d-block p-2">داشبورد</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.users.index') }}" class="link d-block p-2">مدیریت کاربران</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.sales.index') }}" class="link d-block p-2">مدیریت فروش</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.category.index') }}" class="link d-block p-2">مدیریت دسته‌بندی</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.products.index') }}" class="link d-block p-2">مدیریت محصولات</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.products.inventory') }}" class="link d-block p-2">مدیریت موجودی</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.tikets.index') }}" class="link d-block p-2">مدیریت تیکت‌ها</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.comments.index') }}" class="link d-block p-2">مدیریت نظرات</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.articles.comments') }}" class="link d-block p-2">مدیریت نظرات
                                مقالات</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.discounts.index') }}" class="link d-block p-2">مدیریت تخفیفات</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.postal.index') }}" class="link d-block p-2">مدیریت هزینه پست</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.sliders.index') }}" class="link d-block p-2">مدیریت اسلایدر</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.facts.index') }}" class="link d-block p-2">آمار</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.articles.index') }}" class="link d-block p-2">مقالات</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.sessions') }}" class="link d-block p-2">جلسات</a>
                        </li>
                        <li class="my-2 rounded-1 offcanvas-admin-item" data-bs-dismiss="offcanvas">
                            <a href="{{ route('admin.app_version') }}" class="link d-block p-2">ورژن</a>
                        </li>
                    </ul>
                </div>
            </div>
            @yield('admin_content')
        </div>
    @endsection
