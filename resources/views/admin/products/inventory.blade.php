@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'شارژ محصولات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">شارژ محصولات</p>
        <div class="text-start my-3">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">ایجاد محصول جدید</a>
        </div>
        <form action="{{ route('admin.products.inventory') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">دسته‌بندی</label>
                    <select name="category" class="form-select">
                        <option value="">همه</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(Request::get('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">نام</label>
                    <input type="text" class="form-control" name="word" value="{{ Request::get('word') }}"
                        placeholder="نام محصول را بنویسید">
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
                        <th scope="col">دسته‌بندی</th>
                        <th scope="col">نام</th>
                        <th scope="col">موجودی</th>
                        <th scope="col">واحد وزن</th>
                        <th scope="col">ذخیره</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($products as $key => $product)
                        <tr id="row_{{ $product->id }}">
                            <form action="{{ route('admin.products.save_inventory', ['id' => $product->id]) }}"
                                method="POST">
                                @csrf
                                @method('put')
                                <th scope="row">{{ ++$key }}</th>
                                <td>{{ $product->id }}</td>
                                <td>{{ $categories->firstWhere('id', $product->category_id)->name }}</td>
                                <td>{{ $product->name }}</td>
                                <td><input type="text" class="form-control" name="inventory"
                                        value="{{ $product->inventory }}"></td>
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
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm admin-add-inventory-product-btn"
                                        data-bs-toggle="modal" data-bs-target="#modalProducts{{ $product->id }}">
                                        ذخیره
                                    </button>
                                </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalProducts{{ $product->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $product->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $product->id }}">شارژ محصول
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از ذخیره موجودی محصول ({{ $product->name }}) اطمینان دارید؟
                                        </p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <button class="btn btn-success btn-sm">ذخیره</button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Modal -->
                        </form>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
