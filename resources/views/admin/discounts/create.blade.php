@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'ایجاد تخفیف')
@section('admin_content')
    <form action="{{ route('admin.discounts.store') }}" method="POST">
        @csrf
        <div class="row">
            <p class="title p-3">ایجاد تخفیف</p>
            <div class="col-md-4 mb-3">
                <label class="form-label">محصول</label>
                <select name="product_id" class="form-select">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">قیمت اصلی-اختیاری (تومان)</label>
                <input type="number" class="form-control hidden-arrow" value="{{ old('price') }}" name="price">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">نوع تخفیف</label>
                <select name="discount_type" class="form-select">
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
                            @for ($i = 1; $i < 32; $i++)
                                <option value="{{ $i }}" @selected(old('begin_date_d') == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="begin_date_m" class="form-select">
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
                        <select name="begin_date_y" class="form-select">
                            @for ($i = Helper::fa_date('%y') + 2; $i > 1400; $i--)
                                <option value="{{ $i }}" @selected(old('begin_date_y') == $i)>{{ $i }}
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
                            @for ($i = 1; $i < 32; $i++)
                                <option value="{{ $i }}" @selected(old('expire_date_d') == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="expire_date_m" class="form-select">
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
                            @for ($i = Helper::fa_date('%y') + 2; $i > 1400; $i--)
                                <option value="{{ $i }}" @selected(old('expire_date_y') == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-start mt-4">
                <button class="btn btn-primary">ایجاد</button>
            </div>
        </div>
    </form>
@endsection
