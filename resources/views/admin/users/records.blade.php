@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'سوابق خرید')
@section('admin_content')
    <div class="row">
        <p class="title mb-4 p-3">سوابق خرید {{ $user->name }}</p>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">شماره تراکنش</th>
                        <th scope="col">مبلغ پرداختی</th>
                        <th scope="col">هزینه محصولات</th>
                        <th scope="col">هزینه پستی</th>
                        <th scope="col">درگاه پرداخت</th>
                        <th scope="col">تاریخ پرداخت</th>
                        <th scope="col">تاریخ ارسال</th>
                        <th scope="col">مشاهده محصولات</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($records as $key => $record)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $record->transaction_id }}</td>
                            <td>{{ number_format($record->amount) }} تومان</td>
                            <td>{{ number_format($record->final_price_products) }} تومان</td>
                            <td>{{ number_format($record->postal_price) }} تومان</td>
                            <td>{{ $record->gateway }}</td>
                            <td>{{ Helper::fa_date('%y/%m/%d, %H:%i', strtotime($record->date_payment), true) }}</td>
                            <td class="{{ $record->date_send ? 'text-success' : 'text-danger' }}">
                                {{ $record->date_send ? Helper::fa_date('%y/%m/%d, %H:%i', strtotime($record->date_send), true) : 'درانتظار ارسال' }}
                            </td>
                            <td><a href="{{ route('admin.users.record_products', ['id' => $record->id]) }}"
                                    class="btn btn-primary btn-sm">مشاهده</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
