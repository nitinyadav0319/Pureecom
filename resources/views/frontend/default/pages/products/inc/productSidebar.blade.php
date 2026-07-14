<div class="gshop-sidebar bg-white rounded-2 overflow-hidden">
    <!--Filter by search-->
    <div class="sidebar-widget search-widget bg-white py-5 px-4">
        <div class="widget-title d-flex">
            <h6 class="mb-0 flex-shrink-0">{{ localize('Search Now') }}</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>
        <div class="search-form d-flex align-items-center mt-4">
            <input type="hidden" name="view" value="{{ request()->view }}">
            <input type="text" id="search" name="search"
                @isset($searchKey)
       value="{{ $searchKey }}"
       @endisset
                placeholder="{{ localize('Search') }}">
            <button type="submit" class="submit-icon-btn-secondary"><i
                    class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </div>
    <!--Filter by search-->
    <!--Filter by Categories-->
    <div class="sidebar-widget category-widget bg-white py-5 px-4 border-top mobile-menu-wrapper scrollbar h-400px">
        <div class="widget-title d-flex">
            <h6 class="mb-0 flex-shrink-0">{{ localize('Categories') }}</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>
        <ul class="widget-nav mt-4">

            @php
                $product_listing_categories = getSetting('product_listing_categories') != null ? json_decode(getSetting('product_listing_categories')) : [];
                $categories = \App\Models\Category::whereIn('id', $product_listing_categories)->get();
            @endphp
            @foreach ($categories as $category)
                @php
                    $productsCount = \App\Models\ProductCategory::where('category_id', $category->id)->count();
                @endphp
                <li><a href="{{ route('products.index') }}?&category_id={{ $category->id }}"
                        class="d-flex justify-content-between align-items-center">{{ $category->collectLocalization('name') }}<span
                            class="fw-bold fs-xs total-count">{{ $productsCount }}</span></a></li>
            @endforeach

        </ul>
    </div>
    <!--Filter by Categories-->

    <!--Filter by Price-->
    <div class="sidebar-widget price-filter-widget bg-white py-5 px-4 border-top">
        <div class="widget-title d-flex">
            <h6 class="mb-0 flex-shrink-0">{{ localize('Filter by Price') }}</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>
        <div class="at-pricing-range mt-4">
            <form class="range-slider-form">
                <div class="price-filter-range"></div>
                <div class="d-flex align-items-center mt-3">
                    <input type="number" min="0" oninput="validity.valid||(value='0');"
                        class="min_price price-range-field price-input price-input-min" name="min_price"
                        data-value="{{ $min_value }}" data-min-range="0">
                    <span class="d-inline-block ms-2 me-2 fw-bold">-</span>

                    <input type="number" max="{{ $max_range }}"
                        oninput="validity.valid||(value='{{ $max_range }}');"
                        class="max_price price-range-field price-input price-input-max" name="max_price"
                        data-value="{{ $max_value }}" data-max-range="{{ $max_range }}">

                </div>
                <button type="submit" class="btn btn-primary btn-sm mt-3">{{ localize('Filter') }}</button>
            </form>
        </div>
    </div>
    <!--Filter by Price-->
    <!--Filter by Age-->
<div class="sidebar-widget age-filter-widget bg-white py-5 px-4 border-top">
    <div class="widget-title d-flex">
        <h6 class="mb-0 flex-shrink-0">{{ localize('Filter by Age') }}</h6>
        <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
    </div>

    <form method="GET" action="{{ route('products.index') }}">
        {{-- Preserve other filters --}}
        <input type="hidden" name="search" value="{{ request()->search }}">
        <input type="hidden" name="min_price" value="{{ request()->min_price }}">
        <input type="hidden" name="max_price" value="{{ request()->max_price }}">
        <input type="hidden" name="category_id" value="{{ request()->category_id }}">
        <input type="hidden" name="tag_id" value="{{ request()->tag_id }}">

        <div class="mt-4">
            @foreach ($ageValues as $age)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="age[]" value="{{ $age }}"
                        id="age_{{ $age }}" {{ in_array($age, (array)request()->age) ? 'checked' : '' }}>
                    <label class="form-check-label" for="age_{{ $age }}">
                        {{ $age }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary btn-sm mt-3">{{ localize('Apply') }}</button>
    </form>
</div>
<!--Filter by Age-->

            <!-- Filter by Size -->
<div class="sidebar-widget size-filter-widget bg-white py-5 px-4 border-top">
    <div class="widget-title d-flex">
        <h6 class="mb-0 flex-shrink-0">{{ localize('Filter by Size') }}</h6>
        <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
    </div>

    <form method="GET" action="{{ route('products.index') }}">
        {{-- Preserve other filters --}}
        <input type="hidden" name="search" value="{{ request()->search }}">
        <input type="hidden" name="min_price" value="{{ request()->min_price }}">
        <input type="hidden" name="max_price" value="{{ request()->max_price }}">
        <input type="hidden" name="category_id" value="{{ request()->category_id }}">
        <input type="hidden" name="tag_id" value="{{ request()->tag_id }}">

        <div class="mt-4">
            @foreach ($sizeValues as $size)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="size[]" value="{{ $size }}"
                        id="size_{{ $size }}" {{ in_array($size, (array)request()->size) ? 'checked' : '' }}>
                    <label class="form-check-label" for="size_{{ $size }}">
                        {{ $size }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary btn-sm mt-3">{{ localize('Apply') }}</button>
    </form>
</div>
<!-- Filter by Size -->

        
    <!--Filter by Tags-->
    <div class="sidebar-widget tags-widget py-5 px-4 bg-white">
        <div class="widget-title d-flex">
            <h6 class="mb-0">{{ localize('Tags') }}</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>
        <div class="mt-4 d-flex gap-2 flex-wrap">
            @foreach ($tags as $tag)
                <a href="{{ route('products.index') }}?&tag_id={{ $tag->id }}"
                    class="btn btn-outline btn-sm">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
    <!--Filter by Tags-->
</div>
