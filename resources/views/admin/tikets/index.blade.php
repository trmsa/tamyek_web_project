@extends('admin.layout')
@section('title', 'تیکت‌ها')
@section('admin_content')
    <div class="row">
        <p class="title mb-4 p-3">مدیریت تیکت‌ها</p>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">نام و نام‌خانوادگی</th>
                        <th scope="col">موبایل</th>
                        <th scope="col">خوانده نشده</th>
                        <th scope="col">مشاهده</th>
                        <th scope="col">حذف</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users as $key => $user)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $tikets->where('user_id', $user->id)->where('owner', 'user')->where('read', 0)->count() }}
                            </td>
                            <td><a href="{{ route('admin.tikets.show', ['id' => $user->id]) }}"
                                    class="btn btn-primary btn-sm">مشاهده</a></td>
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
                                        <h1 class="modal-title fs-5" id="exampleModalLabel{{ $user->id }}">حذف گفتگو
                                        </h1>
                                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        آیا از حذف گفتگوی ({{ $user->name }}) اطمینان دارید؟
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <form action="{{ route('admin.tikets.delete', ['id' => $user->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger btn-sm">حذف گفتگو</button>
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
