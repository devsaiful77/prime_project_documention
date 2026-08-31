
@push('css')
    <link rel="stylesheet" href="{{ URL::asset('public/BBL_CI/css/account_from.css') }}">
    <style>
        input[type="file"] {
            border: 1px solid #ced4da;
            width: 100%;
            vertical-align: middle;
            margin-bottom: 10px;
            direction: rtl;
        }
        input[type="file"]::-webkit-file-upload-button {
            background: #fff;
            color: #555;
            line-height: 38px;
            padding: 0px 20px;
            border: none;
            float: right;
            border: 2px solid #333;
        }
        .sw-theme-arrows .sw-toolbar {
            display: none !important;
        }
        .card {
            background-color: #D6EDF5 !important;
        }
        .otp_input_lg {
            width: 54px;
            margin: 5px;
            border-radius: 6px;
            padding: 10px 5px;
            text-align: center;
            border: #fff;
            height: 50px;
        }
    </style>
@endpush

<div class="container mt-5">
    <div class="row g-0">
        <div class="d-sm-block d-md-none d-lg-none d-xl-none d-xxl-none align-self-center p-2">
            <form>
                <select class="form-select form-select-sm mt-3 mb-4 ps-0 select_account" aria-label=".form-select-sm example">
                    <option selected>1501252345562455</option>
                </select>
                <select class="form-select form-select-sm mt-4 ps-0 select_account" aria-label=".form-select-sm example">
                    <option selected>TIN Update</option>
                </select>
                <div class="mt-4 mb-3">
                    <label for="" class="form-label mb-0 pb-0">TIN Number</label>
                    <input type="text" class="form-control frm_input py-0" name="">
                </div>
                <div class="mt-4 mb-3">
                    <label for="" class="form-label mb-0 pb-0">Place of Issue</label>
                    <input type="text" class="form-control frm_input py-0" name="">
                </div>
                <div class="mt-4 mb-3">
                    <label for="" class="form-label mb-0 pb-0">Issue Date</label>
                    <input type="text" class="form-control frm_input py-0" name="">
                </div>
                <div class="mt-4 mb-3 e_tin_b_b">
                        <label for="" class="form-label mb-0 pb-0 mb-1">E-TIN Copy</label>
                        <input type="file" class="form-control py-0 e_tin_copy" name="">
                </div>
                <div class="mt-4 mb-3 e_tin_b_b">
                    <label for="" class="form-label mb-0 pb-0 mb-1">Tax Return Slip</label>
                    <input type="file" class="form-control py-0 e_tin_copy" name="">
                </div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary btn-sm mt-4 submit_btn" type="button" data-bs-toggle="modal" data-bs-target="#verifyModal">SUBMIT</button>
                </div>
            </form>
        </div>
        <div class="d-sm-none d-md-block d-lg-block d-xl-block d-xxl-block  align-self-center p-2 web_a_verify">
            <!--   smart wizard form start-->
            <div class="row">
                <div class="col-12 ps-0">
                    <h4 class="a_v_heading">Account Related Service Request</h4>
                </div>
            </div>
            <div class="row d-flex mt-200">
                <div class="smartwizard">
                    <ul>
                        <li style="width: 33.33%;"><a href="#step-1" class="text-center">Step 1<br /><small>Initiate Service Request</small></a></li>
                        <li style="width: 33.33%;"><a href="#step-2" class="text-center">Step 2<br /><small>OTP Validation</small></a></li>
                        <li style="width: 33.33%;"><a href="#step-3" class="text-center">Step 3<br /><small>Confirmation Status</small></a></li>
                    </ul>
                    <div class="mt-4">
                        <div id="step-1">
                            <div class="row frm_field_bg mx-5 my-3">
                                <div class="col-12 my-3">
                                    <label for="">Select Account</label>
                                    <select class="form-select form-select-sm mt-3 mb-5 ps-0 select_account" aria-label=".form-select-sm example">
                                        <option selected>1501252345562455</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row frm_field_bg mx-5 my-3">
                                <div class="col-12 my-3">
                                    <label for="">Select Service Request</label>
                                    <select class="form-select form-select-sm mt-3 mb-5 ps-0 select_account" aria-label=".form-select-sm example">
                                        <option selected>TIN Update</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row frm_field_bg mx-5 my-3">
                                <div class="col-12 my-3">
                                    <label for="" class="form-label mb-0 pb-0">TIN Number</label>
                                    <input type="text" class="form-control frm_input py-1" name="">
                                </div>
                            </div>
                            <div class="row frm_field_bg mx-5 my-3">
                                <div class="col-12 my-3">
                                    <label for="" class="form-label mb-0 pb-0">Place of Issue</label>
                                    <input type="text" class="form-control frm_input py-1" name="">
                                </div>
                            </div>
                            <div class="row frm_field_bg mx-5 my-3">
                                <div class="col-12 my-3">
                                    <label for="" class="form-label mb-0 pb-0">Issue Date</label>
                                    <input type="text" class="form-control frm_input py-1" name="">
                                </div>
                            </div>
                            <div class="row frm_field_bg mx-5 my-3">
                                <div class="col-12 my-3">
                                    <label for="" class="form-label mb-0 pb-0 mb-1">E-TIN Copy</label>
                                    <input type="file" class="form-control py-0 e_tin_copy" name="">
                                </div>
                            </div>
                            <div class="row frm_field_bg mx-5 my-3">
                                <div class="col-12 my-3">
                                    <label for="" class="form-label mb-0 pb-0 mb-1">Tax Return Slip</label>
                                    <input type="file" class="form-control py-1 e_tin_copy" name="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 pt-3 text-end me-5">
                                    <button class="btn btn-primary btn-sm mb-5 submit_btn px-4 me-5 sw-btn-next" type="button">SUBMIT</button>
                                </div>
                            </div>
                        </div>
                        {{-- <div id="step-2">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="ms-5 pb-2 a_v_heading">Verification</h4>
                                    <div class="card mx-5 mb-5">
                                        <div class="card-body text-center">
                                            <p class="a_v_heading">Please enter the OTP from your registered mobile number 018*****456</p>
                                            <form>
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-center">
                                                            <input type="text" class="otp_input_lg" name="" >
                                                            <input type="text" class="otp_input_lg" name="" >
                                                            <input type="text" class="otp_input_lg" name="" >
                                                            <input type="text" class="otp_input_lg" name="" >
                                                            <input type="text" class="otp_input_lg" name="" >
                                                            <input type="text" class="otp_input_lg" name="" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-flex justify-content-center">
                                                        <button class="btn btn-primary btn-sm mt-5 submit_btn verify_btn verify_btn_cancel float-start me-5" type="button">CANCEL</button>
                                                        <button class="btn btn-primary btn-sm mt-5 submit_btn verify_btn float-end bg-white text-dark ms-5" type="button">VERIFY</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-3" class="">
                            <div class="row">
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <!--   smart wizard form end-->
        </div>
    </div>
</div>


<!-- Verification Modal start-->
<div class="modal fade web_a_verify" id="verifyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please enter the OTP from your registered mobile number 018*****456</p>
                <form>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <input type="text" class="otp_input" name="" style="background: #E5A81B;">
                                <input type="text" class="otp_input" name="" >
                                <input type="text" class="otp_input" name="" >
                                <input type="text" class="otp_input" name="" >
                                <input type="text" class="otp_input" name="" >
                                <input type="text" class="otp_input" name="" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mt-5 submit_btn verify_btn verify_btn_cancel float-start me-2" type="button">CANCEL</button>
                            <button class="btn btn-primary btn-sm mt-5 submit_btn verify_btn float-end bg-white text-dark ms-2" type="button">VERIFY</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Verification Modal end-->

@push('js')
<script nonce="{{ app('csp_nonce') }}">
    $(document).ready(function(){
        $('.smartwizard').smartWizard({
            selected: 0,
            theme: 'arrows',
            autoAdjustHeight:true,
            transitionEffect:'fade',
            showStepURLhash: false,
        });
    });
</script>
@endpush

