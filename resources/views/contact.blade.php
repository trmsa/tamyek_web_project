@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('contact.index') }}" />
    <meta name="description"
        content="مشتریان عزیز می‌توانند با ارسال تیکت، تماس تلفنی، شبکه‌های اجتماعی، ارسال پیامک و ایمیل با ما در ارتباط باشند. ما مشتاقانه منتظر شنیدن نظرات،پیشنهادات و انتقادات شما عزیزان هستیم.">
    <meta property="og:title" content="درباره ما" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:url" content="{{ route('contact.index') }}" />
    <meta property="og:description"
        content="مشتریان عزیز می‌توانند با ارسال تیکت، تماس تلفنی، شبکه‌های اجتماعی، ارسال پیامک و ایمیل با ما در ارتباط باشند. ما مشتاقانه منتظر شنیدن نظرات،پیشنهادات و انتقادات شما عزیزان هستیم." />
    <meta property="og:image" content="{{ Config('app.url') }}/images/logo/logo.png" />
    @component('components.schema-sitename')
    @endcomponent

@endsection
@section('title', 'ارتباط با ما')
@section('content')
    <div class="container-xl">
        <div class="row">
            <p class="text-center title mb-4">راه‌های ارتباط با ما</p>
            <p class="text-center text"><a href="{{ route('tiket.index') }}">ارسال تیکت</a></p>
            <p class="text-center text">شماره تماس1: <a class="to-clipbord link" href="tel:09198451125">09198451125</a></p>
            <p class="text-center text">شماره تماس2: <a class="to-clipbord link" href="tel:09202018303">09202018303</a></p>
            <p class="text-center text">ایمیل: <span class="to-clipbord">mail.tamyek@gmail.com</span></p>
            <p class="text-center text">آدرس: زنجان،سهرورد، خ‌طالقانی،آجیل و خشکبار تام یک</p>
            {{-- <div style="width: 280px; overflow: hidden; height: 70px; max-width: 280px;" class="mb-2 mx-auto">
                        <div id="canvas-for-google-map" style="height: 70px; width: 280px; max-width: 280px;">
                            <iframe frameborder="0" title="{{ config('app.name') }}" src="https://maps.google.com/maps?q=36.073957,48.440149&amp;hl=es;z=14&amp;output=embed" style="height: 100%; width: 100%; border: 0px;" __idm_frm__="80"></iframe>
                        </div>
                        <a href="https://www.tubeembed.com/" id="authorize-maps-data" class="embedded-map-code">tubeembed.com</a>
                    </div> --}}
            <p class="text-center text">دانلود و نصب اپلیکیشن تام یک از:</p>
            <div class="d-flex justify-content-center align-items-center mt-1 mb-3">
                {{-- <a href="#" class="ms-3"><img src="/images/icons/playstore.webp" class="app-logo mx-auto"></a> --}}
                <a href="https://cafebazaar.ir/app/com.tamyek.app"><img src="/images/icons/bazar.webp" alt="دریافت از بازار"
                        class="app-logo-min mx-auto"></a>
                <a href="https://myket.ir/app/com.tamyek.app" class="mx-3"><img src="/images/icons/myket.webp"
                        alt="دریافت از مایکت" class="app-logo-min mx-auto"></a>
                <a href="/download/tamyek.apk"><img src="/images/icons/direct.webp" alt="دریافت مستقیم"
                        class="app-logo-min mx-auto"></a>
            </div>
            <p class="text-center text">آدرس ما در شبکه‌های اجتماعی:</p>
            <div class="d-flex align-items-center justify-content-center my-4">
                <a class="mx-3" href="https://www.instagram.com/com.tamyek/?utm_source=qr&r=nametag"><img
                        src="/images/icons/instageram.webp" alt="اینستاگرام" class="big-icon"></a>
                <a class="mx-3" href="https://t.me/tamyek"><img src="/images/icons/telegram.webp" alt="تلگرام"
                        class="big-icon"></a>
                <a class="mx-3" href="https://rubika.ir/tamyek_com"><img src="/images/icons/rubika.webp" alt="روبیکا"
                        class="big-icon"></a>
                <a class="mx-3" href="https://wa.me/09198451125"><img src="/images/icons/whatsapp.webp" alt="واتساپ"
                        class="big-icon"></a>
            </div>
        </div>
    </div>
@endsection
