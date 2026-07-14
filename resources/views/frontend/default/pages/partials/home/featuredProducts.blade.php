@if(($featuredProductsLeft->count() ?? 0) || ($featuredProductsRight->count() ?? 0))
<section class="featured-products my-5">
        <div class="container-fluid" style="padding-left:10%; padding-right:10%;">
        <h2 class="mb-4 text-center">
    {{ getSetting('featured_sub_title') ?? localize('Featured Products') }}
</h2>

        <div class="row g-4">

            @php
                // merge left and right products into single collection
                $featuredProducts = collect($featuredProductsLeft)->merge($featuredProductsRight);
            @endphp

            @foreach($featuredProducts as $product)
                <div class="col-6 col-sm-6 col-md-4">
                    @include('frontend.default.pages.partials.products.trending-product-card', ['product' => $product])
                </div>
            @endforeach

        </div>
    </div>
</section>
@endif
