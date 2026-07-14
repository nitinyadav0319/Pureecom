@if (getSetting('second_section_products') != null)

    @php
        $secondProductIds = json_decode(getSetting('second_section_products'));
        $secondProducts = \App\Models\Product::whereIn('id', $secondProductIds)
            ->isPublished()
            ->get();
    @endphp

    @if ($secondProducts->isNotEmpty())
        <section class="section second-products my-5">
        <div class="container-fluid" style="padding-left:10%; padding-right:10%;">

             <h2 class="section-title mb-4 text-center">
    {{ getSetting('second_section_title') ?? localize('Second Product Section') }}
</h2>

@if (getSetting('second_section_subtitle'))
    <p class="text-center text-muted mb-4">
        {{ getSetting('second_section_subtitle') }}
    </p>
@endif

                {{-- Optional Banner --}}
                @if (getSetting('second_section_banner'))
                    <div class="mb-4 text-center">
                        <a href="{{ getSetting('second_section_banner_link') ?? '#' }}" target="_blank">
                            <img src="{{ uploadedAsset(getSetting('second_section_banner')) }}"
                                alt="Second Section Banner"
                                class="img-fluid">
                        </a>
                    </div>
                @endif

               <div class="row justify-content-center g-2">

    @foreach ($secondProducts as $product)

        <!-- Desktop -->
        <div class="col-lg-3 d-none d-lg-block">
            @include(
                'frontend.default.pages.partials.products.trending-product-card',
                ['product' => $product]
            )
        </div>

        <!-- Mobile -->
        <div class="col-6 d-block d-lg-none">
            @include(
                'frontend.default.pages.partials.products.vertical-product-card',
                [
                    'product' => $product,
                    'bgClass' => 'bg-white'
                ]
            )
        </div>

    @endforeach

</div>

            </div>
        </section>
    @endif
@endif
