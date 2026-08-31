@extends('layouts.admin')
@section('content')
    <style type="text/css">
        .wordwrap {
            word-wrap: break-word !important;
            word-break: break-all !important;
            white-space: normal !important;
        }
        .nowrap {
            white-space: nowrap !important;
        }
        .blink_me {
            animation: blinker 3s linear infinite;
        }
        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
        .card-border{
            border: 1px solid #24a854;
        }
        .custom-card{
            border: 0;
        }
        .custom-card legend,
        .custom-card label{
            color: #1b7e3f;
            font-weight: 500;
            width: 65%;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default .select2-selection--multiple ,
        .custom-card input,
        .custom-card select {
            border: 1px solid #1b7e3f;

        }
        .custom-card input:focus,
        .custom-card input:focus{
            border-color: #1b7e3f;
            box-shadow: 0 0 0 .2rem rgba(0,140, 75, .25);
        }
        .custom-card select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image:
                linear-gradient(45deg, transparent 50%, white 50%),
                linear-gradient(135deg, white 50%, transparent 50%),
                radial-gradient(#1b7e3f 70%, transparent 72%);
            background-position:
                calc(100% - 17px) calc(1em + 2px),
                calc(100% - 12px) calc(1em + 2px),
                calc(100% - .4em) .5em;
            background-size:
                5px 5px,
                5px 5px,
                1.5em 1.5em;
            background-repeat: no-repeat;
        }
        .custom-card select:focus {
            background-image:
                linear-gradient(45deg, white 50%, transparent 50%),
                linear-gradient(135deg, transparent 50%, white 50%),
                radial-gradient(#1b7e3f 70%, transparent 72%);
            background-position:
                calc(100% - 12px) 1em,
                calc(100% - 17px) 1em,
                calc(100% - .4em) .5em;
            background-size:
                5px 5px,
                5px 5px,
                1.5em 1.5em;
            background-repeat: no-repeat;
            border-color: #1b7e3f;
            box-shadow: 0 0 0 .2rem rgba(0,140, 75, .25);
        }
        .card-border .form-group{
            display: flex;
            justify-content: space-between;
            align-items: center;
            white-space: nowrap;
        }
        .card-border .form-group label{
            margin-bottom: 0;
        }
        .custom-card .cwform{
            width: 97%;
        }
        .custom-card .ccomp {
            width: 97%;
        }
        .custom-card .status_old{
            width: 100%;
        }
        .custom-card .status_old .select2-container {
            width: 100%!important;
        }
        .select2-container .select2-selection--multiple {
            min-height: 40px;
            /* border: 1px solid #ccc; */
        }
        .customization-form .col-md-4{
            padding: 0.5rem;
        }
        .customization-form label, .date-report label{
            padding-top: 0rem;
        }
        .select2-search__field{
            width: 100% !important;
        }
        /* .date-report{

        } */
    </style>

    <div class="card-header card-border mb-4">
        <legend>Report</legend>
    </div>
    <div class="row">
    {!! Form::open(['method'=>'get', 'class'=>'form-horizontal', 'action' => ['ReportsController@index'] , 'enctype' => 'multipart/form-data']); !!}

    <div class="card custom-card mb-3 date-report">
        <div class="card-body card-border">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group m-0">
                        <label>Report Type</label>
                        {{ Form::select('report_type', $reportType, (!empty($searchDataForView["report_type"])) ? $searchDataForView["report_type"] : 'gen_report', ['class'=>'form-control report_type']) }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group m-0">
                        <label>Date From</label>
                        <input type="text" name="date_from" class="form-control dtPickerThreeCls readonly datePickerThreeFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off">
                        <input type="text" name="date_from" class="form-control dtPickerHistoryCls readonly datePickerHistoryFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off" disabled>
                        <input type="text" name="date_from" class="form-control dtPickerApiCls readonly datePickerApiFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off" disabled>
                        <input type="text" name="date_from" class="form-control dtPickerFiveCls readonly datePickerFiveFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off">
                        <input type="text" name="date_from" class="form-control dtPickerFourCls readonly datePickerFourFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group m-0">
                        <label>Date to</label>
                        <input type="text" name="date_to" class="form-control dtPickerThreeCls readonly datePickerThreeTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off">
                        <input type="text" name="date_to" class="form-control dtPickerHistoryCls readonly datePickerHistoryTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off" disabled>
                        <input type="text" name="date_to" class="form-control dtPickerApiCls readonly datePickerApiTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off" disabled>
                        <input type="text" name="date_to" class="form-control dtPickerFiveCls readonly datePickerFiveTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off">
                        <input type="text" name="date_to" class="form-control dtPickerFourCls readonly datePickerFourTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card customization-form">
        <div class="card-body card-border">
            <div class="row">


           {{-- {!! Form::open(['method'=>'get', 'class'=>'form-horizontal row', 'action' => ['ReportsController@index'] , 'enctype' => 'multipart/form-data']); !!}--}}
            {{--<div class="col-md-4">
                <div class="form-group">
                    <label>Report Type</label>
                    {{ Form::select('report_type', $reportType, (!empty($searchDataForView["report_type"])) ? $searchDataForView["report_type"] : 'gen_report', ['class'=>'form-control report_type']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Date From</label>
                    <input type="text" name="date_from" class="form-control dtPickerThreeCls readonly datePickerThreeFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off">
                    <input type="text" name="date_from" class="form-control dtPickerHistoryCls readonly datePickerHistoryFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off" disabled>
                    <input type="text" name="date_from" class="form-control dtPickerApiCls readonly datePickerApiFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off" disabled>
                    --}}{{-- <input type="text" name="date_from" class="form-control dtPickerFiveCls readonly datePickerFiveFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off"> --}}{{--
                    <input type="text" name="date_from" class="form-control dtPickerFourCls readonly datePickerFourFrom" style="display: none;" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Date to</label>
                    <input type="text" name="date_to" class="form-control dtPickerThreeCls readonly datePickerThreeTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off">
                    <input type="text" name="date_to" class="form-control dtPickerHistoryCls readonly datePickerHistoryTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off" disabled>
                    <input type="text" name="date_to" class="form-control dtPickerApiCls readonly datePickerApiTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off" disabled>
                    --}}{{-- <input type="text" name="date_to" class="form-control dtPickerFiveCls readonly datePickerFiveTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off"> --}}{{--
                    <input type="text" name="date_to" class="form-control dtPickerFourCls readonly datePickerFourTo" style="display: none;" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off" disabled>
                </div>
            </div>--}}

            <div class="col-md-4 usrws_lg_rprt_class">
                <div class="form-group">
                    <label>Log User Id</label>
                    <input type="text" name="user_id" class="form-control" placeholder="Log User Id" value="{{ $searchDataForView['user_id'] }}">
                </div>
            </div>
            <div class="col-md-4 gen_report_class">
                <div class="form-group">
                    <label>User Id</label>
                    <input type="text" name="curr_user_id" class="form-control curr_user" placeholder="User Id" value="{{ $searchDataForView['curr_user_id'] }}">
                </div>
            </div>
            @IF($isAdmin == true)
                <div class="col-md-4 dptws_lg_rprt_class">
                    <div class="form-group">
                        <label>Department</label>
                        {{ Form::select('department_id', [null=>'Select Department']+$allDepartmentData, (!empty($searchDataForView["department_id"])) ? $searchDataForView["department_id"] : 'wform', ['class'=>'form-control dptws_lg_rprt_class']) }}
                    </div>
                </div>
            @ENDIF
            <div class="col-md-4">
                <div class="form-group">
                    <label>Ticket No</label>
                    <input type="text" name="reference_number" class="form-control" placeholder="Ticket No" value="{{ $searchDataForView['reference_number'] }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Customer ID/Account No</label>
                    <input type="text" name="account_no" class="form-control" placeholder="Customer ID/Account Number" value="{{ $searchDataForView['account_no'] }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" name="mobile_number" class="form-control" placeholder="Mobile No" value="{{ $searchDataForView['mobile_number'] }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Product Type</label>
                    {{ Form::select('product_type', [null=>'Select Product'] + $allProductTypeData, (!empty($searchDataForView["product_type"])) ? $searchDataForView["product_type"] : '', ['class'=>'form-control']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Source</label>
                    {{ Form::select('source', [null=>'Select Source'] + $allSourceData, (!empty($searchDataForView["source"])) ? $searchDataForView["source"] : '', ['class'=>'form-control']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Form Type</label>
                    {{ Form::select('form_type', $formType, (!empty($searchDataForView["form_type"])) ? $searchDataForView["form_type"] : 'wform', ['class'=>'form-control form_type']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Type</label>
                    @php
                        if(!empty($searchDataForView["form_category"])){
                            $pB = $searchDataForView["form_category"];
                        } else {
                            $pB = [];
                        }
                    @endphp
                    <div class="cwform" style="display: none;">
                        <select name="form_category[]" class="form-control catwform form-control select2 status_cls select2" multiple="multiple">
                            {{--<option value="" >Please Select Type</option>--}}
                            @foreach($allWformUnitData as $key => $value)
                                <option value="{{ $key }}"
                                        @foreach($pB as $k => $val)
                                        @if ($key == $val)
                                        selected
                                    @endif
                                    @endforeach
                                >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ccomp" style="display: none;">
                        <select name="form_category[]" class="form-control catcomplaint" multiple="multiple">
                           {{-- <option value="">Please Select Type</option>--}}
                            @foreach($allComplaintUnitData as $key => $value)
                                <option value="{{ $key }}"
                                        @foreach($pB as $k => $val)
                                        @if ($key == $val)
                                        selected
                                    @endif
                                    @endforeach
                                >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cnonc" style="display: none;">
                        <select name="form_category[]" class="form-control catnoncustomer" disabled>
                            <option value="">Please Select Type</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 status_class">
                <div class="form-group">
                    @php
                        if(!empty($searchDataForView["status"])){
                            $pB = $searchDataForView["status"];
                        } else {
                            $pB = [];
                        }
                    @endphp
                    <label>Status</label>
                    <div class="status_old">
                        <select name="status[]" class="form-control select2 status_cls" size="width: 100%;" multiple="multiple">
                            {{--<option value="">Please Select Status</option>--}}
                            @foreach($allStatus as $key => $value)
                                <option value="{{ $key }}"
                                    @foreach($pB as $k => $val)
                                        @if ($key == $val)
                                            selected
                                        @endif
                                    @endforeach
                                >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="dms_report_class">
                        <select name="dms_status" class="form-control status_cls" size="width: 100%;">
                            <option value="">Please Select</option>
                            @foreach($dmsStatus as $key => $value)
                                <option value="{{ $key }}" @if ($searchDataForView["dms_status"] != null && $searchDataForView["dms_status"] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="col-md-4 api_report_class">
                <div class="form-group">
                    @php
                        if(!empty($searchDataForView["apiStatus"])){
                            $pB = $searchDataForView["apiStatus"];
                        } else {
                            $pB = [];
                        }
                    @endphp
                    <label>API Status</label>
                    <div class="api_report_class">
                        <select name="apiStatus[]" class="form-control status_cls select2" multiple="multiple">
                            {{--<option value="">Please Select</option>--}}
                            @foreach($updateAPIStatus as $key => $value)
                                <option value="{{ $key }}"
                                    @foreach($pB as $k => $val)
                                        @if ($key == $val)
                                            selected
                                        @endif
                                    @endforeach
                                >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Customer Number</label>
                    <input type="text" name="SIF_Number" class="form-control" placeholder="Customer Number" value="{{ $searchDataForView['SIF_Number'] }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Is Justified?</label>
                    {{ Form::select('is_justified', [''=>'All','1'=>'Yes','0'=>'No'], (isset($searchDataForView["is_justified"])) ? $searchDataForView["is_justified"] : '', ['class'=>'form-control justifiedcomplaint']) }}
                </div>
            </div>
            {{-- <div class="clearfix">&nbsp;</div> --}}
            {{--
            <div class="col-md-2">
                <div class="form-group">
                    <label class="radio-inline"	style="padding-left: 0;">
                        <input type="checkbox" name="escalation" class="i-checks" value="escalation" {{ $searchDataForView['escalation'] == 'escalation' ? 'checked' : '' }} > <strong>Escalation Report</strong>
                    </label>
                </div>
            </div>
            --}}
            <div class="col-md-4">
			    <div class="form-group ">
                    <label>&nbsp;</label><br>
			        <label class="radio-inline"	style="padding-left: 0;">
			        	<input type="hidden" name="date_type" class="create_date" value="create_date">
			            <input type="checkbox" name="date_type" class="i-checks action_date" value="action_date" {{ $searchDataForView['date_type'] == 'action_date' ? 'checked' : '' }} > <strong>Action Date</strong>
			        </label>
			        {{--
			        <label class="radio-inline"	style="padding-left: 0;">
			            <input type="radio" name="date_type" class="i-checks create_date" value="create_date" {{ $searchDataForView['date_type'] == 'create_date' ? 'checked' : '' }} > <strong>Create Date</strong>
			        </label>
			        --}}
			    </div>
			</div>

            <div class="col-md-12 mt-3">
                {{--
                <input type="hidden" name="date_type" class="date_type" value="{{ $searchDataForView['date_type']}}">
                --}}
                <button class="btn btn-primary" type="submit" onclick="overlay('show');"><i class="fa fa-search"></i> Search</button>
                <button class="btn btn-warning reset-btn" type="button" ><i class="fa fa-eraser" aria-hidden="true"></i> Reset</button>
                @IF(!empty($dataForView))
                    <button class="btn btn-success excel-exp" type="submit" name="export" value="export_to_excel"><i class="fa fa-file-excel-o"></i> Export to excel</button>
                    <span class="btn btn-link error blink _me" style="cursor:default;text-decoration:none;padding-left:0;"><i class="fa fa-arrow-left"></i> For detailed report press export to excel<span class="histmsg" style="display:none;"> (First 20,000 rows will be exported within your selected date range.)</span></span>
                @ENDIF
            </div>
            {{--{!! Form::close(); !!}--}}
            </div>
        </div>
    </div>

    {!! Form::close(); !!}
</div>
    <div class="card">
        <div class="card-body">
        @IF(empty($searchDataForView['report_type']) || ($searchDataForView['report_type'] == 'gen_report'))
            @include('Reports/gen_report')
        @ELSEIF($searchDataForView['report_type'] == 'usrws_lg_rprt')
            @include('Reports/usrws_lg_rprt')
        @ELSEIF($searchDataForView['report_type'] == 'dms_report')
            @include('Reports/dms_report')
        @ELSEIF($searchDataForView['report_type'] == 'hstry_report')
            @include('Reports/hstry_report')
        @ELSEIF($searchDataForView['report_type'] == 'complaint_closing_report')
            @include('Reports/complaint_closing_report')
        @ELSEIF($searchDataForView['report_type'] == 'sendback_report')
            @include('Reports/sendback_report')
        @ELSEIF($searchDataForView['report_type'] == 'ci_send_back_report')
            @include('Reports/ci_send_back_report')
        @ELSEIF($searchDataForView['report_type'] == 'ci_send_back_submit_report')
            @include('Reports/ci_send_back_submit_report')
        @ELSEIF($searchDataForView['report_type'] == 'ci_summary_report')
            @include('Reports/ci_summary_report')
        @ELSEIF($searchDataForView['report_type'] == 'dptws_lg_rprt')
            @include('Reports/usrws_lg_rprt')
        @ELSEIF($searchDataForView['report_type'] == 'hndlr_report')
            @include('Reports/hndlr_report')
        @ELSEIF($searchDataForView['report_type'] == 'api_update_report')
            @include('Reports/api_update_report')
        @ELSE
            <p class="text-center error"><strong>Report Not Available</strong></p>
        @ENDIF
        </div>
    </div>
    <div class="clearfix"><br><br></div>
    <small class="text-left"><strong>Script Execution Time (Sec.) : </strong> {{ !empty($processing_time) ? $processing_time : 'N/A' }}</small>
    <!-- Modal -->
    <div class="modal fade" id="excelWaitModalCenter" tabindex="-1" role="dialog" aria-labelledby="excelWaitModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Alert!!!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <i class="fa fa-spinner fa-spin fa-4x"></i>
            <p>Please wait for a moment! First 20,000 rows will be exported within your selected date range</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
{{-- @endsection
@section('extrajssection') --}}
    <script type="text/javascript">
    $(document).ready(function() {

        var customError = "<?php echo $customError; ?>";
        if(customError == 1){
            customAlert('Information','Please select a date.','red');
        }
        var form_type = $(".form_type").val();
        $(".justifiedcomplaint").prop('disabled',true);
        $(".justified"+form_type).prop('disabled',false);
        var report_type_history;
        var report_type = $(".report_type").val();
        var report_type_history = report_type;

        if (form_type === "wform") {
            $(".ccomp").hide();
            $(".cnonc").hide();
            $(".cwform").show();
            $(".dtPickerFiveCls").hide();
            $(".dtPickerFiveCls").prop('disabled',true);
            $(".dtPickerFiveCls").val('');
            $(".dtPickerFourCls").prop('disabled',true);
            $(".dtPickerFourCls").hide();
            if (report_type == 'hstry_report'){
                //$(".dtPickerThreeCls").hide();
                $(".dtPickerThreeCls").prop('disabled',true);
                $(".dtPickerHistoryCls").show();
                $(".dtPickerHistoryCls").prop('disabled',false);
            }else{
                $(".dtPickerHistoryCls").hide();
                $(".dtPickerHistoryCls").prop('disabled',true);
                $(".dtPickerThreeCls").show();
                $(".dtPickerThreeCls").prop('disabled',false);
            }
            $(".catwform").select2({
                 placeholder: "Please Select Type",
                height: "100%",
                width: "100%",
            });
        } else if (form_type === "complaint") {
            $(".cwform").hide();
            $(".cnonc").hide();
            $(".ccomp").show();
            $(".dtPickerThreeCls").hide();
            $(".dtPickerThreeCls").prop('disabled',true);
            $(".dtPickerThreeCls").val('');

            if (report_type == 'hstry_report'){
                $(".dtPickerFiveCls").hide();
                $(".dtPickerFiveCls").prop('disabled',true);
                $(".dtPickerFourCls").hide();
                $(".dtPickerFourCls").prop('disabled',true);
                $(".dtPickerHistoryCls").show();
                $(".dtPickerHistoryCls").prop('disabled',false);
            }else{
                $(".dtPickerHistoryCls").hide();
                $(".dtPickerHistoryCls").prop('disabled',true);
                // $(".dtPickerFiveCls").show();
                // $(".dtPickerFiveCls").prop('disabled',false);
                // $(".dtPickerFourCls").show();
                // $(".dtPickerFourCls").prop('disabled',false);
            }

            $(".catcomplaint").select2({
                placeholder: "Please Select Type",
                height: "100%",
                width: "100%",
            });
        } else {
            if (report_type == 'hstry_report'){
                $(".dtPickerThreeCls").hide();
                $(".dtPickerThreeCls").prop('disabled',true);
                $(".dtPickerHistoryCls").show();
                $(".dtPickerHistoryCls").prop('disabled',false);
            }else{
                $(".dtPickerHistoryCls").hide();
                $(".dtPickerHistoryCls").prop('disabled',true);
                $(".dtPickerThreeCls").show();
                $(".dtPickerThreeCls").prop('disabled',false);
            }
            $(".cwform").hide();
            $(".ccomp").hide();
            $(".cnonc").show();

            $(".dtPickerFiveCls").prop('disabled',true);
            $(".dtPickerFiveCls").hide();
            $(".dtPickerFiveCls").val('');
            $(".dtPickerFourCls").prop('disabled',true);
            $(".dtPickerFourCls").hide();
        }

        $(".form_type").on('change',function($e){
            var form_type = $(this).val();
            $(".justifiedcomplaint").prop('disabled',true);
            $(".justified"+form_type).prop('disabled',false);
            if (form_type === "wform") {
                $(".ccomp").hide();
                $(".cnonc").hide();
                $(".cwform").show();
                $(".dtPickerFiveCls").hide();
                if (report_type_history == 'hstry_report'){
                    $(".dtPickerThreeCls").hide();
                    $(".dtPickerThreeCls").prop('disabled',true);
                    $(".dtPickerHistoryCls").show();
                    $(".dtPickerHistoryCls").prop('disabled',false);
                    $(".dtPickerHistoryCls").val('');
                }else{
                    $(".dtPickerHistoryCls").hide();
                    $(".dtPickerHistoryCls").prop('disabled',true);
                    $(".dtPickerThreeCls").show();
                    $(".dtPickerThreeCls").prop('disabled',false);
                }
                $(".dtPickerFiveCls").prop('disabled',true);
                $(".dtPickerFiveCls").val('');
                $(".dtPickerFourCls").prop('disabled',true);
                $(".dtPickerFourCls").hide();
                $(".catwform").select2({
                    placeholder: "Please Select Type",
                    height: "100%",
                    width: "100%",
                });
            } else if (form_type === "complaint") {
                $(".cwform").hide();
                $(".cnonc").hide();
                $(".ccomp").show();
                $(".dtPickerThreeCls").hide();
                if (report_type_history == 'hstry_report'){
                    $(".dtPickerFourCls").hide();
                    $(".dtPickerFourCls").prop('disabled',true);
                    $(".dtPickerFiveCls").hide();
                    $(".dtPickerFiveCls").prop('disabled',true);
                    $(".dtPickerHistoryCls").show();
                    $(".dtPickerHistoryCls").prop('disabled',false);
                }else{
                    $(".dtPickerHistoryCls").hide();
                    $(".dtPickerHistoryCls").prop('disabled',true);
                    $(".dtPickerFiveCls").show();
                    $(".dtPickerFiveCls").prop('disabled',false);

                    // $(".dtPickerFourCls").show();
                    // $(".dtPickerFourCls").prop('disabled',false);
                }
                $(".dtPickerThreeCls").prop('disabled',true);
                $(".dtPickerThreeCls").val('');
                $(".catcomplaint").select2({
                    placeholder: "Please Select Type",
                    height: "100%",
                    width: "100%",
                });
            } else {
                if (report_type_history == 'hstry_report'){
                    $(".dtPickerThreeCls").hide();
                    $(".dtPickerThreeCls").prop('disabled',true);
                    $(".dtPickerHistoryCls").show();
                    $(".dtPickerHistoryCls").prop('disabled',false);
                }else{
                    $(".dtPickerHistoryCls").hide();
                    $(".dtPickerHistoryCls").prop('disabled',true);
                    $(".dtPickerThreeCls").show();
                    $(".dtPickerThreeCls").prop('disabled',false);
                }

                $(".dtPickerFiveCls").prop('disabled',true);
                $(".dtPickerFiveCls").hide();
                $(".dtPickerFiveCls").val('');
                $(".dtPickerFourCls").prop('disabled',true);
                $(".dtPickerFourCls").hide();
                $(".cwform").hide();
                $(".ccomp").hide();
                $(".cnonc").show();
            }
        });

        var report_type = $(".report_type").val();

        $(".usrws_lg_rprt_class").hide();
        $(".dms_report_class").hide();
        $(".api_report_class").hide();
        $(".status_class").show();
        $(".form_type option[value=complaint]").removeAttr('disabled', 'disabled');
        $(".form_type option[value=noncustomer]").removeAttr('disabled', 'disabled');
        $(".status_cls").prop('disabled',false);
        $(".dptws_lg_rprt_class").hide();
        $(".dptws_lg_rprt_class").prop('disabled',true);
        $("."+report_type+"_class").prop('disabled',false);
        $("."+report_type+"_class").show();
        $(".gen_report_class").hide();

        $('.action_date').iCheck('enable');

        if(report_type == 'hstry_report'){
            $(".status_class").hide();
            $(".status_cls").prop('disabled',true);
            $(".gen_report_class").show();
            $('.action_date').iCheck('check');
            $('.create_date').iCheck('disable');
            //$(".dtPickerThreeCls").hide();
            $(".dtPickerThreeCls").prop('disabled',true);
            $(".dtPickerHistoryCls").show();
            $(".dtPickerHistoryCls").prop('disabled',false);
        }
        if(report_type == 'api_update_report'){
            $(".status_class").hide();
            $(".api_report_class").show();
            $('.create_date').iCheck('check');
            $('.action_date').iCheck('disable');
            $(".form_type option[value=complaint]").attr('disabled', 'disabled');
            $(".form_type option[value=noncustomer]").attr('disabled', 'disabled');
            $(".dtPickerApiCls").show();
            $(".dtPickerApiCls").prop('disabled',false);
        }

        if(report_type == 'complain_report'){
            $(".status_old").show();
            // $(".status_cls").prop('disabled',true);
            $('.action_date').iCheck('check');
            $('.create_date').iCheck('disable');
            $('.select2').val('').trigger('change');
            $(".form_type option[value=complaint]").attr("disabled", true);
            $(".form_type option[value=noncustomer]").attr("disabled", true);
        }

        if(report_type == 'ci_send_back_report'){
            $(".status_old").show();
            // $(".status_cls").prop('disabled',true);
            $('.action_date').iCheck('check');
            $('.create_date').iCheck('disable');
            $('.select2').val('').trigger('change');
            $(".form_type option[value=complaint]").attr("disabled", true);
            $(".form_type option[value=noncustomer]").attr("disabled", true);
        }

        if(report_type == 'ci_send_back_submit_report'){
            $(".status_old").show();
            // $(".status_cls").prop('disabled',true);
            $('.action_date').iCheck('check');
            $('.create_date').iCheck('disable');
            $('.select2').val('').trigger('change');
            $(".form_type option[value=complaint]").attr("disabled", true);
            $(".form_type option[value=noncustomer]").attr("disabled", true);
        }

        if(report_type == 'ci_summary_report'){
            $(".status_old").show();
            // $(".status_cls").prop('disabled',true);
            $('.action_date').iCheck('check');
            $('.create_date').iCheck('disable');
            $('.select2').val('').trigger('change');
            $(".form_type option[value=complaint]").attr("disabled", true);
            $(".form_type option[value=noncustomer]").attr("disabled", true);
        }


        if(report_type == 'gen_report'){
            $(".gen_report_class").show();
            if (form_type === "complaint") {
                $(".dtPickerThreeCls").prop('disabled',true);
                $(".dtPickerThreeCls").hide();
                $(".dtPickerFiveCls").prop('disabled',false);
                $(".dtPickerFiveCls").show();
                $(".dtPickerThreeCls").val('');
                // $(".dtPickerFourCls").prop('disabled',false);
                // $(".dtPickerFourCls").show();
            } else {
                $(".dtPickerThreeCls").prop('disabled',false);
                $(".dtPickerThreeCls").show();
                $(".dtPickerFiveCls").prop('disabled',true);
                $(".dtPickerFiveCls").hide();
                $(".dtPickerFiveCls").val('');
                $(".dtPickerFourCls").prop('disabled',true);
                $(".dtPickerFourCls").hide();
            }
        }
        if(report_type == 'dms_report'){
            $(".status_old").hide();
            $(".gen_report_class").show();
            $(".dms_report_class").show();
            if (form_type === "complaint") {
                $(".dtPickerThreeCls").prop('disabled',true);
                $(".dtPickerThreeCls").hide();
                $(".dtPickerFiveCls").prop('disabled',false);
                $(".dtPickerFiveCls").show();
                $(".dtPickerThreeCls").val('');
                // $(".dtPickerFourCls").prop('disabled',false);
                // $(".dtPickerFourCls").show();
            } else {
                $(".dtPickerThreeCls").prop('disabled',false);
                $(".dtPickerThreeCls").show();
                $(".dtPickerFiveCls").prop('disabled',true);
                $(".dtPickerFiveCls").hide();
                $(".dtPickerFiveCls").val('');
                $(".dtPickerFourCls").prop('disabled',true);
                $(".dtPickerFourCls").hide();
            }
        }

        if(report_type == 'hndlr_report'){
            $(".gen_report_class").show();

            $(".curr_user").prop('disabled',true);
            $(".dtPickerThreeCls").prop('disabled',true);
            $(".dtPickerThreeCls").show();
            $(".dtPickerFourCls").prop('disabled',true);
            $(".dtPickerFiveCls").prop('disabled',true);
            $('.action_date').iCheck('disable');
            $('.create_date').iCheck('disable');
        } else {
            $(".curr_user").prop('disabled',false);
            if (form_type === "complaint") {
                if (report_type !== 'hstry_report'){
                    $(".dtPickerThreeCls").prop('disabled',true);
                    $(".dtPickerThreeCls").hide();
                    $(".dtPickerFiveCls").prop('disabled',false);
                    $(".dtPickerFiveCls").show();
                    $(".dtPickerThreeCls").val('');
                    // $(".dtPickerFourCls").prop('disabled',false);
                    // $(".dtPickerFourCls").show();
                }
            } else {
                $(".dtPickerThreeCls").prop('disabled',false);
                $(".dtPickerThreeCls").show();
                $(".dtPickerFiveCls").prop('disabled',true);
                $(".dtPickerFiveCls").hide();
                $(".dtPickerFiveCls").val('');
                $(".dtPickerFourCls").prop('disabled',true);
                $(".dtPickerFourCls").hide();
                if (report_type == 'hstry_report'){
                    $(".dtPickerThreeCls").prop('disabled',true);
                    $(".dtPickerThreeCls").hide();
                    $(".dtPickerThreeCls").val('');
                }
                if (report_type == 'api_update_report'){
                    $(".dtPickerThreeCls").prop('disabled',true);
                    $(".dtPickerThreeCls").hide();
                    $(".dtPickerThreeCls").val('');
                }
            }
        }

        if (report_type == 'dptws_lg_rprt') {
            $('.action_date').iCheck('disable');
            $('.create_date').iCheck('check');

            $(".curr_user").prop('disabled',false);

            $(".dtPickerFourCls").prop('disabled',false);
            $(".dtPickerFourCls").show();

            $(".dtPickerThreeCls").prop('disabled',true);
            $(".dtPickerThreeCls").hide();


        }
        if (report_type == 'usrws_lg_rprt') {
            $('.action_date').iCheck('disable');
            $('.create_date').iCheck('check');

            $(".curr_user").prop('disabled',false);

            $(".dtPickerFourCls").prop('disabled',false);
            $(".dtPickerFourCls").show();

            $(".dtPickerThreeCls").prop('disabled',true);
            $(".dtPickerFiveCls").prop('disabled',true);
            $(".dtPickerThreeCls").hide();
            $(".dtPickerFiveCls").hide();
        } else {
            // $(".date_type").val('action_date');
        }
        $('.action_date').iCheck('enable');
        if (report_type == 'dms_report'){
            $(".status_old").hide();
        }else {
            $(".status_old").show();
        }


        $(".report_type").on('change',function($e){
            var report_type = $(this).val();
            report_type_history = report_type;
            $(".dms_report_class").hide();
            $(".api_report_class").hide();
            $(".status_class").show();
            $(".status_cls").prop('disabled',false);
            $('.action_date').iCheck('enable');
            $('.create_date').iCheck('enable');
            $(".form_type option[value=complaint]").removeAttr('disabled', 'disabled');
            $(".form_type option[value=noncustomer]").removeAttr('disabled', 'disabled');

            $(".dptws_lg_rprt_class").prop('disabled',true);
            $(".usrws_lg_rprt_class").hide();
            $(".dptws_lg_rprt_class").hide();
            $(".gen_report_class").hide();

            $(".curr_user").prop('disabled',false);
            $(".dtPickerHistoryCls").hide();
            $(".dtPickerHistoryCls").prop('disabled',true);
            $(".dtPickerHistoryCls").val('');
            $(".dtPickerApiCls").hide();
            $(".dtPickerApiCls").prop('disabled',true);
            $(".dtPickerApiCls").val('');
            $(".dtPickerThreeCls").prop('disabled',false);
            $(".dtPickerThreeCls").show();

            $(".dtPickerFourCls").prop('disabled',true);
            $(".dtPickerFourCls").hide();
            $(".dtPickerFiveCls").hide();
            $(".dtPickerFiveCls").prop('disabled', true);

            $('.histmsg').hide();

            if(report_type == 'api_update_report'){
                $(".dtPickerApiCls").show();
                $(".dtPickerApiCls").prop('disabled',false);
                $(".dtPickerThreeCls").hide();
                $(".dtPickerThreeCls").prop('disabled',true);
                $(".status_class").hide();
                $(".api_report_class").show();
                $('.create_date').iCheck('check');
                $('.action_date').iCheck('disable');
                $('.select2').val('').trigger('change');
                $(".form_type option[value=complaint]").attr('disabled', 'disabled');
                $(".form_type option[value=noncustomer]").attr('disabled', 'disabled');
            }

            if(report_type == 'hstry_report'){
                $(".status_class").hide();
                $(".status_cls").prop('disabled',true);
                $(".gen_report_class").show();
                $('.action_date').iCheck('check');
                $('.create_date').iCheck('disable');
                $(".dtPickerHistoryCls").show();
                $(".dtPickerHistoryCls").prop('disabled',false);
                $(".dtPickerThreeCls").hide();
                $(".dtPickerThreeCls").prop('disabled',true);
            }
            if(report_type == 'gen_report'){
                $(".status_old").show();
                $(".gen_report_class").show();
            }
            if(report_type == 'dms_report'){
                $(".gen_report_class").show();
                $(".status_old").hide();
            }
            if(report_type == 'sendback_report'){
                $(".status_old").show();
               // $(".status_cls").prop('disabled',true);
                $('.action_date').iCheck('check');
                $('.create_date').iCheck('disable');
                $('.select2').val('').trigger('change');
            }
            if(report_type == 'ci_send_back_report'){
                $(".status_old").show();
               // $(".status_cls").prop('disabled',true);
                $('.action_date').iCheck('check');
                $('.create_date').iCheck('disable');
                $('.select2').val('').trigger('change');
                $(".form_type option[value=complaint]").attr("disabled", true);
                $(".form_type option[value=noncustomer]").attr("disabled", true);

            }
            if(report_type == 'complaint_closing_report'){
                $(".form_type option[value=wform]").attr("disabled", true);
                $(".form_type option[value=noncustomer]").attr("disabled", true);
                $(".form_type").val("complaint").change();
            }
            if(report_type == 'ci_send_back_submit_report'){
                $(".status_old").show();
               // $(".status_cls").prop('disabled',true);
                $('.action_date').iCheck('check');
                $('.create_date').iCheck('disable');
                $('.select2').val('').trigger('change');
                $(".form_type option[value=complaint]").attr("disabled", true);
                $(".form_type option[value=noncustomer]").attr("disabled", true);
            }
            if(report_type == 'ci_summary_report'){
                $(".status_old").show();
               // $(".status_cls").prop('disabled',true);
                $('.action_date').iCheck('check');
                $('.create_date').iCheck('disable');
                $('.select2').val('').trigger('change');
                $(".form_type option[value=complaint]").attr("disabled", true);
                $(".form_type option[value=noncustomer]").attr("disabled", true);

            }
            if(report_type == 'hndlr_report'){
                $(".status_old").show();
                $(".gen_report_class").show();
                $(".curr_user").prop('disabled',true);
                $(".dtPickerThreeCls").prop('disabled',true);
                $(".dtPickerFourCls").prop('disabled',true);
                $(".dtPickerFiveCls").prop('disabled',true);
                $('.action_date').iCheck('disable');
                $('.create_date').iCheck('disable');
                $('.select2').val('').trigger('change');
            } else {

            }
            if (report_type == 'dptws_lg_rprt') {
                $(".status_old").show();
                $('.action_date').iCheck('disable');
                $('.create_date').iCheck('check');

                $(".curr_user").prop('disabled',false);

                $(".dtPickerFourCls").prop('disabled',false);
                $(".dtPickerFourCls").show();

                $(".dtPickerThreeCls").prop('disabled',true);
                // $(".dtPickerFiveCls").prop('disabled',true);
                $(".dtPickerThreeCls").hide();
                // $(".dtPickerFiveCls").hide();
                $('.select2').val('').trigger('change');
            } else {

            }
            if (report_type == 'usrws_lg_rprt') {
                $(".status_old").show();
                $('.action_date').iCheck('disable');
                $('.create_date').iCheck('check');

                $(".curr_user").prop('disabled',false);

                $(".dtPickerFourCls").prop('disabled',false);
                $(".dtPickerFourCls").show();

                $(".dtPickerThreeCls").prop('disabled',true);
                $(".dtPickerThreeCls").hide();
                // $(".dtPickerFiveCls").hide();
                // $(".dtPickerFiveCls").prop('disabled', true);
                $('.select2').val('').trigger('change');
            } else {

            }
            $('.action_date').iCheck('enable');
            //$(".status_old").show();
            $("."+report_type+"_class").show();
            $("."+report_type+"_class").prop('disabled',false);
        });


        $(".reset-btn").click(function() {
            $(this).closest('form').find("input[type=text], textarea, select").val("");
            $('.form_type').val('wform');
            $('.report_type').val('gen_report');
            $('.select2').val('').trigger('change');
            $('.catwform').val('').trigger('change');
            $('.catcomplaint').val('').trigger('change');
            $('.catnoncustomer').val('').trigger('change');
            $('.action_date').iCheck('uncheck');
        });

        $(".excel-exp").click(function() {
            var report_type = $('.report_type').val();
            if(report_type == 'hstry_report'){
                $('.histmsg').show();
                $('#excelWaitModalCenter').modal({'backdrop':'static'});
            } else {
                $('.histmsg').hide();
            }
        });

        $(".action_date").on('ifUnchecked', function(event){
            var report_type = $('.report_type').val();
            if(report_type == 'hstry_report'){
                setTimeout(function(){
                    $('.action_date').iCheck('check');
                },0);
            }
        });


        $(".readonly").keydown(function(e){
            e.preventDefault();
        });
    });
    </script>
@endsection
