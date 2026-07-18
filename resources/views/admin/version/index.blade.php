@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'ویرایش ورژن')
@section('admin_content')
    <p class="title mb-4 p-3">ویرایش ورژن</p>
    <form action="{{ route('admin.change_app_version') }}" method="post">
        @csrf
        @if ($version)
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">ورژن</label>
                    <input type="text" class="form-control" name="app_version" value="{{ $version->app_version }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الزامی</label>
                    <select name="is_force" class="form-control">
                        <option value="0" @selected(!$version->is_force)>خیر</option>
                        <option value="1" @selected($version->is_force)>بله</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">پیام</label>
                    <input type="text" class="form-control" name="message" value="{{ $version->message }}">
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary">ویرایش</button>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">ورژن</label>
                    <input type="text" class="form-control" name="app_version" value="">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الزامی</label>
                    <select name="is_force" class="form-control">
                        <option value="0">خیر</option>
                        <option value="1">بله</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">پیام</label>
                    <input type="text" class="form-control" name="message" value="">
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary">ویرایش</button>
                </div>
            </div>
        @endif
    </form>
@endsection
