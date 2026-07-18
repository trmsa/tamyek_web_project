@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'ویرایش محصول')
@section('admin_content')
    <form action="{{ route('admin.products.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <p class="title p-3">ویرایش {{ $product->name }}</p>
            <div class="col-md-4 mb-3">
                <label class="form-label">کد</label>
                <input type="text" class="form-control" value="{{ $product->id }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">نام</label>
                <input type="text" class="form-control" value="{{ $product->name }}" name="name">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">قیمت (تومان)</label>
                <input type="number" class="form-control hidden-arrow" value="{{ $product->price }}" name="price">
            </div>
            @php
                $discount = $product->discount();
            @endphp
            <div class="col-md-4 mb-3">
                <label class="form-label">دسته بندی</label>
                <select name="category" class="form-select">
                    <option value=""></option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">موجودی</label>
                <input type="text" class="form-control hidden-arrow" value="{{ $product->inventory }}" name="inventory">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">واحد وزن</label>
                <select name="unit" class="form-select">
                    <option value=""></option>
                    <option value="kg" @selected($product->unit == 'kg')>کیلوگرم</option>
                    <option value="g" @selected($product->unit == 'g')>گرم</option>
                    <option value="pack" @selected($product->unit != 'kg' && $product->unit != 'g')>بسته‌بندی</option>
                </select>
            </div>
            @php
                $pack_arr = null;
                $pack_weight = null;
                $pack_unit = null;
                if ($product->unit != 'kg' && $product->unit != 'g') {
                    $pack_arr = explode('_', $product->unit);
                    $pack_weight = $pack_arr[0];
                    $pack_unit = $pack_arr[1];
                }
            @endphp
            <div class="col-md-4 mb-3">
                <label class="form-label">وزن بسته</label>
                <input type="number" class="form-control hidden-arrow" value="{{ $pack_weight }}" name="pack_weight">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">واحد بسته</label>
                <select name="pack_unit" class="form-select">
                    <option value=""></option>
                    <option value="kg" @selected($pack_unit == 'kg')>کیلوگرم</option>
                    <option value="g" @selected($pack_unit == 'g')>گرم</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">حداقل سفارش</label>
                <input type="text" class="form-control hidden-arrow" value="{{ $product->min_order }}" name="min_order">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">نوع</label>
                <select name="type" class="form-control">
                    <option value="khorde" @selected($product->type == 'khorde')>خرده</option>
                    <option value="omde" @selected($product->type == 'omde')>عمده</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">جمع مبلغ فروش</label>
                <input type="text" class="form-control" value="{{ number_format($product->total_price_sales) }} تومان"
                    disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">تعداد فروش</label>
                <input type="text" class="form-control" value="{{ $product->sales_count }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">تعداد نظرات</label>
                <input type="text" class="form-control" value="{{ $product->likes_count }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">میانگین امتیاز</label>
                <input type="text" class="form-control" value="{{ $product->likes }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">تعداد بازدید</label>
                <input type="text" class="form-control" value="{{ $product->view_count }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">قیمت با تخفیف</label>
                <input type="text" class="form-control"
                    value="{{ $discount ? number_format($product->discounted_price) . ' تومان' : '---' }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">نوع تخفیف</label>
                <select name="discount_type" class="form-select">
                    <option value=""></option>
                    <option value="public_percent" @selected($product->discount_type == 'public_percent')>عمومی درصدی</option>
                    <option value="public_constant" @selected($product->discount_type == 'public_constant')>عمومی ثابت</option>
                    <option value="code_percent" @selected($product->discount_type == 'code_percent')>خصوصی درصدی</option>
                    <option value="code_constant" @selected($product->discount_type == 'code_constant')>خصوصی ثابت</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">کد تخفیف</label>
                <input type="text" class="form-control" value="{{ $product->discount_code }}" name="discount_code">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">میزان تخفیف</label>
                <input type="text" class="form-control" value="{{ $product->discount_amount }}"
                    name="discount_amount">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">شروع تخفیف</label>
                @php
                    $begin_y = null;
                    $begin_m = null;
                    $begin_d = null;
                    $expire_y = null;
                    $expire_m = null;
                    $expire_d = null;
                    if ($product->discount_begin) {
                        $begin_time = strtotime($product->discount_begin);
                        $expire_time = strtotime($product->discount_expire);
                        $begin_y = Helper::fa_date('%y', $begin_time);
                        $begin_m = Helper::fa_date('%m', $begin_time);
                        $begin_d = Helper::fa_date('%d', $begin_time);
                        $expire_y = Helper::fa_date('%y', $expire_time);
                        $expire_m = Helper::fa_date('%m', $expire_time);
                        $expire_d = Helper::fa_date('%d', $expire_time);
                    }
                @endphp
                <div class="row">
                    <div class="col-4">
                        <select name="begin_date_d" class="form-select">
                            <option value=""></option>
                            @for ($i = 1; $i < 32; $i++)
                                <option value="{{ $i }}" @selected($begin_d == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="begin_date_m" class="form-select">
                            <option value=""></option>
                            <option value="1" @selected($begin_m == '1')>فروردین</option>
                            <option value="2" @selected($begin_m == '2')>اردیبهشت</option>
                            <option value="3" @selected($begin_m == '3')>خرداد</option>
                            <option value="4" @selected($begin_m == '4')>تیر</option>
                            <option value="5" @selected($begin_m == '5')>مرداد</option>
                            <option value="6" @selected($begin_m == '6')>شهریور</option>
                            <option value="7" @selected($begin_m == '7')>مهر</option>
                            <option value="8" @selected($begin_m == '8')>ابان</option>
                            <option value="9" @selected($begin_m == '9')>آذر</option>
                            <option value="10" @selected($begin_m == '10')>دی</option>
                            <option value="11" @selected($begin_m == '11')>بهمن</option>
                            <option value="12" @selected($begin_m == '12')>اسفند</option>
                        </select>
                    </div>
                    <div class="col-4">
                        @php
                            $now_y = Helper::fa_date('%y');
                        @endphp
                        <select name="begin_date_y" class="form-select">
                            <option value=""></option>
                            @for ($i = $now_y + 2; $i >= $now_y - 2; $i--)
                                <option value="{{ $i }}" @selected($begin_y == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <label class="form-label">پایان تخفیف</label>
                <div class="row">
                    <div class="col-4">
                        <select name="expire_date_d" class="form-select">
                            <option value=""></option>
                            @for ($i = 1; $i < 32; $i++)
                                <option value="{{ $i }}" @selected($expire_d == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="expire_date_m" class="form-select">
                            <option value=""></option>
                            <option value="1" @selected($expire_m == '1')>فروردین</option>
                            <option value="2" @selected($expire_m == '2')>اردیبهشت</option>
                            <option value="3" @selected($expire_m == '3')>خرداد</option>
                            <option value="4" @selected($expire_m == '4')>تیر</option>
                            <option value="5" @selected($expire_m == '5')>مرداد</option>
                            <option value="6" @selected($expire_m == '6')>شهریور</option>
                            <option value="7" @selected($expire_m == '7')>مهر</option>
                            <option value="8" @selected($expire_m == '8')>ابان</option>
                            <option value="9" @selected($expire_m == '9')>آذر</option>
                            <option value="10" @selected($expire_m == '10')>دی</option>
                            <option value="11" @selected($expire_m == '11')>بهمن</option>
                            <option value="12" @selected($expire_m == '12')>اسفند</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="expire_date_y" class="form-select">
                            <option value=""></option>
                            @for ($i = $now_y + 5; $i >= $now_y - 2; $i--)
                                <option value="{{ $i }}" @selected($expire_y == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">اینستاگرام</label>
                <input type="text" name="links[instageram]" class="form-control"
                    value="{{ isset($product->links['instageram']) ? $product->links['instageram'] : '' }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">تلگرام</label>
                <input type="text" name="links[telegram]" class="form-control"
                    value="{{ isset($product->links['telegram']) ? $product->links['telegram'] : '' }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">روبیکا</label>
                <input type="text" name="links[rubika]" class="form-control"
                    value="{{ isset($product->links['rubika']) ? $product->links['rubika'] : '' }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">سایر <span class="mini-text">(nuts برای آجیل ترکیبی و <span
                            dir="ltr">{"100": 100000}</span> برای
                        قیمت عمده)</span></label>
                <input type="text" name="other" class="form-control" dir="ltr"
                    value="{{ $product->other }}">
            </div>
            <div class="col-12 mb-3">
                <p class="text mb-3">مغذی‌ها (مقدار | درصد)</p>
                @foreach ($nutrients as $nutrient)
                    <div class="d-inline-block ms-4 mb-4">
                        <label class="form-label">{{ $nutrient->name }}</label>
                        @php
                            $nutrient_value = $product_nutrients->firstWhere('nutrient_id', $nutrient->id);
                            $nutrient_value = $nutrient_value
                                ? "$nutrient_value->amount|$nutrient_value->percent"
                                : null;
                        @endphp
                        <input type="text" class="form-control mini-input" name="nutrients[{{ $nutrient->id }}]"
                            value="{{ $nutrient_value }}">
                    </div>
                @endforeach

            </div>
            <div class="col-12 mb-3">
                <label class="form-label">توضیحات</label>
                <textarea name="description" class="w-100 p-3 border-none text rounded-3 bg-light" rows="5"
                    placeholder="توضیحات این محصول را بنویسید...">{{ $product->description }}</textarea>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">توضیحات متا</label>
                <textarea name="meta_description" class="w-100 p-3 border-none text rounded-3 bg-light" rows="5"
                    placeholder="توضیحات متا این محصول را بنویسید...">{{ $product->meta_description }}</textarea>
            </div>
            <div class="col-12 mb-4">
                <label class="form-label">کلمات کلیدی</label>
                <button class="btn btn-primary btn-sm" type="button" id="admin_add_keywords_input_btn">افزودن</button>
                <div id="admin_keywords_input_box" class="d-flex flex-wrap">
                    @if ($product->keywords)
                        @foreach ($product->keywords as $keyword)
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
                <input type="hidden" value="{{ count($product->images) }}" name="images_count"
                    id="admin_products_images_count">
                @foreach ($product->images as $key => $image)
                    <div class="col-md-4 col-xl-3 mb-3">
                        <button type="button" class="btn btn-outline-danger btn-sm admin-remov-produc-image-btn"
                            value="{{ $key }}">✕</button>
                        <label for="admin_product_image_input_{{ $key }}" class="w-100" role="button">
                            <span class="d-block mb-2">تصویر {{ $key + 1 }}:</span>
                            <img src="{{ $image }}" class="admin-product-image w-100"
                                id="admin_product_image_{{ $key }}">
                        </label>
                        <input type="file" name="images_{{ $key }}" value="{{ public_path($image) }}"
                            id="admin_product_image_input_{{ $key }}"
                            data-image="#admin_product_image_{{ $key }}"
                            class="custom-file-input-hidden admin-product-image-input">
                    </div>
                @endforeach
            </div>
            <div class="text-start mt-4">
                <button class="btn btn-primary">ویرایش</button>
            </div>
        </div>
    </form>
@endsection
