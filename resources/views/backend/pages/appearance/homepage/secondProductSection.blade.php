@extends('backend.layouts.master')

@section('title')
    {{ localize('Website Homepage Configuration') }}
    {{ getSetting('title_separator') }}
    {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
<h2 class="h5 mb-lg-0">
    {{ getSetting('second_section_title') ?? localize('Second Product Section') }}
</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <!-- left -->
                <div class="col-xl-9 order-2 order-xl-1">

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card mb-4">
                            <div class="card-body">

                                {{-- SECTION TITLE --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ localize('Section Title') }}
                                    </label>

                                    <input type="hidden" name="types[]" value="second_section_title">

                                    <input type="text" name="second_section_title" class="form-control"
                                        placeholder="{{ localize('Second Product Section') }}"
                                        value="{{ getSetting('second_section_title') }}">
                                </div>


                                {{-- SECTION SUB TITLE --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ localize('Section Sub Title') }}
                                    </label>

                                    <input type="hidden" name="types[]" value="second_section_subtitle">

                                    <input type="text" name="second_section_subtitle" class="form-control"
                                        value="{{ getSetting('second_section_subtitle') }}">
                                </div>


                                {{-- PRODUCTS --}}
                                @php
                                    $second_products = getSetting('second_section_products')
                                        ? json_decode(getSetting('second_section_products'))
                                        : [];
                                @endphp

                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ localize('Select Products') }}
                                    </label>

                                    <input type="hidden" name="types[]" value="second_section_products">

                                    <select class="select2 form-control" multiple name="second_section_products[]"
                                        data-placeholder="{{ localize('Select products') }}" required>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ in_array($product->id, $second_products) ? 'selected' : '' }}>
                                                {{ $product->collectLocalization('name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- BANNER IMAGE --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ localize('Banner Image') }}
                                    </label>

                                    <input type="hidden" name="types[]" value="second_section_banner">

                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">
                                            {{ localize('Choose Banner Image') }}
                                        </span>

                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">

                                                <input type="hidden" name="second_section_banner"
                                                    value="{{ getSetting('second_section_banner') }}">

                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BANNER LINK --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ localize('Banner Link') }}
                                    </label>

                                    <input type="hidden" name="types[]" value="second_section_banner_link">

                                    <input type="url" class="form-control" name="second_section_banner_link"
                                        placeholder="{{ env('APP_URL') . '/example' }}"
                                        value="{{ getSetting('second_section_banner_link') }}">
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">
                                <i data-feather="save" class="me-1"></i>
                                {{ localize('Save') }}
                            </button>
                        </div>

                    </form>
                </div>

                <!-- right -->
                <div class="col-xl-3 order-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-3">{{ localize('Homepage Configuration') }}</h5>
                            <ul class="list-unstyled">
                                @include('backend.pages.appearance.homepage.inc.rightSidebar')
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        "use strict";
        $(document).ready(function () {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });
    </script>
@endsection