@extends('frontend.default.layouts.master')

@section('contents')

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div>
            <h2 class="fw-bold mb-2">
                {{ $category->name }}
            </h2>

            <h5 class="mb-2">
                Select Any {{ $category->box_limit }} Products
            </h5>

            <h4 class="text-primary fw-bold">
                ₹{{ $category->box_price }}
            </h4>
        </div>

        <div class="mt-3 mt-md-0">
            <h4 class="mb-0 fw-bold">
                Selected :
                <span id="selected-count">0</span>
                / {{ $category->box_limit }}
            </h4>
        </div>

    </div>

    <div class="row g-4">


        @foreach($products as $product)

            <div class="col-lg-3 col-md-4 col-sm-6">

                <div class="position-relative">

                    <input
                        type="checkbox"
                        class="box-product-checkbox"
                        value="{{ $product->variations->first()->id }}"
                        style="
                            position:absolute;
                            top:12px;
                            right:12px;
                            z-index:9999;
                            width:28px;
                            height:28px;
                            cursor:pointer;
                            accent-color:#28a745;
                        ">

                    {{-- Vertical Product Card --}}
                    @include('frontend.default.pages.partials.products.vertical-product-card', [
                        'product' => $product
                    ])

                </div>

            </div>

        @endforeach

    </div>

    <div class="text-center mt-5">

        <button
            id="build-box-btn"
            class="btn btn-primary btn-lg px-5"
            disabled>

            Build My Box

        </button>

    </div>

</div>


<script>
document.getElementById('build-box-btn').addEventListener('click', function(){

    let selectedProducts = [];

    document.querySelectorAll('.box-product-checkbox:checked').forEach(function(item){
        selectedProducts.push(item.value);
    });

    console.log(selectedProducts);

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let limit = {{ $category->box_limit }};

    const checkboxes = document.querySelectorAll('.box-product-checkbox');
    const counter = document.getElementById('selected-count');
    const button = document.getElementById('build-box-btn');

    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            let selected =
                document.querySelectorAll('.box-product-checkbox:checked').length;

            if (selected > limit) {
                this.checked = false;
                alert('You can select only ' + limit + ' products');
                return;
            }

            counter.innerText = selected;

            if (selected == limit) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }

        });

    });

});
</script>

@endsection