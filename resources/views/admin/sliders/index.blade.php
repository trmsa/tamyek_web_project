@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'اسلایدر')
@section('admin_content')
    <div class="row">
        <p class="title p-3">اسلایدر</p>
        <div class="text-start mt-3 mb-4">
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">ایجاد اسلایدر جدید</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">تصویر</th>
                        <th scope="col">هدف</th>
                        <th scope="col">عنوان</th>
                        <th scope="col">متن</th>
                        <th scope="col">ویرایش</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($sliders as $key => $slider)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td><img src="{{ $slider->image }}" class="logo"></td>
                            <td>{{ $slider->for == 'web' ? 'وب' : 'موبایل' }}</td>
                            <td>{{ $slider->title }}</td>
                            <td>{{ $slider->text }}</td>
                            <td><a href="{{ route('admin.sliders.edit', ['id' => $slider->id]) }}"
                                    class="btn btn-primary btn-sm">ویرایش</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalSlider{{ $slider->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalSlider{{ $slider->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $slider->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $slider->id }}">حذف
                                            دسته‌بندی</h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از حذف اسلایدر ({{ $slider->title }}) اطمینان دارید؟</p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.sliders.delete', ['id' => $slider->id]) }}"
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
