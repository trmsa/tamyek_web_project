@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'تخفیفات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">تخفیفات</p>
        <div class="text-start">
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">ایجاد تخفیف جدید</a>
        </div>
        <form action="{{ route('admin.discounts.index') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">نوع تخفیف</label>
                    <select name="discount_type" class="form-select">
                        <option value="">همه</option>
                        <option value="public_percent" @selected(Request::get('discount_type') == 'public_percent')>عمومی درصدی</option>
                        <option value="public_constant" @selected(Request::get('discount_type') == 'public_constant')>عمومی ثابت</option>
                        <option value="code_percent" @selected(Request::get('discount_type') == 'code_percent')>خصوصی درصدی</option>
                        <option value="code_constant" @selected(Request::get('discount_type') == 'code_constant')>خصوصی ثابت</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">محصول</label>
                    <select name="product_id" class="form-select">
                        <option value="">همه</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(Request::get('product_id') == $product->id)>{{ $product->name }}</option>
                        @endforeach
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
                        <th scope="col">ویرایش</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($discounts as $key => $discount)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $discount->id }}</td>
                            <td>{{ $discount->name }}</td>
                            <td>{{ number_format($discount->price) }} تومان</td>
                            <td>{{ number_format($discount->discounted_price) }} تومان</td>
                            @php
                                $discount_type = '---';
                                $discount_amount = '---';
                                if ($discount->discount_type == 'public_percent') {
                                    $discount_type = 'عمومی درصدی';
                                    $discount_amount = $discount->discount_amount . ' %';
                                } elseif ($discount->discount_type == 'public_constant') {
                                    $discount_type = 'عمومی ثابت';
                                    $discount_amount = number_format($discount->discount_amount) . ' تومان';
                                } elseif ($discount->discount_type == 'code_percent') {
                                    $discount_type = 'خصوصی درصدی';
                                    $discount_amount = $discount->discount_amount . ' %';
                                } elseif ($discount->discount_type == 'code_constant') {
                                    $discount_type = 'خصوصی ثابت';
                                    $discount_amount = number_format($discount->discount_amount) . ' تومان';
                                }
                            @endphp
                            <td>{{ $discount_type }}</td>
                            <td>{{ $discount_amount }}</td>
                            <td>{{ $discount->discount_code ?? '---' }}</td>
                            <td>{{ Helper::fa_date('%y/%m/%d', strtotime($discount->discount_begin)) }}</td>
                            <td>{{ Helper::fa_date('%y/%m/%d', strtotime($discount->discount_expire)) }}</td>
                            <td>
                                <a href="{{ route('admin.discounts.edit', ['id' => $discount->id]) }}"
                                    class="btn btn-primary btn-sm">ویرایش</a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalDelete{{ $discount->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalDelete{{ $discount->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $discount->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $discount->id }}">حذف تخفیف
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از حذف تخفیف ({{ $discount->name }}) اطمینان دارید؟</p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.discounts.delete', ['id' => $discount->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger btn-sm">حذف</button>
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
        @if ($discounts instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $discounts->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
