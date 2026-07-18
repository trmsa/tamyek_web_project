@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'محصولات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">محصولات</p>
        <div class="text-start my-3">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">ایجاد محصول جدید</a>
        </div>
        <form action="{{ route('admin.products.search') }}">
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
                        <th scope="col">دسته‌بندی</th>
                        <th scope="col">نام</th>
                        <th scope="col">موجودی</th>
                        <th scope="col">واحد وزن</th>
                        <th scope="col">قیمت اصلی</th>
                        <th scope="col">مقدار تخفیف</th>
                        <th scope="col">قیمت با تخفیف</th>
                        <th scope="col">نوع تخفیف</th>
                        <th scope="col">شروع تخفیف</th>
                        <th scope="col">پایان تخفیف</th>
                        <th scope="col">تعداد فروش</th>
                        <th scope="col">مبلغ فروش</th>
                        <th scope="col">امتیاز</th>
                        <th scope="col">تعداد نظرات</th>
                        <th scope="col">مشاهده نظرات</th>
                        <th scope="col">مشاهده محصول</th>
                        <th scope="col">ویرایش</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($products as $key => $product)
                        <tr id="row_{{ $product->id }}">
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $categories->firstWhere('id', $product->category_id)->name }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->inventory }}</td>
                            @php
                                $discount = $product->discount();
                                $discount_amount = '---';
                                $discount_type = '---';

                                if ($discount == 'public_percent') {
                                    $discount_type = 'عمومی درصدی';
                                    $discount_amount = $product->discount_amount . ' %';
                                } elseif ($discount == 'public_constant') {
                                    $discount_type = 'عمومی ثابت';
                                    $discount_amount = number_format($product->discount_amount) . ' تومان';
                                } elseif ($discount == 'code_percent') {
                                    $discount_type = 'خصوصی درصدی';
                                    $discount_amount = $product->discount_amount . ' %';
                                } elseif ($discount == 'code_constant') {
                                    $discount_type = 'خصوصی ثابت';
                                    $discount_amount = number_format($product->discount_amount) . ' تومان';
                                }

                                if ($product->unit == 'kg') {
                                    $unit = 'کیلوگرم';
                                } elseif ($product->unit == 'g') {
                                    $unit = 'گرم';
                                } else {
                                    $unit = 'بسته‌بندی';
                                }
                            @endphp
                            <td>{{ $unit }}</td>
                            <td>{{ number_format($product->price) }} تومان</td>
                            <td>{{ $discount_amount }}</td>
                            <td>{{ $discount ? number_format($product->discounted_price) : '---' }}</td>
                            <td>{{ $discount ? $discount_type : '---' }}</td>
                            <td>{{ $discount ? Helper::fa_date('%y/%m/%d', strtotime($product->discount_begin)) : '---' }}
                            </td>
                            <td>{{ $discount ? Helper::fa_date('%y/%m/%d', strtotime($product->discount_expire)) : '---' }}
                            </td>
                            <td>{{ $product->sales_count }}</td>
                            <td>{{ number_format($product->total_price_sales) }} تومان</td>
                            <td>{{ $product->likes }}</td>
                            <td>{{ $product->likes_count }}</td>
                            <td><a href="{{ route('admin.products.comments', ['id' => $product->id]) }}"
                                    class="btn btn-primary btn-sm">نظرات</a></td>
                            <td><a href="{{ route('admin.products.show', ['id' => $product->id]) }}"
                                    class="btn btn-primary btn-sm">مشاهده</a></td>
                            <td><a href="{{ route('admin.products.edit', ['id' => $product->id]) }}"
                                    class="btn btn-primary btn-sm">ویرایش</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalProducts{{ $product->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalProducts{{ $product->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $product->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $product->id }}">حذف محصول
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از حذف محصول ({{ $product->name }}) اطمینان دارید؟</p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.products.delete', ['id' => $product->id]) }}"
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
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
