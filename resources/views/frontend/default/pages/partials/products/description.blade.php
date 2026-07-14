<div class="product-info-tab bg-white rounded-2 overflow-hidden pt-6 mt-4">
    <ul class="nav nav-tabs border-bottom justify-content-center gap-5 pt-info-tab-nav">
        <li><a href="#description" class="active" data-bs-toggle="tab">{{ localize('Description') }}</a></li>
        <li><a href="#info" data-bs-toggle="tab">{{ localize('Additional Information') }}</a></li>
        <li><a href="#reviews" data-bs-toggle="tab">{{ localize('Reviews') }}</a></li>

    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active px-4 py-5" id="description">
            @if ($product->description)
                {!! $product->collectLocalization('description') !!}
            @else
                <div class="text-dark text-center border py-2">{{ localize('Not Available') }}
                </div>
            @endif
        </div>
        <div class="tab-pane fade px-4 py-5" id="info">
            <h6 class="mb-2">{{ localize('Additional Information') }}:</h6>
            <table class="w-100 product-info-table">
                @forelse (generateVariationOptions($product->variation_combinations) as $variation)
                    <tr>
                        <td class="text-dark fw-semibold">{{ $variation['name'] }}</td>
                        <td>
                            @foreach ($variation['values'] as $value)
                                {{ $value['name'] }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td class="text-dark text-center" colspan="2">{{ localize('Not Available') }}
                            </td>
                        </tr>
                    @endforelse
                </table>
            </div>
            
             <div class="tab-pane fade px-4 py-5" id="reviews">
        
                <?php 
                
                $reviews = DB::table('reviews')->where('product_id',$product->id)->select('*')->get();

                $review_counts = DB::table('reviews')->where('product_id',$product->id)->select('*')->count();



                ?>
                @if ($review_counts)
                    
                <h6 class="mb-2">{{ localize('Reviews') }}({{$review_counts}})</h6>
                @else
                <h6 class="mb-2">{{ localize('Reviews') }} (0)</h6>


                @endif

                

               

                
                <div class="row">

                

                    <div class="col-md-6">
                        @foreach ($reviews as $review )
                        
                        <div class="review-main">
                            <p class="article-comment__author text--strong">
                                <b>{{$review->name}}</b> -
                                   <time class="article-comment__date"><?php
                                    $newDate = date("Y-m-d", strtotime($review->created_at));

                                    echo $newDate;

                                 ?></time>
                            </p>
                            
                            <div class="article-comment__content rte">
                                <p>{{$review->message}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-8">
                       <div class="article__comment">
                          <p class="article__comment-form-title heading h2">Leave a comment</p>
                       </div>
                       <div class="form mt-4">
                          <form method="POST" action="{{route('review-store')}}">
                            @csrf
                              <input type="hidden" name="product_id" value="{{$product->id}}">
                             <div class="article__comment-form-wrapper">
                                <div class="form__input-row">
                                   <div class="form__input-wrapper form__input-wrapper--labelled">
                                      <input type="text" class="form__field form__field--text" name="author" required="required">
                                      <label for="comment-form-name" class="form__floating-label">Name</label>
                                   </div>
                                   <div class="form__input-wrapper form__input-wrapper--labelled">
                                      <input id="comment-form-email" type="email" class="form__field form__field--text " name="email" required="required">
                                      <label for="comment-form-email" class="form__floating-label">Email</label>
                                   </div>
                                </div>
                                <div class="form__input-wrapper form__input-wrapper--labelled">
                                   <textarea id="comment-form-body" name="body" rows="5" class="form__field form__field--textarea " required="required"></textarea>
                                   <label for="comment-form-body" class="form__floating-label">Your Message</label>
                                </div>
                                <button type="submit" class="form__submit button button--primary button--min-width">Post comment</button>
                             </div>
                          </form>
                       </div>
                    </div>
                 </div>

            
         
            </div>

        </div>
    </div>
    </div>


<style>

.form__input-wrapper {
    position: relative;
    width: 100%;
}

.form__input-wrapper--labelled .form__field {
    padding-top: 20px;
    padding-bottom: 3px;
    border: 1px solid #dfdfdf;
    margin: 5px 0px;
    width: 100%;
}

.form__floating-label {
    position: absolute;
    left: 13px;
    top: 0;
    line-height: 48px;
    font-size: 1rem;
    color: var(--text-color);
    transform: scale(1);
    transform-origin: left top;
    transition: transform .2s ease-in-out;
    pointer-events: none;
}

label, legend {
    display: block;
}

button.form__submit.button.button--primary.button--min-width {
    padding: 11px 36px;
    border-radius: 4px;
}
</style>


<script>


const modal = document.getElementsByClassName('idMyModal');
const img = document.getElementsByClassName('toZoom');
const modalImg = document.getElementsByClassName('modal-content');
for ( let i = 0; i < img.length; i++ ) {
  img[i].onclick = function () {
    modal[i].style.display = "block";
    modalImg[i].src = this.src;
  }
}

var span = document.getElementsByClassName("close");
for ( let i = 0; i < span.length; i++ ) {
  span[i].onclick = function() { 
    modal[i].style.display = "none";
  }
}

</script>