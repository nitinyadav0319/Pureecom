<section class="banner-section position-relative z-1 overflow-hidden bg-white">
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/bg-shape-3.png') }}" 
         alt="bg shape"
         class="position-absolute start-0 bottom-0 z--1 w-100">

        <div class="container-fluid" style="padding-left:10%; padding-right:10%;">
        <div class="row g-0">
            @foreach ($banner_section_one_banners as $banner)
                <div class="col-12">
                    <a href="{{ $banner->link }}" class="d-block">
                        <img src="{{ uploadedAsset($banner->image) }}" 
                             class="img-fluid w-100 banner-img" 
                             alt="Banner">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

