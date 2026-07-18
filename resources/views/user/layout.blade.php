@extends('_master')
@section('meta')
    <meta name="robots", content="nofollow,noindex">
@endsection
@section('content')
    <div class="container-xl">
        <div class="row mb-4">
            <div><button class="btn btn-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
                    aria-controls="offcanvasScrolling"><img src="/images/icons/content.webp" alt="منو"
                        class="mini-icon">منو </button></div>

            <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasScrolling"
                aria-labelledby="offcanvasScrollingLabel">
                <div class="offcanvas-body">
                    <ul>
                        <li class="rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                            <a class="link d-block p-2 my-2" href="{{ route('user.index') }}">مشخصات کاربر</a>
                        </li>
                        <li class="rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                            <a class="link d-block p-2 my-2" href="{{ route('user.edit') }}">ویرایش مشخصات</a>
                        </li>
                        <li class="rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                            <a class="link d-block p-2 my-2" href="{{ route('cart.index') }}">سبد خرید</a>
                        </li>
                        <li class="rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                            <a class="link d-block p-2 my-2" href="{{ route('favorits.index') }}">علاقه‌مندی‌ها</a>
                        </li>
                        <li class="rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                            <a class="link d-block p-2 my-2" href="{{ route('user.records') }}">سوابق خرید</a>
                        </li>
                        <li class="rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                            <a class="link d-block p-2 my-2" href="{{ route('tiket.index') }}">تیکت</a>
                        </li>
                        <li class="rounded-3 offcanvas-body-item" data-bs-dismiss="offcanvas">
                            <a class="link d-block p-2 my-2" href="{{ route('logout') }}">خروج از حساب کاربری</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            @yield('content_user')
        </div>
    </div>
@endsection
