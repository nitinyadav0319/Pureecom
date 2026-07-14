@extends('frontend.default.layouts.master')

@php
    $detailedProduct = $product;
@endphp

@section('title')
    @if ($detailedProduct->meta_title)
        {{ $detailedProduct->meta_title }}
    @else
        {{ localize('Product Details') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
    @endif
@endsection

@section('meta_description')
    {{ $detailedProduct->meta_description }}
@endsection

@section('meta_keywords')
    @foreach ($detailedProduct->tags as $tag)
        {{ $tag->name }} @if (!$loop->last)
            ,
        @endif
    @endforeach
@endsection

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploadedAsset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploadedAsset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ formatPrice($detailedProduct->min_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('products.show', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploadedAsset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ getSetting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ formatPrice($detailedProduct->min_price) }}" />
    <meta property="product:price:currency" content="{{ env('DEFAULT_CURRENCY') }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection


@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('Product Details') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Home') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Products') }}</li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">{{ localize('Product Details') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->

    <!--product details start-->
    <section class="product-details-area">
        <div class="container-fluid">
            <div class="row g-4">

                <div class="col-xl-12">
                    <div class="product-details">
                        <!-- product-view-box -->
                        @include(
                            'frontend.default.pages.partials.products.product-view-box',
                            compact('product')
                        )
                                                <!-- product-view-box -->
                                                 <!-- 🔹 Pincode Check Section -->
    <form action="{{ route('check.delivery') }}" method="POST">
                            @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="vendor_id" value="{{ $product->vendor_id }}">

         <div class="mb-3">
       <input type="text" name="pincode" placeholder="Enter your pincode" class="form-control" required>
                            </div>
        <button type="submit" class="btn btn-primary">Check Delivery Availability</button>
                        </form>

     @if(session('delivery_message'))
      <div class="alert alert-info mt-3">
             {{ session('delivery_message') }}
                        </div>
                    @endif
                        <!-- description -->
                        @include(
                            'frontend.default.pages.partials.products.description',
                            compact('product')
                        )
                        <!-- description -->
                    </div>

                    </div>
            </div>
    </section>
    <!--product details end-->
    
    <!--related product slider start -->
        @include('frontend.default.pages.partials.products.related-products', [
            'relatedProducts' => $relatedProducts,
        ])
        <!--related products slider end-->
@endsection
