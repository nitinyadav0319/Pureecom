@if (getSetting('best_selling_products') != null)
    @php
        $bestSellingProductIds = json_decode(getSetting('best_selling_products'));
        $bestSellingProducts = \App\Models\Product::whereIn('id', $bestSellingProductIds)
            ->isPublished()
            ->get();
    @endphp

    @if ($bestSellingProducts->isNotEmpty())
        <section class="section best-selling my-5">
            <div class="container">
                <h2 class="section-title mb-4 text-center">
                    {{ localize('Best Selling Products') }}
                </h2>

                {{-- Optional Banner --}}
                @if (getSetting('best_selling_banner'))
                    <div class="mb-4 text-center">
                        <a href="{{ getSetting('best_selling_banner_link') ?? '#' }}" target="_blank">
                            <img src="{{ uploadedAsset(getSetting('best_selling_banner')) }}"
                                alt="Best Selling Banner"
                                class="img-fluid">
                        </a>
                    </div>
                @endif

                <div class="row g-2">
                    @foreach ($bestSellingProducts as $product)
                        <div class="col-lg-3 col-md-4 col-6">
                            @include('frontend.default.pages.partials.products.vertical-product-card', [
                                'product' => $product,
                                'bgClass' => 'bg-white',
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif