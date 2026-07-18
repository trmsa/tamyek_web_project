@extends('admin.layout')
@section('title', 'ایجاد مقاله')
@section('admin_content')
    <p class="title mb-4 p-3">ایجاد مقاله</p>
    <form action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">عنوان</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">دسته‌بندی</label>
                <select name="category" class="form-select">
                    <option value="public" @selected(old('category') == 'public')>عمومی</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">نوع مقاله</label>
                <select name="type" class="form-select">
                    <option value="article" @selected(old('type') == 'article')>مقاله</option>
                    <option value="news" @selected(old('type') == 'news')>خبری</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">توضیحات متا</label>
                <textarea name="meta_description" rows="3" class="form-control" required>{{ old('meta_description') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">انتشار</label>
                <select name="published" class="form-select">
                    <option value="0" @selected(old('published') == '0')>عدم انتشار</option>
                    <option value="1" @selected(old('published') == '1')>منتشر</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">نویسنده</label>
                <input type="text" class="form-control" name="auther" value="{{ old('auther') }}" required>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">کلمات کلیدی</label>
                <button class="btn btn-primary btn-sm" type="button" id="admin_add_keywords_input_btn">افزودن</button>
                <div id="admin_keywords_input_box" class="d-flex flex-wrap">
                    @if (old('keywords'))
                        @foreach (old('keywords') as $keyword)
                            <div class="ms-2">
                                <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                                <input type="text" class="form-control mini-input" value="{{ $keyword }}"
                                    name="keywords[]">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">محتوا(html)</label>
                <textarea name="content" rows="20" dir="ltr" class="form-control" placeholder="فرمت آدرس تصویر __image_1"
                    required>{!! old('content') !!}</textarea>
            </div>
            <div class="row" id="admin_products_images_box">
                <label class="form-label mb-4">تصاویر <button class="btn btn-primary btn-sm" type="button"
                        id="admin_add_image_input_btn">افزودن</button></label>
                <input type="hidden" value="1" name="images_count" id="admin_products_images_count">
                <div class="col-md-4 col-xl-3 mb-3">
                    <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                    <label for="admin_product_image_input_0" class="w-100" role="button">
                        <span class="d-block mb-2">تصویر 1:</span>
                        <img src="/images/icons/camera.webp" class="admin-product-image w-100" id="admin_product_image_0">
                    </label>
                    <input type="file" name="images_0" value="same" id="admin_product_image_input_0"
                        data-image="#admin_product_image_0" class="custom-file-input-hidden admin-product-image-input">
                </div>
            </div>

            <div>
                <button class="btn btn-primary">ایجاد</button>
            </div>
        </div>
    </form>
@endsection
