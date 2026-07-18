@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'آمار موجودی محصولات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">آمار موجودی محصولات</p>
        <form action="{{ route('admin.facts.products.inventory') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">فیلتر بر اساس</label>
                    <select name="filter" class="form-select">
                        <option value="asc" @selected(Request::get('filter') == 'asc')>صعودی</option>
                        <option value="desc" @selected(Request::get('filter') == 'desc')>نزولی</option>
                    </select>
                </div>
            </div>
            <div class="my-3">
                <button class="btn btn-primary">جستجو</button>
            </div>
        </form>
        <div class="col-md-6">
            <p class="titr p-2 mb-2 rounded-3 bg-danger text-light">تعداد محصولات تمام شده: {{ $finished }}</p>
        </div>
        <div class="col-md-6">
            <p class="titr p-2 mb-2 rounded-3 bg-warning text-light">تعداد محصولات در حال اتمام: {{ $finishing }}</p>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">نام</th>
                        <th scope="col">کد</th>
                        <th scope="col">موجودی</th>
                        <th scope="col">واحد وزن</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($products as $key => $product)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->inventory }}</td>
                            @php
                                if ($product->unit == 'kg') {
                                    $unit = 'کیلوگرم';
                                } elseif ($product->unit == 'g') {
                                    $unit = 'گرم';
                                } else {
                                    $wu_arr = explode('_', $product->unit);
                                    $weight = $wu_arr[0];
                                    $unit_pack = $wu_arr[1] == 'kg' ? 'کیلوگرم' : 'گرم';
                                    $unit = "بسته $weight $unit_pack";
                                }
                            @endphp
                            <td>{{ $unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
