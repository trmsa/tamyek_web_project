@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'نظرات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">نظرات</p>
        <form action="{{ route('admin.comments.index') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">وضعیت</label>
                    <select name="status" class="form-select">
                        <option value="">همه</option>
                        <option value="0" @selected(Request::get('status') == '0')>تایید نشده</option>
                        <option value="1" @selected(Request::get('status') == '1')>تایید شده</option>
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
                        <th scope="col">نام کاربر</th>
                        <th scope="col">موبایل</th>
                        <th scope="col">کد محصول</th>
                        <th scope="col">نام محصول</th>
                        <th scope="col">امتیاز</th>
                        <th scope="col">نظر</th>
                        <th scope="col">پاسخ</th>
                        <th scope="col">تایید</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($comments as $key => $comment)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $comment->user->name }}</td>
                            <td>{{ $comment->user->mobile }}</td>
                            @php
                                $product = $products->firstWhere('id', $comment->product_id);
                            @endphp
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $comment->like }}</td>
                            <td>{{ $comment->text }}</td>
                            <td><a href="{{ route('admin.comments.show', ['id' => $comment->id]) }}"
                                    class="btn btn-primary btn-sm">پاسخ</a></td>
                            <td>
                                @if ($comment->status === 1)
                                    <span class="text-success">تایید شده</span>
                                @else
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalConfirm{{ $comment->id }}">تایید</button>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalDelete{{ $comment->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                        <!-- Modal Confirm -->
                        <div class="modal fade" id="modalConfirm{{ $comment->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $comment->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $comment->id }}">تایید نظر
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از تایید نظر ({{ $comment->user->name }}) اطمینان دارید؟</p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.comments.confirm', ['id' => $comment->id]) }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm">تایید</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Modal -->
                        <!-- Modal Delete -->
                        <div class="modal fade" id="modalDelete{{ $comment->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $comment->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $comment->id }}">حذف نظر</h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از حذف نظر ({{ $comment->user->name }}) اطمینان دارید؟</p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.comments.delete', ['id' => $comment->id]) }}"
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
