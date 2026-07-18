@php
    use App\Helpers\Helper;
@endphp
@extends('user.layout')
@section('meta')

    <meta name="robots", content="nofollow,noindex">

@endsection
@section('title', 'ارسال تیکت')
@section('content_user')
    <div class="container-xl">
        <div class="row">
            <h1 class="title mb-3">تیکت پشتیبانی</h1>
            <div class="shadow rounded-4 p-4">
                <div class="text-start">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        حذف گفتگو
                    </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" dir="rtl">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">حذف گفتگو</h1>
                                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                آیا از حذف گفتگو اطمینان دارید؟
                            </div>
                            <div class="modal-footer justify-content-start">
                                <form action="{{ route('tiket.delete') }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm">حذف گفتگو</button>
                                </form>
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">بستن</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Modal -->
                <div class="tikets-box my-4">
                    @foreach ($tikets as $tiket)
                        @if ($tiket->owner == 'user')
                            <div class="w-75 ms-auto mb-3">
                                <p class="text p-3 question-text rounded-4 mb-0">{{ $tiket->message }}</p>
                                <span class="mini-text d-flex align-items-end me-2">
                                    <img src="{{ $tiket->read ? 'images/icons/seen.webp' : 'images/icons/unseen.webp' }}"
                                        alt="seen" class="mini-icon ms-2">
                                    {{ $user->name }}
                                    {{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($tiket->created_at), true) }}
                                </span>
                            </div>
                        @else
                            <div class="w-75 me-auto mb-3">
                                <p class="text p-3 answer-text rounded-4 mb-0">{{ $tiket->message }}</p>
                                <span class="mini-text me-2">پشتیبانی
                                    {{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($tiket->created_at), true) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="add-tiket-box">
                    <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data" id="tiket_form">
                        @csrf
                        <textarea name="message" id="tiket_text" class="w-100 p-3 rounded-3 border-none text bg-light" rows="5" required
                            placeholder="سوال خود را بنویسید..."></textarea>
                        <label for="tiket_attachment_input" role="button">
                            <img src="/images/icons/attachment.webp" alt="پیوست" class="icon">
                        </label>
                        <span id="tiket_attachment_name"></span>
                        <input type="file" name="attachment" class="custom-file-input-hidden"
                            id="tiket_attachment_input">
                        <div class="text-start mt-2">
                            <button class="btn btn-success">ارسال</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
