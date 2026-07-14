<?php 
use Illuminate\Support\Facades\DB;
$countries = DB::table('countries')->get();



?>

<div class="modal fade addAddressModal" id="addAddressModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>

                <div class="gstore-product-quick-view bg-white rounded-3 py-6 px-4">
                    <h2 class="modal-title fs-5 mb-3">{{ localize('Add New Address') }}</h2>
                    <div class="row align-items-center g-4 mt-3">
                        <form action="{{ route('address.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Full Name') }}</label>
                                        <input type="text" name="name" placeholder="Enter your full name" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Phone Number') }}</label>
                                        <input type="text" name="phone" placeholder="Enter your phone number" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Pincode') }}</label>
                                        <input type="text" id="pincode" name="pincode"
                                            placeholder="Enter 6-digit pincode" maxlength="6" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('State') }}</label>
                                        <input type="text" id="state" name="state_name" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('District') }}</label>
                                        <input type="text" id="district" name="district_name" readonly>
                                    </div>
                                </div>

                                <!-- <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Taluka / Sub-District') }}</label>
                                        <input type="text" id="taluka" name="taluka_name"
                                            placeholder="Enter Taluka name">
                                    </div>
                                </div> -->

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Village / Area') }}</label>
                                        <select id="village" name="village" class="form-select">
                                            <option value="">{{ localize('Select Village / Area') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('House / Building No.') }}</label>
                                        <input type="text" name="house_no" placeholder="Ex: H.No. 45, Green Villa"
                                            required>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('Landmark (Optional)') }}</label>
                                        <input type="text" name="landmark" placeholder="Nearby school, temple, etc.">
                                    </div>
                                </div>




                                <div class="col-sm-6">
                                    <div class="w-100 label-input-field">
                                        <label>{{ localize('Default Address?') }}</label>
                                        <select class="select2Address" name="is_default">
                                            <option value="0">{{ localize('No') }}</option>
                                            <option value="1">{{ localize('Set Default') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('Address') }}</label>
                                        <textarea rows="4" placeholder="{{ localize('2/5 Elephant Road, New Town') }}"
                                            name="address" required></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-6 d-flex">
                                <button type="submit"
                                    class="btn btn-secondary btn-md me-3">{{ localize('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade editAddressModal" id="editAddressModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="gstore-product-quick-view bg-white rounded-3 py-6 px-4">
                    <h2 class="modal-title fs-5 mb-3">{{ localize('Update Address') }}</h2>

                    <div class="spinner pt-6 pb-8 d-none">
                        <div class="row align-items-center g-4 mt-3">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="edit-address d-none">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade deleteAddressModal" id="deleteAddressModal">
    <div class="modal-dialog address-delete-modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="bg-white rounded-3 py-6 px-4">
                    <h2 class="modal-title fs-5 mb-3">{{ localize('Delete Address') }}</h2>
                    <div class="pt-6 pb-8 text-center">
                        <h6>{{ localize('Want to delete this address?') }}</h6>
                    </div>
                    <div class="text-center">
                        <a href="" class="btn btn-secondary delete-address-link">{{ localize('Delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    <script>
        "use strict";

        var parent = '.addAddressModal';



        $(document).ready(function () {
            $('#pincode').on('keyup', function () {
                let pincode = $(this).val();

                if (pincode.length === 6) {
                    // API call to India Post
                    $.ajax({
                        url: `https://api.postalpincode.in/pincode/${pincode}`,
                        method: 'GET',
                        success: function (response) {
                            if (response[0].Status === "Success") {
                                let data = response[0].PostOffice[0];
                                let offices = response[0].PostOffice;

                                // Auto-fill State and District
                                $('#state').val(data.State);
                                $('#district').val(data.District);

                                // Populate Village dropdown
                                let villageSelect = $('#village');
                                villageSelect.empty();
                                villageSelect.append(`<option value="">Select Village / Area</option>`);
                                offices.forEach(function (office) {
                                    villageSelect.append(`<option value="${office.Name}">${office.Name}</option>`);
                                });
                            } else {
                                $('#state').val('');
                                $('#district').val('');
                                $('#village').empty().append(`<option value="">No Data Found</option>`);
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



        // runs when the document is ready --> for media files
        $(document).ready(function () {
            if ($("input[name='shipping_address_id']").is(':checked')) {
                let city_id = $("input[name='shipping_address_id']:checked").data('city_id');
                getLogistics(city_id);
            }
        });


        //  new address
        function addNewAddress() {
            $('#addAddressModal').modal('show');
            parent = '.addAddressModal';
            addressModalSelect2(parent);
        }

        // 🧠 Edit Address Function
        function editAddress(id) {
            if (!id) {
                alert('Invalid Address ID');
                return;
            }

            // Show modal + loader
            $('#editAddressModal').modal('show');
            $('#editAddressModal .spinner').removeClass('d-none');
            $('#editAddressModal .edit-address').addClass('d-none');

            // AJAX call to fetch edit form
            $.ajax({
                url: `/address/${id}/edit`, // 👈 make sure route bana hua hai
                type: 'GET',
                success: function (response) {
                    // Hide loader, show form
                    $('#editAddressModal .spinner').addClass('d-none');
                    $('#editAddressModal .edit-address').removeClass('d-none').html(response);
                },
                error: function () {
                    $('#editAddressModal .spinner').addClass('d-none');
                    alert('Failed to load address data. Please try again.');
                }
            });
        }
    </script>
@endsection