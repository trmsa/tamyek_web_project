@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'نظر مقاله')
@section('admin_content')
    <div class="row">
        <p class="title mb-4 p-3">نظر {{ $comment->user->name }} درباره مقاله {{ $article->title }} با کد {{ $article->id }}
        </p>
        <div class="shadow rounded-4 p-4">
            <div class="text-start">
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    حذف نظر
                </button>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                dir="rtl">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">حذف نظر</h1>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            آیا از حذف نظر ({{ $comment->user->name }}) اطمینان دارید؟
                        </div>
                        <div class="modal-footer justify-content-start">
                            <form action="{{ route('admin.articles.delete_comment', ['id' => $comment->id]) }}"
                                method="POST">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm">حذف نظر</button>
                            </form>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Modal -->
            <div class="tikets-box my-4">
                @if ($comment->text)
                    <div class="w-75 me-auto mb-3">
                        <p class="text p-3 answer-text rounded-4 mb-0">{{ $comment->text }}</p>
                        <span class="mini-text me-2">{{ $comment->user->name }}
                            {{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($comment->created_at), true) }}</span>
                    </div>
                @endif
                @if ($comment->answer)
                    <div class="w-75 ms-auto mb-3">
                        <p class="text p-3 question-text rounded-4 mb-0">{{ $comment->answer }}</p>
                        <span class="mini-text d-flex align-items-end me-2">پشتیبانی
                            {{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($comment->updated_at), true) }}</span>
                    </div>
                @endif
            </div>
            @if (!$comment->answer)
                <div class="add-tiket-box">
                    <form action="{{ route('admin.articles.answer_comment', ['id' => $comment->id]) }}" method="POST">
                        @csrf
                        <textarea name="answer" class="w-100 p-3 rounded-3 border-none text bg-light" rows="5" required
                            placeholder="پاسخ خود را بنویسید..."></textarea>
                        <div class="text-start mt-2">
                            <button class="btn btn-success">ارسال و تایید</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
