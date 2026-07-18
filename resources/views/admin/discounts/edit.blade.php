@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'ویرایش تخفیف')
@section('admin_content')
    <form action="{{ route('admin.discounts.update', ['id' => $product->id]) }}" method="POST">
        @csrf
        @method('put')
        <div class="row">
            <p class="title p-3">ویرایش تخفیف {{ $product->name }}</p>
            <div class="col-md-4 mb-3">
                <label class="form-label">کد</label>
                <input type="text" class="form-control" value="{{ $product->id }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">قیمت اصلی (تومان)</label>
                <input type="number" class="form-control hidden-arrow" value="{{ $product->price }}" name="price">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">قیمت با تخفیف</label>
                <input type="text" class="form-control" value="{{ number_format($product->discounted_price) }} تومان"
                    disabled>
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
                <input type="text" class="form-control" value="{{ $product->discount_amount }}" name="discount_amount">
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
                        <select name="begin_date_y" class="form-select">
                            <option value=""></option>
                            @for ($i = Helper::fa_date('%y') + 2; $i > 1400; $i--)
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
                            @for ($i = Helper::fa_date('%y') + 2; $i > 1400; $i--)
                                <option value="{{ $i }}" @selected($expire_y == $i)>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-start mt-4">
                <button class="btn btn-primary">ویرایش</button>
            </div>
        </div>
    </form>
@endsection
