@extends('layouts.auth')

@section('title', 'Vendor Onboarding - Step 5')

@section('content')
    @php
        $currentStep = 5;
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
                        <h3 class="mb-0">Step 5: Tax & Compliance</h3>
                    </div>
                    <div class="card-body p-4">

                        {{-- Custom Border Styling --}}
                        <style>
                            .form-control,
                            .form-select {
                                border: 1.8px solid #2b2b2b !important;
                                /* carbon black border */
                                border-radius: 8px !important;
                                box-shadow: none !important;
                                transition: all 0.2s ease-in-out;
                            }

                            .form-control:focus,
                            .form-select:focus {
                                border-color: #000 !important;
                                box-shadow: 0 0 0 0.2rem rgba(43, 43, 43, 0.2);
                            }
                        </style>

                        <form method="POST" action="{{ route('vendor.onboarding.step5.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- PAN Number --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">PAN Number <span class="text-danger">*</span></label>
                                <input type="text" name="pan_number" class="form-control form-control-lg"
                                    value="{{ old('pan_number', $vendor->pan_number ?? '') }}" required>
                            </div>

                            {{-- GST Number --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">GST Number</label>
                                <input type="text" name="gst_number" class="form-control form-control-lg"
                                    value="{{ old('gst_number', $vendor->gst_number ?? '') }}">
                            </div>

                            {{-- IEC Code --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">IEC Code</label>
                                <input type="text" name="iec_code" class="form-control form-control-lg"
                                    value="{{ old('iec_code', $vendor->iec_code ?? '') }}">
                            </div>

                            {{-- KYC Documents --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">KYC Documents</label>
                                <input type="file" name="kyc_docs[]" class="form-control form-control-lg" multiple>
                                <small class="text-muted">You can upload multiple files (PDF, JPG, PNG).</small>

                                @if(isset($vendor) && $vendor->kyc_docs)
                                    <div class="mt-2">
                                        <strong>Already uploaded:</strong>
                                        <ul>
                                            @foreach(explode(',', $vendor->kyc_docs) as $file)
                                                <li><a href="{{ asset('storage/kyc_docs/' . $file) }}" target="_blank">{{ $file }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step4') }}"
                                    class="btn btn-outline-success btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    Next <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>

                            <p class="mt-3 text-muted small">
                                Upload KYC documents required for tax & compliance. Multiple files allowed.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection