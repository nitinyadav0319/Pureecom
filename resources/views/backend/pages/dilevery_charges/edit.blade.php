@extends('backend.layouts.master')

@section('title')
    {{ localize('Update Brand') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto flex-grow-1">
                                    <div class="tt-page-title">
                                        <h2 class="h5 mb-0">Update Dilevery Charge <sup
                                                class="badge bg-soft-warning px-2"></sup></h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">

                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.dilevery-charges.update') }}" method="POST" class="pb-650">
                        @csrf
                        <input type="hidden" name="id" value="{{ $dilevery_chargeData->id }}">
                        <input type="hidden" name="lang_key" value="">
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">

                                <div class="mb-4">
                                    <label for="name" class="form-label">Amount</label>
                                    <input type="text" name="amount" id="name"
                                        placeholder="" class="form-control" required
                                        value="{{$dilevery_chargeData->amount}}">
                                </div>


                            </div>
                        </div>
                        <!--basic information end-->



                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- submit button end -->

                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        "use strict";

        // runs when the document is ready --> for media files
        $(document).ready(function() {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });
    </script>
@endsection
