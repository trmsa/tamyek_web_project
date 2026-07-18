@php
    use App\Helpers\Helper;
@endphp
@extends('user.layout')
@section('meta')

    <meta name="robots", content="nofollow,noindex">

@endsection
@section('title', 'تام یک - سوابق خرید')
@section('content_user')
    <p class="title mb-4">سوابق خرید</p>
    @if (count($records))
        <div class="row">
            @foreach ($records as $key => $record)
                <div class="col-sm-6 col-lg-4 mt-4">
                    <div class="shadow rounded-4 p-3 bg-secondary-subtle">
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>سفارش شماره:</p>
                            <p>{{ $key + 1 }}</p>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>مبلغ پرداختی:</p>
                            <p>{{ number_format($record->amount) }} تومان</p>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>هزینه محصولات:</p>
                            <p>{{ number_format($record->final_price_products) }} تومان</p>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>هزینه پستی:</p>
                            <p>{{ number_format($record->postal_price) }} تومان</p>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>تاریخ پرداخت:</p>
                            <p>{{ Helper::fa_date('%y/%m/%d %H:%i', strtotime($record->date_payment)) }}</p>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>شماره تراکنش:</p>
                            <p>{{ $record->transaction_id }}</p>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>تاریخ ارسال:</p>
                            <p>{{ isset($record->date_send) ? Helper::fa_date('%y/%m/%d', strtotime($record->date_send)) : 'در انتظار ارسال' }}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            @php
                                $send_way = 'پست پیشتاز';
                                if ($record->send_way === 'barbary') {
                                    $send_way = 'باربری- پس‌کرایه (برعهده خریدار)';
                                } elseif ($record->send_way === 'bus') {
                                    $send_way = 'اتوبوسرانی- پس‌کرایه (برعهده خریدار)';
                                } elseif ($record->send_way === 'tipax') {
                                    $send_way = 'تیپاکس- پس‌کرایه (برعهده خریدار)';
                                }
                            @endphp
                            <p>ارسال شده با:</p>
                            <p>{{ $send_way }}</p>
                        </div>
                        <p class="mb-0">کد مرسوله: </p>
                        <p class="text-start mb-1 border-bottom border-secondary">{{ $record->shipment_code ?? '---' }}</p>
                        @if ($record->send_way == null || $record->send_way == 'post')
                            <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                                <p>رهگیری مرسوله:</p>
                                <p>
                                    @if ($record->shipment_code)
                                        <a href="https://tracking.post.ir/?id={{ $record->shipment_code }}" target="_blank"
                                            class="btn btn-success">رهگیری مرسوله</a>
                                    @else
                                        ---
                                    @endif
                                </p>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-1 border-bottom border-secondary">
                            <p>محصولات این سفارش:</p>
                            <p><a
                                    href="{{ route('user.records.show', ['t_id' => $record->id]) }}"class="btn btn-success">مشاهده</a>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-danger title">شما هیچ سابقه خریدی ندارید.</p>
    @endif
@endsection
