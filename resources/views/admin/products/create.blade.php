@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'ایجاد محصول')
@section('admin_content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <p class="title p-3">ایجاد محصول</p>
            <div class="col-md-4 mb-3">
                <label class="form-label">نام</label>
                <input type="text" class="form-control" value="{{ old('name') }}" name="name">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">قیمت (تومان)</label>
                <input type="number" class="form-control hidden-arrow" value="{{ old('price') }}" name="price">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">دسته بندی</label>
                <select name="category" class="form-select">
                    <option value=""></option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">موجودی</label>
                <input type="text" class="form-control hidden-arrow" value="{{ old('inventory') }}" name="inventory">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">واحد وزن</label>
                <select name="unit" class="form-select">
                    <option value=""></option>
                    <option value="kg" @selected(old('unit') == 'kg')>کیلوگرم</option>
                    <option value="g" @selected(old('unit') == 'g')>گرم</option>
                    <option value="pack" @selected(old('unit') == 'pack')>بسته‌بندی</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">وزن بسته</label>
                <input type="number" class="form-control hidden-arrow" value="{{ old('pack_weight') }}" name="pack_weight">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">واحد بسته</label>
                <select name="pack_unit" class="form-select">
                    <option value=""></option>
                    <option value="kg" @selected(old('pack_unit') == 'kg')>کیلوگرم</option>
                    <option value="g" @selected(old('pack_unit') == 'g')>گرم</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">حداقل سفارش</label>
                <input type="text" class="form-control hidden-arrow" value="{{ old('min_order') }}" name="min_order">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">نوع</label>
                <select name="type" class="form-control">
                    <option value="khorde" @selected(old('type') == 'khorde')>خرده</option>
                    <option value="omde" @selected(old('type') == 'omde')>عمده</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">نوع تخفیف</label>
                <select name="discount_type" class="form-select">
                    <option value=""></option>
                    <option value="public_percent" @selected(old('discount_type') == 'public_percent')>عمومی درصدی</option>
                    <option value="public_constant" @selected(old('discount_type') == 'public_constant')>عمومی ثابت</option>
                    <option value="code_percent" @selected(old('discount_type') == 'code_percent')>خصوصی درصدی</option>
                    <option value="code_constant" @selected(old('discount_type') == 'code_constant')>خصوصی ثابت</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">کد تخفیف</label>
                <input type="text" class="form-control" value="{{ old('discount_code') }}" name="discount_code">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">میزان تخفیف</label>
                <input type="text" class="form-control" value="{{ old('discount_amount') }}" name="discount_amount">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">شروع تخفیف</label>
                <div class="row">
                    <div class="col-4">
                        <select name="begin_date_d" class="form-select">
                            <option value=""></option>
                            @for ($i = 1; $i < 32; $i++)
                                <option value="{{ $i }}" @selected(old('begin_date_d') == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="begin_date_m" class="form-select">
                            <option value=""></option>
                            <option value="1" @selected(old('begin_date_m') == '1')>فروردین</option>
                            <option value="2" @selected(old('begin_date_m') == '2')>اردیبهشت</option>
                            <option value="3" @selected(old('begin_date_m') == '3')>خرداد</option>
                            <option value="4" @selected(old('begin_date_m') == '4')>تیر</option>
                            <option value="5" @selected(old('begin_date_m') == '5')>مرداد</option>
                            <option value="6" @selected(old('begin_date_m') == '6')>شهریور</option>
                            <option value="7" @selected(old('begin_date_m') == '7')>مهر</option>
                            <option value="8" @selected(old('begin_date_m') == '8')>ابان</option>
                            <option value="9" @selected(old('begin_date_m') == '9')>آذر</option>
                            <option value="10" @selected(old('begin_date_m') == '10')>دی</option>
                            <option value="11" @selected(old('begin_date_m') == '11')>بهمن</option>
                            <option value="12" @selected(old('begin_date_m') == '12')>اسفند</option>
                        </select>
                    </div>
                    <div class="col-4">
                        @php
                            $now_y = Helper::fa_date('%y');
                        @endphp
                        <select name="begin_date_y" class="form-select">
                            <option value=""></option>
                            @for ($i = $now_y + 2; $i >= $now_y - 2; $i--)
                                <option value="{{ $i }}" @selected(old('begin_date_y') == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">پایان تخفیف</label>
                <div class="row">
                    <div class="col-4">
                        <select name="expire_date_d" class="form-select">
                            <option value=""></option>
                            @for ($i = 1; $i < 32; $i++)
                                <option value="{{ $i }}" @selected(old('expire_date_d') == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="expire_date_m" class="form-select">
                            <option value=""></option>
                            <option value="1" @selected(old('expire_date_m') == '1')>فروردین</option>
                            <option value="2" @selected(old('expire_date_m') == '2')>اردیبهشت</option>
                            <option value="3" @selected(old('expire_date_m') == '3')>خرداد</option>
                            <option value="4" @selected(old('expire_date_m') == '4')>تیر</option>
                            <option value="5" @selected(old('expire_date_m') == '5')>مرداد</option>
                            <option value="6" @selected(old('expire_date_m') == '6')>شهریور</option>
                            <option value="7" @selected(old('expire_date_m') == '7')>مهر</option>
                            <option value="8" @selected(old('expire_date_m') == '8')>ابان</option>
                            <option value="9" @selected(old('expire_date_m') == '9')>آذر</option>
                            <option value="10" @selected(old('expire_date_m') == '10')>دی</option>
                            <option value="11" @selected(old('expire_date_m') == '11')>بهمن</option>
                            <option value="12" @selected(old('expire_date_m') == '12')>اسفند</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="expire_date_y" class="form-select">
                            <option value=""></option>
                            @for ($i = $now_y + 5; $i >= $now_y - 2; $i--)
                                <option value="{{ $i }}" @selected(old('expire_date_y') == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">اینستاگرام</label>
                <input type="text" name="links[instageram]" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">تلگرام</label>
                <input type="text" name="links[telegram]" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">روبیکا</label>
                <input type="text" name="links[rubika]" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">سایر <span class="mini-text">(nuts برای آجیل ترکیبی و <span
                            dir="ltr">{"100": 100000}</span> برای
                        قیمت عمده)</span></label>
                <input type="text" name="other" class="form-control">
            </div>
            <div class="col-12 mb-3">
                <p class="text mb-3">مغذی‌ها (مقدار | درصد)</p>
                @foreach ($nutrients as $nutrient)
                    <div class="d-inline-block ms-4 mb-4">
                        <label class="form-label">{{ $nutrient->name }}</label>
                        <input type="text" class="form-control mini-input" name="nutrients[{{ $nutrient->id }}]"
                            value="{{ old("nutrients.$nutrient->id") }}">
                    </div>
                @endforeach

            </div>
            <div class="col-12 mb-3">
                <label class="form-label">توضیحات</label>
                <textarea name="description" class="w-100 p-3 border-none text rounded-3 bg-light" rows="5"
                    placeholder="توضیحات این محصول را بنویسید...">{{ old('description') }}</textarea>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">توضیحات متا</label>
                <textarea name="meta_description" class="w-100 p-3 border-none text rounded-3 bg-light" rows="5"
                    placeholder="توضیحات متا این محصول را بنویسید...">{{ old('meta_description') }}</textarea>
            </div>
            <div class="col-12 mb-4">
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
            <div class="row" id="admin_products_images_box">
                <label class="form-label mb-4">تصاویر <button class="btn btn-primary btn-sm" type="button"
                        id="admin_add_image_input_btn">افزودن</button></label>
                <input type="hidden" value="1" name="images_count" id="admin_products_images_count">
                <div class="col-md-4 col-xl-3 mb-3">
                    <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                    <label for="admin_product_image_input_0" class="w-100" role="button">
                        <span class="d-block mb-2">تصویر 1:</span>
                        <img src="/images/icons/camera.webp" class="admin-product-image w-100"
                            id="admin_product_image_0">
                    </label>
                    <input type="file" name="images_0" value="same" id="admin_product_image_input_0"
                        data-image="#admin_product_image_0" class="custom-file-input-hidden admin-product-image-input">
                </div>
            </div>
            <div class="text-start mt-5">
                <button class="btn btn-primary">ایجاد</button>
            </div>
        </div>
    </form>
@endsection
