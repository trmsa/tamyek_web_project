@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'محبوب‌ترین محصولات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">محبوب‌ترین محصولات</p>
        <form action="{{ route('admin.facts.products.likes') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">فیلتر بر اساس</label>
                    <select name="filter" class="form-select">
                        <option value="likes_desc" @selected(Request::get('filter') == 'likes_desc')>امتیاز نزولی</option>
                        <option value="likes_asc" @selected(Request::get('filter') == 'likes_asc')>امتیاز صعودی</option>
                        <option value="likes_count_desc" @selected(Request::get('filter') == 'likes_count_desc')>تعداد نظرات نزولی</option>
                        <option value="likes_count_asc" @selected(Request::get('filter') == 'likes_count_asc')>تعداد نظرات صعودی</option>
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
                        <th scope="col">نام محصول</th>
                        <th scope="col">کد محصول</th>
                        <th scope="col">امتیاز</th>
                        <th scope="col">تعداد نظرات</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($products as $key => $product)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->likes }}</td>
                            <td>{{ $product->likes_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
