<div class="col-6 col-md-3 col-lg-2 mb-4 px-1">
    <div class="p-1 shadow rounded-3 product-box">
        <a href="{{ route('products.show', ['id' => $product->id]) }}"
            class="home-product-link position-relative d-block mx-auto">
            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" title="{{ $product->name }}"
                class="w-100 rounded-3 shadow product-image" width="1024" height="768">
            <h2 class="text-center mt-2 product-title">{{ $product->name }}</h2>
            @if ($product->inventory > 0)
                @if ($product->unit == 'kg')
                    <p class="mini-text mb-1 text-center">هر کیلو</p>
                @elseif($product->unit == 'g')
                    <p class="mini-text mb-1 text-center">هر گرم</p>
                @else
                    @php
                        $pack = explode('_', $product->unit);
                        $weight_pack = $pack[0];
                        $unit_pack = $pack[1] == 'kg' ? 'کیلویی' : 'گرمی';
                    @endphp
                    <p class="mini-text mb-1 text-center">{{ $weight_pack }}{{ $unit_pack }}</p>
                @endif
                @if ($product->discount() == 'public_percent' || $product->discount() == 'public_constant')
                    <p class="text-decoration-line-through text-center text-danger mb-0 product-text">
                        {{ number_format($product->price) }} تومان</p>
                    <p class="text-center product-text mb-0">{{ number_format($product->discounted_price) }} تومان</p>
                    @if ($product->discount_type == 'public_percent')
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $product->discount_amount }}%
                        </span>
                    @endif
                @else
                    <p class="text-center product-text mb-0">{{ number_format($product->price) }} تومان</p>
                @endif
            @else
                <p class="text-center text-danger text">ناموجود</p>
            @endif

        </a>
        <div class="text-center mt-6">
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $product->id }}" name="product_id">
                <button class="btn btn-success add-cart-btn" @disabled(!$product->inventory)>افزودن به سبد خرید</button>
            </form>
        </div>
    </div>
</div>
