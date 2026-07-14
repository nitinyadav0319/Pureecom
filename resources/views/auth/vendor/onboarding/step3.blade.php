@extends('layouts.auth')

@section('title', 'Vendor Onboarding - Step 3')

@section('content')
    <style>
        /* 🌑 Carbon black border for input fields */
        input.form-control {
            border: 1px solid #2b2b2b;
            /* subtle carbon black */
            box-shadow: none;
            transition: border-color 0.2s ease-in-out;
        }

        input.form-control:focus {
            border-color: #000;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
        }

        /* Also apply to input-group (so IFSC + button looks neat) */
        .input-group .form-control {
            border-right: none;
        }

        .input-group .btn {
            border: 1px solid #2b2b2b;
            border-left: none;
        }

        .input-group .btn:focus {
            box-shadow: none;
        }
    </style>

    @php
        $currentStep = 3;
        $totalSteps = 7;
        $progress = ($currentStep / $totalSteps) * 100;
    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                {{-- Dynamic Wizard Progress Bar --}}
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
                        <h3 class="mb-0">Step 3: Bank Details</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.onboarding.step3.store') }}" id="bank-details-form">
                            @csrf

                            {{-- IFSC --}}
                            <div class="mb-3">
                                <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="ifsc_code" name="ifsc_code"
                                        class="form-control form-control-lg text-uppercase"
                                        value="{{ old('ifsc_code', $vendor->ifsc_code ?? '') }}" maxlength="11" required>
                                    <button type="button" id="ifsc_check_btn" class="btn btn-success" title="Validate IFSC">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                                <div id="ifsc_help" class="form-text">IFSC should be 11 characters (e.g. SBIN0005943)</div>
                                <div id="ifsc_error" class="text-danger mt-2 d-none"></div>
                            </div>

                            {{-- Bank Name --}}
                            <div class="mb-3">
                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" id="bank_name" name="bank_name" class="form-control form-control-lg"
                                    value="{{ old('bank_name', $vendor->bank_name ?? '') }}" readonly required>
                            </div>

                            {{-- Branch Name --}}
                            <div class="mb-3">
                                <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" id="branch_name" name="branch_name" class="form-control form-control-lg"
                                    value="{{ old('branch_name', $vendor->branch_name ?? '') }}" readonly required>
                            </div>

                            {{-- Account Holder Name --}}
                            <div class="mb-3">
                                <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" name="account_holder_name" class="form-control form-control-lg"
                                    value="{{ old('account_holder_name', $vendor->account_holder_name ?? '') }}" required>
                            </div>

                            {{-- Account Number --}}
                            <div class="mb-3">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" class="form-control form-control-lg"
                                    value="{{ old('account_number', $vendor->account_number ?? '') }}" required>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step2') }}"
                                    class="btn btn-outline-success btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" id="submit_btn" class="btn btn-success btn-lg px-5">
                                    Next <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>

                            {{-- Note --}}
                            <p class="mt-3 text-muted small">
                                IFSC lookup is automatic. If branch/bank are not found, please verify IFSC or enter details
                                manually.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- IFSC lookup script --}}
    <script>
        (function () {
            const ifscInput = document.getElementById('ifsc_code');
            const checkBtn = document.getElementById('ifsc_check_btn');
            const bankName = document.getElementById('bank_name');
            const branchName = document.getElementById('branch_name');
            const ifscError = document.getElementById('ifsc_error');
            const submitBtn = document.getElementById('submit_btn');

            function setLoading(loading) {
                if (loading) {
                    checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    checkBtn.disabled = true;
                    submitBtn.disabled = true;
                    ifscError.classList.add('d-none');
                } else {
                    checkBtn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i>';
                    checkBtn.disabled = false;
                    submitBtn.disabled = false;
                }
            }

            function clearFields() {
                bankName.value = '';
                branchName.value = '';
            }

            function showError(msg) {
                ifscError.textContent = msg;
                ifscError.classList.remove('d-none');
                clearFields();
            }

            function validIfscFormat(code) {
                return /^[A-Z]{4}0[0-9A-Z]{6}$/.test(code);
            }

            function fetchIfsc(code) {
                setLoading(true);
                fetch(`https://ifsc.razorpay.com/${code}`)
                    .then(response => {
                        if (!response.ok) throw new Error('IFSC not found');
                        return response.json();
                    })
                    .then(data => {
                        bankName.value = data.BANK || '';
                        branchName.value = data.BRANCH || data.ADDRESS || '';
                        ifscError.classList.add('d-none');
                    })
                    .catch(err => {
                        console.error(err);
                        showError('IFSC not found or invalid. Please check and try again.');
                    })
                    .finally(() => setLoading(false));
            }

            checkBtn.addEventListener('click', function () {
                const code = (ifscInput.value || '').trim().toUpperCase();
                if (code.length !== 11 || !validIfscFormat(code)) {
                    showError('Please enter a valid 11 character IFSC code (format: AAAA0BBBBBB).');
                    return;
                }
                fetchIfsc(code);
            });

            ifscInput.addEventListener('blur', function () {
                const code = (this.value || '').trim().toUpperCase();
                if (code.length === 11 && validIfscFormat(code)) {
                    fetchIfsc(code);
                }
            });

            ifscInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
                ifscError.classList.add('d-none');
                if (this.value.length < 11) clearFields();
            });

        })();
    </script>
@endsection