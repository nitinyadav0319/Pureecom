@php
    $top_category_ids = getSetting('top_category_ids') != null ? json_decode(getSetting('top_category_ids')) : [];
    $topCategories = \App\Models\Category::whereIn('id', $top_category_ids)->get();
@endphp
<div class="container">
    <h2 class="text-center fw-bold mb-2">{{ localize('Top Categories') }}</h2>
    <p class="text-center text-muted mb-4">
        {{ localize('Explore our best collections for your kids') }}
    </p>

    <div class="row ">
        @foreach ($topCategories as $category)
            <div class="col-lg-2 col-md-3 col-6 justify-content-center">
                <a href="{{ route('products.index', ['category_id' => $category->id]) }}"
                    class="category-capsule text-decoration-none text-center">
                    <div class="category-img">
                        @if($category->thumbnail_image)
                            <img src="{{ uploadedAsset($category->thumbnail_image) }}"
                                alt="{{ $category->collectLocalization('name') }}">
                        @else
                            <img src="{{ asset('frontend/default/images/placeholder.png') }}"
                                alt="{{ $category->collectLocalization('name') }}">
                        @endif
                    </div>
                    <h6 class="mt-2 fw-semibold text-dark">
                        {{ $category->collectLocalization('name') }}
                    </h6>
                </a>
            </div>
        @endforeach
    </div>
</div>