@extends('layouts.auth')

@section('title', 'Vendor Onboarding - Step 4')

@section('content')
    <style>
        /* 🌑 Carbon black border for all form fields */
        input.form-control,
        select.form-select {
            border: 1px solid #2b2b2b;
            /* subtle carbon black border */
            box-shadow: none;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        input.form-control:focus,
        select.form-select:focus {
            border-color: #000;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
        }

        /* Multiple select styling improvement */
        select[multiple] {
            height: auto;
            min-height: 120px;
        }
    </style>

    @php
        $currentStep = 4;
        $totalSteps = 7;
        $progress = ($currentStep / $totalSteps) * 100;
    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            Step {{ $currentStep }} of {{ $totalSteps }}
                        </div>
                    </div>
                </div>

                {{-- Card --}}
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h3 class="mb-0">Step 4: Product Information</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.onboarding.step4.store') }}">
                            @csrf

                            {{-- Product Categories --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Product Category / Categories <span class="text-danger">*</span>
                                </label>
                                <select name="product_categories[]" class="form-select form-select-lg" multiple required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if(isset($vendor) && in_array($category->id, explode(',', $vendor->product_categories ?? ''))) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple categories.</small>
                            </div>

                            {{-- Average Order Value --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Average Order Value <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="avg_order_value" class="form-control form-control-lg"
                                    placeholder="e.g., 5000"
                                    value="{{ old('avg_order_value', $vendor->avg_order_value ?? '') }}" required>
                            </div>

                            {{-- Expected Product Listing Count --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Expected Product Listing Count <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="expected_listing_count" class="form-control form-control-lg"
                                    placeholder="e.g., 100"
                                    value="{{ old('expected_listing_count', $vendor->expected_listing_count ?? '') }}"
                                    required>
                            </div>

                            {{-- Business Model --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Do you manufacture or resell products? <span class="text-danger">*</span>
                                </label>
                                <select name="business_model" class="form-select form-select-lg" required>
                                    <option value="">Select</option>
                                    <option value="manufacturer" @if(old('business_model', $vendor->business_model ?? '') == 'manufacturer') selected @endif>Manufacturer</option>
                                    <option value="reseller" @if(old('business_model', $vendor->business_model ?? '') == 'reseller') selected @endif>Reseller</option>
                                </select>
                            </div>

                            {{-- Product Certification --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Product Certification (if any)</label>
                                <input type="text" name="product_certification" class="form-control form-control-lg"
                                    placeholder="e.g., ISO 9001"
                                    value="{{ old('product_certification', $vendor->product_certification ?? '') }}">
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step3') }}"
                                    class="btn btn-outline-success btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    Next <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>

                            <p class="mt-3 text-muted small">
                                Select the categories you intend to sell products in. You can choose multiple categories.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection