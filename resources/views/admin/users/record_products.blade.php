@php
    use App\Helpers\Helper;
@endphp
@extends('admin.layout')
@section('title', 'محصولات سوابق خرید')
@section('admin_content')
    <div class="row">
        <p class="title mb-4 p-3">محصولات سوابق خرید {{ $user->name }}</p>
        <div class="table-responsive">
            <table class="table table-hover table-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">نام محصول</th>
                        <th scope="col">کد محصول</th>
                        <th scope="col">تعداد</th>
                        <th scope="col">وزن</th>
                        <th scope="col">واحد وزن</th>
                        <th scope="col">فی</th>
                        <th scope="col">جمع قیمت</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($record->products as $product)
                        <tr>
                            <td><a href="{{ route('products.show', ['id' => $product['product_id']]) }}"
                                    class="link">{{ $product['name'] }}</a></td>
                            <td>{{ $product['product_id'] }}</td>
                            <td>{{ $product['count'] }}</td>
                            <td>{{ $product['weight'] ?? '---' }}</td>
                            <td>{{ $product['unit'] == 'kg' ? 'کیلوگرم' : ($product['unit'] == 'g' ? 'گرم' : '---') }}</td>
                            <td>{{ number_format($product['price']) }} تومان</td>
                            <td>{{ number_format($product['total_price']) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
