@extends('BBL_CI.layouts.master')
@push('app-title')
    Send Back Tickets
@endpush
@section('content')
@push('css')
<style>
    .input-fecha:focus {
        outline: 1px solid #e4a52c;
        background: #e4a52c;
    }
</style>

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

<div class="bg-wrapper">
        <!-- APP -->
    <div class="d-block d-sm-none align-self-center">
        <form id="cifFormApp" action="{{ url('/CI/updateWForm') }}" method="POST">
            @csrf
            <div class="container-fluid custom-layout app_view">
                <div class="dropdown-wrapper mb-4">
                    <label class="mb-1"> {{ $type }} Number</label>
                    @if($product_type == 1)
                        <input type="text" value="{{$accNumber->mask_card_no}}" name="account_number" class="form-control" readonly />
                    @else
                        <input type="text" value="{{$accNumber->account_number}}" name="account_number" class="form-control" readonly />
                    @endif
                </div>
                <input type="hidden" name="request_mode" id="request_mode" value="app" />
                <div class="dropdown-wrapper mb-4">
                    <label class="mb-1 d-block">Service Request</label>
                    <input type="text" value="{{$service_name->name}}" class="form-control" id="request_type_mobile" readonly />
                </div>
                <input type="hidden" name="product_type" value="{{ $product_type }}" />
                <input type="hidden" name="ci_token" value="{{ $ci_token }}" />
                <input type="hidden" name="reference_number" value="{{ $refNum }}" />
                <input type="hidden" name="w_form_type" value="{{$service_name->id}}" />
                <input type="hidden" name="otp_mode" value="1"/>
                <div class="dropdown-wrapper mb-4">
                    @include('BBL_CI.partials.send_back_ticket_details_app', ['issue_fields' => $fields, 'arraySingle' => $arraySingle])
                </div>
                <div id="issue_attachment_item">
                    @include('BBL_CI.partials.CIissue_attachment_item_app', ['attachment_item' => $attachment_item, 'send_back' => true])
                </div>
                @if($uploadedAttachment->count() > 0)
                    @foreach($uploadedAttachment as $row)
                        <div class="align-items-center pt-3">

                            @if($row->name)
                                <div class="mr-2 font-weight-bold" style="color: white;">{{ $row->name }}:</div>
                            @else
                                <div class="mr-2 font-weight-bold">Bank attachment:</div>
                            @endif
                            <div class="">
                                <a href="javascript:void(0)" style="color: #7cb5ec">{{$row->file_name}}</a>
                                <a href="{{ route('CI.attachment-download', $row->file_name) }}" style="color: #E5A812"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="mt-4 otp_mode_wrap">
                    <h6 style="font-size: 14px" class="mb-3">Select Authorization Mode</h6>
                    <div class="otp_mode pr-2 active" id="otp_sms">SMS</div>
                    {{--@if($isEmailOtp == 1)
                        <div class="otp_mode" id="otp_email">Email</div>
                    @endif--}}
                </div>

                {{--<div class="form-check pt-4 otp_mode_wrap">
                    <input class="form-check-input pl-3 ml-3 checkme" type="checkbox" name="" value="1" data-toggle="tooltip" data-placement="buttom" title="Please Check">
                    <label class="form-check-label" for="checkme">I accept the <a href="#"  class="text-decoration-underline termConditionModalBtn">Terms and Conditions</a></label>
                </div>--}}
            </div>
            <div class="mt-3 mr-2" style="text-align: right;margin-right: 1rem">
                <button class="btn btn-primary btn-sm mt-4 submit_btn form_condition" type="submit" id="cifFormSubmitVerifyModal">SUBMIT</button>
            </div>
        </form>
    </div>

    <!-- WEB -->
    <div class="container-fluid custom-layout" id="web_view">
        <div class="row">
            <div class="web-item-wrap">
                <h4 class="service-title d-none d-sm-block">
                    @if($product_type == 2) Account Related Send Back Request @elseif($product_type == 3) Debit Card Related Send Back Request @elseif($product_type == 1) Credit Card Related Send Back Request @elseif($product_type == 4) Loan
                    Related Send Back Request @endif
                </h4>
                <div class="d-none d-sm-block" style="min-height: 100vh;">
                    <div class="">
                        <div class="card mt-3 mb-5">
                            <div class="card-body input-wrapper">
                                <div class="step_wrap">
                                    <ol class="track-progress" id="tracker" data-steps="3">
                                        <li class="done step1">
                                        <span class="hidden-xs">
                                            Step 1
                                            {{--<small class="hidden-xs">Initiate Transaction</small>--}}
                                        </span>
                                            <span class="visible-xs">1</span>
                                            <i></i>
                                        </li>
                                        <li class="step2">
                                        <span class="hidden-xs">
                                            Step 2
                                            {{--<small class="hidden-xs">Verify Transaction</small>--}}
                                        </span>
                                            <span class="visible-xs">2</span>
                                            <i></i>
                                        </li>
                                        <li class="step3">
                                        <span class="hidden-xs">
                                            Step 3
                                            {{--<small class="hidden-xs">Transaction Status</small>--}}
                                        </span>
                                            <span class="visible-xs">3</span>
                                        </li>
                                    </ol>
                                </div>
                                <div id="form_log_wrap">
                                    <form id="cifForm" action="{{ url('/CI/updateWForm') }}" method="POST">
                                        {{-- @dd($accNumber) --}}
                                        @csrf
                                        <input type="hidden" name="ci_token" value="{{ $ci_token }}" />
                                        <input type="hidden" name="is_send_back" value="{{$is_send_back}}" />
                                        <input type="hidden" name="otp_mode" value="1"/>
                                        <input type="hidden" name="reference_number" value="{{ $refNum }}" />
                                        <input type="hidden" name="product_type" value="{{ $product_type }}" />
                                        <div class="card card-color">
                                            <div class="card-body" style="padding: 0;">
                                                <div class="form-group" style="padding-bottom: 8px;">
                                                    <label class="mb-1"> {{ $type }} Number </label>
                                                    @if($product_type == 1 || $product_type == 3 )
                                                        <input type="text" value="{{$accNumber->mask_card_no}}" name="account_number" class="form-control" readonly />
                                                    @else
                                                        <input type="text" value="{{$accNumber->account_number}}" name="account_number" class="form-control" readonly />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-color">
                                            <div class="card-body" style="padding: 0;">
                                                <div class="form-group" style="padding-bottom: 8px;">
                                                    <label class="mb-1 d-block">Service Request</label>
                                                    <input type="text" value="{{$service_name->name}}" class="form-control" readonly />
                                                    <input type="hidden" name="w_form_type" value="{{$service_name->id}}" />
                                                    @IF($errors->has('w_form_type'))
                                                        <div class="error-message">{{ $errors->first('w_form_type') }}</div>
                                                    @ENDIF
                                                </div>
                                            </div>
                                        </div>

                                        @include('BBL_CI.partials.send_back_ticket_details', ['issue_fields' => $fields, 'arraySingle' => $arraySingle])

                                        <div id="issue_attachment_item">
                                            @include('BBL_CI.partials.CIissue_attachment_item', ['attachment_item' => $attachment_item, 'send_back' => true])
                                        </div>

                                        @if($uploadedAttachment->count() > 0)
                                            {{--<h6 class="pt-3 m-0" style="color: #333"> Attachment:</h6>--}}
                                            @foreach($uploadedAttachment as $row)
                                                <div class="d-flex align-items-center pt-3">

                                                    @if($row->name)
                                                        <div class="mr-2 font-weight-bold" style="color: white;">{{ $row->name }}:</div>
                                                    @else
                                                        <div class="mr-2 font-weight-bold">Bank attachment:</div>
                                                    @endif

                                                    <div class="" >&nbsp;
                                                        <a href="javascript:void(0)" style="color: #7cb5ec">{{$row->file_name}}</a>
                                                        <a href="{{ route('CI.attachment-download', $row->file_name) }}" style="color: #E5A812"><i class="fa fa-download"></i></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="mt-4 mb-4 otp_mode_wrap" >
                                            <h6 class="mb-3">Select Authorization Mode :</h6>
                                            <div class="otp_mode pr-2 active" id="otp_sms">SMS</div>
                                            {{--@if($isEmailOtp == 1)
                                                <div class="otp_mode" id="otp_email">Email</div>
                                            @endif--}}
                                        </div>

                                        {{--<div class="form-check pt-3 otp_mode_wrap">
                                            <input class="form-check-input pl-3 ml-3 checkme" type="checkbox" name="" value="1" data-toggle="tooltip" data-placement="buttom" title="Please Check">
                                            <label class="form-check-label" for="checkme">I accept the <a href="#"  class="text-decoration-underline termConditionModalBtn">Terms and Conditions</a></label>
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="verifyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title showOtp" id="verifyModalLabel">Verification</h5>
                </div>
                <div class="modal-body">
                    <p>Please enter the OTP from your registered mobile number {{ $mobile_no }}</p>
                    <div class="text-center">
                        <div class="alert alert-danger d-none d-inline-block" id="errorMessageShow"></div>
                        <div class="alert alert-success d-none d-inline-block" id="successMessageShow"></div>
                    </div>

                    <form id="verifyOtpModalForm" action="{{ route('CI.otp-submit') }}" method="post">
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="hidden" name="product_type" value="{{$product_type}}">
                                <input type="hidden" name="ci_token" value="{{ $ci_token }}" id="ci_token">
                                <input type="hidden" class="reference_number" name="reference_number" value="{{@$reference_number}}">
                                <input type="hidden" class="otpCode" name="otpCode" value="{{@$otpCode}}">
                                <input type="hidden" class="otpGenId" name="otp_auto_id" value="{{@$otpGenId}}" id="otpGenId">
                                <input type="hidden" name="request_mode_in_otp" id="request_mode_in_otp" value="" />
                                <input type="hidden" name="invalidCount" value="{{ @$invalidCoun }}" id="invalidCount">

                                <div class="d-flex justify-content-center contenedor-fecha-interior-app otp_inputed_val">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha otp_input" maxlength="1" required name="otp1" id="otp_1">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha otp_input" maxlength="1" required name="otp2" id="otp_2">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha otp_input" maxlength="1" required name="otp3" id="otp_3">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha otp_input" maxlength="1" required name="otp4" id="otp_4">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha otp_input" maxlength="1" required name="otp5" id="otp_5">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha otp_input" maxlength="1" required name="otp6" id="otp_6">
                                </div>
                            </div>
                        </div>

                        <div class="otp_counter border-0 p-0 mt-1 pt-3">
                            <div id="revese-timer" data-minute="3"></div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12 d-flex justify-content-between border-0 p-0 m-0">
                                <button class="btn btn-primary btn-sm mt-1 submit_btn verify_btn verify_btn_cancel sendBackCancel  float-start" type="button" data-bs-dismiss="modal" id="cancelVarifyModal">CANCEL</button>
                                <button class="btn btn-primary btn-sm mt-1 submit_btn verify_btn verify_btn_cancel float-end" type="button" id="verifyOtpModalBtn">VERIFY</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
   {{-- <div class="modal" id="termConditionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
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
</div>

    @push('js')
        <script nonce="{{ app('csp_nonce') }}">
		
			let storedFilesGlobal = [];
            $(document).ready(function () {
                $(document).on('click', '.sendBackCancel', function () {
                    let url = $(this).data('url');
                    
                    const reference_number = "{{ $refNum }}";
    
                    if(storedFilesGlobal.length != 0){
                        $.ajax({
                            url: "{{ route('CI.attachment.remover') }}",
                            type: "POST",
                            data:{
                                files : storedFilesGlobal,
                                reference_number : reference_number,
                                _token : "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                console.log('Success:', response);
                            },
                            error: function (xhr) {
                                console.error('Error:', xhr.responseText);
                            },
                            complete: function () {
                                if (url) {
                                    window.location.href = url;
                                }
                            }
                        })
                    }else{
                        if (url) {
                            window.location = url;
                        }
                    }
                });
            });
            // OTP Text input remove
            function validateNumericInput(event)
            {
                const input = event.target;
                const value = input.value;
                if (isNaN(value)) {
                    input.value = '';
                }
            }

            <!-- OTP Email Select Zihad -->
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

            // Muajjam Hossain

            $(document).ready(function() {
                $('.checkme').change(function () {
                    if(this.checked){
                        $('.form_condition').removeAttr('disabled');
                    }else {
                        $('.form_condition').attr('disabled', 'disabled');
                    }
                })
            });

            // update web
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
                                toastr.error("Something went wrong!");
                            }
                        })
                        .fail(function(xhr) {
                            if(xhr.status == 422){
                                $('.loadingOverlay').addClass('loader-none');
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

            <!-- CI Form Submit App -->
            $(document).ready(function () {
                $("#cifFormApp").submit(function (e) {
                    e.preventDefault();
                    var form = $("#cifFormApp");
                    var data =  new FormData($(this)[0]);
                    var action = form.attr("action");
                    var otp_mode = $("input[name='otp_mode']").val();
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
				storedFilesGlobal = data.storedFiles || [];
                                //$(".showOtp").text('Verification '+data.otpCode);
                                $(".otpCode").val(data.otpCode);
                                $(".otpGenId").val(data.otpGenId);
                                $(".reference_number").val(data.reference_number);
                                $('.loadingOverlay').addClass('loader-none');

                                $('#verifyModal').modal('show');
                                $('#invalidCount').val(data.invalidCount);
                                otpCall();
                                $('#invalidCount').val(0);
                                $("#verifyOtpModalBtn").removeAttr('disabled');
                            } else {
                                $('.loadingOverlay').addClass('loader-none');
                                toastr.error("Something went wrong!");
                            }
                        })
                        .fail(function(xhr, status, error) {
                            if(xhr.status == 422){
                                $('.loadingOverlay').addClass('loader-none');
                                printErrorMsg(xhr.responseJSON.errors);
                            } else {
                                $('.loadingOverlay').addClass('loader-none');
                                //toastr.error("Something went wrong!");
				
				let errorMessage = `
				    Ajax Error:
				    Status: ${xhr.status} (${xhr.statusText})
				    Error: ${error}<br>
				    Response: ${xhr.responseText ? xhr.responseText.substring(0, 500) : 'No response'}`;
				    alert(errorMessage);
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


            // otp input for app
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
		});
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
            /* OTP Counter */
            // var timer;
            // var max = 180;
            // var percentage = max;
            // var dialColor = "#f7f7f7";
            // var percColor = "#cb1111";

            // function setPercentage(percentage) {
            //     var element = document.createElement("style");
            //     element.innerHTML = ".percent:before {";

            //     if (percentage > 0.5) {
            //         document.getElementsByClassName(
            //             "chart"
            //         )[0].style.backgroundColor = percColor;
            //         element.innerHTML += "background-color: " + dialColor + ";";
            //         element.innerHTML +=
            //             "transform:rotate(" + (180 - (percentage - 0.5) * 360) + "deg);";
            //         document.getElementsByClassName("percent")[0].style.transform =
            //             "rotate(" + (-180 + (percentage - 0.5) * 360) + "deg)";
            //     } else {
            //         document.getElementsByClassName(
            //             "chart"
            //         )[0].style.backgroundColor = dialColor;
            //         element.innerHTML += "background-color: " + percColor + ";";
            //         element.innerHTML += "transform:rotate(" + percentage * 360 + "deg);";
            //         document.getElementsByClassName("percent")[0].style.transform =
            //             "rotate(0deg)";
            //     }

            //     element.innerHTML += "}";
            //     document.getElementsByTagName("head")[0].appendChild(element);
            // }

            // function updatePercentage() {
            //     percentage -= 0.1;
            //     if (percentage <= 0) {
            //         clearInterval(timer);
            //         $('.resend_otp').removeClass('d-none');
            //     }
            //     setPercentage(percentage / max > 0 ? percentage / max : 0);
            //     document.getElementsByClassName("filler")[0].innerHTML =
            //         Math.ceil(percentage) + "s";
            // }

            // function init() {
            //     timer = setInterval(updatePercentage, 100);
            // }

            /* End */


            // resend function

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
                        $('#successMessageShow').text("OTP resend Successfully!").removeClass('d-none');
                        setTimeout(function() {
                            $('#successMessageShow').addClass('d-none');;
                        }, 7000);
                        otpCall();
                        $('#invalidCount').val(0);
                        $("#verifyOtpModalBtn").removeAttr('disabled');
                    },
                    error: function(error) {
                        // toastr.error(error.responseJSON.message);
                        $('#errorMessageShow').text('Please try again later').removeClass('d-none');
                        setTimeout(function() {
                            $('#errorMessageShow').addClass('d-none');
                        }, 7000);
                    }
                });
            }

            $('#cancelTermConditionModal').on('click', function(e){
                e.preventDefault();
                $('#termConditionModal').modal('hide');
            });

            $('.termConditionModalBtn').on('click', function(e){
                e.preventDefault();
                $('#termConditionModal').modal('show');
            });

            // input otp Field
            $(document).ready(function(){

                $(".input-fecha").keypress(function(event){
                    var currentValue = $(this).val();

                    // if(currentValue.length !=0){
                    //     event.preventDefault();
                    if(event.which !== 8 && (event.which < 48 || event.which > 57) || currentValue.length !=0){
                        event.preventDefault();
                    }
                    // }
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

            $('#cancelVarifyModal').on('click', function(e){
                $('#errorMessageShow').text("").addClass('d-none');
                $('#successMessageShow').text("").addClass('d-none');
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
                        $('#errorMessageShow').text("").addClass('d-none');
                        $('#successMessageShow').text("").addClass('d-none');
                        $('#request_mode_in_otp').val('app');
                        var url = $('#verifyOtpModalForm').attr('action');
                        var request = $('#verifyOtpModalForm').serialize();

                        $.ajax({
                            url: url,
                            type: "POST",
                            _token: '{{ csrf_token() }}',
                            dataType: "json",
                            data: request,
                            success: function(data){
                                if(data.otpMessage =="" ||  data.otpMessage == null){
                                    $('#verifyModal').modal('hide');
                                    $('#issue_name').text(data.issue_name);
                                    $('#reference_number').text(data.reference_number);
                                    $('#back_to_home').attr("href", data.CIUrl);
                                    $('#ticket_status').attr("href", data.ticketStatusUrl);
                                    $("#make_another").attr("href", data.backUrl);
                                    $('#successModal').modal('show');
                                    $('#cifFormApp')[0].reset();
                                    document.getElementById("verifyOtpModalForm").reset();
                                    $('.loadingOverlay').addClass('loader-none');
                                }else{
                                    // $('#errorMessageShow').text(data.otpMessage);
                                    $('#invalidCount').val(data.invalidCount);
                                    $('#errorMessageShow').removeClass('d-none').text(data.otpMessage);
                                    setTimeout(function() {
                                        $('#errorMessageShow').addClass('d-none');
                                    }, 7000);
                                    // toastr.error('Verify is disabled if wrong OTP is provided 3 times', data.otpMessage);
                                    if($('#invalidCount').val() > 2){
                                        $("#verifyOtpModalBtn").attr("disabled", true);
                                        $("#regenerateOtp").attr("disabled", true);
                                    }
                                }

                            }
                        });

                    }else{
                        $('#verifyModal').modal('hide');
                        $('#successModal').modal('hide');
                    }


                }else{
                    $('#errorMessageShow').text("Please input OTP").removeClass('d-none');
                    setTimeout(function() {
                        $('#errorMessageShow').text("").addClass('d-none');
                    }, 7000);
                }

            });



            $('#request_type').on('change', function () {
                var issue_id = $('#request_type').val();
                var type = "wform";
                var request_for = "web";
                if (issue_id.length > 0) {
                    $.post('{{ url('customer-interface/issue-extra-form') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        request_for:request_for
                    }, function (data) {
                        onChangeRequestType(issue_id);
                        $('#issue_extra').html(data);

                    });
                } else {
                    $('#issue_extra').html(null);
                }
            });

            //issue attachment
            $('#request_type').on('change', function () {
                var issue_id = $('#request_type').val();
                var type = "wform";
                if (issue_id.length > 0) {
                    $.post('{{ url('CIissue-attachment') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        type: type,
                    }, function (data) {
                        $('#issue_attachment_item').html(data);
                    });
                } else {
                    //$('#attachment_item').html(null);
                }
            });

            $('#request_type_mobile').on('change', function () {
                var issue_id = $('#request_type_mobile').val();
                var type = "wform";
                if (issue_id.length > 0) {
                    $.post('{{ url('CIissue-attachment') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        type: type,
                    }, function (data) {
                        $('#issue_attachment_item_mobile').html(data);
                    });
                } else {
                    //$('#attachment_item').html(null);
                }
            });

            //  request_type_mobile issue_extra_mobile
            $('#request_type_mobile').on('change', function () {
                var issue_id = $('#request_type_mobile').val();
                var type = "wform";
                var request_for = "app";
                if (issue_id.length > 0) {
                    $.post('{{ url('customer-interface/issue-extra-form') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        request_for:request_for
                    }, function (data) {
                        $('#issue_extra_mobile').html(data);
                        onChangeRequestType(issue_id);
                    });
                } else {
                    $('#issue_extra_mobile').html(null);
                }
            });

            $(document).ready(function() {
                // On load issue type hide dependant fields
                let issue_id = "{{ $service_name->id }}";
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
                    // let issue_id = $('#request_type').val();
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
