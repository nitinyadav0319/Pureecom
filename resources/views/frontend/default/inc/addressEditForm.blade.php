<form action="{{ route('address.update') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $address->id }}">

    <div class="row g-4">

        <div class="col-sm-6">
            <div class="label-input-field">
                <label>{{ localize('Full Name') }}</label>
                <input type="text" name="name" value="{{ $address->name ?? '' }}" required>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="label-input-field">
                <label>{{ localize('Phone Number') }}</label>
                <input type="text" name="phone" value="{{ $address->phone ?? '' }}" required>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="label-input-field">
                <label>{{ localize('Pincode') }}</label>
                <input type="text" id="edit_pincode" name="pincode" value="{{ $address->pincode ?? '' }}" maxlength="6"
                    required>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="label-input-field">
                <label>{{ localize('State') }}</label>
                <input type="text" id="edit_state" name="state_name" value="{{ $address->state_name ?? '' }}" readonly>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="label-input-field">
                <label>{{ localize('District') }}</label>
                <input type="text" id="edit_district" name="district_name" value="{{ $address->district_name ?? '' }}"
                    readonly>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="label-input-field">
                <label>{{ localize('Village / Area') }}</label>
                <select id="edit_village" name="village" class="form-select">
                    <option value="{{ $address->village ?? '' }}">{{ $address->village ?? 'Select Village / Area' }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="label-input-field">
                <label>{{ localize('House / Building No.') }}</label>
                <input type="text" name="house_no" value="{{ $address->house_no ?? '' }}" required>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="label-input-field">
                <label>{{ localize('Landmark (Optional)') }}</label>
                <input type="text" name="landmark" value="{{ $address->landmark ?? '' }}">
            </div>
        </div>

        <div class="col-sm-12">
            <div class="label-input-field">
                <label>{{ localize('Full Address') }}</label>
                <textarea rows="4" name="address" required>{{ $address->address ?? '' }}</textarea>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="w-100 label-input-field">
                <label>{{ localize('Default Address?') }}</label>
                <select class="select2Address" name="is_default">
                    <option value="0" {{ $address->is_default == 0 ? 'selected' : '' }}>{{ localize('No') }}</option>
                    <option value="1" {{ $address->is_default == 1 ? 'selected' : '' }}>{{ localize('Set Default') }}
                    </option>
                </select>
            </div>
        </div>

        <div class="mt-6 d-flex">
            <button type="submit" class="btn btn-secondary btn-md me-3">{{ localize('Update Address') }}</button>
        </div>
    </div>
</form>

<script>
    "use strict";
    $(document).ready(function () {
        $('#edit_pincode').on('keyup', function () {
            let pincode = $(this).val();
            if (pincode.length === 6) {
                $.ajax({
                    url: `https://api.postalpincode.in/pincode/${pincode}`,
                    method: 'GET',
                    success: function (response) {
                        if (response[0].Status === "Success") {
                            let data = response[0].PostOffice[0];
                            let offices = response[0].PostOffice;

                            $('#edit_state').val(data.State);
                            $('#edit_district').val(data.District);

                            let villageSelect = $('#edit_village');
                            villageSelect.empty();
                            villageSelect.append(`<option value="">Select Village / Area</option>`);
                            offices.forEach(function (office) {
                                villageSelect.append(`<option value="${office.Name}">${office.Name}</option>`);
                            });
                        } else {
                            $('#edit_state').val('');
                            $('#edit_district').val('');
                            $('#edit_village').empty().append(`<option value="">No Data Found</option>`);
                            alert('Invalid Pincode or not found in India Post records.');
                        }
                    },
                    error: function () {
                        alert('Unable to fetch data. Please check internet connection.');
                    }
                });
            }
        });
    });
</script>