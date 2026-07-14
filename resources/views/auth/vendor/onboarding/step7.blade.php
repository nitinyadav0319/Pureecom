@extends('layouts.auth')

@section('title', 'Vendor Onboarding - Step 7')

@section('content')
    @php
        $currentStep = 7;
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
                        <h3 class="mb-0">Step 7: Terms & Agreement</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.onboarding.step7.store') }}">
                            @csrf

                            {{-- Terms & Conditions Checkbox --}}
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="agreed_terms" class="form-check-input" id="agreed_terms"
                                    value="1" required {{ old('agreed_terms', $vendor->agreed_terms ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="agreed_terms">
                                    I have read and agree to the <a href="#">Vendor Terms & Conditions</a>.
                                </label>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step6') }}"
                                    class="btn btn-outline-success btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>

                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    Submit <i class="fa-solid fa-check ms-2"></i>
                                </button>
                            </div>

                            <p class="mt-3 text-muted small">By submitting, you agree to all the vendor terms and
                                conditions. Your account will be sent for admin approval.</p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection