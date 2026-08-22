
@extends('BBL_CI.layouts.master')
@section('content')

@push('css')
<style>
    .alert {
        padding: 2px;
        font-size: 16px;
    }
    .input-fecha:focus {
        outline: 1px solid #03427e;
        background: #03427e;
    }
</style>
@endpush

    <div class="container-fluid custom-layout">
        <div class="row">
            <div class="d-none d-sm-block web-item-wrap p-0" style="min-height: 100vh;">
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
                                <li class="done">
                                    <span class="hidden-xs">Step2
                                        <small class="hidden-xs">Verify Transaction</small>
                                    </span>
                                    <span class="visible-xs">2</span>
                                    <i></i>
                                </li>
                                <li class="">
                                    <span class="hidden-xs">Step3
                                        <small class="hidden-xs">Transaction Status</small>
                                    </span>
                                    <span class="visible-xs">3</span>
                                </li>
                            </ol>
                        </div>
                        <div class="__tab__body__content__verification">
                            <b class="showOtp">Verification</b>
                            @if(Session::has('otpMessage'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('otpMessage') }}</p>
                            @endif
                        </div>
                        <div class="border p-4">
                            <form id="verifyOtpForm" action="{{ route('CI.otp-submit') }}" method="post">
                                @csrf
                                <input type="hidden" name="product_type" value="{{$product_type}}">
                                <input type="hidden" name="ci_token" value="{{ $ci_token }}">
                                <input type="hidden" name="reference_number" value="{{$reference_number}}">
                                <input type="hidden" class="otpCode" name="otpCode" value="{{$otpCode}}">
                                <input type="hidden" class="otpGenId" name="otp_auto_id" value="{{@$otpGenId}}" id="otpGenId">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="__tab__body__content">
                                        <div class="__tab__body__content__bg">
                                            <div class="__tab__body__content__title">
                                                <p>Please enter the OTP from your registered mobile number {{ $mobile_number }}</p>
                                            </div>
                                            <div class="__tab__body__content__input__table__cell contenedor-fecha-interior">
                                                <div class="__tab__body__content__input__item contenedor-input-fecha">
                                                    <input class="input-fecha" maxlength="1" name="otp1" required placeholder="" type="text">
                                                </div>
                                                <div class="__tab__body__content__input__item contenedor-input-fecha">
                                                    <input class="input-fecha" maxlength="1" name="otp2" required placeholder="" type="text" disabled>
                                                </div>
                                                <div class="__tab__body__content__input__item contenedor-input-fecha">
                                                    <input class="input-fecha" maxlength="1" name="otp3" required placeholder="" type="text" disabled>
                                                </div>
                                                <div class="__tab__body__content__input__item contenedor-input-fecha">
                                                    <input class="input-fecha" maxlength="1" name="otp4" required placeholder="" type="text" disabled>
                                                </div>
                                                <div class="__tab__body__content__input__item contenedor-input-fecha">
                                                    <input class="input-fecha" maxlength="1" name="otp5" required placeholder="" type="text" disabled>
                                                </div>
                                                <div class="__tab__body__content__input__item contenedor-input-fecha">
                                                    <input class="input-fecha" maxlength="1" name="otp6" required placeholder="" type="text" disabled>
                                                </div>
                                                <div class="__tab__body__content__input__item contenedor-input-fecha">
                                                    <input class="input-fecha" maxlength="1" name="otp7" required placeholder="" type="text" disabled>
                                                </div>
                                            </div>

                                            <div class="otp_counter">
                                                <div id="revese-timer" data-minute="1"></div>
                                            </div>
                                            <div class="__tab__body__content__btn__group">
                                                <div class="__tab__body__content__btn__verify">
                                                     <button type="button" onclick="window.location='{{ route('CI.account-verify', ['product_type' => $product_type, 'CIToken' => $ci_token, 'request_type' => 'service']) }}'" >Cancel</button>
                                                </div>
                                                <div class="__tab__body__content__btn__verify">
                                                    <button type="submit" id="verifyOtp">Verify</button>
                                                </div>
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
