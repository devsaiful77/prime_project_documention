@extends('BBL_CI.layouts.master')
@push('app-title')
    Banking Services
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
    .service-tabs {
        display: flex;
        width: 100%;
        gap: 0.5rem;
        padding: 4px;
        background: #f0f4fb;
        border-radius: 14px;
        box-shadow: inset 0 0 0 1px rgba(82, 123, 188, 0.18);
    }

    .service-tabs .nav-item {
        flex: 1 1 0;
        width: 50%;
    }

    .service-tabs .nav-link {
        border-radius: 12px;
        font-weight: 700;
        padding: 10px 20px;
        color: #527BBC;
        width: 100%;
        background: transparent;
        border: 1px solid transparent;
        transition: all 0.25s ease;
    }

    .service-tabs .nav-link:hover {
        background: #e4edfb;
        color: #23406d;
    }

    .service-tabs .nav-link.active,
    .service-tabs .nav-link:focus,
    .service-tabs .nav-link:focus-visible,
    .service-tabs .nav-link.show {
        background: linear-gradient(135deg, #527BBC, #426aa6);
        color: #fff;
        box-shadow: 0 6px 14px rgba(82, 123, 188, 0.3);
    }

    .service-tabs .nav-link.active i,
    .service-tabs .nav-link:focus i,
    .service-tabs .nav-link:focus-visible i,
    .service-tabs .nav-link.show i {
        color: #fff;
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
        <!-- BPID & Auction -->
        <div class="row g-2 mb-4">
            <div class="col-12">

                <!-- Tabs -->
                <ul class="nav service-tabs mb-3 w-100" id="ticket-history-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link service-tab-link {{ $request_type == 'BPID' ? 'active' : '' }}"
                                id="service-ticket-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#service-ticket-history"
                                type="button"
                                role="tab">
                            BPID
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link service-tab-link {{ $request_type == 'Auction' ? 'active' : '' }}"
                                id="complaint-ticket-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#complaint-ticket-history"
                                type="button"
                                role="tab">
                            Auction
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="ticket-history-tabContent">

                    <!-- BPID Ticket -->
                    <div class="tab-pane fade {{ $request_type == 'BPID' ? 'show active' : '' }}"
                        id="service-ticket-history"
                        role="tabpanel">

                        <div class="row g-2">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 align-self-start">
                                <div class="card type-item-bg">
                                    <div class="card-body ps-0 pe-0">

                                        @if($sameIssueRequestFound)
                                            <div class="bbl_customer_service text-center">
                                                <a href="#"
                                                class="text-decoration-none text-center text-dark fs-5">
                                                    <div class="img-round">
                                                        <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/bpid.png') }}" alt="">
                                                    </div>
                                                    <div class="service_txt">You have a Already Submitted BPID Account.</div>
                                                </a>
                                            </div>
                                        @else
                                        <div class="bbl_customer_service text-center">
                                            <a href="#"
                                            class="text-decoration-none text-center text-dark fs-5 dashboardBtn" data-url="{{ route('BPID.service-type', ['CIToken' => $ci_token, 'request_type' => 'BPID']) }}">
                                                <div class="img-round">
                                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/bpid.png') }}" alt="">
                                                </div>
                                                <div class="service_txt">BPID Account Opening</div>
                                            </a>
                                        </div>
                                        @endif


                                    </div>
                                </div>
                            </div>


                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="history-title">
                                        <h5 class="service_title">BPID Ticket History</h5>
                                    </div>
                                </div>
                                <!-- Send Back Request -->
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="card type-item-bg">
                                        <div class="card-body ps-0 pe-0">
                                            <div class="bbl_customer_service">
                                                <a href="#"
                                                class="text-decoration-none text-center text-dark fs-5 serviceTicketHistory dashboardBtn"
                                                data-url="{{ route('BPID.send-back-details', ['CIToken' => $ci_token, 'request_type' => getId('BPID')]) }}">
    
                                                    <div class="img-round">
                                                        <img class="mx-auto d-block"
                                                            src="{{ URL::asset('public/BBL_CI/ui/send_back.png') }}"
                                                            alt="">
                                                    </div>
    
                                                    <div class="service_txt">
                                                        Send Back Request
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Ticket Status -->
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="card type-item-bg">
                                        <div class="card-body ps-0 pe-0">
                                            <div class="bbl_customer_service">
                                                <a href="#"
                                                class="text-decoration-none text-center text-dark fs-5 serviceTicketHistory dashboardBtn"
                                                data-url="{{ route('BPID.ticket-status-details', ['CIToken' => $ci_token, 'request_type' => getId('BPID')]) }}">
    
                                                    <div class="img-round">
                                                        <img class="mx-auto d-block"
                                                            src="{{ URL::asset('public/BBL_CI/ui/ticket_status.png') }}"
                                                            alt="">
                                                    </div>
    
                                                    <div class="service_txt">
                                                        Ticket Status
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>


                        </div>
                    </div>

                    <!-- Auction Ticket -->
                    <div class="tab-pane fade {{ $request_type == 'Auction' ? 'show active' : '' }}"
                        id="complaint-ticket-history"
                        role="tabpanel">

                        <div class="row g-2">

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 align-self-start">
                                <div class="card type-item-bg">
                                    <div class="card-body ps-0 pe-0">
                                        <div class="bbl_customer_service text-center">
                                            <a href="#"
                                            class="text-decoration-none text-center text-dark fs-5 dashboardBtn" data-url="{{ route('BPID.service-type', ['CIToken' => $ci_token, 'request_type' => 'Auction']) }}">
                                                <div class="img-round">
                                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/ui/auction.png') }}" alt="">
                                                </div>
                                                <div class="service_txt">Auction Request</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="history-title">
                                        <h5 class="service_title">Auction Ticket History</h5>
                                    </div>
                                </div>
                                <!-- Auction Send Back -->
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="card type-item-bg">
                                        <div class="card-body ps-0 pe-0">
                                            <div class="bbl_customer_service">
                                                <a href="#"
                                                class="text-decoration-none text-center text-dark fs-5 complaintTicketHistory dashboardBtn"
                                                data-url="{{ route('BPID.send-back-details', ['CIToken' => $ci_token, 'request_type' => getId('AUCTION_REQUEST')]) }}">
    
                                                    <div class="img-round">
                                                        <img class="mx-auto d-block"
                                                            src="{{ URL::asset('public/BBL_CI/ui/send_back.png') }}"
                                                            alt="">
                                                    </div>
    
                                                    <div class="service_txt">
                                                        Send Back Request
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Complaint Status -->
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="card type-item-bg">
                                        <div class="card-body ps-0 pe-0">
                                            <div class="bbl_customer_service">
                                                <a href="#"
                                                class="text-decoration-none text-center text-dark fs-5 complaintTicketHistory dashboardBtn"
                                                data-url="{{ route('BPID.ticket-status-details', ['CIToken' => $ci_token, 'request_type' => getId('AUCTION_REQUEST')]) }}">
    
                                                    <div class="img-round">
                                                        <img class="mx-auto d-block"
                                                            src="{{ URL::asset('public/BBL_CI/ui/ticket_status.png') }}"
                                                            alt="">
                                                    </div>
    
                                                    <div class="service_txt">
                                                        Ticket Status
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
        $(document).on('click', '.dashboardBtn', function (e) {
            let url = $(this).data('url');
            openURL(url);
        });
    </script>
@endpush
