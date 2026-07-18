@extends('user.layout')
@section('title', 'تام یک - سوابق خرید')
@section('content_user')
    <p class="title mb-4">محصولات خریداری شده تراکنش {{ $record->transaction_id }}</p>
    @if ($record)
        <div class="table-responsive my-5">
            <table class="table table-hover table-border table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">نام محصول</th>
                        <th scope="col">تعداد</th>
                        <th scope="col">وزن</th>
                        <th scope="col">جمع قیمت</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($record->products as $product)
                        @php
                            if ($product['weight']) {
                                $weight = $product['weight'];
                                $unit = $product['unit'] == 'kg' ? 'کیلوگرم' : 'گرم';
                            } else {
                                $wu_arr = explode('_', $product['unit']);
                                $weight = $wu_arr[0];
                                $unit = $wu_arr[1] == 'kg' ? 'کیلوگرم' : 'گرم';
                            }
                        @endphp
                        <tr>
                            <td><a href="{{ route('products.show', ['id' => $product['product_id']]) }}"
                                    class="link">{{ $product['name'] }}</a></td>
                            <td>{{ $product['count'] }}</td>
                            <td>{{ $weight }} {{ $unit }}</td>
                            <td>{{ number_format($product['total_price']) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
