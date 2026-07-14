@extends('layouts.auth')

@section('title', 'Vendor Onboarding - Step 6')

@section('content')
    @php
        $currentStep = 6;
        $totalSteps = 7;
        $progress = ($currentStep / $totalSteps) * 100;
    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            Step {{ $currentStep }} of {{ $totalSteps }}
                        </div>
                    </div>
                </div>

                {{-- Card --}}
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0">Step 6: Logistics & Fulfillment</h3>
                    </div>
                    <div class="card-body p-4">

                        {{-- Custom Border Styling --}}
                        <style>
                            .form-control,
                            .form-select,
                            textarea.form-control {
                                border: 1.8px solid #2b2b2b !important;
                                /* carbon black border */
                                border-radius: 8px !important;
                                box-shadow: none !important;
                                transition: all 0.2s ease-in-out;
                            }

                            .form-control:focus,
                            .form-select:focus,
                            textarea.form-control:focus {
                                border-color: #000 !important;
                                box-shadow: 0 0 0 0.2rem rgba(43, 43, 43, 0.2);
                            }
                        </style>

                        <form method="POST" action="{{ route('vendor.onboarding.step6.store') }}">
                            @csrf

                            {{-- Own Logistics --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Do you have your own logistics? <span
                                        class="text-danger">*</span></label>
                                <select name="has_own_logistics" class="form-select form-select-lg" required>
                                    <option value="">Select</option>
                                    <option value="1" {{ old('has_own_logistics', $vendor->has_own_logistics ?? '') == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('has_own_logistics', $vendor->has_own_logistics ?? '') == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- Preferred Shipping --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preferred Shipping Partners</label>
                                <input type="text" name="preferred_shipping" class="form-control form-control-lg"
                                    value="{{ old('preferred_shipping', $vendor->preferred_shipping ?? '') }}">
                            </div>

                            {{-- Warehouse Address --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Warehouse Address</label>
                                <textarea name="warehouse_address" class="form-control form-control-lg"
                                    rows="4">{{ old('warehouse_address', $vendor->warehouse_address ?? '') }}</textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step5') }}"
                                    class="btn btn-outline-primary btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>

                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    Next <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>

                            <p class="mt-3 text-muted small">
                                Provide accurate logistics and warehouse information to ensure smooth order fulfillment.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection