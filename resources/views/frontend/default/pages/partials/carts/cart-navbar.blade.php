@forelse ($carts as $cart)
    @php
        $product = $cart->product_variation->product ?? null;
    @endphp

    @if($product)
        <li class="d-flex align-items-center pb-3 @if (!$loop->first) pt-3 @endif">
            <div class="thumb-wrapper">
                <a href="{{ route('products.show', $product->slug) }}">
                    <img src="{{ uploadedAsset($product->thumbnail_image) }}" alt="products"
                        class="img-fluid rounded-circle">
                </a>
            </div>
            <div class="items-content ms-3">
                <a href="{{ route('products.show', $product->slug) }}">
                    <h6 class="mb-0">{{ $product->collectLocalization('name') }}</h6>
                </a>

                @foreach (generateVariationOptions($cart->product_variation->combinations) as $variation)
                    <span class="fs-xs text-muted">
                        @foreach ($variation['values'] as $value)
                            {{ $value['name'] }}
                        @endforeach
                        @if (!$loop->last), @endif
                    </span>
                @endforeach

                <div class="products_meta mt-1 d-flex align-items-center">
                    <div>
                        <span class="price text-primary fw-semibold">
                            {{ formatPrice(variationDiscountedPrice($product, $cart->product_variation)) }}
                        </span>
                        <span class="count fs-semibold">x {{ $cart->qty }}</span>
                    </div>
                    <button class="remove_cart_btn ms-2" onclick="handleCartItem('delete', {{ $cart->id }})">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>
        </li>
    @endif
@empty
    <li>
        <img src="{{ staticAsset('frontend/default/assets/img/empty-cart.svg') }}" alt="" class="img-fluid">
    </li>
@endforelse
