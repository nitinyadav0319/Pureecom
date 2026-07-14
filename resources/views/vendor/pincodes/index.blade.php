@extends('backend.layouts.master')

@section('title')
    {{ localize('Manage Vendor Pincodes') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <div class="container py-4">
        <h3 class="mb-4">Manage Your Service Pincodes</h3>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Add by State/District --}}
        <div class="card mt-4">
            <div class="card-header">Add Pincodes by Region</div>
            <div class="card-body">
                <form action="{{ route('vendor.pincodes.addMultiple') }}" method="POST" id="multiPincodeForm">
                    @csrf

                    {{-- State --}}
                    <div class="mb-3">
                        <label class="form-label">Select State</label>
                        <select name="state" id="state" class="form-control">
                            <option value="">-- Select State --</option>
                            @foreach($states as $state)
                                <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- District --}}
                    <div class="mb-3">
                        <label class="form-label">Select District</label>
                        <select name="district" id="district" class="form-control">
                            <option value="">-- Select District --</option>
                        </select>
                    </div>

                    {{-- Pincode checkboxes --}}
                    <div id="pincode-container">
                        <p class="text-muted">Select a district to view its pincodes...</p>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-success">Add Selected</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Assigned Pincodes --}}
        <div class="card mt-4">
            <div class="card-header">Your Assigned Pincodes</div>
            <div class="card-body">
                @if($assignedPincodes->count() > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Pincode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedPincodes as $assigned)
                                <tr>
                                    <td>{{ $assigned->pincode }}</td>
                                    <td>
                                        <form action="{{ route('vendor.pincodes.destroy', $assigned->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No pincodes assigned yet.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Simple Pure JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // 🔹 Get Districts by State
            document.getElementById('state').addEventListener('change', function () {
                let state = this.value;
                let districtSelect = document.getElementById('district');
                let pincodeContainer = document.getElementById('pincode-container');

                districtSelect.innerHTML = '<option value="">Loading...</option>';
                pincodeContainer.innerHTML = '<p class="text-muted">Select a district to view its pincodes...</p>';

                if (!state) {
                    districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                    return;
                }

                fetch(`/get-districts-by-state?state=${state}`)
                    .then(res => res.json())
                    .then(data => {
                        let options = '<option value="">-- Select District --</option>';
                        data.forEach(d => {
                            options += `<option value="${d}">${d}</option>`;
                        });
                        districtSelect.innerHTML = options;
                    });
            });

            // 🔹 Get Pincodes by District
            document.getElementById('district').addEventListener('change', function () {
                let district = this.value;
                let container = document.getElementById('pincode-container');
                if (!district) {
                    container.innerHTML = '<p class="text-muted">Select a district to view its pincodes...</p>';
                    return;
                }

                container.innerHTML = '<p>Loading pincodes...</p>';

                fetch(`/get-pincodes-by-district?district=${district}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            container.innerHTML = '<p class="text-danger">No pincodes found for this district.</p>';
                            return;
                        }

                        let html = `
                            <label class="form-label fw-bold">Pincodes in ${district}</label>
                            <div class="mb-2">
                                <input type="checkbox" id="selectAll" /> <label for="selectAll">Select All</label>
                            </div>
                            <div class="d-flex flex-wrap gap-3">`;

                        data.forEach(pin => {
                            html += `
                                <div class="form-check">
                                    <input class="form-check-input pincode-check" type="checkbox" name="pincode_ids[]" value="${pin.id}">
                                    <label class="form-check-label">${pin.pincode}</label>
                                </div>`;
                        });

                        html += `</div>`;
                        container.innerHTML = html;

                        // Select All functionality
                        document.getElementById('selectAll').addEventListener('change', function () {
                            document.querySelectorAll('.pincode-check').forEach(chk => chk.checked = this.checked);
                        });
                    });
            });
        });
    </script>
@endsection