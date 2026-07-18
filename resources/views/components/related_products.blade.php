<div class="row mt-4 position-relative products-scroll-box">
    <p class="title">محصولات مرتبط</p>
    <div class="d-flex align-items-center products-container-scroll pb-4">
        @foreach ($relateds as $related)
            <div class="px-2">
                <div class="p-1 shadow rounded-3 related-product-box">
                    <a href="{{ route('products.show', ['id' => $related->id]) }}"
                        class="home-product-link position-relative d-block mx-auto">
                        <img src="{{ $related->images[0] }}" alt="{{ $related->name }}" title="{{ $related->name }}"
                            class="w-100 rounded-3 shadow product-image" width="1024" height="768">
                        <h2 class="text-center mt-2 product-title">{{ $related->name }}</h2>
                        @if ($related->inventory > 0)
                            @if ($related->unit == 'kg')
                                <p class="mini-text mb-1 text-center">هر کیلو</p>
                            @elseif($related->unit == 'g')
                                <p class="mini-text mb-1 text-center">هر گرم</p>
                            @else
                                @php
                                    $pack = explode('_', $related->unit);
                                    $weight_pack = $pack[0];
                                    $unit_pack = $pack[1] == 'kg' ? 'کیلویی' : 'گرمی';
                                @endphp
                                <p class="mini-text mb-1 text-center">{{ $weight_pack }}{{ $unit_pack }}</p>
                            @endif
                            @if ($related->discount() == 'public_percent' || $related->discount() == 'public_constant')
                                <p class="text-decoration-line-through text-center text-danger mb-0 product-text">
                                    {{ number_format($related->price) }} تومان</p>
                                <p class="text-center product-text mb-0">{{ number_format($related->discounted_price) }}
                                    تومان</p>
                                @if ($related->discount_type == 'public_percent')
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $related->discount_amount }}%
                                    </span>
                                @endif
                            @else
                                <p class="text-center product-text mb-0">{{ number_format($related->price) }} تومان</p>
                            @endif
                        @else
                            <p class="text-center text-danger text">ناموجود</p>
                        @endif

                    </a>
                    <div class="text-center mt-5">
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" value="{{ $related->id }}" name="product_id">
                            <button class="btn btn-success add-cart-btn" @disabled(!$related->inventory)>افزودن به سبد
                                خرید</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
