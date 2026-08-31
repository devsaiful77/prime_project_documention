@extends('BBL_CI.layouts.master')

@section('content')
<style>
    @media only screen and (max-width: 425px){
        .web-request {
            display: none;
        }
        .mobile-request {
            display: block;
        }
        .main-select{
            /* justify-content: center; */
        }
        .feedback-card-body {
            padding: 0px !important;
        }
    }
    @media only screen and (min-width: 768px) {
        .mobile-request {
            display: none;
        }
        .web-request {
            display: block;
        }
        .feedback-service {
            /* background: #ddd; */
        }
    }
</style>
<div class="container-fluid custom-layout">
        <div class="col-12 ">
            <div style="background: #e4a52c; height: 28px;" class="mb-4 d-flex main-select">
                <div class="form-check form-check pe-3 ms-2">
                    <input class="form-check-input" type="radio" value="service_request" id="serviceRequest" checked>
                    <input class="form-check-input serviceRequest d-none" type="radio" value="service_request" >
                    <label class="form-check-label text-white" for="serviceRequest">
                        <p class="web-request">Service Request</p>
                        <p class="mobile-request">Service</p>
                    </label>
                </div>
                <div class="form-check px-3">
                    <input class="form-check-input" type="radio" value="complaint_request" id="complaintChecked" >
                    <input class="form-check-input d-none complaintChecked" type="radio" checked>
                    <label class="form-check-label  text-white" for="complaintChecked">
                        <p class="web-request">Complaint Request</p>
                        <p class="mobile-request">Complaint</p>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input feedback" type="radio" value="feedback" id="flexCheckChecked" >
                    <input class="form-check-input d-none flexCheckChecked" type="radio" checked>
                    <label class="form-check-label  text-white" for="flexCheckChecked">
                        Feedback
                    </label>
                </div>
            </div>
        </div>
        {{-- <div class="borderer">
        </div> --}}
        <div id="seviceContent">
            <div class="row g-2 mb-4">
                <div class="col-12">
                    <div style="color: #699DC5; padding-bottom: 10px;">
                        <h5 class="service_title"><i class="fas fa-user-cog"></i> Service Request Type :</h5>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 2, 'aysToken' => $ci_token]) }}')">
                                    <div>
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/account.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Account</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service text-center">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 3,'aysToken' => $ci_token]) }}')">
                                       <div>
                                           <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card2.png') }}" alt="">
                                       </div>
                                        <div class="service_txt">Debit Card</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 1,'aysToken' => $ci_token]) }}')">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card2.png') }}"
                                             alt="">
                                        <div class="service_txt">Credit Card</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service">
                                    <a href="{{ route('CI.account-verify', ['product_type' => 4,'aysToken' => $ci_token]) }}"
                                       class="text-decoration-none text-center text-dark fs-5">
                                        <div>
                                            <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/loan.png') }}" alt="">
                                        </div>
                                        <div class="service_txt">Loan</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <div style="padding-top: 30px; padding-bottom: 10px; color: #699DC5">
                        <h5 class="service_title"><i class="fas fa-clipboard-list"></i> Service Ticket History :</h5>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service ">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.send-back-details', ['aysToken' => $ci_token]) }}')">
                                        {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/s_b_r.svg') }}" alt=""> --}}
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/SBR.png') }}"
                                             alt="">
                                       <div class="service_txt">Send Back Request</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service ">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.ticket-status-details', ['aysToken' => $ci_token]) }}')">
                                        {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/t_s.svg') }}" alt=""> --}}
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/status.png') }}"
                                             alt="">
                                        <div class="service_txt">Ticket Status</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="complaintContent" class="d-none">
            <div class="row g-2 mb-4">
                <div class="col-12">
                    <div style="color: #699DC5; padding-bottom: 10px;">
                        <h5 class="service_title"><i class="fas fa-user-cog"></i> Complaint Request Type :</h5>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 2, 'aysToken' => $ci_token]) }}')">
                                    <div>
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/account.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Account</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service text-center">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 3,'aysToken' => $ci_token]) }}')">
                                       <div>
                                           <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card2.png') }}" alt="">
                                       </div>
                                        <div class="service_txt">Debit Card</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 1,'aysToken' => $ci_token]) }}')">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card2.png') }}"
                                             alt="">
                                        <div class="service_txt">Credit Card</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <div style="padding-top: 30px; padding-bottom: 10px; color: #699DC5">
                        <h5 class="service_title"><i class="fas fa-clipboard-list"></i> Complaint Ticket History :</h5>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service ">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.send-back-details', ['aysToken' => $ci_token]) }}')">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/SBR.png') }}"
                                             alt="">
                                       <div class="service_txt">Send Back Request</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service">
                                <div class="bbl_customer_service ">
                                    <a href="#"
                                       class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.ticket-status-details', ['aysToken' => $ci_token]) }}')">
                                        {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/t_s.svg') }}" alt=""> --}}
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/status.png') }}"
                                             alt="">
                                        <div class="service_txt">Ticket Status</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-none" id="feedbackContent">
            <div class="row">
                <div class="">
                    <div class="web-item-wrap">
                        <div id="form_log_wrap">
                            <form id="" action="" method="POST">
                                @csrf
                                <div class="card">
                                    <div class="card-body feedback-card-body">
                                        {{-- <div class=" mt-2">
                                            <div class="col-12">
                                                <div class="d-flex feedback-service">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" value="service_request_feedback" id="serviceRequestFeedback" >
                                                        <label class="form-check-label" for="serviceRequestFeedback">
                                                            Service
                                                        </label>
                                                    </div>
                                                    <div class="form-check ps-5">
                                                        <input class="form-check-input" type="checkbox" value="" id="compChecked" >
                                                        <label class="form-check-label" for="compChecked">
                                                            Complaint
                                                        </label>
                                                    </div>
                                                </div>
                                                <label for="" class="mt-3">Select Service Request</label>
                                                <select class="form-select form-select-sm mt-1 ps-0 select_account mb-4" aria-label=".form-select-sm example">
                                                    <option>Please Select One</option>
                                                    <option>TIN Update</option>
                                                    <option>NID Update</option>
                                                    <option>TIN Update</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="">
                                            <div class="col-12 my-3">
                                                <label for="" class="form-label mb-0 pb-0">Comments</label>
                                                <textarea name=""class="form-control mt-1" cols="40" rows="40" style="height: 70px;"></textarea>
                                            </div>
                                            <div class="mt-3" style="text-align: right;">
                                                <button type="submit" class="btn btn-primary btn-sm form_condition" id="formSubmitBtn">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 col-xxl-10 mt-4">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Success:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('success') }} </p>
                    </div>
                @elseif(session('info'))
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Note:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('info') }} </p>
                    </div>
                @elseif(session('warning'))
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Alert:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('warning') }} </p>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-error" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Alert:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('error') }} </p>
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Note:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> Service request will take minimum 3 working days to resolve.</p>
                        <p class="ps-3 alert-text"><strong>*</strong> Check <strong>“Send Back Request”</strong> tab to check send back
                            tickets from bank.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function() {
                // $('#textbox1').val($(this).is(':checked'));

                $('#complaintChecked').click(function(e) {
                    e.preventDefault();
                        $("#seviceContent").hide();
                        $(this).addClass('d-none');
                        $(".complaintChecked").removeClass('d-none');
                        $(".serviceRequest").removeClass('d-none');
                        $("#serviceRequest").addClass('d-none');
                        $("#complaintContent").removeClass('d-none');
                        $("#feedbackContent").addClass('d-none');
                        $(".flexCheckChecked").addClass('d-none');
                        $("#flexCheckChecked").removeClass('d-none');
                });
                $('#serviceRequest').click(function(e) {
                    e.preventDefault();
                    // $(this).is(':checked')
                    $("#seviceContent").show();
                    $("#complaintContent").addClass('d-none');
                    $("#feedbackContent").addClass('d-none');
                    $(".complaintChecked").addClass('d-none');


                });
                $('.serviceRequest').click(function(e) {
                    e.preventDefault();

                    $(this).addClass('d-none');
                    $("#serviceRequest").removeClass('d-none');
                    $("#seviceContent").show();
                    $("#complaintContent").addClass('d-none');
                    $("#feedbackContent").addClass('d-none');
                    $(".complaintChecked").addClass('d-none');
                    $("#complaintChecked").removeClass('d-none');

                });
                $('#flexCheckChecked').click(function(e) {
                    e.preventDefault();
                    $('#flexCheckChecked').attr( "checked" );
                    $("#complaintChecked").removeClass('d-none');
                    $(".complaintChecked").addClass('d-none');
                    $(".flexCheckChecked").removeClass('d-none');
                    $("#feedbackContent").removeClass('d-none');
                    $("#seviceContent").hide();
                    $("#complaintContent").addClass('d-none');
                    $("#serviceRequest").addClass('d-none');
                    $(".serviceRequest").removeClass('d-none');
                });

                $('.flexCheckChecked').click(function(e) {
                    e.preventDefault();
                    $(this).addClass('d-none');
                    $("#flexCheckChecked").removeClass('d-none');
                    $("#complaintChecked").addClass('d-none');
                    $(".complaintChecked").removeClass('d-none');
                    $("#feedbackContent").removeClass('d-none');
                    $("#seviceContent").hide();
                    $("#complaintContent").addClass('d-none');
                    $("#serviceRequest").addClass('d-none');
                    $(".serviceRequest").removeClass('d-none');
                });
            });
        </script>
@endpush
