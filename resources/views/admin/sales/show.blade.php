@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'فاکتور فروش')
@section('admin_content')
    <div class="row">
        <p class="title mb-4 p-3 text-center">فاکتور فروش</p>
        <div class="col-md-6 mb-3">
            <label class="form-label">نام و نام‌خانوادگی</label>
            <input type="text" class="form-control" disabled value="{{ $user->name }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">موبایل</label>
            <input type="text" class="form-control" disabled value="{{ $user->mobile }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">استان</label>
            <input type="text" class="form-control" disabled value="{{ $province->name }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">شهر</label>
            <input type="text" class="form-control" disabled value="{{ $city->name }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">آدرس</label>
            <input type="text" class="form-control" disabled value="{{ $user->address }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">پلاک</label>
            <input type="text" class="form-control" disabled value="{{ $user->plaque }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">کدپستی</label>
            <input type="text" class="form-control" disabled value="{{ $user->postal_code }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">شماره تراکنش</label>
            <input type="text" class="form-control" disabled value="{{ $sales->transaction_id }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">مبلغ پرداختی</label>
            <input type="text" class="form-control" disabled value="{{ number_format($sales->amount) }} تومان">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">هزینه محصولات</label>
            <input type="text" class="form-control" disabled
                value="{{ number_format($sales->final_price_products) }} تومان">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">هزینه پستی</label>
            <input type="text" class="form-control" disabled value="{{ number_format($sales->postal_price) }} تومان">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">تاریخ پرداخت</label>
            <input type="text" class="form-control" disabled
                value="{{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($sales->date_payment), true) }}">
        </div>
        @php
            $gateway = 'نا مشخص';
            if ($sales->gateway == 'refah') {
                $gateway = 'رفاه';
            } elseif ($sales->gateway == 'zarinpal') {
                $gateway = 'زرین پال';
            }
        @endphp
        <div class="col-md-6 mb-3">
            <label class="form-label">درگاه پرداخت</label>
            <input type="text" class="form-control" disabled value="{{ $gateway }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">کد پیگیری درگاه</label>
            <input type="text" class="form-control" disabled value="{{ $sales->rrn }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">سفارش شده از</label>
            <input type="text" class="form-control" disabled
                value="{{ ($sales->origin == 'web' ? 'وب' : $sales->origin == 'app') ? 'اپ' : '---' }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">نوع سفارش</label>
            <input type="text" class="form-control" disabled
                value="{{ $sales->type == 'nuts' ? 'آجیل سفارشی' : 'معمولی' }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">روش ارسال</label>
            <input type="text" class="form-control" disabled value="{{ $sales->send_way }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">توضیحات</label>
            <textarea class="form-control" rows="3" disabled>{{ $sales->send_description }}</textarea>
        </div>
        <div class="table-responsive my-5">
            <table class="table table-hover table-border table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">نام محصول</th>
                        <th scope="col">کد محصول</th>
                        <th scope="col">تعداد</th>
                        <th scope="col">وزن</th>
                        <th scope="col">واحد وزن</th>
                        <th scope="col">فی</th>
                        <th scope="col">جمع قیمت</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($sales->products as $product)
                        @php
                            if ($product['weight']) {
                                $weight = $product['weight'];
                                $unit = $product['unit'] == 'kg' ? 'کیلوگرم' : 'گرم';
                            } else {
                                $wu_arr = explode('_', $product['unit']);
                                $weight = $wu_arr[0];
                                $unit = $wu_arr[1] == 'kg' ? 'کیلوگرم' : 'گرم';
                            }
                        @endphp
                        <tr>
                            <td><a href="{{ route('products.show', ['id' => $product['product_id']]) }}"
                                    class="link">{{ $product['name'] }}</a></td>
                            <td>{{ $product['product_id'] }}</td>
                            <td>{{ $product['count'] }}</td>
                            <td>{{ $weight }}</td>
                            <td>{{ $unit }}</td>
                            <td>{{ number_format($product['price']) }} تومان</td>
                            <td>{{ number_format($product['total_price']) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-start">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                data-bs-target="#modalUser{{ $user->id }}">
                تایید ارسال
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalUser{{ $user->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel{{ $user->id }}" aria-hidden="true" dir="rtl">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $user->id }}">تایید ارسال</h1>
                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="titr">
                            اطلاعات ارسال محصولات ({{ $user->name }}) را وارد نمایید.
                        </p>
                        <form action="{{ route('admin.sales.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="sales_id" value="{{ $sales->id }}">
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
                                                <option value="{{ $i }}" @selected($day == $i)>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select name="send_date_m" class="form-select">
                                            <option value="1" @selected($month == '1')>فروردین</option>
                                            <option value="2" @selected($month == '2')>اردیبهشت</option>
                                            <option value="3" @selected($month == '3')>خرداد</option>
                                            <option value="4" @selected($month == '4')>تیر</option>
                                            <option value="5" @selected($month == '5')>مرداد</option>
                                            <option value="6" @selected($month == '6')>شهریور</option>
                                            <option value="7" @selected($month == '7')>مهر</option>
                                            <option value="8" @selected($month == '8')>ابان</option>
                                            <option value="9" @selected($month == '9')>آذر</option>
                                            <option value="10" @selected($month == '10')>دی</option>
                                            <option value="11" @selected($month == '11')>بهمن</option>
                                            <option value="12" @selected($month == '12')>اسفند</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select name="send_date_y" class="form-select">
                                            <option value="{{ $year }}">{{ $year }}</option>
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
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modal -->
    </div>
@endsection
