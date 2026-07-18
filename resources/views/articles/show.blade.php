@php
    use App\Helpers\Helper;
@endphp
@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('articles.show', ['id' => $article->id]) }}" />
    <meta name="description" content="{{ $article->meta_description }}">
    <meta name="keywords" content="{{ implode(', ', $article->keywords) }}" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $article->title }}" />
    <meta property="og:description" content="{{ $article->meta_description }}" />
    <meta property="og:url" content="{{ route('articles.show', ['id' => $article->id]) }}" />
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    <meta property="og:image" content="{{ config('app.url') . $article->images[0] }}" />
    @component('components.schema-sitename')
    @endcomponent
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ route('articles.show', ['id' => $article->id]) }}"
      },
      "headline": "{{ $article->title }}",
      "description": "{{ $article->meta_description }}",
      "image": "{{ config('app.url').$article->images[0] }}",
      "author": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}"
      },
      "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ config('app.url').'/images/logo/logo.png' }}"
        }
      },
      "datePublished": "{{ date('Y-m-d', strtotime($article->published)) }}",
      "dateModified": "{{ date('Y-m-d', strtotime($article->updated_at)) }}"
    }
  </script>

@endsection
@section('title', $article->title)
@section('content')
    <div class="container">
        {!! $article->content !!}

        @if (count($products_related))
            @component('components.related_products', ['relateds' => $products_related])
            @endcomponent
        @endif
        @if (count($articles_related))
            @component('components.related_articles', ['relateds' => $articles_related])
            @endcomponent
        @endif

        <div class="row">
            <p class="title mt-5">نظرات</p>
            <div class="shadow rounded-5">
                <div class="text-start m-3">
                    <button class="btn btn-success dropdown-toggle add-comment-btn"
                        value="{{ Auth::check() ? true : false }}">ثبت نظر</button>
                </div>
                <div class="m-3 hidden add-comment-box">
                    <p class="text">افزودن نظر</p>
                    <div class="text-center" dir="ltr">
                        <button value="1" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="2" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="3" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="4" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                        <button value="5" class="likes-star-btn">
                            <div class="star"></div>
                        </button>
                    </div>
                    <p class="text-center mt-1">امتیاز به مقاله</p>
                    <form action="{{ route('articles.comments', ['article_id' => $article->id]) }}" method="POST"
                        id="comment_form">
                        @csrf
                        <input type="hidden" value="0" name="like" id="like">
                        <textarea name="text" id="comment_text" class="w-100 p-3 border-none text rounded-3 bg-light" rows="5"
                            placeholder="نظر خود را بنویسید..."></textarea>
                        <div class="text-start mt-2">
                            <button class="btn btn-success" id="send_comment_btn">ارسال</button>
                        </div>
                    </form>
                </div>
                @if ($article->likes_count > 0)
                    <div class="m-3">
                        <p class="text mb-1 d-flex align-items-center">امتیاز: <span class="mini-star"></span>
                            {{ $article->likes }}</p>
                        <p class="text">تعداد نظرات: {{ $article->likes_count }}</p>
                    </div>
                    @foreach ($comments as $comment)
                        <div class="rounded-4 p-3 m-3 question-text w-75">
                            <div>
                                <img src="{{ $comment->user->avatar ?? '/images/icons/user.webp' }}" alt="کاربر"
                                    class="product-comment-avatar rounded-pill">
                                <span class="text mx-2">{{ $comment->user->name }}</span>
                                <span
                                    class="text">{{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($comment->created_at), true) }}</span>
                            </div>
                            <span class="user-like-box my-2" dir="ltr">
                                <div class="star star-sm @if ($comment->like >= 1) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 2) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 3) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 4) active @endif"></div>
                                <div class="star star-sm @if ($comment->like >= 5) active @endif"></div>
                            </span>
                            <p class="mb-0">{{ $comment->text }}</p>
                        </div>
                        @if ($comment->answer)
                            <div class="rounded-4 p-3 m-3 answer-text w-75 me-auto">
                                <span class="text text-success">پاسخ پشتیبانی</span>
                                <p class="mb-0 mt-2">{{ $comment->answer }}</p>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p class="text m-3">تاکنون هیچ نظری برای این محصول ثبت نشده است.</p>
                @endif

                <div dir="ltr">
                    {{ $comments->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
