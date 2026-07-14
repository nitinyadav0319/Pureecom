<section class="position-relative banner-section z-1 overflow-hidden">
    <!-- Background Shape -->
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/bg-shape-4.png') }}" 
         alt="bg shape"
         class="position-absolute start-0 bottom-0 w-100 z--1">

        <div class="container-fluid" style="padding-left:10%; padding-right:10%;">
        <div class="d-flex flex-nowrap">
            <!-- First Banner -->
            <div class="flex-fill p-0">
                <a href="{{ getSetting('banner_section_two_banner_one_link') }}">
                    <img src="{{ uploadedAsset(getSetting('banner_section_two_banner_one')) }}" 
                         alt="Banner One"
                         class="custom-banner-img">
                </a>
            </div>

            <!-- Second Banner -->
            <div class="flex-fill p-0">
                <a href="{{ getSetting('banner_section_two_banner_two_link') }}">
                    <img src="{{ uploadedAsset(getSetting('banner_section_two_banner_two')) }}" 
                         alt="Banner Two"
                         class="custom-banner-img">
                </a>
            </div>
        </div>
    </div>
</section>
