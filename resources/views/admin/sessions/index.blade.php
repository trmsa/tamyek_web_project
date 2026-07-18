@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'جلسات')
@section('admin_content')
    <div class="row">
        <p class="title p-3">جلسات</p>
        <div class="my-3 d-flex justify-content-end">
            <form action="{{ route('admin.clear_trash') }}" method="POST">
                @csrf
                @method('delete')
                <button class="btn btn-sm btn-danger">پاکسازی سشن ها و خربدهای ناموفق</button>
            </form>
            <a href="{{ route('admin.tokens') }}" class="btn btn-sm btn-success me-3">توکن‌های اپ</a>
        </div>
        <form action="{{ route('admin.sessions') }}">
            <div class="row">
                <div class="col-md-5">
                    <label for="filter" class="form-label">مرتب سازی براساس:</label>
                    <select name="filter" id="filtter" class="form-control">
                        <option value="activity" @selected(Request::get('filter') == 'activity')>آخرین فعالیت</option>
                        <option value="user" @selected(Request::get('filter') == 'user')>کاربر</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label" for="order_by">صعودی/نزولی:</label>
                    <select name="order_by" id="order_by" class="form-control">
                        <option value="asc" @selected(Request::get('order_by') == 'asc')>صعودی</option>
                        <option value="desc" @selected(Request::get('order_by') == 'desc')>نزولی</option>
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
                        <th scope="col">شناسه کاربر</th>
                        <th scope="col">نام کاربر</th>
                        <th scope="col">موبایل</th>
                        <th scope="col">مرورگر</th>
                        <th scope="col">ip</th>
                        <th scope="col">آخرین فعالیت</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                    @foreach ($sessions as $key => $session)
                        @php
                            $user = $users->find($session->user_id);
                        @endphp
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <th>{{ $session->user_id ?? '---' }}</th>
                            <th>{{ $user->name ?? '---' }}</th>
                            <th>{{ $user->mobile ?? '---' }}</th>
                            <th>{{ $session->user_agent }}</th>
                            <th>{{ $session->ip_address }}</th>
                            <th>{{ $session->last_activity ? Helper::fa_date('%y/%m/%d, %H:%s', $session->last_activity) : '---' }}
                            </th>
                            <th><button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalSession{{ $key }}">حذف</button></th>
                            <!-- Modal -->
                            <div class="modal fade" id="modalSession{{ $key }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel{{ $key }}" aria-hidden="true" dir="rtl">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5 ms-auto" id="exampleModalLabel{{ $key }}">
                                                حذف جلسه</h1>
                                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="titr"> آیا از حذف جلسه ({{ $user->name ?? '---' }}) اطمینان دارید؟
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-start">
                                            <form action="{{ route('admin.delete_session') }}" method="POST">
                                                <input type="hidden" name="user_id" value="{{ $session->user_id }}">
                                                <input type="hidden" name="last_activity"
                                                    value="{{ $session->last_activity }}">
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
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        @if ($sessions instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $sessions->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
