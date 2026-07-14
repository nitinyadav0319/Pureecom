@extends('layouts.auth')

@section('title', 'Vendor Onboarding - Step 2')

@section('content')
    <style>
        /* Halka carbon-black border for inputs */
        input.form-control {
            border: 1px solid #2b2b2b;
            /* carbon black shade */
            box-shadow: none;
            transition: border-color 0.2s ease-in-out;
        }

        input.form-control:focus {
            border-color: #000;
            /* darker when focused */
            outline: none;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                {{-- Wizard Progress Bar --}}
                <div class="mb-4">
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">
                            Step 2 of 3
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0">Step 2: Contact Information</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.onboarding.step2.store') }}">
                            @csrf

                            {{-- Contact Person --}}
                            <div class="mb-3">
                                <label class="form-label">Authorized Contact Person <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="contact_person" class="form-control form-control-lg"
                                    value="{{ old('contact_person', $vendor->contact_person ?? '') }}" required>
                            </div>

                            {{-- Designation --}}
                            <div class="mb-3">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" name="designation" class="form-control form-control-lg"
                                    value="{{ old('designation', $vendor->designation ?? '') }}" required>
                            </div>

                            {{-- Alternate Phone --}}
                            <div class="mb-3">
                                <label class="form-label">Alternate Phone (Optional)</label>
                                <input type="text" name="alt_phone" class="form-control form-control-lg"
                                    value="{{ old('alt_phone', $vendor->alt_phone ?? '') }}">
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step1') }}"
                                    class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection