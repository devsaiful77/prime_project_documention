@extends('BBL_CI.layouts.master')
@section('content')
@push('css')

<style>
    .pull-right {
        float: right;
    }

    .input-wrapper label {
        font-size: 14px;
    }

    a,
    a:active {
        color: white;
        text-decoration: none;
    }

    a:hover {
        color: #999;
    }

    .arrow-steps .step {
        font-size: 18px;
        text-align: center;
        color: grey;
        cursor: default;
        margin: 0 2px;
        padding: 4px 60px 25px 40px;
        min-width: 160px;
        float: left;
        position: relative;
        background-color: #ECEBE6;
        transition: background-color 0.2s ease;
        height: 61px;
    }

    .arrow-steps .step:after,
    .arrow-steps .step:before {
        content: " ";
        position: absolute;
        top: 0;
        right: -28px;
        width: 0;
        height: 0px;
        border-top: 30px solid transparent;
        border-bottom: 32px solid transparent;
        border-left: 17px solid #E9B432;
        z-index: 2;
        transition: border-color 0.2s ease;
    }

    .arrow-steps .step:before {
        right: auto;
        left: 0;
        border-left: 28px solid #fff;
        z-index: 0;
    }

    .arrow-steps .step:first-child:before {
        border: none;
    }

    .arrow-steps .step:last-child:after {
        border: none;
    }

    .arrow-steps .step.current {
        color: #fff;
        background-color: #E9B432;
    }

    .arrow-steps .step.current:after {
        border-left: 28px solid #E9B432;
    }

    .p-lable {
        font-size: 15px;
        margin: 0 0 0 0;
        padding: 0 0 0 0;
    }

    .first {
        border-radius: 5px 0 0 5px;
    }

    .last {
        border-radius: 0 5px 5px 0;
    }

    .arrow-steps .step:after {
        content: " ";
        position: absolute;
        top: 0;
        right: -28px;
        width: 0;
        height: 0px;
        border-top: 30px solid transparent;
        border-bottom: 32px solid transparent;
        border-left: 28px solid #ECEBE6;
        z-index: 2;
        transition: border-color 0.2s ease;
    }



    .account__verify__content__nav {
        justify-content: center;
    }
    .__tab__body__content__verification {
        padding-top: 15px;
        padding-bottom: 10px;
        color: #D6EDF5;
    }
    .__tab__body__content__input__item contenedor-input-fecha  disabledinput {
        width: 50px;
        height: 50px;
        border-radius: 3px;
        outline: none;
        padding: 10px;
        border: 2px solid #dddddd;
    }
    .__tab__body__content__input__table__cell {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .__tab__body__content__time p {
        border: 5px solid #cb1111;
        width: 130px;
        height: 130px;
        line-height: 120px;
        text-align: center;
        border-radius: 100%;
        font-size: 35px;
        font-weight: 600;
        margin: 0 auto;
    }
    .__tab__body__content__time {
        padding-top: 30px;
        padding-bottom: 30px;
    }
    .__tab__body__content__btn__group {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .__tab__body__content__btn__cancel button {
        border: none;
        border: 2px solid #dddddd;
        padding: 5px 20px;
        display: inline-block;
        border-radius: 4px;
        background: transparent;
    }
    .__tab__body__content__btn__verify button {
        border: none;
        border: 2px solid #dddddd;
        padding: 5px 20px;
        display: inline-block;
        border-radius: 4px;
        background: transparent;
    }

    .input-fecha {
        width: 50px;
        height: 40px;
        border-radius: 5px;
        text-align:center;
        font-size:20px;
        background: #f1f1f1;
    }

    .border {
        background: #D6EDF5;
        border-radius: 10px;
    }

    .__tab__body__content__btn__cancel button,.__tab__body__content__btn__verify button {
        margin-bottom: 15px;
        border-radius: 20px;
    }

     .__tab__body__content__btn__cancel button:hover,.__tab__body__content__btn__verify button:hover {
         background: #f1f1f1;
    }

    .__tab__body__content__title p{
        font-size:14px;
    }
</style>
@php
    $token = $ci_token;
       try {
           $token = decrypt($token);
           $callbackUrl = \App\CustomerInterfaceToken::where('token', $token)->first('callback_url');
       } catch (Throwable $e) {
           $callbackUrl = '';
       }
@endphp
@endpush

    <div class="d-none d-sm-block container-fluid custom-layout" style="min-height: 100vh;">
        <div class="web-item-wrap p-0">
            <h4 class="service-title d-none d-sm-block mb-3" style="color: #699DC5; font-weight: bold">
                @if($product_type == 2)
                    Account Related Service Request
                @elseif($product_type == 3)
                    Debit Card Related Service Request
                @elseif($product_type == 1)
                    Credit Card Related Service Request
                @elseif($product_type == 4)
                    Loan Related Service Request
                @endif
            </h4>
            <div class="card">
                <div class="card-body">
                    <div class="step_wrap">
                        <ol class="track-progress" id="tracker" data-steps="3">
                            <li class="">
                                <span class="hidden-xs">Step1
                                <small class="hidden-xs">Initiate Transaction</small>
                                </span>
                                <span class="visible-xs">1</span>
                                <i></i>
                            </li>
                            <li class="">
                                <span class="hidden-xs">Step2
                                    <small class="hidden-xs">Verify Transaction</small>
                                </span>
                                <span class="visible-xs">2</span>
                                <i></i>
                            </li>
                            <li class="done">
                                <span class="hidden-xs">Step3
                                    <small class="hidden-xs">Transaction Status</small>
                                </span>
                                <span class="visible-xs">3</span>
                            </li>
                        </ol>
                    </div>
                    <div class="border p-4 mt-5">
                        <div class="text-center">
                            <p class="text-center text-success" style="font-size: 50px; text-align:center;margin: 0";><i class="fa-regular fa-circle-check"></i></p>
                            <p>Success</p>
                            <p>{{ $issue_name }} <br> Successfully Logged. Ticket No: {{ $reference_number }}</p>
                        </div>
                        <div style="display: flex;justify-content: space-around;margin-top: 40px;">
                            <a href="{{ route('CI.service', ['CIToken' => $ci_token]) }}" class="btn btn-sm btn-success">Make Another</a>
                            <a href="{{ route('CI.ticket-status-details', ['CIToken' => $ci_token]) }}" class="btn btn-sm btn-info">Ticket Status</a>
                            <a href="{{ $callbackUrl->callback_url }}" class="btn btn-sm btn-danger">Back To Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('js')
    <script nonce="{{ app('csp_nonce') }}">
         $(document).ready(function(){

            let $inputs = $('.contenedor-fecha-interior input').on('input', e => {
                let $input = $(e.target);
                let index = $inputs.index($input);

                if ($input.val().length >= $input.prop('maxlength')) {
                    $inputs.eq(index + 1).prop('disabled', false).focus();
                }
            });

        });


    </script>
@endpush
@endsection
