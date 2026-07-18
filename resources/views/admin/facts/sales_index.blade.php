@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'آمار فروش')
@section('admin_content')
    <div class="row">
        <p class="title p-3">آمار فروش</p>
        <form action="{{ route('admin.facts.sales.index') }}" id="filter_facts_form">
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label class="form-label">از تاریخ</label>
                    <div class="row">
                        <div class="col-4">
                            <select name="begin_date_d" class="form-select">
                                @for ($i = 1; $i < 32; $i++)
                                    <option value="{{ $i }}" @selected(Request::get('begin_date_d') == $i)>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-4">
                            <select name="begin_date_m" class="form-select">
                                <option value="1" @selected(Request::get('begin_date_m') == '1')>فروردین</option>
                                <option value="2" @selected(Request::get('begin_date_m') == '2')>اردیبهشت</option>
                                <option value="3" @selected(Request::get('begin_date_m') == '3')>خرداد</option>
                                <option value="4" @selected(Request::get('begin_date_m') == '4')>تیر</option>
                                <option value="5" @selected(Request::get('begin_date_m') == '5')>مرداد</option>
                                <option value="6" @selected(Request::get('begin_date_m') == '6')>شهریور</option>
                                <option value="7" @selected(Request::get('begin_date_m') == '7')>مهر</option>
                                <option value="8" @selected(Request::get('begin_date_m') == '8')>ابان</option>
                                <option value="9" @selected(Request::get('begin_date_m') == '9')>آذر</option>
                                <option value="10" @selected(Request::get('begin_date_m') == '10')>دی</option>
                                <option value="11" @selected(Request::get('begin_date_m') == '11')>بهمن</option>
                                <option value="12" @selected(Request::get('begin_date_m') == '12')>اسفند</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select name="begin_date_y" class="form-select">
                                @for ($i = Helper::fa_date('%y') + 2; $i > 1400; $i--)
                                    <option value="{{ $i }}" @selected(Request::get('begin_date_y') == $i)>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label">تا تاریخ</label>
                    <div class="row">
                        <div class="col-4">
                            <select name="end_date_d" class="form-select">
                                @for ($i = 1; $i < 32; $i++)
                                    <option value="{{ $i }}" @selected(Request::get('end_date_d') == $i)>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-4">
                            <select name="end_date_m" class="form-select">
                                <option value="1" @selected(Request::get('end_date_m') == '1')>فروردین</option>
                                <option value="2" @selected(Request::get('end_date_m') == '2')>اردیبهشت</option>
                                <option value="3" @selected(Request::get('end_date_m') == '3')>خرداد</option>
                                <option value="4" @selected(Request::get('end_date_m') == '4')>تیر</option>
                                <option value="5" @selected(Request::get('end_date_m') == '5')>مرداد</option>
                                <option value="6" @selected(Request::get('end_date_m') == '6')>شهریور</option>
                                <option value="7" @selected(Request::get('end_date_m') == '7')>مهر</option>
                                <option value="8" @selected(Request::get('end_date_m') == '8')>ابان</option>
                                <option value="9" @selected(Request::get('end_date_m') == '9')>آذر</option>
                                <option value="10" @selected(Request::get('end_date_m') == '10')>دی</option>
                                <option value="11" @selected(Request::get('end_date_m') == '11')>بهمن</option>
                                <option value="12" @selected(Request::get('end_date_m') == '12')>اسفند</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select name="end_date_y" class="form-select">
                                @for ($i = Helper::fa_date('%y') + 2; $i > 1400; $i--)
                                    <option value="{{ $i }}" @selected(Request::get('end_date_y') == $i)>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-start">
                    <button class="btn btn-primary">دریافت</button>
                </div>
            </div>
        </form>
        <div class="col-md-6">
            <p class="titr p-2 mb-2 rounded-3 bg-info text-light">کل فروش محصول: {{ number_format($sales->total_products) }}
                تومان</p>
        </div>
        <div class="col-md-6">
            <p class="titr p-2 mb-2 rounded-3 bg-info text-light">کل فروش با احتساب هزینه پست:
                {{ number_format($sales->total_amount) }} تومان</p>
        </div>
        <div class="col-md-6">
            <p class="titr p-2 mb-2 rounded-3 bg-info text-light">کل هزینه پست: {{ number_format($sales->total_postal) }}
                تومان</p>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">نام</th>
                        <th scope="col">موبایل</th>
                        <th scope="col">شماره تراکنش</th>
                        <th scope="col">تاریخ پرداخت</th>
                        <th scope="col">تاریخ ارسال</th>
                        <th scope="col">مبلغ محصولات</th>
                        <th scope="col">مبلغ کل</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users_sales as $key => $sale)
                        <tr>
                            @php
                                $user = $users->firstWhere('id', $sale->user_id);
                            @endphp
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $sale->transaction_id }}</td>
                            <td>{{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($sale->date_payment), true) }}</td>
                            <td class="{{ $sale->date_send ? 'text-success' : 'text-danger' }}">
                                {{ $sale->date_send ? Helper::fa_date('%y/%m/%d, %H:%i', strtotime($sale->date_send), true) : 'درانتظار ارسال' }}
                            </td>
                            <td>{{ number_format($sale->final_price_products) }} تومان</td>
                            <td>{{ number_format($sale->amount) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
