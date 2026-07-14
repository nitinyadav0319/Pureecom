<style>
    section.gshop-hero img {
    width: 100%;
}
</style>
<section class="gshop-hero ">
            <div id="demo" class="carousel slide" data-bs-ride="carousel">

                <!-- Indicators/dots -->
                <div class="carousel-indicators">
                    @foreach($sliders as $photo)
                  <button type="button" data-bs-target="#demo" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></button>
                  @endforeach
                </div>
                
                <!-- The slideshow/carousel -->
                <div class="carousel-inner">
                    @foreach ($sliders as $slider)
                    <div class="carousel-item {{ $loop->first ? ' active' : '' }}">
                        <img src="{{ uploadedAsset($slider->image) }}" alt=""
                        class="img-fluid  ">
                    </div>
                    @endforeach
                </div>
                
                <!-- Left and right controls/icons -->
                <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                  <span class="carousel-control-next-icon"></span>
                </button>
              </div>
           
           
           

  
    <!--<div class="at-header-social d-none d-xl-flex align-items-center position-absolute">-->
    <!--    <span class="title fw-medium">{{ localize('Follow on') }}</span>-->
    <!--    <ul class="social-list ms-3">-->
    <!--        <li>-->
    <!--            <a href="{{ getSetting('facebook_link') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>-->
    <!--        </li>-->
    <!--        <li><a href="{{ getSetting('twitter_link') }}" target="_blank"><i class="fab fa-twitter"></i></a></li>-->
    <!--        <li><a href="{{ getSetting('linkedin_link') }}" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>-->
    <!--        <li><a href="{{ getSetting('youtube_link') }}" target="_blank"><i class="fab fa-youtube"></i></a></li>-->
    <!--    </ul>-->
    <!--</div>-->
    <div class="gshop-hero-slider-pagination theme-slider-control position-absolute top-50 translate-middle-y z-5">
    </div>
</section>
