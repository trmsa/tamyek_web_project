@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'خرید‌های کاربران')
@section('admin_content')
    <div class="row">
        <p class="title p-3">خرید‌های کاربران</p>
        <form action="{{ route('admin.facts.users.sales') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">فیلتر بر اساس</label>
                    <select name="filter" class="form-select">
                        <option value="count_desc" @selected(Request::get('filter') == 'count_desc')>تعداد نزولی</option>
                        <option value="count_asc" @selected(Request::get('filter') == 'count_asc')>تعداد صعودی</option>
                        <option value="price_desc" @selected(Request::get('filter') == 'price_desc')>مبلغ نزولی</option>
                        <option value="price_asc" @selected(Request::get('filter') == 'price_asc')>مبلغ صعودی</option>
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
                        <th scope="col">نام</th>
                        <th scope="col">موبایل</th>
                        <th scope="col">استان</th>
                        <th scope="col">شهر</th>
                        <th scope="col">تعداد خریدها</th>
                        <th scope="col">جمع مبلغ خریدها</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users as $key => $user)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $provinces->firstWhere('id', $user->province_id)->name ?? '---' }}</td>
                            <td>{{ $cities->firstWhere('id', $user->city_id)->name ?? '---' }}</td>
                            <td>{{ $user->buies_count }}</td>
                            <td>{{ number_format($user->total_price_buies) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
