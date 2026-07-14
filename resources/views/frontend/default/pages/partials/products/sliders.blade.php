<div class="quickview-double-slider">
    @php
        if (!is_null($product->gallery_images)) {
            $galleryImages = explode(',', $product->gallery_images);
        } else {
            $galleryImages = [];
            $galleryImages[] = $product->thumbnail_image;
        }

        $primaryImage = $galleryImages[0] ?? $product->thumbnail_image;
    @endphp

    <div class="product-gallery-flipkart">
        <div class="product-gallery-thumbs">
            @foreach ($galleryImages as $galleryImage)
                <button type="button" class="product-gallery-thumb {{ $loop->first ? 'active' : '' }}"
                    data-image="{{ uploadedAsset($galleryImage) }}"
                    aria-label="{{ $product->collectLocalization('name') }} image {{ $loop->iteration }}">
                    <img src="{{ uploadedAsset($galleryImage) }}?thumb"
                        alt="{{ $product->collectLocalization('name') }}">
                </button>
            @endforeach
        </div>

        <div class="product-gallery-main">
            <div class="product-gallery-zoom">
                <img src="{{ uploadedAsset($primaryImage) }}" alt="{{ $product->collectLocalization('name') }}"
                    class="product-gallery-main-img">
            </div>
        </div>
    </div>
</div>

<script>
    function initProductGalleryFlipkart() {
        document.querySelectorAll('.product-gallery-flipkart').forEach(function(gallery) {
            if (gallery.dataset.galleryReady === '1') {
                return;
            }

            gallery.dataset.galleryReady = '1';

            const mainImage = gallery.querySelector('.product-gallery-main-img');
            const zoomBox = gallery.querySelector('.product-gallery-zoom');
            const thumbs = gallery.querySelectorAll('.product-gallery-thumb');

            if (!mainImage || !zoomBox || !thumbs.length) {
                return;
            }

            const setMainImage = function(thumb) {
                thumbs.forEach(function(item) {
                    item.classList.remove('active');
                });
                thumb.classList.add('active');
                mainImage.src = thumb.dataset.image;
                mainImage.style.transform = 'scale(1)';
                mainImage.style.transformOrigin = 'center center';
            };

            thumbs.forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    setMainImage(thumb);
                });
                thumb.addEventListener('mouseenter', function() {
                    setMainImage(thumb);
                });
            });

            zoomBox.addEventListener('mousemove', function(event) {
                const rect = zoomBox.getBoundingClientRect();
                const x = ((event.clientX - rect.left) / rect.width) * 100;
                const y = ((event.clientY - rect.top) / rect.height) * 100;

                mainImage.style.transformOrigin = x + '% ' + y + '%';
                mainImage.style.transform = 'scale(1.85)';
            });

            zoomBox.addEventListener('mouseleave', function() {
                mainImage.style.transform = 'scale(1)';
                mainImage.style.transformOrigin = 'center center';
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProductGalleryFlipkart);
    } else {
        initProductGalleryFlipkart();
    }
</script>
