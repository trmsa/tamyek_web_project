@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'نظرات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">نظرات {{ $product->name }}</p>
        <div class="shadow rounded-5">
            @if ($product->likes_count > 0)
                <div class="m-3">
                    <p class="titr mb-0">میانگین امتیاز: {{ $product->likes }}</p>
                    <p class="titr">تعداد نظرات: {{ $product->likes_count }}</p>
                </div>
                @foreach ($comments as $comment)
                    <div class="rounded-4 p-3 m-3 question-text w-75">
                        <div>
                            <img src="{{ $comment->user->avatar ?? '/images/icons/user.webp' }}"
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
                <p class="titr m-3">تاکنون هیچ نظری برای این محصول ثبت نشده است.</p>
            @endif
        </div>

        @if ($comments instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $comments->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
