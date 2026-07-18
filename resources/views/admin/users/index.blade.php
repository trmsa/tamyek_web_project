@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'کاربران')
@section('admin_content')
    <div class="row">
        <p class="title mb-4 p-3">آمار کاربران</p>
        <div class="col-md-6 mb-4">
            <form action="{{ route('admin.users.search') }}">
                <label class="form-label">موبایل:</label>
                <input type="text" value="{{ $mobile ?? null }}" class="form-control hidden-arrow" name="mobile">
                <button class="btn btn-primary mt-3">جستجو</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">نام و نام‌خانوادگی</th>
                        <th scope="col">موبایل</th>
                        <th scope="col">استان</th>
                        <th scope="col">شهر</th>
                        <th scope="col">آدرس</th>
                        <th scope="col">پلاک</th>
                        <th scope="col">کدپستی</th>
                        <th scope="col">تاریخ عضویت</th>
                        <th scope="col">تعداد خرید</th>
                        <th scope="col">مبلغ خریدها</th>
                        <th scope="col">تیکت‌ها</th>
                        <th scope="col">سوابق خرید</th>
                        <th scope="col">ویرایش</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users as $key => $user)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
                            @php
                                $city = $cities->where('id', $user->city_id)->first();
                                $province = $city ? $provinces->firstWhere('id', $city->province_id)->name : '---';
                                $city = $city ? $city->name : '---';
                            @endphp
                            <td>{{ $province }}</td>
                            <td>{{ $city }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->plaque }}</td>
                            <td>{{ $user->postal_code }}</td>
                            <td>{{ Helper::fa_date('%y-%m-%d', strtotime($user->created_at)) }}</td>
                            @php
                                $buies = $sales->where('user_id', $user->id);
                                $buies_count = $buies->count();
                                $total_price = $buies->sum('final_price_products');
                            @endphp
                            <td>{{ $buies_count }}</td>
                            <td>{{ number_format($total_price) }} تومان</td>
                            <td><a href="{{ route('admin.tikets.show', ['id' => $user->id]) }}"
                                    class="btn btn-primary btn-sm">تیکت‌ها</a></td>
                            <td><a href="{{ route('admin.users.records', ['id' => $user->id]) }}"
                                    class="btn btn-primary btn-sm">سوابق</a></td>
                            <td><a href="{{ route('admin.users.edit', ['id' => $user->id]) }}"
                                    class="btn btn-primary btn-sm">ویرایش</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalUser{{ $user->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modalUser{{ $user->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $user->id }}" aria-hidden="true" dir="rtl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $user->id }}">حذف کاربر
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        آیا از حذف کاربر ({{ $user->name }}) اطمینان دارید؟
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.users.delete', ['id' => $user->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger btn-sm">حذف کاربر</button>
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
        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="row" dir="ltr">
                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
