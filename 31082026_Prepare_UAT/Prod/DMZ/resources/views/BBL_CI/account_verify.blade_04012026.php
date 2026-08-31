@extends('BBL_CI.layouts.master')
@section('content')
@push('css')
<style>
    .input-selector:focus {
        outline: 1px solid #0e47a2;
        background: #0e47a2;
        color: #FFFFFF;
    }
</style>
@endpush
    @php
        $type = '';
        if ($product_type == 4) {
            $type = 'Loan Account';
        } elseif ($product_type == 3) {
            $type = 'Debit Card';
        } elseif ($product_type == 1) {
            $type = 'Credit Card';
        } else {
            $type = 'Account';
        }
    @endphp
    @push('app-title')
        {{ $type }}
    @endpush
<div class="bg-wrapper service-main-wrap">
    <!-- APP -->
    <div class="d-block d-sm-none align-self-center">
        @if($request_type == 'service')
            <form id="cifFormApp" action="{{ url('/CI/newWForm') }}" method="POST">
                @else
                    <form id="cifFormApp" action="{{ url('/CI/newComplaint') }}" method="POST">
                        @endif
                        @csrf
                        <input type="hidden" name="product_type" value="{{ $product_type }}" />
                        <input type="hidden" name="ci_token" value="{{ $ci_token }}">
                        <input type="hidden" name="api_response" value="{{ $api_response }}"/>
                        <input type="hidden" name="request_type" value="{{ $request_type }}"/>
                        <input type="hidden" name="otp_mode" value="1"/>
                        <div class="container-fluid custom-layout app_view">
                            <div class="dropdown-wrapper mb-4">
                                <label class="mb-1">Select {{ $type }}<span class="required">*</span></label>
                                <select class="dropdown-select single_select2 single_select2_focus" name="account_number">
                                    @if(count($accountNumbers) != 1)
                                        <option value="">Select {{ $type }}</option>
                                    @endif
                                    @foreach($accountNumbers as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="account_number_err error-message"></div>
                            </div>
                            <div class="dropdown-wrapper mb-4">
                                <label class="mb-1 d-block text-capitalize">Select {{ $request_type ?? '' }} Request<span class="required">*</span></label>
                                <select class="dropdown-select single_select2 single_select2_focus" name="w_form_type" id="request_type_mobile">
                                    <option value="">Select Service Request</option>
                                    @foreach($unit_items as $key => $item)
                                        @php
                                            $pBServiceCat = old('w_form_type');
                                            $selectedServiceCat = "";
                                            if($pBServiceCat == $item['id']) {
                                                $selectedServiceCat = "selected";
                                            }
                                        @endphp

                                        <option value="{{ $item['id'] }}" {{ $selectedServiceCat }}>{{$item['name']}}</option>
                                    @endforeach
                                </select>
                                <div class="w_form_type_err error-message"></div>
                            </div>
                            <input type="hidden" name="request_mode" id="request_mode" value="app" />
                            <div id="issue_extra_mobile"> </div>
                            <div id="issue_attachment_item_mobile">
                                @php
                                    $pbServType="";
                                    if(!empty(old('w_form_type'))) {
                                    $service_request = $pbServType = old('w_form_type');
                                        $attachment_item = App\IssueAttachmentConfig::where('issue_id', $service_request)->orderBy('order_by', "ASC")->get();
                                    }
                                @endphp
                                @include('BBL_CI.partials.CIissue_attachment_item_app')
                            </div>
                            <div class="mt-4 otp_mode_wrap d-none">
                                <h6 style="font-size: 14px" class="mb-3">Select Authorization Mode</h6>
                                <div class="otp_mode pr-2 active" id="otp_sms">SMS</div>
                                {{--@if($isEmailOtp == 1)
                                    <div class="otp_mode" id="otp_email">Email</div>
                                @endif--}}
                            </div>

                            {{--<div class="form-check pt-4 otp_mode_wrap d-none">
                                <input class="form-check-input pl-3 ml-3 checkme" type="checkbox" name="" value="1" data-toggle="tooltip" data-placement="buttom" title="Please Check">
                                <label class="form-check-label" for="checkme">I accept the <a href="#" class="text-decoration-underline termConditionModalBtn">Terms and Conditions</a></label>
                            </div>--}}

                        </div>
                        <div class="mt-3 mr-2" style="text-align: right;margin-right: 1rem">
                            <button class="btn btn-primary btn-sm mt-3 submit_btn form_condition" type="submit" id="cifFormSubmitVerifyModal">SUBMIT</button>
                        </div>
                    </form>
                </div>

    <!-- WEB -->
    <div class="container-fluid custom-layout" id="web_view">
        <div class="row">
            <div class="d-none d-sm-block">
                <div class="web-item-wrap">
                    <h4 class="service-title d-none d-sm-block text-capitalize">
                        {{ $type }} Related {{ $request_type ?? '' }} Request
                    </h4>
                    <div class="card mt-3 mb-5">
                        <div class="card-body input-wrapper">
                            <div class="step_wrap">
                                <ol class="track-progress" id="tracker" data-steps="3">
                                    <li class="done step1">
                                        <span class="hidden-xs">Step1</span>
                                        <span class="visible-xs">1</span>
                                        <i></i>
                                    </li>
                                    <li class="step2">
                                        <span class="hidden-xs">Step2</span>
                                        <span class="visible-xs">2</span>
                                        <i></i>
                                    </li>
                                    <li class="step3">
                                        <span class="hidden-xs">Step3</span>
                                        <span class="visible-xs">3</span>
                                    </li>
                                </ol>
                            </div>
                            <div id="form_log_wrap">
                                <form id="cifForm" action="{{  $request_type == 'service' ? url('/CI/newWForm') : url('/CI/newComplaint') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="ci_token" value="{{ $ci_token }}">
                                    <input type="hidden" name="product_type" value="{{ $product_type }}"/>
                                    <input type="hidden" name="request_type" value="{{ $request_type }}"/>
                                    <input type="hidden" name="api_response" value="{{ $api_response }}"/>
                                    <input type="hidden" name="is_send_back" value="{{ $is_send_back }}"/>
                                    <input type="hidden" name="otp_mode" value="1"/>
                                    
                                    <div class="card card-color">
                                        <div class="card-body" style="padding:0">
                                            <div class="form-group" style="padding-bottom: 8px;">
                                                <label class="mb-1">{{ $type }} Number <span class="required"> * </span></label>
                                                <select class="form-select single_select2 single_select2_focus" name="account_number" >
                                                    @if(count($accountNumbers) != 1)
                                                        <option value="">Select {{ $type }}</option>
                                                    @endif
                                                    @foreach($accountNumbers as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="account_number_err error-message"></div>
                                            </div>
                                        </div>
                                    </div>

				<div class="card card-color">
                                        <div class="card-body" style="padding:0">
                                            <div class="form-group" style="padding-bottom: 8px;">
                                                <label class="mb-1 d-block text-capitalize">{{ $request_type ?? '' }} Request<span class="required">*</span></label>
                                                <select class="form-select single_select2" name="w_form_type" id="request_type">
                                                    <option value="">Please Select Service</option>
                                                    @foreach($unit_items as $key => $item)
                                                        @php
                                                            $pBServiceCat = old('w_form_type');
                                                            $selectedServiceCat = "";
                                                            if($pBServiceCat == $item['id']) {
                                                                $selectedServiceCat = "selected";
                                                            }
                                                        @endphp

                                                        <option value="{{ $item['id'] }}" {{ $selectedServiceCat }}>{{$item['name']}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="w_form_type_err error-message"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="issue_extra">
                                        @php
                                            $pbServType="";
                                            if(!empty(old('w_form_type'))) {
                                                $service_request = $pbServType = old('w_form_type');
                                                $issue_fields = App\Services\CI\FieldSetGroupService::getFieldSet($service_request);
                                            }
                                        @endphp
                                        @include('BBL_CI.partials.extra_form_field_with_group')
                                    </div>
                                    <div id="issue_attachment_item">
                                        @php
                                            $pbServType="";
                                            if(!empty(old('w_form_type'))) {
                                            $service_request = $pbServType = old('w_form_type');
                                                $attachment_item = App\IssueAttachmentConfig::where('issue_id', $service_request)->orderBy('order_by', "ASC")->get();
                                            }
                                        @endphp
                                        @include('BBL_CI.partials.CIissue_attachment_item')
                                    </div>

                                    <div class="mt-4 otp_mode_wrap d-none">
                                        <h6 style="font-size: 14px" class="mb-3">Select Authorization Mode</h6>
                                        <div class="otp_mode pr-2 active" id="otp_sms">SMS</div>
                                        {{--@if($isEmailOtp == 1)
                                            <div class="otp_mode" id="otp_email">Email</div>
                                        @endif--}}
                                    </div>
                                    {{--<div class="form-check pt-4 otp_mode_wrap d-none">
                                        <input class="form-check-input pl-3 ml-3 checkme" type="checkbox" name="" value="1" data-toggle="tooltip" data-placement="buttom" title="Please Check">
                                        <label class="form-check-label" for="checkme">I accept the <a href="#" class="text-decoration-underline termConditionModalBtn">Terms and Conditions</a></label>
                                    </div>--}}

                                    <div class="mt-3" style="text-align: right;">
                                        <button type="submit" class="btn btn-primary btn-sm form_condition" id="formSubmitBtn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OTP Modal For App -->
    <div class="modal fade" id="verifyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title showOtp" id="verifyModalLabel">Verification</h5>
                </div>
                <div class="modal-body">
                    <p class="otp_sms_modal d-none">Please enter the OTP from your registered mobile number <span class="masked_phone"></span></p>
                    {{--<p class="otp_email_modal d-none">Please enter the OTP from your registered Email address {{ $email_address }}</p>--}}
                    <div class="text-center">
                        <div class="alert alert-danger d-none d-inline-block" id="errorMessageShow"></div>
                        <div class="alert alert-success d-inline-block" id="successMessageShow"></div>
                    </div>


                    <form id="verifyOtpModalForm" action="{{ route('CI.otp-submit') }}" method="post">
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="hidden" name="request_type" value="{{ $request_type }}" id="request_type">
                                <input type="hidden" name="product_type" value="{{ $product_type }}">
                                <input type="hidden" name="invalidCount" value="{{ @$invalidCoun }}" id="invalidCount">
                                <input type="hidden" name="ci_token" value="{{ $ci_token }}" id="ci_token">
                                <input type="hidden" class="reference_number" name="reference_number" value="{{@$reference_number}}">
                                <input type="hidden" class="otpGenId" name="otp_auto_id" value="{{@$otpGenId}}" id="otpGenId">
                                <input type="hidden" name="request_mode_in_otp" id="request_mode_in_otp" value="" />
                                <input type="hidden" name="currentTime" id="currentTime" value="{{ date('Y-m-d H:i:s') }}" />

                                <div class="d-flex justify-content-center contenedor-fecha-interior-app otp_inputed_val">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha input-selector otp_input" maxlength="1" required name="otp1" id="otp_1">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha input-selector otp_input" maxlength="1" required name="otp2" id="otp_2">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha input-selector otp_input" maxlength="1" required name="otp3" id="otp_3">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha input-selector otp_input" maxlength="1" required name="otp4" id="otp_4">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha input-selector otp_input" maxlength="1" required name="otp5" id="otp_5">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha input-selector otp_input" maxlength="1" required name="otp6" id="otp_6">
                                </div>
                            </div>
                        </div>

                        <div class="otp_counter border-0 p-0 mt-1 pt-3">
                            <div id="revese-timer" data-minute="3"></div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12 d-flex justify-content-between border-0 p-0 m-0">
                                <button class="btn btn-primary btn-sm mt-1 submit_btn verify_btn verify_btn_cancel float-start" type="button" id="cancelVarifyModal">CANCEL</button>
                                <button class="btn btn-primary btn-sm mt-1 submit_btn verify_btn verify_btn_cancel float-end" type="button" id="verifyOtpModalBtn">VERIFY</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Success Modal For App -->
    <div class="modal fade" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <p class="text-center" style="font-size: 60px; text-align:center"><i class="fa-regular fa-circle-check"></i></p>
                    <p>Success</p>
                    <p><span id="issue_name"></span> Request  has been received <br> Successfully. Ticket No. <span id="reference_number"></span></p>
                </div>

                <div style="display: flex;justify-content: space-around;margin-top: 10px;margin-bottom: 20px">
                    <a href="#" class="btn btn-sm btn-success" id="make_another">Make Another</a>
                    <a href="#" class="btn btn-sm btn-info" id="ticket_status">Ticket Status</a>
                    <a href="#" class="btn btn-sm btn-danger" id="back_to_home">Back To Home</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Term and Codition -->
</div>

   {{--<div class="modal" id="termConditionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
       <div class="modal-dialog">
           <div class="modal-content" style="background: #f1f1f1; color: #090000; border: 2px solid blue;">
           <div class="modal-header">
               <h5 class="modal-title">Term and Condition</h5>
           </div>
           <div class="modal-body">
               @php
                   $settingsInfo = \App\Setting::first();
               @endphp
               <p class="card-text">{!! $settingsInfo->term_condition ?? ''  !!}</p>
               <button class="btn btn-primary btn-sm mt-1 submit_btn float-end me-2" type="button" id="cancelTermConditionModal">Close</button>
           </div>
           </div>
       </div>
   </div>--}}


    @push('js')
        <script nonce="{{ app('csp_nonce') }}">

            // OTP Text input remove
            function validateNumericInput(event)
            {
                const input = event.target;
                const value = input.value;
                if (isNaN(value)) {
                    input.value = '';
                }
            }

	    $(document).ready(function() {
		$('.otp_input').on('input', function(event){
		    validateNumericInput(event);
		})
	    });


            $(document).ready(function() {
                $('.checkme').change(function () {
                    if(this.checked){
                        $('.form_condition').removeAttr('disabled');
                    }else {
                        $('.form_condition').attr('disabled', 'disabled');
                    }
                })
            });

            <!-- Zihad -->
            $('.file-upload').change(function() {
                var filepath = this.value;
                var m = filepath.match(/([^\/\\]+)$/);
                var filename = m[1];
                $('#filename-1').html(filename);
            });
            <!-- OTP Email Select -->
            $('.otp_mode').on('click', function (e) {
                var IdName = $(this).attr('id');
                $(".otp_mode").removeClass("active");
                $(this).addClass("active");
                if (IdName == 'otp_sms'){
                    $("input[name='otp_mode']").val("1");
                } else {
                    $("input[name='otp_mode']").val("2");
                }
            });

            <!-- Get issue Fieldset -->
            $('#request_type').on('change', function () {
                var issue_id = $(this).val();
                var type = "wform";
                var request_for = "web";
                var ci_token = '{{ $ci_token }}';
                $('.otp_mode_wrap').addClass('d-none');
                if (issue_id.length > 0) {
                    $.post('{{ url('CI/issue-extra-form') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        request_for:request_for,
                        ci_token: ci_token,
                        beforeSend: function() {
                            $('.loadingOverlay').removeClass('loader-none');
                        },
                    }, function (data) {
                        $('#issue_extra').html(data);
                        $('.otp_mode_wrap').removeClass('d-none');
                        onChangeRequestType(issue_id);
                        $('.fieldset_select2').select2();
                        setTimeout(function () {
                            $('.loadingOverlay').addClass('loader-none');
                        }, 800);
                    });
                } else {
                    $('#issue_extra').html(null);
                    $('.loadingOverlay').addClass('loader-none');
                }
            });

            <!-- Get issue attachment Web -->
            $('#request_type').on('change', function () {
                var issue_id = $(this).val();
                var type = "wform";
                var ci_token = '{{ $ci_token }}';
                if (issue_id.length > 0) {
                    $.post('{{ url('CI/issue-attachment') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        type: type,
                        ci_token: ci_token,
                    }, function (data) {
                        $('#issue_attachment_item').html(data);
                    });
                } else {
                    //$('#attachment_item').html(null);
                }
            });

            <!-- CI Form Submit Web -->
            $(document).ready(function () {
                $("#cifForm").submit(function (e) {
                    e.preventDefault();
                    var form = $("#cifForm");
                    var data =  new FormData($(this)[0]);
                    var action = form.attr("action");
                    $('.loadingOverlay').removeClass('loader-none');

                    $.ajax({
                        url: action,
                        method: form.attr("method"),
                        data: data,
                        processData: false,
                        contentType: false,
                        beforeSend:function(){
                            $(document).find('div.error-message').text('');
                            $(document).find('div.error-message').removeClass('open');
                        },
                    })
		    .done(function(data) {
			alert(1);
                        if(data.success){
                            $(document).find('.step1').removeClass('done');
                            $(document).find('.step2').addClass('done');

                            $.post('{{ url('CI/otp-verify') }}', {
                                _token: '{{ csrf_token() }}',
                                data: data,
                                beforeSend: function() {
                                    $('.loadingOverlay').removeClass('loader-none');
                                },
                            }, function (data) {
                                $('#form_log_wrap').html(data);
                                otpCall();
                                $('#invalidCount').val(0);
                                $('.loadingOverlay').addClass('loader-none');
                            });

                        } else {
                            $('.loadingOverlay').addClass('loader-none');
                            toastr.error(data.otpMessage);
                        }
                    })
		    .fail(function(xhr) {
			alert(2);
                        if(xhr.status == 422){
                            $('.loadingOverlay').addClass('loader-none');
			    if(xhr.responseJSON.errorType == '2'){
                                alert(xhr.responseJSON.message);
                            }
                            printErrorMsg(xhr.responseJSON.errors);
                        } else {
                            toastr.error("Something went wrong!");
                        }
                    });
                });
                function printErrorMsg(msg) {
                    var i = -1;
                    $.each(msg, function (key, value) {
                        i++;
                        var imageFile = key.substring(0, 4);
                        if(imageFile == "file"){
                            let convertedID = key.replace(/\./g, "_").replace(/\b(\d+)\b/g, "_$1_")
                            $('.'+convertedID).text(value);
                            $('.'+convertedID).addClass('open');
                        }else {
                            $('.'+key+'_err').text(value);
                            $('.'+key+'_err').addClass('open');
                        }
                    });
                }
            });

            <!-- End -->


            <!-- OTP For Web added by ZIHAD -->
            let timerInterval = null;
            function otpCall () {
		if(timerInterval !== null){
		    clearInterval(timerInterval);
		    timerInterval = null;
		}
                const FULL_DASH_ARRAY = 283;
                var Minute = $("#revese-timer").data("minute");
                var Seconds = Math.round(60 * Minute);
                const TIME_LIMIT = Seconds;
                let timePassed = 0;
                let timeLeft = TIME_LIMIT;
                let remainingPathColor = 'green';

                $("#revese-timer").html(`
                    <div class="base-timer">
                        <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <g class="base-timer__circle">
                                <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                                <path
                                    id="base-timer-path-remaining"
                                    stroke-dasharray="283"
                                    class="base-timer__path-remaining ${remainingPathColor}"
                                    d="
                                        M 50, 50
                                        m -45, 0
                                        a 45,45 0 1,0 90,0
                                        a 45,45 0 1,0 -90,0
                                    "
                                ></path>
                            </g>
                        </svg>
                        <span id="base-timer-label" class="base-timer__label">
                            ${formatTime(timeLeft)}
                        </span>
                    </div>
                `);

                function onTimesUp() {
                    clearInterval(timerInterval);
                }

                function startTimer() {
                    timerInterval = setInterval(() => {
                        timePassed = timePassed += 1;
                        timeLeft = TIME_LIMIT - timePassed;
                        $("#base-timer-label").html(formatTime(timeLeft));
                        setCircleDasharray();
                        //setRemainingPathColor(timeLeft);

                        if (timeLeft === 0) {
                            $("#base-timer-label").html(`<button type="button" class="resend_otp" id="regenerateOtp">Resend</button>`);
                            $("#base-timer-path-remaining").removeClass('green').addClass('end');
                            onTimesUp();
                        }
                    }, 1000);
                }

		$(document).on('click', '.resend_otp', function(){
		    resendOTP();
		})

                function formatTime(time) {
                    const minutes = Math.floor(time / 60);
                    let seconds = time % 60;

                    if (seconds < 10) {
                        time = `${time}`;
                    }

                    return `${time}s`;
                }

                function calculateTimeFraction() {
                    const rawTimeFraction = timeLeft / TIME_LIMIT;
                    return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
                }

                function setCircleDasharray() {
                    const circleDasharray = `${(calculateTimeFraction() * FULL_DASH_ARRAY).toFixed(0)} 283`;
                    $("#base-timer-path-remaining").attr("stroke-dasharray", circleDasharray);
                }

                startTimer();
            }

            <!-- CI Form Submit App -->
            $(document).ready(function () {
                $("#cifFormApp").submit(function (e) {
                    e.preventDefault();
                    var form = $("#cifFormApp");
                    var data =  new FormData($(this)[0]);
                    var action = form.attr("action");
                    var otp_mode = $("input[name='otp_mode']").val();
                    $('#successMessageShow').text("").addClass('d-none');
                    $('#errorMessageShow').text("").addClass('d-none');
                    $('.otp_sms_modal').addClass('d-none');
                    $('.otp_email_modal').addClass('d-none');

                    $.ajax({
                        url: action,
                        method: form.attr("method"),
                        data: data,
                        processData: false,
                        contentType: false,
                        beforeSend:function(){
                            $(document).find('div.error-message').text('');
                            $(document).find('div.error-message').removeClass('open');
                            $('.loadingOverlay').removeClass('loader-none');
                        },
                    })
                        .done(function(data) {
                            if(data.success){
                                //$(".showOtp").text('Verification '+data.otpCode);
                                $(".otpGenId").val(data.otpGenId);
                                $(".reference_number").val(data.reference_number);
                                $('.loadingOverlay').addClass('loader-none');
				$(".masked_phone").text(data.mobile_no);
                                if (otp_mode == 1){
                                    $('.otp_sms_modal').removeClass('d-none');
                                    $('.otp_email_modal').addClass('d-none');
                                }else {
                                    $('.otp_email_modal').removeClass('d-none');
                                    $('.otp_sms_modal').addClass('d-none');
                                }
                                $('#verifyModal').modal('show');
                                $('#invalidCount').val(data.invalidCount);
                                otpCall();
                                $('#invalidCount').val(0);
                                $("#verifyOtpModalBtn").removeAttr('disabled');

                            } else {
                                $('.loadingOverlay').addClass('loader-none');
                                toastr.error(data.otpMessage);
				alert(data);
                            }
                        })
                        .fail(function(xhr, status, error) {
                            if(xhr.status == 422){
                                $('.loadingOverlay').addClass('loader-none');
			        if(xhr.responseJSON.errorType == '2'){
                                    alert(xhr.responseJSON.message);
                                }
                                printErrorMsg(xhr.responseJSON.errors);
                            } else {
                                $('.loadingOverlay').addClass('loader-none');
                                // toastr.error("Something went wrong!");
				let errorMessage = `
				    <b>Ajax Error:</b><br>
				    Status: ${xhr.status} (${xhr.statusText})<br>
				    Error: ${error}<br>
				    Response: ${xhr.responseText ? xhr.responseText.substring(0, 500) : 'No response'} <br>`;
				    //toaster.error(errorMessage);

                            }
                        });
                });
                function printErrorMsg(msg) {
                    var i = -1;
                    $.each(msg, function (key, value) {
                        i++;
                        var imageFile = key.substring(0, 4);
                        if(imageFile == "file"){
                            let convertedID = key.replace(/\./g, "_").replace(/\b(\d+)\b/g, "_$1_")
                            $('.'+convertedID).text(value);
                            $('.'+convertedID).addClass('open');
                        }else {
                            $('.'+key+'_err').text(value);
                            $('.'+key+'_err').addClass('open');
                        }
                    });
                }
            });

            // resend OTP For App
            function resendOTP(){
                $('.loadingOverlay').removeClass('loader-none');
                // otp resend
                var otp_1 = $('#otp_1').val('');
                var otp_2 = $('#otp_2').val('');
                var otp_3 = $('#otp_3').val('');
                var otp_4 = $('#otp_4').val('');
                var otp_5 = $('#otp_5').val('');
                var otp_6 = $('#otp_6').val('');
                var otp_mode = 1;

                $('#inputedOtpId').val("");
                $('#inputOtp').val("");
                $('.error').html('');
                $('#successMessageShow').text("").addClass('d-none');
                $('#errorMessageShow').text("").addClass('d-none');
                // setTimeout(function() {
                //     $('#successMessageShow').text("").removeClass('d-none');
                //     $('#errorMessageShow').text("").removeClass('d-none');
                // }, 7000);
                var otpGenId = $('.otpGenId').val();
                var ci_token = $('#ci_token').val();
                $.ajax({
                    url: "{{ url('/CI/otp/re-generate') }}",
                    type: "POST",
                    _token: '{{ csrf_token() }}',
                    dataType: "json",
                    data: {otpGenId:otpGenId, ci_token:ci_token, otp_mode:otp_mode},
                    success: function(data){
                        //$(".showOtp").text('Verification '+data.otpCode);
                        $(".otpCode").val(data.otpCode);
                        $(".otpGenId").val(data.otpGenId);
                        // $('#invalidCount').val(data.invalidCount);
                        // $("#verifyOtpModalBtn").attr("disabled", false);

                        // count down start
                        $('.resend_otp').addClass('d-none');
                        $('.loadingOverlay').addClass('loader-none');
                        otpCall();
                        $('#invalidCount').val(0);
                        $("#verifyOtpModalBtn").removeAttr('disabled');
                        $('#successMessageShow').text("OTP resend Successfully!").removeClass('d-none');
                        setTimeout(function() {
                            $('#successMessageShow').text("").addClass('d-none');
                        }, 7000);
                    },
                    error: function(error) {
                        $('#errorMessageShow').text('Please try again later').removeClass('d-none');
                        setTimeout(function() {
                            $('#errorMessageShow').text("").addClass('d-none');
                        }, 7000);
                    }
                });
            }

            // input otp Field
            $(document).ready(function(){

                $(".input-fecha").keypress(function(event){
                    var currentValue = $(this).val();
                    if(event.which !== 8 && (event.which < 48 || event.which > 57) || currentValue.length !=0){
                        event.preventDefault();
                    }
                });

                let $inputs = $('.contenedor-fecha-interior-app input').on('input', e => {
                    let $input = $(e.target);
                    $('.input-fecha').attr('max', 1);
                    let index = $inputs.index($input);
                    if ($input.val().length >= $input.prop('maxlength')) {
                        // $inputs.eq(index).focus();
                        $inputs.eq(index + 1).prop('disabled', false).focus();
                    }
                });
            });

            $('#cancelTermConditionModal').on('click', function(e){
                e.preventDefault();
                $('#termConditionModal').modal('hide');
            });

            $('.termConditionModalBtn').on('click', function(e){
                e.preventDefault();
                $('#termConditionModal').modal('show');
            });

            $('#cancelVarifyModal').on('click', function(e){
                var otp_1 = $('#otp_1').val('');
                var otp_2 = $('#otp_2').val('');
                var otp_3 = $('#otp_3').val('');
                var otp_4 = $('#otp_4').val('');
                var otp_5 = $('#otp_5').val('');
                var otp_6 = $('#otp_6').val('');
                clearInterval(timerInterval);
                $('#invalidCount').val(0);
                $("#regenerateOtp").removeAttr('disabled');
                $("#verifyOtpModalBtn").removeAttr('disabled');
                $('#verifyModal').modal('hide');
            });

            $('#verifyOtpModalBtn').on('click', function(e){
                e.preventDefault();
                var otp_1 = $('#otp_1').val();
                var otp_2 = $('#otp_2').val();
                var otp_3 = $('#otp_3').val();
                var otp_4 = $('#otp_4').val();
                var otp_5 = $('#otp_5').val();
                var otp_6 = $('#otp_6').val();
                if(otp_1 !="" && otp_2 !="" && otp_3 !="" && otp_4 !="" && otp_5 !="" && otp_6 !=""){
                    if (window.innerWidth < 800) {
                        $('#request_mode_in_otp').val('app');
                        var url = $('#verifyOtpModalForm').attr('action');
                        var request = $('#verifyOtpModalForm').serialize();
                        $('.loadingOverlay').removeClass('loader-none');
                        $('#errorMessageShow').addClass('d-none').text("");
                        today = new Date().toLocaleString();
                        $('#currentTime').val(today);
                        $.ajax({
                            url: url,
                            type: "POST",
                            _token: '{{ csrf_token() }}',
                            dataType: "json",
                            data: request,
                            success: function(data){
                                if(data.otpMessage =="" ||  data.otpMessage == null){
                                    $('.loadingOverlay').addClass('loader-none');
                                    $('#verifyModal').modal('hide');
                                    $('#issue_name').text(data.issue_name);
                                    $('#reference_number').text(data.reference_number);

                                    $('#back_to_home').attr("href", data.CIUrl);
                                    $('#ticket_status').attr("href", data.ticketStatusUrl);
                                    $("#make_another").attr("href", data.backUrl);

                                    // $('#make_another').href = data.backUrl;
                                    $('#successModal').modal('show');
                                    $('#cifFormApp')[0].reset();
                                    document.getElementById("verifyOtpModalForm").reset();
                                }else{
                                    $('.loadingOverlay').addClass('loader-none');
                                    $('#errorMessageShow').removeClass('d-none').text(data.otpMessage);
                                    setTimeout(function() {
                                        $('#errorMessageShow').addClass('d-none');
                                    }, 8000);
                                     //$('#errorMessageShow').text('Verify is disabled if wrong OTP is provided 3 times', data.otpMessage);
                                    $('#invalidCount').val(data.invalidCount);
                                    if($('#invalidCount').val() > 2){
                                        $("#verifyOtpModalBtn").attr("disabled", true);
                                        $("#regenerateOtp").attr("disabled", true);
                                    }

                                }
                            }
                        });
                    } else {
                        $('#verifyModal').modal('hide');
                        $('#successModal').modal('hide');
                    }
                } else {
                    $('#errorMessageShow').text("Please input OTP").removeClass('d-none');
                    setTimeout(function() {
                        $('#errorMessageShow').addClass('d-none');
                    }, 8000);
                }
            });


            <!-- Get issue Fieldset App -->

            $('#request_type_mobile').on('change', function () {
                var issue_id = $(this).val();
                var request_for = "app";
                var type = "wform";
                var ci_token = '{{ $ci_token }}';
                $('.otp_mode_wrap').addClass('d-none');
                if (issue_id.length > 0) {
                    $.post('{{ url('CI/issue-extra-form') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        request_for:request_for,
                        ci_token: ci_token,
                        beforeSend: function() {
                            $('.loadingOverlay').removeClass('loader-none');
                        },
                    }, function (data) {
                        $('#issue_extra_mobile').html(data);
                        $('.otp_mode_wrap').removeClass('d-none');
                        onChangeRequestType(issue_id);
                        $('.fieldset_select2').select2();
                        setTimeout(function () {
                            $('.loadingOverlay').addClass('loader-none');
                        }, 800);
                    });
                } else {
                    $('#issue_extra_mobile').html(null);
                    $('.loadingOverlay').addClass('loader-none');
                }

                if (issue_id.length > 0) {
                    $.post('{{ url('CI/issue-attachment') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        request_for:request_for,
                        type: type,
                        ci_token: ci_token,
                    }, function (data) {
                        $('#issue_attachment_item_mobile').html(data);
                    });
                } else {
                    //$('#attachment_item').html(null);
                }

            });

            $(document).ready(function() {
                // On load issue type hide dependant fields
                let issue_id = "{{ old('w_form_type') }}";
                onChangeRequestType(issue_id);

                let value = $('.DependantFields').val();
                let id = $('.DependantFields').data('id');
                if (value && value.length > 0) {
                    issueDependantFields(issue_id,value,id);
                } else {
                    issueDependant(issue_id,id)
                }

                // On change conditional fields show/hide dependant fields
                $(document.body).on('change', '.DependantFields', function() {
                    let web = $('#request_type').val();
                    let mobile = $('#request_type_mobile').val();
                    let issue_id = web.trim() === "" ? mobile : web;
                    let value = $(this).val();
                    let id = $(this).data('id');
                    if (value && value.length > 0) {
                        issueDependantFields(issue_id,value,id);
                    } else {
                        issueDependant(issue_id,id)
                    }
                });
            });

            function onChangeRequestType(issue_id) {
                if (issue_id) {
                    $.ajax({
                        url: "{{ url('CI/issue/conditional') }}"+"/"+issue_id,
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            $.each(response, function (key, val) {
                                let value = val.field_name;
                                $('.'+value+'').hide();
                            });
                        }
                    });
                }
            }

            function issueDependantFields(issue_id,value,id) {
                if (issue_id,value,id) {
                    $.ajax({
                        url: "{{ url('CI/issue/dependant/fields') }}"+"/"+issue_id+"/"+value+"/"+id,
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            if (response && response.length > 0) {
                                $.each(response, function (key, val) {
                                    let field = val.field_name;
                                    if (field && field.length > 0) {
                                        $.ajax({
                                            url: "{{ url('CI/issue/conditional/fields') }}"+"/"+issue_id+"/"+id,
                                            type: "GET",
                                            dataType: "json",
                                            success: function (response) {
                                                $.each(response, function (key, val) {
                                                    let value = val.field_name;
                                                    if (value === field) {
                                                        $('.'+ value +'').show();
                                                        let inputElement = $('[name="'+value+'##CI##"]');
                                                        inputElement.attr("name", value);
                                                    } else {
                                                        $('.'+value+'').hide();
                                                        let inputElement = $('[name="'+value+'"]');
                                                        if (inputElement) {
                                                            var currentName = $(inputElement).attr("name");
                                                            let newName = currentName + "##CI##";
                                                            inputElement.name = newName;
                                                            inputElement.attr("name", newName);
                                                        }
                                                        if (inputElement.name && inputElement.name.includes("##CI##")) {
                                                            inputElement.name = inputElement.name.replace(/##CI##/g, "");
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    } else {
                                        issueDependant(issue_id,id)
                                    }
                                });
                            } else {
                                issueDependant(issue_id,id)
                            }
                        }
                    });
                }
            };

            function issueDependant(issue_id,id) {
                var ci_token = '{{ $ci_token }}';
                if (issue_id,id) {
                    $.ajax({
                        url: "{{ url('CI/issue/conditional/fields') }}"+"/"+issue_id+"/"+id,
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            $.each(response, function (key, val) {
                                let value = val.field_name;
                                $('.'+value+'').hide();
                            });
                        }
                    });
                }
            };

        </script>
    @endpush
@endsection
