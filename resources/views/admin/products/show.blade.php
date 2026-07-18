@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', $product->name)
@section('admin_content')
    <div class="row">
        <p class="title p-3">{{ $product->name }}</p>
        <div class="col-md-4 mb-3">
            <label class="form-label">کد</label>
            <input type="text" class="form-control" value="{{ $product->id }}" disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">نام</label>
            <input type="text" class="form-control" value="{{ $product->name }}" disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">قیمت</label>
            <input type="text" class="form-control" value="{{ number_format($product->price) }} تومان" disabled>
        </div>
        @php
            $discount = $product->discount();
            $discount_amount = '---';
            $discount_type = '---';
            $unit = '---';
            if ($discount == 'public_percent') {
                $discount_type = 'عمومی درصدی';
                $discount_amount = $product->discount_amount . ' %';
            } elseif ($discount == 'public_constant') {
                $discount_type = 'عمومی ثابت';
                $discount_amount = number_format($product->discount_amount) . ' تومان';
            } elseif ($discount == 'code_percent') {
                $discount_type = 'خصوصی درصدی';
                $discount_amount = $product->discount_amount . ' %';
            } elseif ($discount == 'code_constant') {
                $discount_type = 'خصوصی ثابت';
                $discount_amount = number_format($product->discount_amount) . ' تومان';
            }

            if ($product->unit == 'kg') {
                $unit = 'کیلوگرم';
            } elseif ($product->unit == 'g') {
                $unit = 'گرم';
            }
        @endphp
        <div class="col-md-4 mb-3">
            <label class="form-label">دسته بندی</label>
            <input type="text" class="form-control" value="{{ $category->name }}" disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">موجودی</label>
            <input type="text" class="form-control" value="{{ $product->inventory }}" disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">واحد وزن</label>
            <input type="text" class="form-control" value="{{ $unit }}" disabled>
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
            <input type="text" class="form-control" value="{{ $discount_type }}" disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">کد تخفیف</label>
            <input type="text" class="form-control" value="{{ $discount ? $product->discount_code : '---' }}" disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">شروع تخفیف</label>
            <input type="text" class="form-control"
                value="{{ $discount ? Helper::fa_date('%y/%m/%d', strtotime($product->discount_begin)) : '---' }}"
                disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">پایان تخفیف</label>
            <input type="text" class="form-control"
                value="{{ $discount ? Helper::fa_date('%y/%m/%d', strtotime($product->discount_expire)) : '---' }}"
                disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">میزان تخفیف</label>
            <input type="text" class="form-control" value="{{ $discount_amount }}" disabled>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">اینستاگرام</label>
            <input type="text" name="instageram" class="form-control"
                value="{{ isset($product->links['instageram']) ? $product->links['instageram'] : '' }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">تلگرام</label>
            <input type="text" name="telegram" class="form-control"
                value="{{ isset($product->links['telegram']) ? $product->links['telegram'] : '' }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">روبیکا</label>
            <input type="text" name="rubika" class="form-control"
                value="{{ isset($product->links['rubika']) ? $product->links['rubika'] : '' }}">
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">توضیحات</label>
            <p class="text">{{ $product->description }}</p>
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">توضیحات متا</label>
            <p class="text">{{ $product->meta_description }}</p>
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">کلمات کلیدی</label>
            <p class="text">{{ $product->keywords ? implode(', ', $product->keywords) : '---' }}</p>
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">تصاویر</label>
            <img class="product-product-image rounded-4 shadow" src="{{ $product->images[0] }}"
                alt="{{ $product->name }}">
            <div class="position-relative">
                <div class="product-product-images-box shadow mt-5 d-flex align-items-center rounded-4">
                    @foreach ($product->images as $image)
                        <img src="{{ $image }}" alt="{{ $product->name }}"
                            class="product-product-small-image rounded-pill mx-2">
                    @endforeach
                    <div class="scroll-btns-box position-absolute">
                        <span class="images-prev-scroll-btn scroll-btn shadow rounded-pill">«</span>
                        <span class="images-next-scroll-btn scroll-btn shadow rounded-pill me-3">»</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
