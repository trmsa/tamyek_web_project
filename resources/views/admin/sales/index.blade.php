@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'فروش‌ها')
@section('admin_content')
    <form action="{{ route('admin.sales.index') }}">
        <div class="row">
            <p class="title mb-4 p-3">فروش‌ها</p>
            <div class="col-md-6">
                <label class="form-label">فیلتر بر اساس:</label>
                <select name="send" class="form-select">
                    <option value="0" @selected($send == 0)>ارسال نشده</option>
                    <option value="1" @selected($send == 1)>ارسال شده</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">جستجوی شماره تراکنش:</label>
                <input type="text" name="transaction_id" value="{{ $transaction_id }}" class="form-control hidden-arrow">
            </div>
            <div class="col-md-6">
                <label class="form-label">جستجوی شماره موبایل:</label>
                <input type="text" name="mobile" value="{{ $mobile }}" class="form-control hidden-arrow">
            </div>
            <div class="my-3">
                <button class="btn btn-primary">جستجو</button>
            </div>
    </form>
    </div>
    <div class="row">
        <div class="table-responsive mt-4">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">نام و نام‌خانوادگی</th>
                        <th scope="col">موبایل</th>
                        <th scope="col">شماره تراکنش</th>
                        <th scope="col">مبلغ پرداختی</th>
                        <th scope="col">تاریخ پرداخت</th>
                        <th scope="col">تاریخ ارسال</th>
                        <th scope="col">کد مرسوله</th>
                        <th scope="col">رهگیری مرسوله</th>
                        <th scope="col">مشاهده محصولات</th>
                        <th scope="col">تایید ارسال</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($sales as $key => $sale)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            @php
                                $user = $users->firstWhere('id', $sale->user_id);
                            @endphp
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
                            @php
                                $city = $cities->firstWhere('id', $user->city_id);
                                $province = $city ? $provinces->firstWhere('id', $city->province_id)->name : '---';
                                $city = $city ? $city->name : '---';
                            @endphp
                            <td>{{ $sale->transaction_id }}</td>
                            <td>{{ number_format($sale->amount) }} تومان</td>
                            <td>{{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($sale->date_payment), true) }}</td>
                            <td class="{{ $sale->date_send ? 'text-success' : 'text-danger' }}">
                                {{ $sale->date_send ? Helper::fa_date('%y/%m/%d, %H:%i', strtotime($sale->date_send), true) : 'درانتظار ارسال' }}
                            </td>
                            <td>{{ $sale->shipment_code ?? '---' }}</td>
                            <td>
                                @if ($sale->shipment_code)
                                    <a href="https://tracking.post.ir/?id={{ $sale->shipment_code }}" target="_blank"
                                        class="btn btn-success btn-sm">رهگیری</a>
                                @else
                                    ---
                                @endif
                            </td>
                            <td><a href="{{ route('admin.sales.show', ['id' => $sale->id]) }}"
                                    class="btn btn-primary btn-sm">مشاهده</a></td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalUser{{ $user->id }}">
                                    تایید
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalUser{{ $user->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $user->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $user->id }}">تایید ارسال
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> اطلاعات ارسال محصولات ({{ $user->name }}) را وارد نمایید.</p>
                                        <form action="{{ route('admin.sales.send') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="sales_id" value="{{ $sale->id }}">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <div class="mb-2">
                                                <label class="form-label">تاریخ ارسال</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        @php
                                                            $year = Helper::fa_date('%y');
                                                            $month = Helper::fa_date('%m');
                                                            $day = Helper::fa_date('%d');
                                                        @endphp
                                                        <select name="send_date_d" class="form-select">
                                                            @for ($i = 1; $i < 32; $i++)
                                                                <option value="{{ $i }}"
                                                                    @selected($day == $i)>{{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <select name="send_date_m" class="form-select">
                                                            <option value="1" @selected($month == '1')>فروردین
                                                            </option>
                                                            <option value="2" @selected($month == '2')>اردیبهشت
                                                            </option>
                                                            <option value="3" @selected($month == '3')>خرداد
                                                            </option>
                                                            <option value="4" @selected($month == '4')>تیر</option>
                                                            <option value="5" @selected($month == '5')>مرداد
                                                            </option>
                                                            <option value="6" @selected($month == '6')>شهریور
                                                            </option>
                                                            <option value="7" @selected($month == '7')>مهر</option>
                                                            <option value="8" @selected($month == '8')>ابان
                                                            </option>
                                                            <option value="9" @selected($month == '9')>آذر
                                                            </option>
                                                            <option value="10" @selected($month == '10')>دی</option>
                                                            <option value="11" @selected($month == '11')>بهمن
                                                            </option>
                                                            <option value="12" @selected($month == '12')>اسفند
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <select name="send_date_y" class="form-select">
                                                            <option value="{{ $year }}">{{ $year }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">کد مرسوله</label>
                                                <input type="text" name="shipment_code" class="form-control">
                                            </div>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <button class="btn btn-success btn-sm">تایید</button>
                                        </form>

                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Modal -->
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
