@extends('layouts.auth')

@section('title', 'Vendor Onboarding - Step 1')

@section('content')
    <div class="container py-5">

        {{-- 🌑 Custom Input Styling --}}
        <style>
            /* Carbon black border styling for all inputs, selects & textareas */
            .form-control,
            .form-select {
                border: 1px solid #2b2b2b !important;
                /* Carbon black */
                border-radius: 6px;
                box-shadow: none;
                transition: all 0.2s ease-in-out;
                background-color: #fafafa;
                /* soft contrast */
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #007bff !important;
                /* Bootstrap blue on focus */
                box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
                background-color: #fff;
            }

            textarea.form-control {
                resize: vertical;
            }
        </style>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0">Step 1: Business Information</h3>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.onboarding.step1.store') }}">
                            @csrf

                            {{-- Business Name --}}
                            <div class="mb-3">
                                <label class="form-label">Business Name <span class="text-danger">*</span></label>
                                <input type="text" name="business_name" class="form-control form-control-lg"
                                    value="{{ old('business_name', $vendor->business_name ?? '') }}" required>
                            </div>

                            {{-- Business Type --}}
                            <div class="mb-3">
                                <label class="form-label">Business Type <span class="text-danger">*</span></label>
                                <input type="text" name="business_type" class="form-control form-control-lg"
                                    value="{{ old('business_type', $vendor->business_type ?? '') }}" required>
                            </div>

                            {{-- Business Reg/GST --}}
                            <div class="mb-3">
                                <label class="form-label">Business Registration Number / GSTIN <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="business_reg_no" class="form-control form-control-lg"
                                    value="{{ old('business_reg_no', $vendor->business_reg_no ?? '') }}" required>
                            </div>

                            {{-- Establishment Date --}}
                            <div class="mb-3">
                                <label class="form-label">Date of Establishment <span class="text-danger">*</span></label>
                                <input type="date" name="establishment_date" class="form-control form-control-lg"
                                    value="{{ old('establishment_date', $vendor->establishment_date ?? '') }}" required>
                            </div>

                            {{-- Business Address --}}
                            <div class="mb-3">
                                <label class="form-label">Business Address <span class="text-danger">*</span></label>
                                <textarea name="business_address" class="form-control form-control-lg" rows="3"
                                    required>{{ old('business_address', $vendor->business_address ?? '') }}</textarea>
                            </div>

                            {{-- Location Row --}}
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" id="pincode" name="zip" class="form-control form-control-lg"
                                        value="{{ old('zip', $vendor->zip ?? '') }}" maxlength="6" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" class="form-control form-control-lg"
                                        value="{{ old('city', $vendor->city ?? '') }}" readonly required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                    <input type="text" id="district" name="district" class="form-control form-control-lg"
                                        value="{{ old('district', $vendor->district ?? '') }}" readonly required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" id="state" name="state" class="form-control form-control-lg"
                                        value="{{ old('state', $vendor->state ?? '') }}" readonly required>
                                </div>
                            </div>

                            {{-- Village Selector --}}
                            <div class="mt-3">
                                <label class="form-label">Village / Area <span class="text-danger">*</span></label>
                                <select name="village" id="village" class="form-select form-select-lg" required>
                                    <option value="">-- Select Village / Area --</option>
                                </select>
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-lg btn-primary px-5">
                                    Next Step <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- 📍 Auto Pincode Lookup --}}
    <script>
        document.getElementById('pincode').addEventListener('keyup', function () {
            let pincode = this.value.trim();

            if (pincode.length === 6) {
                fetch(`https://api.postalpincode.in/pincode/${pincode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data[0].Status === "Success") {
                            let postOffices = data[0].PostOffice;
                            let first = postOffices[0];

                            document.getElementById('city').value = first.Block || first.Division;
                            document.getElementById('district').value = first.District;
                            document.getElementById('state').value = first.State;

                            // Fill village dropdown
                            let villageSelect = document.getElementById('village');
                            villageSelect.innerHTML = '<option value="">-- Select Village / Area --</option>';

                            postOffices.forEach(po => {
                                let opt = document.createElement('option');
                                opt.value = po.Name;
                                opt.textContent = po.Name;
                                villageSelect.appendChild(opt);
                            });

                            // If only one village, auto select
                            if (postOffices.length === 1) {
                                villageSelect.value = postOffices[0].Name;
                            }

                        } else {
                            clearLocationFields();
                            alert("❌ Invalid Pincode");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        clearLocationFields();
                        alert("⚠️ Could not fetch address details.");
                    });
            }
        });

        function clearLocationFields() {
            document.getElementById('city').value = "";
            document.getElementById('district').value = "";
            document.getElementById('state').value = "";
            document.getElementById('village').innerHTML = '<option value="">-- Select Village / Area --</option>';
        }
    </script>
@endsection