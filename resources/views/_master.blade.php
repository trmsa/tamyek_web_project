<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="tamyek" />
    <link rel="manifest" href="/site.webmanifest" />
    @yield('meta')
    @vite('resources/scss/app.scss')
    <title>@yield('title')</title>
</head>

<body>
    <div class="alert alert-danger hidden"></div>
    <div class="alert alert-success hidden"></div>
    @if (session()->has('message_danger'))
        <div class="alert alert-danger">
            <button class="btn btn-danger-subtle btn-sm position-absolute alert-close-btn">✕</button>
            {{ session('message_danger') }}
        </div>
    @endif
    @if (session()->has('message_success'))
        <div class="alert alert-success">
            <button class="btn btn-danger-subtle btn-sm position-absolute alert-close-btn">✕</button>
            {{ session('message_success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <button class="btn btn-danger-subtle btn-sm position-absolute alert-close-btn">✕</button>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif
    @include('partial.header')

    <main class="master-content">
        @yield('content')
    </main>

    @include('partial.footer')

    @vite('resources/js/app.js')
    @yield('adminjs')
</body>

</html>
