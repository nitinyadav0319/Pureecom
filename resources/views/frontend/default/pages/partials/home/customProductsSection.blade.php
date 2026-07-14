<section class="featured-products pb-100 position-relative overflow-hidden z-1">

    <div class="container-fluid" style="padding-left:10%; padding-right:10%;">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-title text-center mb-4">
                    <h3 class="mb-2">{{ localize(getSetting('custom_section_products_title')) }}</h3>
                    <p class="mb-0">{{ localize(getSetting('custom_section_products_sub_title')) }}</p>
                </div>
            </div>
        </div>

        <div class="row g-2">

            @php
                $custom_section_products = getSetting('custom_section_products') != null
                    ? json_decode(getSetting('custom_section_products'))
                    : [];

                $left_products = \App\Models\Product::whereIn('id', $custom_section_products)->get();
            @endphp

            @foreach ($left_products as $product)

                <!-- Desktop -->
                <div class="col-lg-4 d-none d-lg-block">
                    @include('frontend.default.pages.partials.products.horizontal-product-card', [
                        'product' => $product,
                        'bgClass' => 'bg-white',
                    ])
                </div>

                <!-- Mobile -->
                <div class="col-6 d-block d-lg-none">
                    @include('frontend.default.pages.partials.products.vertical-product-card', [
                        'product' => $product,
                        'bgClass' => 'bg-white',
                    ])
                </div>

            @endforeach

        </div>
    </div>

</section>