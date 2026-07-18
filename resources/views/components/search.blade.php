@php
    $base = $base ?? null;
    $nutrients_id = $nutrients_id ?? null;
@endphp
<div class="mt-3 mb-5 text-center">
    <label class="ms-3 cursor-pointer form-check-label">
        <input type="radio" class="base_search form-check-input" name="base_search" @checked($base != 'nutrient')
            value="name">
        جستجو با نام محصول
    </label>
    <label class=cursor-pointer form-check-label">
        <input type="radio" class="base_search form-check-input" name="base_search" @checked($base == 'nutrient')
            value="nutrient">
        جستجو با مغذی‌ها
    </label>
</div>
<form action="{{ route('search') }}"
    class="col-md-7 mx-auto mb-4 position-relative {{ $base == 'nutrient' ? 'hidden' : '' }}" id="search_form">
    <div class="search-box">
        <input type="text" class="search-input my-auto" name="word" value="{{ $word ?? '' }}" required
            placeholder="نام محصول مورد نظر خود را بنویسید">
        <input type="hidden" name="base" value="name">
        <button class="search-btn btn btn-success">جستجو</button>
    </div>
    <div class="auto-complate-box">
        @foreach ($products_name as $name)
            <li class="hidden">{{ $name }}</li>
        @endforeach
    </div>
</form>

<form action="{{ route('search') }}" id="nutrient_search_form" class="{{ $base != 'nutrient' ? 'hidden' : '' }}">
    <input type="hidden" name="base" value="nutrient">
    <p class="titr text-center mb-4">مغذی های مورد نظر خود را انتخاب کنید:</p>
    <div class="row p-1 m-0 border border-2 border-success rounded-3 search-nutrients-box">
        @foreach ($nutrients as $nutrient)
            @php
                $check = false;
                if ($nutrients_id && array_search($nutrient->id, $nutrients_id) !== false) {
                    $check = true;
                }
            @endphp
            <div class="col-6 col-md-3 my-2 form-check form-check-reverse">
                <input class="form-check-input" type="checkbox" value="{{ $nutrient->id }}" name="nutrients_id[]"
                    id="nutrient_{{ $nutrient->id }}" @checked($check)>
                <label class="form-check-label" for="nutrient_{{ $nutrient->id }}">{{ $nutrient->name }}</label>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <button class="btn btn-success rounded-5 px-4">جستجو</button>
    </div>
</form>
