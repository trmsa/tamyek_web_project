@php
    use App\Helpers\Helper;
@endphp
<a href="{{ route('articles.show', ['id' => $article->id]) }}"
    class="@if (isset($related_class)) related-product-box @else col-6 col-md-4 col-lg-3 @endif link d-flex flex-column mt-3 shadow rounded-4 p-1 article-box">
    <img src="{{ $article->images[0] }}" alt="{{ $article->title }}" class="rounded-3 h-50 w-100 mx-auto">
    <h2 class="titr mt-2 text-center mb-auto">{{ $article->title }}</h2>
    @if ($article->type == 'article')
        <div class="d-flex justify-content-between px-1">
            <p class="mini-text mb-1 w-fit-content">نویسنده: {{ $article->auther }}</p>
            <p class="mini-text mb-1 w-fit-content d-flex align-items-center"><span class="mini-star"></span>
                {{ $article->likes }} :امتیاز</p>
        </div>
    @endif
    <div class="d-flex justify-content-between px-1">
        <p class="mini-text mb-0 w-fit-content">انتشار:
            {{ Helper::fa_date('%y/%m/%d', strtotime($article->published)) }}</p>
        <p class="mini-text mb-0 w-fit-content">{{ $article->view_count }} :بازدید</p>
    </div>
</a>
