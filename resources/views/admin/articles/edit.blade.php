@extends('admin.layout')
@section('title', 'ویرایش مقاله')
@section('admin_content')
    <p class="title mb-4 p-3">ویرایش مقاله</p>
    <form action="{{ route('admin.articles.update', ['id' => $article->id]) }}" enctype="multipart/form-data" method="post">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">عنوان</label>
                <input type="text" class="form-control" name="title" value="{{ $article->title }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">دسته‌بندی</label>
                <select name="category" class="form-select">
                    <option value="public" @selected($article->category == 'public')>عمومی</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($article->category == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">نوع مقاله</label>
                <select name="type" class="form-select">
                    <option value="article" @selected($article->type == 'article')>مقاله</option>
                    <option value="news" @selected($article->type == 'news')>خبری</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">توضیحات متا</label>
                <textarea name="meta_description" rows="3" class="form-control" required>{{ $article->meta_description }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">انتشار</label>
                <select name="published" class="form-select">
                    <option value="0" @selected($article->published == null)>عدم انتشار</option>
                    <option value="1" @selected($article->published != null)>منتشر</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">نویسنده</label>
                <input type="text" class="form-control" name="auther" value="{{ $article->auther }}" required>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">کلمات کلیدی</label>
                <button class="btn btn-primary btn-sm" type="button" id="admin_add_keywords_input_btn">افزودن</button>
                <div id="admin_keywords_input_box" class="d-flex flex-wrap">
                    @if (count($article->keywords))
                        @foreach ($article->keywords as $keyword)
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
                    required>{!! $article->content !!}</textarea>
            </div>
            <div class="row" id="admin_products_images_box">
                <label class="form-label mb-4">تصاویر <button class="btn btn-primary btn-sm" type="button"
                        id="admin_add_image_input_btn">افزودن</button></label>
                <input type="hidden" value="{{ count($article->images) }}" name="images_count"
                    id="admin_products_images_count">
                @foreach ($article->images as $key => $image)
                    <div class="col-md-4 col-xl-3 mb-3">
                        <button type="button" class="btn btn-outline-danger btn-sm admin-remov-produc-image-btn"
                            value="{{ $key }}">✕</button>
                        <label for="admin_product_image_input_{{ $key }}" class="w-100" role="button">
                            <span class="d-block mb-2">تصویر {{ $key + 1 }}:</span>
                            <img src="{{ $image }}" title="{{ $image }}" class="admin-product-image w-100"
                                id="admin_product_image_{{ $key }}">
                        </label>
                        <input type="file" name="images_{{ $key }}" value="{{ public_path($image) }}"
                            id="admin_product_image_input_{{ $key }}"
                            data-image="#admin_product_image_{{ $key }}"
                            class="custom-file-input-hidden admin-product-image-input">
                    </div>
                @endforeach
            </div>

            <div>
                <button class="btn btn-primary">ویرایش</button>
            </div>
        </div>
    </form>
@endsection
