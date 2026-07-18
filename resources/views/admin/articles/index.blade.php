@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'مقالات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">مقالات</p>
        <div class="text-start my-3">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">ایجاد مقاله جدید</a>
        </div>
        <form action="{{ route('admin.articles.search') }}" method="POST">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">دسته‌بندی</label>
                    <select name="category" class="form-select">
                        <option value="">همه</option>
                        <option value="public" @selected(Request::get('category') == 'public')>عمومی</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(Request::get('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">عنوان</label>
                    <input type="text" class="form-control" name="word" value="{{ Request::get('word') }}"
                        placeholder="عنوان مقاله را بنویسید">
                    <div class="text-start mt-3">
                        <button class="btn btn-primary">جستجو</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">عنوان</th>
                        <th scope="col">دسته‌بندی</th>
                        <th scope="col">تاریخ انتشار</th>
                        <th scope="col">نوع</th>
                        <th scope="col">نویسنده</th>
                        <th scope="col">تعداد لایک</th>
                        <th scope="col">میانگین لایک</th>
                        <th scope="col">ویرایش</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($articles as $key => $article)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->category == 'public' ? 'عمومی' : $categories->firstWhere('id', $article->category)->name }}
                            </td>
                            <td>{{ $article->published ? Helper::fa_date('%y/%m/%d', strtotime($article->published)) : 'منشتر نشده' }}
                            </td>
                            <td>{{ $article->type == 'article' ? 'مقاله' : 'خبری' }}</td>
                            <td>{{ $article->auther }}</td>
                            <td>{{ number_format($article->likes_count) }}</td>
                            <td>{{ $article->likes }}</td>
                            <td><a href="{{ route('admin.articles.edit', ['id' => $article->id]) }}"
                                    class="btn btn-primary btn-sm">ویرایش</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalArticles{{ $article->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalArticles{{ $article->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $article->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $article->id }}">حذف مقاله
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="titr"> آیا از حذف مقاله ({{ $article->title }}) اطمینان دارید؟</p>
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.articles.delete', ['id' => $article->id]) }}"
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
        @if ($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $articles->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
