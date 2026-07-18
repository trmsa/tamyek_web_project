@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'ایجاد دسته‌بندی')
@section('admin_content')
    <p class="title mb-4 p-3">ایجاد دسته‌بندی‌</p>
    <form action="{{ route('admin.category.store') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">نام</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">توضیحات متا</label>
                <textarea name="meta_description" rows="3" class="form-control" required></textarea>
            </div>
            <div class="col-12 my-3">
                <label for="admin_category_image_input" role="button">
                    <span class="d-block mb-2">تصویر:</span>
                    <img src="/images/icons/camera.webp" alt="افزودن" id="admin_category_image">
                </label>
                <input type="file" name="image" id="admin_category_image_input" class="custom-file-input-hidden"
                    required>
            </div>
            <div>
                <button class="btn btn-primary">ایجاد</button>
            </div>
        </div>
    </form>
@endsection
