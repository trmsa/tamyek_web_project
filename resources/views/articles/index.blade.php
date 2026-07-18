@extends('_master')
@section('meta')

    <link rel="canonical" href="{{ route('articles.index') }}" />
    <meta name="description" content="مقالات تام یک ">
    <meta property="og:site_name" content="{{ Config('app.name') }}" />
    @component('components.schema-sitename')
    @endcomponent

@endsection
@section('title', 'مقالات تام یک')
@section('content')
    <div class="container-xl">
        <h1 class="title my-3">مقالات تام یک</h1>
        <form id="articles_search_form">
            <div class="row">
                <div class="col-3 col-md-4 px-1 text">
                    <label for="article_category_select" class="form-label">دسته‌بندی</label>
                    <select name="category" class="form-control" id="article_category_select">
                        <option value="">همه</option>
                        <option value="public" @selected(Request::get('category') == 'public')>عمومی</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(Request::get('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 col-md-4 px-1 text">
                    <label for="article_orderby_select" class="form-label">تاریخ انتشار</label>
                    <select name="order_by" class="form-control" id="article_orderby_select">
                        <option value="desc" @selected(Request::get('order_by') == 'desc')>نزولی</option>
                        <option value="asc" @selected(Request::get('order_by') == 'asc')>صعودی</option>
                    </select>
                </div>
                <div class="col-6 col-md-4 px-1 text">
                    <label for="word" class="form-label">جستجو</label>
                    <input type="text" name="word" value="{{ Request::get('word') }}" class="form-control"
                        id="word" placeholder="عنوان مقاله را بنویسید">
                    <div class="mt-3 text-start">
                        <button class="btn btn-success">جستجو</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            @foreach ($articles as $article)
                @component('components.article', compact('article'))
                @endcomponent
            @endforeach
        </div>
    </div>
@endsection
