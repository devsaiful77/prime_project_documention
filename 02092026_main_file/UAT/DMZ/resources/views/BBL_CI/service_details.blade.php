@extends('BBL_CI.layouts.master')

@section('content')
    <style>
        .card {
            border: 0;
        }

        @media (max-width: 575.98px) {
            .row>* {
                width: 50% !important;
            }

            .bbl_notice {
                display: none;
            }
        }

        .service_txt {
            font-size: 15px;
            font-weight: 500;

        }
    </style>

    <div class="container-fluid mt-5">
        <div class="row g-0">
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 align-self-center p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="bbl_customer_service">
                            <a href="{{ route('customer-interface.account-verify', 2) }}"
                                class="text-decoration-none text-center text-dark fs-5">
                                {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/account.svg') }}" alt=""> --}}
                                <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/account.png') }}"
                                    alt="">
                                <p class="service_txt">Account</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 align-self-center p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="bbl_customer_service">
                            <div class="bbl_customer_service">
                                <a href="{{ route('customer-interface.account-verify', 2) }}"
                                    class="text-decoration-none text-center text-dark fs-5">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/debit_card.svg') }}" alt=""> --}}
                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card.png') }}"
                                        alt="">
                                    <p class="service_txt">Debit Card</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 align-self-center p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="bbl_customer_service">
                            <div class="bbl_customer_service">
                                <a href="{{ route('customer-interface.account-verify', 3) }}"
                                    class="text-decoration-none text-center text-dark fs-5">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/debit_card.svg') }}" alt=""> --}}
                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/card.png') }}"
                                        alt="">
                                    <p class="service_txt">Credit Card</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 align-self-center p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="bbl_customer_service">
                            <div class="bbl_customer_service">
                                <a href="{{ route('customer-interface.account-verify', 4) }}"
                                    class="text-decoration-none text-center text-dark fs-5">
                                    {{-- <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/money.svg') }}" alt=""> --}}
                                    <img class="mx-auto d-block" src="{{ URL::asset('public/BBL_CI/img/loan.png') }}"
                                        alt="">
                                    <p class="service_txt">Loan</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row g-0">
            <div class="d-sm-none d-md-block d-lg-block d-xl-block d-xxl-block align-self-center p-2 bbl_notice">
                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Note:</h6>
                    <p class="ps-3"><strong>*</strong> Service request will take minimum 3 working days to resolve.</p>
                    <p class="ps-3"><strong>*</strong> Check <strong>“Send Back Request”</strong> tab to check send back
                        tickets from bank.</p>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-md-10 col-lg-10 col-xl-10 col-xxl-10 d-sm-none d-md-block d-lg-block d-xl-block d-xxl-block align-self-center p-2 bbl_notice">
                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Note:</h6>
                    <p class="ps-3"><strong>*</strong> Service request will take minimum 3 working days to resolve.</p>
                    <p class="ps-3"><strong>*</strong> Check <strong>“Send Back Request”</strong> tab to check send back
                        tickets from bank.</p>
                </div>
            </div>
        </div>

    </div>
@endsection
