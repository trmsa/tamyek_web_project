@extends('admin.layout')
@section('title', 'تعیین هزینه پستی')
@section('admin_content')
    <p class="title p-3">تعیین هزینه پستی</p>
    <form action="{{ route('admin.postal.store') }}" method="POST">
        @csrf
        <div class="row">
            @php
                $nears = $postals->whereNotNull('province_id')->where('type', 'near');
                $big_cities = $postals->whereNotNull('city_id')->where('type', 'big_city');
                $self_province = $postals->firstWhere('type', 'self_province');
                $self_city = $postals->firstWhere('type', 'self_city');
                $base = $postals->firstWhere('type', 'base');
                $many_weight = $postals->firstWhere('type', 'many_weight');
                $min_price_free_postal = $postals->firstWhere('type', 'min_price_free_postal');
            @endphp
            <div class="col-md-6 mb-4">
                <label class="form-label">هزینه پستی پایه (غیرهمجوار)</label>
                <input type="number" class="form-control hidden-arrow" name="base_price"
                    value="{{ $base ? $base->province_price : null }}">
            </div>
            <div class="col-md-6 mb-4">
                <label class="form-label">هزینه وزن بیشتر از یک کیلوگرم</label>
                <input type="number" class="form-control hidden-arrow" name="many_weight"
                    value="{{ $many_weight ? $many_weight->province_price : null }}">
            </div>
            <div class="col-md-6 mb-4">
                <label class="form-label">ارسال رایگان برای خریدهای بالاتر از</label>
                <input type="number" class="form-control hidden-arrow" name="min_price_free_postal"
                    value="{{ $min_price_free_postal ? $min_price_free_postal->province_price : null }}">
            </div>
            <div class="row" id="near_provinces_box">
                <p class="titr">استان‌های همجوار: <button class="btn btn-primary btn-sm" type="button"
                        id="admin_add_near_province_btn" data-bs-toggle="modal"
                        data-bs-target="#modalAddCity">افزودن</button></p>
                @foreach ($nears as $near)
                    @php
                        $province = $provinces->firstWhere('id', $near->province_id);
                    @endphp
                    <div class="col-md-6 mb-4">
                        <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                        <label class="form-label">{{ $province->name }}</label>
                        <input type="number" class="form-control hidden-arrow" name="nears_price[{{ $near->province_id }}]"
                            value="{{ $near->province_price }}">
                    </div>
                @endforeach
            </div>
            <div class="row" id="big_cities_box">
                <p class="titr">کلان شهرها: <button class="btn btn-primary btn-sm" type="button"
                        id="admin_add_big_city_btn" data-bs-toggle="modal" data-bs-target="#modalAddCity">افزودن</button>
                </p>
                @foreach ($big_cities as $big_city)
                    @php
                        $city = $cities->firstWhere('id', $big_city->city_id);
                    @endphp
                    <div class="col-md-6 mb-4">
                        <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                        <label class="form-label">{{ $city->name }}</label>
                        <input type="number" class="form-control hidden-arrow"
                            name="big_cities_price[{{ $big_city->city_id }}]" value="{{ $big_city->city_price }}">
                    </div>
                @endforeach
            </div>
            <div id="self_province_box">
                <p class="titr">استان مبدا: <button class="btn btn-primary btn-sm" type="button"
                        id="admin_add_self_province_btn" data-bs-toggle="modal"
                        data-bs-target="#modalAddCity">افزودن</button></p>
                @if ($self_province)
                    @php
                        $self_province_info = $provinces->firstWhere('id', $self_province->province_id);
                    @endphp
                    <div class="col-md-6 mb-4">
                        <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                        <label class="form-label">{{ $self_province_info->name }}</label>
                        <input type="number" class="form-control hidden-arrow"
                            name="self_province_price[{{ $self_province->province_id }}]"
                            value="{{ $self_province->province_price }}">
                    </div>
                @endif
            </div>
            <div id="self_city_box">
                <p class="titr">شهر مبدا: <button class="btn btn-primary btn-sm" type="button"
                        id="admin_add_self_city_btn" data-bs-toggle="modal" data-bs-target="#modalAddCity">افزودن</button>
                </p>
                @if ($self_city)
                    @php
                        $self_city_info = $cities->firstWhere('id', $self_city->city_id);
                    @endphp
                    <div class="col-md-6 mb-4">
                        <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                        <label class="form-label">{{ $self_city_info->name }}</label>
                        <input type="number" class="form-control hidden-arrow"
                            name="self_city_price[{{ $self_city->city_id }}]" value="{{ $self_city->city_price }}">
                    </div>
                @endif
            </div>
            <div class="text-start mt-3">
                <button class="btn btn-primary">ذخیره</button>
            </div>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="modalAddCity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        dir="rtl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">افزودن استان یا شهر</h1>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">استان</label>
                        <select id="province" class="form-select">
                            <option value=""></option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="modal_city_selext_box">
                        <label class="form-label">شهر</label>
                        <select id="city" class="form-select">

                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-start">
                    <button class="btn btn-success btn-sm" id="modal_add_province_city_btn">افزودن</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal -->
    <select id="all_cities" class="d-none">
        @foreach ($cities as $city)
            <option class="city-province-{{ $city->province_id }}" value="{{ $city->id }}">{{ $city->name }}
            </option>
        @endforeach
    </select>
@endsection
