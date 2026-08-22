@extends('BBL_CI.layouts.master')
@push('app-title')
    
@endpush
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
<div class="bg-wrapper">
    <div class="container-fluid custom-layout">
        <div class="row g-2 mb-4">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-2 align-self-start">
                <div class="card type-item-bg">
                    <div class="card-body ps-0 pe-0">
                        <div class="bbl_customer_service text-center">
                            <a href="javascript:void(0)"
                               class="text-decoration-none text-center text-dark fs-5 dashboardBtn" data-url="{{ route('CI.service-type', ['CIToken' => $ci_token, 'request_type' => 'service']) }}">
                                <div class="img-round">
                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/service.png') }}" alt="">
                                </div>
                                <div class="service_txt">Service Request</div>
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
                               class="text-decoration-none text-center text-dark fs-5 dashboardBtn" data-url="{{ route('CI.service-type', ['CIToken' => $ci_token, 'request_type' => 'complaint']) }}">
                                <div class="img-round">
                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/complaint.png') }}" alt="">
                                </div>
                                <div class="service_txt">Complaint</div>
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
                               class="text-decoration-none text-center text-dark fs-5 dashboardBtn" data-url="{{ route('CI.service-type', ['CIToken' => $ci_token, 'request_type' => 'feedback']) }}">
                                <div class="img-round">
                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/feedback.png') }}" alt="">
                                </div>
                                <div class="service_txt">Feedback</div>
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
                               class="text-decoration-none text-center text-dark fs-5 dashboardBtn" data-url="{{ route('BPID.service', ['CIToken' => $ci_token, 'request_type' => 'BPID']) }}">
                                <div class="img-round">
                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/feedback.png') }}" alt="">
                                </div>
                                <div class="service_txt">Treasury Bill / Bond</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection

@push('js')

<script nonce="{{ app('csp_nonce') }}">
    $(document).on('click', '.dashboardBtn', function(){
	let url = $(this).data('url');
	openURL(url);
    });
</script>

@endpush
