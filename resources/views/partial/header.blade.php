@php
    $user = Auth::user();
    $shoping_cart = $user ? $user->shoping_cart : null;
    $shoping_cart = $shoping_cart ? count($shoping_cart) : 0;
@endphp
<header class="fixed-top custom-light d-none d-md-block">
    <div class="container-xl my-2">
        <div class="row align-items-center">
            <div class="w-fit-content">
                <a href="{{ route('home') }}"><img src="/images/logo/logo.png" class="logo" alt="تام یک"></a>
            </div>
            <div class="col-md-7 d-flex mx-auto justify-content-evenly">

                <div><a class="header-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">خانه</a>
                </div>
                <div><a class="header-link {{ request()->is('products') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">محصولات</a></div>
                <div><a class="header-link {{ request()->is('nuts') ? 'active' : '' }}" href="{{ route('nuts') }}">آجیل
                        دلخواه</a></div>
                <div><a class="header-link {{ request()->is('articles') ? 'active' : '' }}"
                        href="{{ route('articles.index') }}">مقالات</a></div>
                <div><a class="header-link {{ request()->is('search') ? 'active' : '' }}"
                        href="{{ route('search') }}">جستجو</a></div>
                <div><a class="header-link {{ request()->is('contact') ? 'active' : '' }}"
                        href="{{ route('contact.index') }}">ارتباط با ما</a></div>


            </div>
            <div class="w-fit-content">
                <div class="d-flex justify-content-end align-items-center">
                    <a class="ms-4 position-relative" href="{{ route('cart.index') }}">
                        <img class="header-shoping-cart" src="/images/icons/shoping-cart.webp" alt="سبد خرید">
                        @auth
                            @if ($shoping_cart)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    {{ $shoping_cart }}
                                </span>
                            @endif
                        @endauth
                    </a>
                    @auth
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                فهرست
                            </button>
                            <ul class="dropdown-menu text-end dropdown-menu-dark">
                                @if (Auth::user()->is_admin())
                                    <li><a class="dropdown-item {{ request()->is('admin') ? 'active' : '' }}"
                                            href="{{ route('admin.home') }}">مدیریت</a></li>
                                @endif
                                <li><a class="dropdown-item {{ request()->is('favorits') ? 'active' : '' }}"
                                        href="{{ route('favorits.index') }}">علاقمندی‌ها</a></li>
                                <li><a class="dropdown-item {{ request()->is('user') ? 'active' : '' }}"
                                        href="{{ route('user.index') }}">حساب کاربری</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">خروج</a></li>
                            </ul>
                        </div>
                    @else
                        <a class="btn btn-success" href="{{ route('login') }}">ورود | ثبت نام</a>
                    @endauth

                </div>
            </div>
        </div>
    </div>
</header>

<header class="fixed-bottom d-md-none custom-light">
    <div class="d-flex justify-content-between py-2 px-4">
        <span class="d-flex flex-column align-items-center mobile-header-link mobile-header-menu-btn">
            <img src="/images/icons/content.webp" alt="menu" class="mobile-header-menu-icon">
            <span>منو</span>
        </span>
        <a href="/" class="d-flex flex-column align-items-center mobile-header-link">
            <img src="/images/icons/home.webp" alt="home" class="mobile-header-menu-icon">
            <span class="{{ Route::is('home') ? 'active' : '' }}">خانه</span>
        </a>
        <a href="{{ route('cart.index') }}"
            class="d-flex flex-column align-items-center position-relative mobile-header-link">
            <img class="mobile-header-menu-icon" alt="shopping cart" src="/images/icons/shoping-cart.webp">
            <span class="{{ Route::is('cart.index') ? 'active' : '' }}">سبد خرید</span>
            @auth
                @if ($shoping_cart)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                        {{ $shoping_cart }}
                    </span>
                @endif
            @endauth
        </a>
        @auth
            <a href="{{ route('user.index') }}" class="d-flex flex-column align-items-center mobile-header-link">
                <img src="/images/icons/user.webp" alt="profile" class="mobile-header-menu-icon">
                <span class="{{ Route::is('user.index', 'user.edit') ? 'active' : '' }}">پروفایل</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="d-flex flex-column align-items-center mobile-header-link">
                <img src="/images/icons/user.webp" alt="sign in" class="mobile-header-menu-icon">
                <span>ورود</span>
            </a>
        @endauth
    </div>
    <div class="position-absolute bg-light mobile-header-menu-list">
        <a href="{{ route('category') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('category') ? 'active' : '' }}">دسته‌بندی‌ها</a>
        <a href="{{ route('products.index') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('products') ? 'active' : '' }}">محصولات</a>
        <a href="{{ route('nuts') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('nuts') ? 'active' : '' }}">آجیل دلخواه</a>
        <a href="{{ route('search') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('search') ? 'active' : '' }}">جستجو</a>
        <a href="{{ route('discounts') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('discounts') ? 'active' : '' }}">تخفیف‌ها</a>
        <a href="{{ route('articles.index') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('articles') ? 'active' : '' }}">مقالات</a>
        <a href="{{ route('favorits.index') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('favorits') ? 'active' : '' }}">علاقمندی‌ها</a>
        <a href="{{ route('contact.index') }}"
            class="d-block p-2 mobile-header-menu-link {{ request()->is('contact') ? 'active' : '' }}">ارتباط با ما</a>
    </div>
</header>
