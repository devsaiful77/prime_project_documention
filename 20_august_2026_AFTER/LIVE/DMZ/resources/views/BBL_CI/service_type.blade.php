@extends('BBL_CI.layouts.master')
@push('app-title')
    Service Request
@endpush
@section('content')
<div class="bg-wrapper service-main-wrap">
    <div class="container-fluid custom-layout">
        <div id="seviceContent" class="">
            <div class="row g-2 mb-4">
                <div class="col-12">
                    <div style="color: #699DC5;">
                        <h5 class="service_title">Service Request Type</h5>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5 serviceType" data-url="{{ route('CI.account-verify', ['product_type' => 2, 'CIToken' => $ci_token, 'request_type' => 'service']) }}">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/account.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Account</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5 serviceType" data-url="{{ route('CI.account-verify', ['product_type' => 3, 'CIToken' => $ci_token, 'request_type' => 'service']) }}">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/debit_card.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Debit Card</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service text-center">
                                <a href="#"
                                   class="text-decoration-none text-center text-dark fs-5 serviceType" data-url="{{ route('CI.account-verify', ['product_type' => 1, 'CIToken' => $ci_token, 'request_type' => 'service']) }}">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/credit_card.png') }}" alt="">
                                    </div>
                                    <div class="service_txt">Credit Card</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5 serviceType" data-url="{{ route('CI.account-verify', ['product_type' => 4,'CIToken' => $ci_token, 'request_type' => 'service']) }}">
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/credit_card.png') }}"
                                             alt="">
                                    </div>
                                    <div class="service_txt">Loan / Investment</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <div class="history-title">
                        <h5 class="service_title">Service Ticket History</h5>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5 serviceTicketHistory" data-url="{{ route('CI.send-back-details', ['CIToken' => $ci_token, 'request_type' => 'service']) }}">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/s_b_r.svg') }}" alt=""> --}}
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/send_back.png') }}"
                                             alt="">
                                    </div>
                                    <div class="service_txt">Send Back Request</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-3 col-lg-2 col-xl-3 col-xxl-2 align-self-center">
                    <div class="card type-item-bg">
                        <div class="card-body ps-0 pe-0">
                            <div class="bbl_customer_service ">
                                <a href="javascript:void(0)"
                                   class="text-decoration-none text-center text-dark fs-5 serviceTicketHistory" data-url="{{ route('CI.ticket-status-details', ['CIToken' => $ci_token, 'request_type' => 'service']) }}">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/t_s.svg') }}" alt=""> --}}
                                    <div class="img-round">
                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/ticket_status.png') }}"
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

	    $(document).on('click', '.serviceType', function(e){
		let url = $(this).data('url');
		openURL(url);
	    });

	    $(document).on('click', '.serviceTicketHistory', function(e){
		let url = $(this).data('url');
		openURL(url);
	    });

        });
    </script>
@endpush
