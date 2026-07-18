@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'آمار شهرهای کاربران')
@section('admin_content')
    <div class="row">
        <p class="title p-3">آمار شهرهای کاربران</p>
        <form action="{{ route('admin.facts.users.city') }}">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">استان</label>
                    <select name="province" class="form-select">
                        <option value=""></option>
                        <option value="asc" @selected(Request::get('province') == 'asc')>صعودی</option>
                        <option value="desc" @selected(Request::get('province') == 'desc')>نزولی</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">شهر</label>
                    <select name="city" class="form-select">
                        <option value=""></option>
                        <option value="asc" @selected(Request::get('city') == 'asc')>صعودی</option>
                        <option value="desc" @selected(Request::get('city') == 'desc')>نزولی</option>
                    </select>
                </div>
            </div>
            <div class="my-3">
                <button class="btn btn-primary">جستجو</button>
            </div>
        </form>
        @if ($provinces_count)
            @foreach ($provinces_count as $province_count)
                @php
                    $user_province = $provinces->firstWhere('id', $province_count->province_id);
                @endphp
                <div class="col-md-4 col-xl-3">
                    <p class="titr p-2 mb-2 rounded-3 bg-primary text-light">
                        {{ $user_province ? $user_province->name : 'نامشخص' }}: {{ $province_count->province_count }} نفر
                    </p>
                </div>
            @endforeach
        @endif

        @if ($cities_count)
            @foreach ($cities_count as $city_count)
                @php
                    $user_cities = $cities->firstWhere('id', $city_count->city_id);
                @endphp
                <div class="col-md-4 col-xl-3">
                    <p class="titr p-2 mb-2 rounded-3 bg-primary text-light">
                        {{ $user_cities ? $user_cities->name : 'نامشخص' }}: {{ $city_count->city_count }} نفر</p>
                </div>
            @endforeach
        @endif
    </div>
@endsection
