@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'پرفروش‌ترین محصولات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">پرفروش‌ترین محصولات</p>
        <form action="{{ route('admin.facts.products.sales') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">فیلتر بر اساس</label>
                    <select name="filter" class="form-select">
                        <option value="count_desc" @selected(Request::get('filter') == 'count_desc')>تعداد نزولی</option>
                        <option value="count_asc" @selected(Request::get('filter') == 'count_asc')>تعداد صعودی</option>
                        <option value="price_desc" @selected(Request::get('filter') == 'price_desc')>قیمت نزولی</option>
                        <option value="price_asc" @selected(Request::get('filter') == 'price_asc')>قیمت صعودی</option>
                    </select>
                </div>
            </div>
            <div class="my-3">
                <button class="btn btn-primary">جستجو</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">کد محصول</th>
                        <th scope="col">نام محصول</th>
                        <th scope="col">قیمت اصلی</th>
                        <th scope="col">قیمت با تخفیف</th>
                        <th scope="col">نوع تخفیف</th>
                        <th scope="col">مقدار تخفیف</th>
                        <th scope="col">کد تخفیف</th>
                        <th scope="col">شروع تخفیف</th>
                        <th scope="col">پایان تخفیف</th>
                        <th scope="col">تعداد فروش</th>
                        <th scope="col">کل مبلغ فروش</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($products as $key => $product)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->price) }} تومان</td>
                            <td>{{ number_format($product->discounted_price) }} تومان</td>
                            @php
                                $discount_type = '---';
                                $discount_amount = '---';
                                if ($product->discount_type == 'public_percent') {
                                    $discount_type = 'عمومی درصدی';
                                    $discount_amount = $product->discount_amount . ' %';
                                } elseif ($product->discount_type == 'public_constant') {
                                    $discount_type = 'عمومی ثابت';
                                    $discount_amount = number_format($product->discount_amount) . ' تومان';
                                } elseif ($product->discount_type == 'code_percent') {
                                    $discount_type = 'خصوصی درصدی';
                                    $discount_amount = $product->discount_amount . ' %';
                                } elseif ($product->discount_type == 'code_constant') {
                                    $discount_type = 'خصوصی ثابت';
                                    $discount_amount = number_format($product->discount_amount) . ' تومان';
                                }
                            @endphp
                            <td>{{ $discount_type }}</td>
                            <td>{{ $discount_amount }}</td>
                            <td>{{ $product->discount_code ?? '---' }}</td>
                            <td>{{ $product->discount_begin ? Helper::fa_date('%y/%m/%d', strtotime($product->discount_begin)) : '---' }}
                            </td>
                            <td>{{ $product->discount_expire ? Helper::fa_date('%y/%m/%d', strtotime($product->discount_expire)) : '---' }}
                            </td>
                            <td>{{ $product->sales_count }}</td>
                            <td>{{ number_format($product->total_price_sales) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
