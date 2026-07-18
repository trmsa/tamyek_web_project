@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'دسته‌بندی‌ها')
@section('admin_content')
    <div class="row">
        <p class="title p-3">دسته‌بندی‌ها</p>
        <div class="text-start mt-3 mb-4">
            <a href="{{ route('admin.category.create') }}" class="btn btn-primary">ایجاد دسته‌بندی جدید</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">تصویر</th>
                        <th scope="col">نام</th>
                        <th scope="col">متا</th>
                        <th scope="col">ویرایش</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($categories as $key => $category)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td><img src="{{ $category->image }}" class="logo"></td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->meta_description }}</td>
                            <td><a href="{{ route('admin.category.edit', ['id' => $category->id]) }}"
                                    class="btn btn-primary btn-sm">ویرایش</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalCategory{{ $category->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalCategory{{ $category->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $category->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $category->id }}">حذف دسته‌بندی
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از حذف دسته‌بندی ({{ $category->name }}) اطمینان دارید؟</p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.category.delete', ['id' => $category->id]) }}"
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
    </div>
@endsection
