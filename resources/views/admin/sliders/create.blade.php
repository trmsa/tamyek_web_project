@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'ایجاد اسلایدر')
@section('admin_content')
    <p class="title mb-4 p-3">ایجاد اسلایدر‌</p>
    <form action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">عنوان</label>
                <input type="text" class="form-control" name="title">
            </div>
            <div class="col-md-6">
                <label class="form-label">متن</label>
                <input type="text" class="form-control" name="text">
            </div>
            <div class="col-md-6">
                <label class="form-label">هدف</label>
                <select name="for" id="for" class="form-select">
                    <option value="web">وب</option>
                    <option value="mobile">موبایل</option>
                </select>
            </div>
            <div class="col-12 my-3">
                <label for="admin_slider_image_input" role="button">
                    <span class="d-block mb-2">تصویر:</span>
                    <img src="/images/icons/camera.webp" id="admin_slider_image">
                </label>
                <input type="file" name="image" id="admin_slider_image_input" class="custom-file-input-hidden"
                    required>
            </div>
            <div>
                <button class="btn btn-primary">ایجاد</button>
            </div>
        </div>
    </form>
@endsection
