@extends('BBL_CI.layouts.master')
@push('app-title')
    Send Back Tickets
@endpush
@section('content')
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
    <div class="d-block d-lg-none align-self-center">
        <form id="cifFormApp" action="{{ url('/CI/updateWForm') }}" method="POST">
            @csrf
            <div class="container-fluid custom-layout app_view">
                <div class="dropdown-wrapper mb-4 d-none">
                    <label class="mb-1 text-white"> {{ $type }} Number</label>
                    @if($product_type == 1)
                        <input type="text" value="{{$accNumber->mask_card_no}}" name="account_number" class="form-control" readonly />
                    @else
                        <input type="text" value="{{$accNumber->account_number}}" name="account_number" class="form-control" readonly />
                    @endif
                </div>
                <input type="hidden" name="request_mode" id="request_mode" value="app" />
                <div class="dropdown-wrapper mb-4 d-none">
                    <label class="mb-1 d-block text-white">Service Request</label>
                    <input type="text" value="{{$service_name->name}}" class="form-control" id="request_type_mobile" readonly />
                </div>
                <input type="hidden" name="product_type" value="{{ $product_type }}" />
                <input type="hidden" name="ci_token" value="{{ $ci_token }}" />
                <input type="hidden" name="reference_number" value="{{ $refNum }}" />
                <input type="hidden" name="w_form_type" value="{{$service_name->id}}" />
                <input type="hidden" name="otp_mode" value="1"/>
                <div class="dropdown-wrapper mb-4">
                    @include('BBL_BPID.partials.sendBack.send_back_ticket_details_app', ['issue_fields' => $fields, 'arraySingle' => $arraySingle])
                </div>
                <div id="issue_attachment_item">
                    @include('BBL_BPID.partials.sendBack.CIissue_attachment_item_app', ['attachment_item' => $attachment_item, 'send_back' => true])
                </div>
                @if($uploadedAttachment->count() > 0)
                    @foreach($uploadedAttachment as $row)
                        <div class="align-items-center pt-3">

                            @if($row->name)
                                <div class="mr-2 font-weight-bold" style="color: white">{{ $row->name }}:</div>
                            @else
                                <div class="mr-2 font-weight-bold">Bank attachment:</div>
                            @endif
                            <div class="">
                                <a href="#" style="color: #7cb5ec">{{$row->file_name}}</a>
                               <a href="{{ route('CI.attachment-download', $row->file_name) }}" style="color: #E5A812"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                    @endforeach
                @endif


                {{--<div class="form-check pt-4 otp_mode_wrap">
                    <input class="form-check-input pl-3 ml-3 checkme" type="checkbox" name="" value="1" data-toggle="tooltip" data-placement="buttom" title="Please Check">
                    <label class="form-check-label" for="checkme">I accept the <a href="#"  class="text-decoration-underline termConditionModalBtn">Terms and Conditions</a></label>
                </div>--}}
                <div class="">
                    <button class="btn btn-primary btn-sm mt-4 submit_btn form_condition w-100" type="submit" id="cifFormSubmitVerifyModal">SUBMIT</button>
                </div>
            </div>
            
        </form>
    </div>

    <!-- WEB -->
    <div class="container-fluid custom-layout" id="web_view">
        <div class="row">
            <div class="web-item-wrap">
                <h4 class="service-title d-none d-lg-block">
                    @if($request_type == getId('BPID'))
                        BPID Account Opening Form
                    @else
                        Auction Request Form
                    @endif
                </h4>
                <div class="d-none d-lg-block" style="min-height: 100vh;">
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
                                        <div class="card card-color d-none">
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
                                        <div class="card card-color d-none">
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

                                        @include('BBL_BPID.partials.sendBack.send_back_ticket_details', ['issue_fields' => $fields, 'arraySingle' => $arraySingle])

                                        <div id="issue_attachment_item">
                                            @include('BBL_BPID.partials.sendBack.CIissue_attachment_item', ['attachment_item' => $attachment_item, 'send_back' => true])
                                        </div>

                                        @if($uploadedAttachment->count() > 0)
                                            {{--<h6 class="pt-3 m-0" style="color: #333"> Attachment:</h6>--}}
                                            @foreach($uploadedAttachment as $row)
                                                <div class="d-flex align-items-center pt-3">

                                                    @if($row->name)
                                                        <div class="mr-2 font-weight-bold" style="color: white">{{ $row->name }}:</div>
                                                    @else
                                                        <div class="mr-2 font-weight-bold">Bank attachment:</div>
                                                    @endif

                                                    <div class="" >&nbsp;
                                                        <a href="#" style="color: #7cb5ec">{{$row->file_name}}</a>
                                                        <a href="{{ route('CI.attachment-download', $row->file_name) }}" style="color: #E5A812"><i class="fa fa-download"></i></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        {{--<div class="form-check pt-3 otp_mode_wrap">
                                            <input class="form-check-input pl-3 ml-3 checkme" type="checkbox" name="" value="1" data-toggle="tooltip" data-placement="buttom" title="Please Check">
                                            <label class="form-check-label" for="checkme">I accept the <a href="#"  class="text-decoration-underline termConditionModalBtn">Terms and Conditions</a></label>
                                        </div>--}}

                                        <div class="mt-3" style="text-align: right;">
                                            <button type="submit" class="btn btn-primary btn-sm form_condition w-100" id="formSubmitBtn">Submit</button>
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
                                <input type="hidden" class="request_type" name="request_type" value="{{$issue_id}}">

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
                            <div id="revese-timer" data-minute="1"></div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12 d-flex justify-content-between border-0 p-0 m-0">
                                <button class="btn btn-primary btn-sm mt-1 submit_btn verify_btn verify_btn_cancel sendBackCancel float-start" type="button" data-bs-dismiss="modal" id="cancelVarifyModal">CANCEL</button>
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

</div>

    @push('js')
        <script nonce="{{ app('csp_nonce') }}">
            window.SBT_CONFIG = {
                csrfToken: "{{ csrf_token() }}",
                cspNonce: "{{ app('csp_nonce') }}",
                refNum: "{{ $refNum }}",
                serviceNameId: "{{ $service_name->id }}",
                urls: {
                    attachmentRemover: "{{ route('CI.attachment.remover') }}",
                    otpVerify: "{{ url('CI/otp-verify') }}",
                    otpRegenerate: "{{ url('/CI/otp/re-generate') }}",
                    issueExtraForm: "{{ url('customer-interface/issue-extra-form') }}",
                    ciIssueAttachment: "{{ url('CIissue-attachment') }}",
                    issueConditional: "{{ url('CI/issue/conditional') }}",
                    issueDependantFields: "{{ url('CI/issue/dependant/fields') }}",
                    issueConditionalFields: "{{ url('CI/issue/conditional/fields') }}"
                }
            };
        </script>
        <script src="{{ URL::asset('public/BBL_BPID/js/send_back_ticket.js') }}" nonce="{{ app('csp_nonce') }}"></script>
    @endpush
@endsection