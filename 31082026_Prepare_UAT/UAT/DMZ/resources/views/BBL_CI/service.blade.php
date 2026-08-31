@extends('BBL_CI.layouts.master')

@section('content')
<style>
    .text-white {
        user-select: none;
        cursor: pointer;
    }
    @media only screen and (max-width: 425px){
        .text-white {
            font-size: 11px;
        }
    }
    .body-bg {
        background: #ffffff;
        background-image: none;
    }
    .logo-white{
        display: none;
    }
    .logo-primary{
        display: block;
    }
    .header-copyright-area{
        background: #ffffff;
    }
    .service_title {
        color: #fff;
        background: none;
        padding: 10px 10px;
        border-left: 0;
        font-size: 16px;
        font-weight: 600;
        border-bottom: 1px solid #ffffff;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    @media only screen and (max-width: 580px){
        .bg-wrapper .type-item-bg {
            border: 0;
            background: none;
        }
        .custom-layout {
            padding-top: 40px;
        }
    }
</style>
<div class="bg-wrapper" style="background-image: url(' {{asset('public/BBL_CI/img/image_bg_icon.png')}}')">
    <div class="container-fluid custom-layout">
        <div class="col-12 ">
            <div style="" class="mb-4 d-flex main-select">
                <div class="form-check form-check pe-3">
                    <input class="form-check-input" type="radio" value="service" name="request_type" id="serviceRequest" checked>
                    <label class="form-check-label text-white" for="serviceRequest">
                        <p>Service Request</p>
                    </label>
                </div>
                <div class="form-check px-3">
                    <input class="form-check-input" type="radio" value="complaint" name="request_type" id="complaintRequest" >
                    <label class="form-check-label text-white" for="complaintRequest">
                        <p>Complaint</p>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input feedback" type="radio" value="feedback" name="request_type" id="feedbackRequest" >
                    <label class="form-check-label  text-white" for="feedbackRequest">
                        Feedback
                    </label>
                </div>
            </div>
        </div>

        <div id="seviceContent" class="">
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
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 2, 'CIToken' => $ci_token, 'request_type' => 'service']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/account_white.png') }}" alt="">
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
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 3, 'CIToken' => $ci_token, 'request_type' => 'service']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card_white.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Debit Card</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 1, 'CIToken' => $ci_token, 'request_type' => 'service']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card_white.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Credit Card</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 4,'CIToken' => $ci_token, 'request_type' => 'service']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card_white.png') }}"
                                             alt="">
                                    </div>
                                    <div class="service_txt">Loan</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.send-back-details', ['CIToken' => $ci_token]) }}')">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/s_b_r.svg') }}" alt=""> --}}
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/SBR_white.png') }}"
                                             alt="">
                                    </div>
                                    <div class="service_txt">Send Back Request</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.ticket-status-details', ['CIToken' => $ci_token]) }}')">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/t_s.svg') }}" alt=""> --}}
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/status_white.png') }}"
                                             alt="">
                                    </div>
                                    <div class="service_txt">Ticket Status</div>
                                </a>
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
                        <h5 class="service_title"><i class="fas fa-user-cog"></i> Complaint Type :</h5>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="javascript:void(0)" class="text-decoration-none text-center text-dark fs-5"  onclick="openURL('{{ route('CI.account-verify', ['product_type' => 2, 'CIToken' => $ci_token, 'request_type' => 'complaint']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/account_white.png') }}" alt="">
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
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 1, 'CIToken' => $ci_token, 'request_type' => 'complaint']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card_white.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Credit Card</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.account-verify', ['product_type' => 3, 'CIToken' => $ci_token, 'request_type' => 'complaint']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card_white.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Debit Card</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="javascript:void(0)" class="text-decoration-none text-center text-dark fs-5"  onclick="openURL('{{ route('CI.account-verify', ['product_type' => 4, 'CIToken' => $ci_token, 'request_type' => 'complaint']) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card_white.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Loan</div>
                                </a>
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
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)" class="text-decoration-none text-center text-dark fs-5"  onclick="openURL('{{ route('CI.complaint-send-back-status', ['CIToken' => $ci_token]) }}')">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/SBR_white.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Send Back Complaint</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)" class="text-decoration-none text-center text-dark fs-5" onclick="openURL('{{ route('CI.comaplaint-ticket-status', ['CIToken' => $ci_token]) }}')">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/t_s.svg') }}" alt=""> --}}
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/status_white.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Ticket Status</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="feedbackContent" class="d-none">
            <div class="row">
                <div class="web-item-wrap">
                    @if(session('success_feedback'))
                        <div class="mb-2 p-0 font-weight-bold text-center alert alert-success" style="font-size: 14px;">
                            <strong>*</strong> {{ session('success_feedback') }}
                        </div>
                    @endif
                    <div id="form_log_wrap">
                        <form id="" action="{{ route('CI.submit_feedback') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ci_token" value="{{ $ci_token }}">
                            <div class="card">
                                <div class="card-body feedback-card-body">
                                    <div class="">
                                        <div class="col-12">
                                            <label for="" class="form-label mb-0 pb-0">Please share your thoughts</label>
                                            <textarea name="comments" class="form-control mt-1" cols="40" rows="40" style="height: 70px;" required></textarea>
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

        <div class="row note-wrap" id="noteArea">
            <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8 col-xxl-8 mt-4">
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
                        <p class="ps-3 alert-text"><strong>*</strong> Service request/Complaint will take minimum 3 working days to resolve.</p>
                        <p class="ps-3 alert-text"><strong>*</strong> Please check <strong>“Send Back Request” or, “Send Back Complaint"</strong> tab to check send back tickets from bank.</p>
                        <p class="ps-3 alert-text"><strong>*</strong> Please check <strong>“Ticket Status”</strong> tab to check your Ticket Status.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script nonce="{{ app('csp_nonce') }}">
        $(document).ready(function(){
            var getSession = sessionStorage.getItem('activeMenu');
            // var url =  document.referrer;
            // var parts = url.split("/");
            // var lastPart = parts[parts.length - 1];
            if (getSession == 'complaint'){
                $('input[name="request_type"][value="complaint"]').prop('checked', true);
                $('#seviceContent').addClass('d-none');
                $('#complaintContent').removeClass('d-none');
                $('#feedbackContent').addClass('d-none');
                $('#noteArea').removeClass('d-none');
            } else if(getSession == 'feedback'){
                $('input[name="request_type"][value="feedback"]').prop('checked', true);
                $('#seviceContent').addClass('d-none');
                $('#complaintContent').addClass('d-none');
                $('#feedbackContent').removeClass('d-none');
                $('#noteArea').addClass('d-none');
            } else if(getSession == 'service') {
                $('input[name="request_type"][value="service"]').prop('checked', true);
                $('#seviceContent').removeClass('d-none');
                $('#complaintContent').addClass('d-none');
                $('#feedbackContent').addClass('d-none');
                $('#noteArea').removeClass('d-none');
            }
            $('input[name="request_type"]').change(function(){
                $('#loading').removeClass('loader-none');
                sessionStorage.removeItem('activeMenu');
                let selectedValue = $('input[name="request_type"]:checked').val();

                if (selectedValue == 'service'){
                    sessionStorage.setItem('activeMenu', 'service');
                    $('#seviceContent').removeClass('d-none');
                    $('#complaintContent').addClass('d-none');
                    $('#feedbackContent').addClass('d-none');
                    $('#noteArea').removeClass('d-none');
                    setTimeout(function () {
                        $('#loading').addClass('loader-none');
                    }, 300);
                }else if (selectedValue == 'complaint'){
                    sessionStorage.setItem('activeMenu', 'complaint');
                    $('#seviceContent').addClass('d-none');
                    $('#complaintContent').removeClass('d-none');
                    $('#feedbackContent').addClass('d-none');
                    $('#noteArea').removeClass('d-none');
                    setTimeout(function () {
                        $('#loading').addClass('loader-none');
                    }, 300);
                }else {
                    sessionStorage.setItem('activeMenu', 'feedback');
                    $('#seviceContent').addClass('d-none');
                    $('#complaintContent').addClass('d-none');
                    $('#feedbackContent').removeClass('d-none');
                    $('#noteArea').addClass('d-none');
                    setTimeout(function () {
                        $('#loading').addClass('loader-none');
                    }, 300);
                }
            });
            setTimeout(function () {
                sessionStorage.removeItem('activeMenu');
            }, 2000000);
        });
    </script>
@endpush
