@php
    use Carbon\Carbon;
@endphp
@extends('layouts.admin')
@section('content')
<style type="text/css">
/*th, td {
    white-space: nowrap;
}*/

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
<form class="form-inline" id="handler-search">
    <br/>
    <div class="form-group fm-checkbox">
        <label class="radio-inline">
            <input type="radio" name="active_tab" class="i-checks form-type" value="wform" {{ $searchDataForView['active_tab'] == 'wform' ? 'checked' : '' }} > <strong>Service Request</strong>
        </label>
        <label class="radio-inline">
            <input type="radio" name="active_tab" class="i-checks form-type" value="complaint" {{ $searchDataForView['active_tab'] == 'complaint' ? 'checked' : '' }} > <strong>Complaint</strong>
        </label>
        <label class="radio-inline">
            <input type="radio" name="active_tab" class="i-checks form-type" value="{{ \App\Enum\IssueTypeEnum::NON_CUSTOMER }}" {{ $searchDataForView['active_tab'] == \App\Enum\IssueTypeEnum::NON_CUSTOMER ? 'checked' : '' }} > <strong>Non Customer</strong>
        </label>
    </div>
    <div class="clearfix">&nbsp;</div>
    <div class="form-group">
      @inject('allServiceCategory','App\Services\UtilService')
            @php                        
              $serviceCategoryResult = $allServiceCategory->getServiceCategoryByService($searchDataForView['active_tab']);              
              $pBServiceCat = $searchDataForView['service_category'];
            @endphp
      
            <select class="form-control wFormType" style="width: 200px" name="service_category" id="service_category">
              <option value="">Select Category</option>
              @foreach($serviceCategoryResult as $allSerCategory)
                @php
                $selectedServiceCat = "";
                if($pBServiceCat == $allSerCategory->id) {
                  $selectedServiceCat = "selected";
                }
                @endphp
                <option value="{{ $allSerCategory->id }}" {{$selectedServiceCat}}> {{ $allSerCategory->name }} </option>
              @endforeach
            </select>
    </div>    
    <div class="form-group">
        <select class="form-control wFormType" name="service_type" style="width: 200px" id="request_type">
              <option value="">Select Service</option>
        </select>
    </div>
    <div class="form-group">
        <input type="text" name="date_from" class="form-control datePicker" placeholder="Log Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off">
    </div>
    <div class="form-group">
        <input type="text" name="date_to" class="form-control datePicker" placeholder="Log Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off">
    </div>

    <input type="hidden" name="cmmn_pgntion" value="{{ $searchDataForView['cmmn_pgntion'] }}">
    <input type="hidden" name="cmmn_search" value="{{ $searchDataForView['cmmn_search'] }}">

    <button type="submit" class="btn btn-success" style="margin-bottom: 0px;"><i class="fa fa-search"></i> <strong>Find</strong></button>
    <!-- <button type="reset" class="btn btn-warning btn-3d auth-btn">Reset</button>  -->
</form>
<div class="error">{{ $errors->first('account_type') }}</div>
<div class="clearfix">&nbsp;</div>
<?php
$wFormActBtn = " active";
$compActBtn = "";
$wFormActTab = " in active";
$compActTab = "";

if ($searchDataForView['active_tab'] == "wform") {
    $wFormActBtn = " active";
    $compActBtn = "";
    $wFormActTab = " in active";
    $compActTab = "";
} elseif ($searchDataForView['active_tab'] == "complaint") {
    $wFormActBtn = "";
    $compActBtn = " active";
    $wFormActTab = "";
    $compActTab = " in active";
}

$searchDataForView['viewFrom'] = 'handler';
$getUrlQuery = "";

$forwardTime = (!empty($settingsData->forward_time)) ? $settingsData->forward_time : 10;
$blinkTime = (!empty($settingsData->sla_blink)) ? $settingsData->sla_blink : 5;

?>
@inject('workflow_list','App\Services\WorkFlowService')
@inject('flow_type','App\Services\WorkFlowService')

<table class="table table-striped w-auto bordered" style="margin-bottom:0;">
    <colgroup>
        <col width="10%">
        <col width="90%">
    </colgroup>
    <tr>
        <td style="padding-top:10px; padding-bottom:10px;">
            <select class="form-control commonPagination" name="pagination">
                <option {{($searchDataForView['cmmn_pgntion'] == 15)? 'selected':'' }} value="15">15</option>
                <option {{($searchDataForView['cmmn_pgntion'] == 25)? 'selected':'' }} value="25">25</option>
                <option {{($searchDataForView['cmmn_pgntion'] == 50)? 'selected':'' }} value="50">50</option>
                <option {{($searchDataForView['cmmn_pgntion'] == 75)? 'selected':'' }} value="75">75</option>
                <option {{($searchDataForView['cmmn_pgntion'] == 100)? 'selected':'' }} value="100">100</option>
            </select>
        </td>
        <td style="padding-top:10px; padding-bottom:10px;">
            <div class="input-group">
                <input class="form-control commonSearchBar" name="common_search" value="{{$searchDataForView['cmmn_search']}}" type="text" placeholder="Ticket Number/ Account Number/ Customer Name/ Product type/ Service Type/ Log date/ Log time/ Status/ Logger/ Last Worked by">
                <span class="input-group-btn"> 
                    <button class="btn btn-default btnSearch" type="button"><span class="fa fa-search"></span></button> 
                </span>
            </div>
        </td>
    </tr>
</table>
<?php
$currentUrl = url('/Supports/handler/');
$orderByDefIcon = 'fa fa-sort';
$orderByIcon = 'fa fa-sort';

$existingOrderBy = (!empty($_GET['orderby'])) ? substr($_GET['orderby'], strpos($_GET['orderby'],'-')+1) : 'reference.reference_number';
if (!empty($_GET)) {
    $currentOrderBy = 'DESC-';
    if (!empty($_GET['orderby'])) {
        $orderByArr  = explode('-', $_GET['orderby']);
        $orderName = (!empty($orderByArr[0])) ? $orderByArr[0] : 'DESC';
        $columnsName = (!empty($orderByArr[1])) ? $orderByArr[1] : 'reference.reference_number';

        if ($orderName == 'ASC') {
            $orderByIcon = 'fa fa-sort-asc';
            $orderName = 'DESC';
        } else {
            $orderByIcon = 'fa fa-sort-desc';
            $orderName = 'ASC';
        }
        $currentOrderBy = $orderName.'-';
        unset($_GET['orderby']);
    }
    $_GET['orderby'] = $currentOrderBy;

    $currentGetData = http_build_query($_GET);
    $currentUrl .= '?'.$currentGetData;

} else {
    $orderName = 'DESC';
    $currentOrderBy = $orderName.'-';
    $currentUrl .= '?orderby='.$currentOrderBy;
}
?>

@IF($searchDataForView['active_tab'] == "wform")
    <div class="table-responsive" id="handlerid">
        <table class="table table-striped w-auto bordered">
            <thead>
            <tr style="background-color: #DFF0D8">
                <th class="text-center"><a href="{{$currentUrl.'reference.reference_number'}}"><i class="{{($existingOrderBy == 'reference.reference_number') ? $orderByIcon:$orderByDefIcon }}"></i> Ticket No </a></th>
                <th class="text-center"><a href="{{$currentUrl.'w_form.account_number'}}"><i class="{{($existingOrderBy == 'w_form.account_number') ? $orderByIcon:$orderByDefIcon }}"></i> Account Number</a></th>
                <th class="text-center"><a href="{{$currentUrl.'w_form.customer_name'}}"><i class="{{($existingOrderBy == 'w_form.customer_name') ? $orderByIcon:$orderByDefIcon }}"></i> Customer Name</a></th>
                <th class="text-center"><a href="{{$currentUrl.'w_form.product_type'}}"><i class="{{($existingOrderBy == 'w_form.product_type') ? $orderByIcon:$orderByDefIcon }}"></i> Product Type</a></th>
                <th class="text-center"><a href="{{$currentUrl.'unit_items.name'}}"><i class="{{($existingOrderBy == 'unit_items.name') ? $orderByIcon:$orderByDefIcon }}"></i> Service Type</a></th>
                <th class="text-center"><a href="{{$currentUrl.'reference.date'}}"><i class="{{($existingOrderBy == 'reference.date') ? $orderByIcon:$orderByDefIcon }}"></i> Log Date </a></th>
                <th class="text-center"><a href="{{$currentUrl.'w_form.time_and_ext'}}"><i class="{{($existingOrderBy == 'w_form.time_and_ext') ? $orderByIcon:$orderByDefIcon }}"></i> Log Time </a></th>
                <th class="text-center">In Date</th>
                <th class="text-center">In Time</th>
                <th class="text-center">Duration (H:M)</th>
                <th class="text-center">SLA Time (M)</th>
                <th class="text-center"><a href="{{$currentUrl.'reference.form_status'}}"><i class="{{($existingOrderBy == 'reference.form_status') ? $orderByIcon:$orderByDefIcon }}"></i> Status</a></th>
                <th class="text-center"><a href="{{$currentUrl.'reference.created_by'}}"><i class="{{($existingOrderBy == 'reference.created_by') ? $orderByIcon:$orderByDefIcon }}"></i> Logger</a></th>
                <th class="text-center">Last Worked by</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
			<?php //prd($wFormData); ?>
            @IF(!empty($wFormData['data']))
                @FOREACH($wFormData['data'] as $data)
                    @php

                        $commentDatas = getCommentsInDateTimeRef($data['reference_number']);
                        $data['last_comment'] = $commentDatas['last_comment'];
                        $data['in_date_time'] = $commentDatas['in_date_time'];

                        $flow_type_name = $flow_type->getFlowTypeCheck($data['reference_number']);
                        if(empty($flow_type_name))
                            {
                                $flow_type_name ='';
                            }
                    @endphp
                    @if($flow_type_name==\App\Enum\FlowEnum::REGULAR)
                    @php $work = $workflow_list->workflowStage($data['reference_number']);

                    $sla_user=false;
                    if(\Illuminate\Support\Facades\Auth::user()->hasRole([\App\Enum\RoleEnum::LOGGER,\App\Enum\RoleEnum::EXECUTIVE])){
                        $sla_user=true;
                    }
                    $touch = $work['touch'];
                    $sla = $work['sla'];
                    $hold = $work['hold'];
                    $attach = $work['attach'];
                    $attach_item = $work['attach_item'];
                    @endphp
                    @else
                        @php
                            $sla_user=false;
                            $touch='';
                            $sla ='';
                             $hold ='';
                             $attach='';
                            $attach_item ='';
                        @endphp
                    @endif
                    <?php
                    $form_status = $data['form_status'];
                    if($form_status == 8 || $form_status == 0 || $form_status == null) {
                        $status = "Pending";
                    } else if ($form_status == 11) {
                        $status = "Close";
                    } else if ($form_status == -1) {
                        $status = "Reject";
                    } else if ($form_status == 10) {
                        $status = "Hold";
                    }else{
                      $status = "Wip";
                    }

                    $acces_d = date("d-m-Y h:i:s A", $data['date'] );
                    if($form_status != 0 && !empty($data['access_date'])) { // changed.....
                        $acces_d = date("d-m-Y h:i:s A", $data['access_date'] );
                    }
                    $acces_date = strtotime($acces_d." +3 days");

                    $current_date = date("d-m-Y h:i:s A");
                    $last_date = strtotime($current_date);

					/*
                    $styleColor = "";
                    if( $acces_date > $last_date){
                        $styleColor = "";
                    } else {
                        $styleColor = "style='color:#FF0000;'";
                    }
					*/
                    /*$lastAccessDate = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d')  : $data['UNXTIME'];
                    $lastAccessTime = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('H:i:s')  : $data['time_and_ext'];*/

                    $lastAccessDayHour = 0;
                    $lastAccessDayMinutes = 0;
                    $lastAccessDaySeconds = 0;

                    $todaysDayHour = 0;
                    $todaysDayMinutes = 0;
                    $todaysDaySeconds = 0;

					/*$sqlFormStatusTime = \Illuminate\Support\Facades\DB::table('comments')
					->where('comments.reference_number',$data['reference_number'])
					->where('comments.isapproved',1)
					->orderBy('comments.time','DESC')
					->pluck('time')
					->take(1)
					->toArray();

					$sqlFormQueueInTime = 0;
					foreach ($sqlFormStatusTime as $value) {
						$sqlFormQueueInTime = $value;
					}
                    */
                    $sqlFormQueueInTime = 0;
                    if (!empty($data['in_date_time'])) {
                        $sqlFormQueueInTime = $data['in_date_time']['time'];
                    }
					$isSendBack = (!empty($data['in_date_time']))? $data['in_date_time']['issendback']:0;

                    /*
                    $lastAccessDate = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d');
                    $lastAccessTime = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('H:i:s');
                    $lastAccessDateTime = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d H:i:s');
                    */

                    $lastAccessDate = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('Y-m-d')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d');
                    $lastAccessTime = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('H:i:s');
                    $lastAccessDateTime = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d H:i:s');


                    // $totalWorkingDaysOnThisReq = (!empty($data['in_date_time'])) ? $data['in_date_time']['total_working_days2']  : $data['total_working_days'] ;
                    if (!empty($data['in_date_time'])) {
                        $totalWorkingDaysOnThisReq = getWorkingDaysRef(date('Y-m-d',$data['in_date_time']['time']));
                    } else {
                        $totalWorkingDaysOnThisReq = getWorkingDaysRef(date('Y-m-d',$data['date']));
                    }

                    $totalWorkingHoursOnThisReq = (!empty($totalWorkingDaysOnThisReq)) ? $totalWorkingDaysOnThisReq * 8 : 0;


                    $lastAccessDateForCalc = str_replace('-', '', $lastAccessDate);
                    $lastAccessDateTimeNumb = str_replace("-", "", str_replace(" ", "", str_replace(":", "", $lastAccessDateTime)));


                    $lastAccessDateForCalcMin = $lastAccessDateForCalc.$dataForView['office_from'];
                    $lastAccessDateForCalcMax = $lastAccessDateForCalc.$dataForView['office_to'];


                    date_default_timezone_set('Asia/Dhaka');
                    $todaysDateCalc = date("Ymd");
                    $todaysDateTimeCalc = date("YmdHis");
                    $todaysDateCalcMin = $todaysDateCalc.$dataForView['office_from'];
                    $todaysDateCalcMax = $todaysDateCalc.$dataForView['office_to'];

                    $todaysDateTime = date('Y-m-d H:i:s');
                    $todaysDate = date('Y-m-d');

                    if (!empty($workingDays[$lastAccessDate])) {
                        if (($lastAccessDateTimeNumb >= $lastAccessDateForCalcMin && $lastAccessDateTimeNumb <= $lastAccessDateForCalcMax)) {
                            $lastAccessDateTimeObj = new DateTime($lastAccessDateTime);

                            if ($lastAccessDate != $todaysDate) {
                                $lastAccessDateLastObj = new DateTime($lastAccessDate.' '.$dataForView['office_to_str']);
                            } else {
                                
                                   
                               
                                if ($todaysDateTimeCalc >= $todaysDateCalcMin && $todaysDateTimeCalc <= $todaysDateCalcMax) {
                                    $lastAccessDateLastObj = new DateTime($todaysDateTime);
                                } else {
                                    $lastAccessDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_to_str']);
                                }
                            }

                            $interval = $lastAccessDateTimeObj->diff($lastAccessDateLastObj);

                            $lastAccessDayHour = $interval->format('%h');
                            $lastAccessDayMinutes = $interval->format('%i');
                            $lastAccessDaySeconds = $interval->format('%s');
                        }
                    }
                    if(!empty($workingDays[$todaysDate])) {
                        if (($todaysDateTimeCalc >= $todaysDateCalcMin && $todaysDateTimeCalc <= $todaysDateCalcMax) && ($lastAccessDate != $todaysDate)) {
                            $todaysDateTimeObj = new DateTime($todaysDateTime);
                            $todaysDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_from_str']);

                            $interval = $todaysDateTimeObj->diff($todaysDateLastObj);

                            $todaysDayHour = $interval->format('%h');
                            $todaysDayMinutes = $interval->format('%i');
                            $todaysDaySeconds = $interval->format('%s');
                        } elseif (($todaysDateTimeCalc > $todaysDateCalcMax) && ($lastAccessDate != $todaysDate)) {
                            $todaysDateTimeObj = new DateTime($todaysDate.' '.$dataForView['office_to_str']);
                            $todaysDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_from_str']);

                            $interval = $todaysDateTimeObj->diff($todaysDateLastObj);

                            $todaysDayHour = $interval->format('%h');
                            $todaysDayMinutes = $interval->format('%i');
                            $todaysDaySeconds = $interval->format('%s');
                        }
                    }



                    $totalHoursOnThisQueue = $lastAccessDayHour + $totalWorkingHoursOnThisReq + $todaysDayHour;
                    $totalMinutesOnThisQueue = $lastAccessDayMinutes + $todaysDayMinutes;
                    $totalSecondsOnThisQueue = $lastAccessDaySeconds + $todaysDaySeconds;

                    $queueDurationInMinutes = ($totalHoursOnThisQueue * 60) + $totalMinutesOnThisQueue;

                    $totalHoursOnThisQueue = sprintf("%02d", $totalHoursOnThisQueue);
                    $totalMinutesOnThisQueue = sprintf("%02d", $totalMinutesOnThisQueue);
                    $totalSecondsOnThisQueue = sprintf("%02d", $totalSecondsOnThisQueue);

                    $queue_duration = $totalHoursOnThisQueue.':'.$totalMinutesOnThisQueue;
                    $blink = issue_breach($data['reference_number'],$queueDurationInMinutes);


                    $issue_flow_type = $data['flow_type'];
                    $sla_maker = $data['sla_maker'];
                    $sla_checker = $data['sla_checker'];
					$highPriority = trim($data['priority']);

                    $styleColor = "";
                    $QueueSLATime = "";
                    if ($issue_flow_type == "regular") {
                        if ($data['unit_id'] == 2) {
                            $QueueSLATime = $sla_checker;
                            $sla_checker_blink = $sla_checker - $blinkTime;
                            if (($queueDurationInMinutes > $sla_checker_blink) && ($queueDurationInMinutes < $sla_checker)) {
                                $styleColor = "style='font-weight:bold;color:#008000;animation: blinker 1s linear infinite;'";
                            } elseif ($queueDurationInMinutes > $sla_checker) {
                                $styleColor = "style='font-weight:bold;color:#FF0000;'";
                            }
							if($isSendBack > 0){
								$styleColor = "style='font-weight:bold;color:#0000FF;'";
							}
							if($highPriority == "High"){
								$styleColor = "style='font-weight:bold;color:#FFA500;'";
							}
                        } else {
                            $QueueSLATime = "$sla_maker";
                            $sla_maker_blink = $sla_maker - $blinkTime;
                            if (($queueDurationInMinutes > $sla_maker_blink) && ($queueDurationInMinutes < $sla_maker_blink)) {
                                $styleColor = "style='font-weight:bold;color:#008000;animation: blinker 1s linear infinite;'";
                            } elseif ($queueDurationInMinutes > $sla_maker) {
                                $styleColor = "style='font-weight:bold;color:#FF0000;'";
                            }
							if($isSendBack > 0){
								$styleColor = "style='font-weight:bold;color:#0000FF;'";
							}
							if($highPriority == "High"){
								$styleColor = "style='font-weight:bold;color:#FFA500;'";
							}
                        }

                    } else {
                        $sla_forward_blink = $forwardTime - $blinkTime;
                        /* For Forward and other */
                        if (($queueDurationInMinutes > $sla_forward_blink) && ($queueDurationInMinutes < $forwardTime)) {
                            $styleColor = "style='font-weight:bold;color:#008000;animation: blinker 1s linear infinite;'";
                        } elseif ($queueDurationInMinutes > $forwardTime) {
                            $styleColor = "style='font-weight:bold;color:#FF0000;'";
                        }
						if($highPriority == "High"){
							$styleColor = "style='font-weight:bold;color:#FFA500;'";
						}
                    }
                    ?>

                    <tr>
						<?php
						/*
						$styleColor = '';
						if($sla_user==true && $queueDurationInMinutes > $sla){
							$styleColor = "style='font-weight:bold;color:#FF0000;'";
						}
						if($blink==true){
							$styleColor = "style='font-weight:bold;color:#008000;'";
						}
						if($isSendBack > 0){
							$styleColor = "style='font-weight:bold;color:#0000FF;'";
						}
						*/
						?>

                        <td {!! $styleColor !!} class="text-center">
                            <?php
                            $detailsUrl = url('/Supports/WFormDetails/'.encrypt($data['reference_number']));

                            $searchDataForView['qd'] = encrypt($queueDurationInMinutes);
                            if (!empty($searchDataForView)) {
                                $getUrlQuery = http_build_query($searchDataForView);
                                $detailsUrl .= '?'.$getUrlQuery;
                            }
                            ?>
                            <a href="{{ $detailsUrl }}">{{ $data['reference_number'] }}</a>
                        </td>
                        <td {!! $styleColor !!} class="text-center">{{ $data['account_number'] }}</td>
                        <td {!! $styleColor !!} class="text-left">{{ $data['customer_name'] }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ (!empty($data['product_type'])) ? $data['product_type'] : $data['product_type_ext'] }}</td>
                        <td {!! $styleColor !!} class="text-left">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['w_form_type'] }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ Carbon::createFromTimestamp($data['date'])->format('Y-m-d') }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $data['time_and_ext'] }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($sqlFormQueueInTime)->format('Y-m-d') }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($sqlFormQueueInTime)->format('h:i:s a') }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $queue_duration }}</td>

                        <td {!! $styleColor !!} class="text-center">{{ $QueueSLATime }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $status }}</td>
                        <td {!! $styleColor !!} class="text-center">
                            {{ $data['created_by'] }}
                        </td>


                        <td {!! $styleColor !!} class="text-center">

                            @IF(!empty($data['access_by']))
                                {{$data['access_by']}}
                            @ELSE
                                <?php
                                $lastAccess = "";
                                if (!empty($data['last_comment'])) {
                                    $lastAccess = $data['last_comment']['user_id'];
                                }
                                ?>
                                @IF(!empty($lastAccess))
                                    {{$lastAccess}}
                                @ELSE
                                    {{$data['created_by']}}
                                @ENDIF
                            @ENDIF

                        </td>


                        <td {!! $styleColor !!} class="text-center">
                            @IF($form_status == 10)
                                <p class="no-padding-margin">Hold by <strong>{{$data['access_by']}}</strong></p>
                                @ability('superadmin,admin','revokeAssignedRequest')
                                    <?php
                                    $searchUrl = url('/Supports/unassign/'.encrypt($data['reference_number']).'?reqFrom=unhold');
                                    if (!empty($searchDataForView)) {
                                        $searchUrl .= '&'.$getUrlQuery;
                                    }
                                    ?>
                                    <a href="{{$searchUrl}}" style="color:red">Un-Hold</a>
                                @endability

                            @ELSEIF($form_status != 11)
                                @IF(!empty($data['access_by']))
                                    <p class="no-padding-margin">Assigned to <strong>{{$data['access_by']}}</strong></p>
                                    <!--  -->
                                    @ability('superadmin,admin','revokeAssignedRequest')
                                    <?php
                                    $searchUrl = url('/Supports/unassign/'.encrypt($data['reference_number']));
                                    if (!empty($searchDataForView)) {
                                        $searchUrl .= '?'.$getUrlQuery;
                                    }
                                    ?>
                                    <a href="{{$searchUrl}}" style="color:red">Un-Assign</a>
                                    @endability
                                @ELSE
                                    <?php /* $searchUrl = url('/Supports/assign/'.encrypt($data['reference_number'])); if (!empty($searchDataForView)) {$searchUrl .= '?'.$getUrlQuery; } <a href="{{$searchUrl}}">Assign</a> */ ?>
                                @ENDIF
                            @ENDIF
                        </td>
                    </tr>


                @ENDFOREACH

            @ENDIF


            </tbody>

             <tfoot>
                @IF(!empty($wFormDataObj))
                @IF($wFormDataObj->total() > $wFormDataObj->perPage())
                    <tr><td class="text-right vcenter no-padding-margin-tb" colspan="14">{{ $wFormDataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
                @ENDIF
                @ENDIF
            </tfoot>
        </table>
    </div>
@ELSEIF($searchDataForView['active_tab'] == "complaint")
    <div class="table-responsive" id="handlerid">
        <table class="table table-striped w-auto bordered">
            <thead>
                <tr style="background-color: #DFF0D8">
                    <th class="text-center"><a href="{{$currentUrl.'reference.reference_number'}}"><i class="{{($existingOrderBy == 'reference.reference_number') ? $orderByIcon:$orderByDefIcon }}"></i> Ticket No </a></th>
                    <th class="text-center"><a href="{{$currentUrl.'complaint.account_number'}}"><i class="{{($existingOrderBy == 'complaint.account_number') ? $orderByIcon:$orderByDefIcon }}"></i> Account Number</a></th>
                    <th class="text-center"><a href="{{$currentUrl.'complaint.customer_name'}}"><i class="{{($existingOrderBy == 'complaint.customer_name') ? $orderByIcon:$orderByDefIcon }}"></i> Customer Name</a></th>
                    <th class="text-center"><a href="{{$currentUrl.'complaint.product_type'}}"><i class="{{($existingOrderBy == 'complaint.product_type') ? $orderByIcon:$orderByDefIcon }}"></i> Product Type</a></th>
                    <th class="text-center"><a href="{{$currentUrl.'unit_items.name'}}"><i class="{{($existingOrderBy == 'unit_items.name') ? $orderByIcon:$orderByDefIcon }}"></i> Complaint Type</a></th>
                    <th class="text-center"><a href="{{$currentUrl.'reference.date'}}"><i class="{{($existingOrderBy == 'reference.date') ? $orderByIcon:$orderByDefIcon }}"></i> Log Date </a></th>
                    <th class="text-center"><a href="{{$currentUrl.'complaint.time_and_ext'}}"><i class="{{($existingOrderBy == 'complaint.time_and_ext') ? $orderByIcon:$orderByDefIcon }}"></i> Log Time </a></th>
                    <th class="text-center">In Date</th>
                    <th class="text-center">In Time</th>
                    <th class="text-center">Duration (H:M)</th>
                    <th class="text-center">SLA Time (M)</th>
                    <th class="text-center"><a href="{{$currentUrl.'reference.form_status'}}"><i class="{{($existingOrderBy == 'reference.form_status') ? $orderByIcon:$orderByDefIcon }}"></i> Status</a></th>
                    <th class="text-center"><a href="{{$currentUrl.'reference.created_by'}}"><i class="{{($existingOrderBy == 'reference.created_by') ? $orderByIcon:$orderByDefIcon }}"></i> Logger</a></th>
                    <th class="text-center">Last Worked by</th>
                    <th class="text-center">Action</th>
                </tr>
          
            </thead>
            <tbody>

            <?php //prd($complaintData); ?>
            @IF(!empty($complaintData['data']))

                @FOREACH($complaintData['data'] as $data)
                    @php

                        $commentDatas = getCommentsInDateTimeRef($data['reference_number']);
                        $data['last_comment'] = $commentDatas['last_comment'];
                        $data['in_date_time'] = $commentDatas['in_date_time'];

                        $flow_type_name = $flow_type->getFlowTypeCheck($data['reference_number']);

                    @endphp
                    @if($flow_type_name==\App\Enum\FlowEnum::REGULAR)
                    @php $work = $workflow_list->workflowStage($data['reference_number']);

                    $sla_user=false;
                    if(\Illuminate\Support\Facades\Auth::user()->hasRole([\App\Enum\RoleEnum::LOGGER,\App\Enum\RoleEnum::EXECUTIVE])){
                        $sla_user=true;
                    }
                    $touch = $work['touch'];
                    $sla = $work['sla'];
                    $hold = $work['hold'];
                    $attach = $work['attach'];
                    $attach_item = $work['attach_item'];
                    @endphp
                    @else
                         @php
                        $sla_user=false;
                        $touch='';
                        $sla ='';
                         $hold ='';
                         $attach='';
                        $attach_item ='';
                         @endphp
                    @endif
                    <?php
                    $form_status = $data['form_status'];
                    if($form_status == 8 || $form_status == 0 || $form_status == null) {
                        $status = "Pending";
                    } else if ($form_status == 11) {
                        $status = "Close";
                    } else if ($form_status == -1) {
                        $status = "Reject";
                    } else if ($form_status == 10) {
                        $status = "Hold";
                    } else{
                      $status = "Wip";
                    }


                    $acces_d = date("d-m-Y h:i:s A", $data['date'] );
                    if($form_status != 0 && !empty($data['access_date'])) { // changed.....
                        $acces_d = date("d-m-Y h:i:s A", $data['access_date'] );
                    }
                    $acces_date = strtotime($acces_d." +3 days");

                    $current_date = date("d-m-Y h:i:s A");
                    $last_date = strtotime($current_date);

					/*
                    $styleColor = "";
                    if( $acces_date > $last_date){
                        $styleColor = "";
                    } else {
                        $styleColor = "style='color:#FF0000;'";
                    }
					*/

                    /*$lastAccessDate = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d')  : $data['UNXTIME'];
                    $lastAccessTime = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('H:i:s')  : $data['time_and_ext'];*/

                    $lastAccessDayHour = 0;
                    $lastAccessDayMinutes = 0;
                    $lastAccessDaySeconds = 0;

                    $todaysDayHour = 0;
                    $todaysDayMinutes = 0;
                    $todaysDaySeconds = 0;

					/*$sqlFormStatusTime = \Illuminate\Support\Facades\DB::table('comments')
					->where('comments.reference_number',$data['reference_number'])
					->where('comments.isapproved',1)
					->orderBy('comments.time','DESC')
					->pluck('time')
					->take(1)
					->toArray();

					$sqlFormQueueInTime = 0;
					foreach ($sqlFormStatusTime as $value) {
						$sqlFormQueueInTime = $value;
					}*/

                    $sqlFormQueueInTime = 0;
                    if (!empty($data['in_date_time'])) {
                        $sqlFormQueueInTime = $data['in_date_time']['time'];
                    }
					//echo $sqlFormQueueInTime;


					$isSendBack = (!empty($data['in_date_time']))? $data['in_date_time']['issendback']:0;

                    /*$lastAccessDate = (!empty($data['last_comment'])) ? \Illuminate\Support\Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d');
                    $lastAccessTime = (!empty($data['last_comment'])) ? \Carbon\Carbon::parse($data['last_comment']['created_at'])->format('H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('H:i:s');
                    $lastAccessDateTime = (!empty($data['last_comment'])) ? \Carbon\Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d H:i:s');*/

                    $lastAccessDate = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('Y-m-d')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d');
                    $lastAccessTime = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('H:i:s');
                    $lastAccessDateTime = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d H:i:s');

                    // $totalWorkingDaysOnThisReq = (!empty($data['in_date_time'])) ? $data['in_date_time']['total_working_days2']  : $data['total_working_days'] ;

                    if (!empty($data['in_date_time'])) {
                        $totalWorkingDaysOnThisReq = getWorkingDaysRef(date('Y-m-d',$data['in_date_time']['time']));
                    } else {
                        $totalWorkingDaysOnThisReq = getWorkingDaysRef(date('Y-m-d',$data['date']));
                    }

                    $totalWorkingHoursOnThisReq = (!empty($totalWorkingDaysOnThisReq)) ? $totalWorkingDaysOnThisReq * 8 : 0;


                    $lastAccessDateForCalc = str_replace('-', '', $lastAccessDate);
                    $lastAccessDateTimeNumb = str_replace("-", "", str_replace(" ", "", str_replace(":", "", $lastAccessDateTime)));


                    $lastAccessDateForCalcMin = $lastAccessDateForCalc.$dataForView['office_from'];
                    $lastAccessDateForCalcMax = $lastAccessDateForCalc.$dataForView['office_to'];


                    date_default_timezone_set('Asia/Dhaka');
                    $todaysDateCalc = date("Ymd");
                    $todaysDateTimeCalc = date("YmdHis");
                    $todaysDateCalcMin = $todaysDateCalc.$dataForView['office_from'];
                    $todaysDateCalcMax = $todaysDateCalc.$dataForView['office_to'];

                    $todaysDateTime = date('Y-m-d H:i:s');
                    $todaysDate = date('Y-m-d');

                    if (!empty($workingDays[$lastAccessDate])) {
                        if (($lastAccessDateTimeNumb >= $lastAccessDateForCalcMin && $lastAccessDateTimeNumb <= $lastAccessDateForCalcMax)) {
                            $lastAccessDateTimeObj = new DateTime($lastAccessDateTime);

                            if ($lastAccessDate != $todaysDate) {
                                $lastAccessDateLastObj = new DateTime($lastAccessDate.' '.$dataForView['office_to_str']);
                            } else {
                                if ($todaysDateTimeCalc >= $todaysDateCalcMin && $todaysDateTimeCalc <= $todaysDateCalcMax) {
                                    $lastAccessDateLastObj = new DateTime($todaysDateTime);
                                } else {
                                    $lastAccessDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_to_str']);
                                }
                            }

                            $interval = $lastAccessDateTimeObj->diff($lastAccessDateLastObj);

                            $lastAccessDayHour = $interval->format('%h');
                            $lastAccessDayMinutes = $interval->format('%i');
                            $lastAccessDaySeconds = $interval->format('%s');
                        }
                    }

                    if(!empty($workingDays[$todaysDate])) {

                        if (($todaysDateTimeCalc >= $todaysDateCalcMin && $todaysDateTimeCalc <= $todaysDateCalcMax) && ($lastAccessDate != $todaysDate)) {
                            $todaysDateTimeObj = new DateTime($todaysDateTime);
                            $todaysDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_from_str']);

                            $interval = $todaysDateTimeObj->diff($todaysDateLastObj);

                            $todaysDayHour = $interval->format('%h');
                            $todaysDayMinutes = $interval->format('%i');
                            $todaysDaySeconds = $interval->format('%s');
                        } elseif (($todaysDateTimeCalc > $todaysDateCalcMax) && ($lastAccessDate != $todaysDate)) {
                            $todaysDateTimeObj = new DateTime($todaysDate.' '.$dataForView['office_to_str']);
                            $todaysDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_from_str']);

                            $interval = $todaysDateTimeObj->diff($todaysDateLastObj);

                            $todaysDayHour = $interval->format('%h');
                            $todaysDayMinutes = $interval->format('%i');
                            $todaysDaySeconds = $interval->format('%s');
                        }
                    }


                    $totalHoursOnThisQueue = $lastAccessDayHour + $totalWorkingHoursOnThisReq + $todaysDayHour;
                    $totalMinutesOnThisQueue = $lastAccessDayMinutes + $todaysDayMinutes;
                    $totalSecondsOnThisQueue = $lastAccessDaySeconds + $todaysDaySeconds;

                    $queueDurationInMinutes = ($totalHoursOnThisQueue * 60) + $totalMinutesOnThisQueue;

                    $totalHoursOnThisQueue = sprintf("%02d", $totalHoursOnThisQueue);
                    $totalMinutesOnThisQueue = sprintf("%02d", $totalMinutesOnThisQueue);
                    $totalSecondsOnThisQueue = sprintf("%02d", $totalSecondsOnThisQueue);

                    $queue_duration = $totalHoursOnThisQueue.':'.$totalMinutesOnThisQueue;

                    $blink = issue_breach($data['reference_number'],$queueDurationInMinutes);

                    $styleColor = '';
                    $QueueSLATime = "";

                    $issue_flow_type = $data['flow_type'];
                    $sla_maker = $data['sla_maker'];
                    $sla_checker = $data['sla_checker'];
					$highPriority = trim($data['priority']);
                    $complain_sla_time = $data['complain_sla_time'];

                    if ($issue_flow_type == "regular") {
                        if ($data['unit_id'] == 2) {
                            $QueueSLATime = $sla_checker;
                            $sla_checker_blink = $sla_checker - $blinkTime;
                            if (($queueDurationInMinutes > $sla_checker_blink) && ($queueDurationInMinutes < $sla_checker)) {
                                $styleColor = "style='font-weight:bold;color:#008000;animation: blinker 1s linear infinite;'";
                            } elseif ($queueDurationInMinutes > $sla_checker) {
                                $styleColor = "style='font-weight:bold;color:#FF0000;'";
                            }
							if($isSendBack > 0){
								$styleColor = "style='font-weight:bold;color:#0000FF;'";
							}
							if($highPriority == "High"){
								$styleColor = "style='font-weight:bold;color:#FFA500;'";
							}

                        } else {
                            $QueueSLATime = $sla_maker;
                            $sla_maker_blink = $sla_maker - $blinkTime;
                            if (($queueDurationInMinutes > $sla_maker_blink) && ($queueDurationInMinutes < $sla_maker)) {
                                $styleColor = "style='font-weight:bold;color:#008000;animation: blinker 1s linear infinite;'";
                            } elseif ($queueDurationInMinutes > $sla_maker) {
                                $styleColor = "style='font-weight:bold;color:#FF0000;'";
                            }
							if($isSendBack > 0){
								$styleColor = "style='font-weight:bold;color:#0000FF;'";
							}
							if($highPriority == "High"){
								$styleColor = "style='font-weight:bold;color:#FFA500;'";
							}
                        }

                    } else {

                        $QueueSLATime = $complain_sla_time;
                        $sla_forward_blink = $complain_sla_time - $blinkTime;
                        /* For Forward and other */
                        if (($queueDurationInMinutes > $sla_forward_blink) && ($queueDurationInMinutes < $complain_sla_time)) {
                            $styleColor = "style='font-weight:bold;color:#008000;animation: blinker 1s linear infinite;'";
                        } elseif ($queueDurationInMinutes > $complain_sla_time) {
                            $styleColor = "style='font-weight:bold;color:#FF0000;'";
                        }
						if($highPriority == "High"){
								$styleColor = "style='font-weight:bold;color:#FFA500;'";
							}
                    }
                    ?>

                    <tr>

						<?php
						/*$styleColor = '';
						if($sla_user==true && $queueDurationInMinutes > $sla){
							$styleColor = "style='font-weight:bold;color:#FF0000;'";
						}
						if($blink==true){
							$styleColor = "style='font-weight:bold;color:#008000;'";
						}
						if($isSendBack > 0){
							$styleColor = "style='font-weight:bold;color:#0000FF;'";
						}*/
						?>

                        <td {!! $styleColor !!} class="text-center">
                            <?php
                            $detailsUrl = url('/Supports/ComplaintDetails/'.encrypt($data['reference_number']));
                            $searchDataForView['qd'] = encrypt($queueDurationInMinutes);
                            if (!empty($searchDataForView)) {
                                $getUrlQuery = http_build_query($searchDataForView);
                                $detailsUrl .= '?'.$getUrlQuery;
                            }
                            ?>
                            <a href="{{ $detailsUrl }}">{{ $data['reference_number'] }}</a>
                        </td>
                        <td {!! $styleColor !!} class="text-center">{{ $data['account_number'] }}</td>
                        <td {!! $styleColor !!} class="text-left">{{ $data['customer_name'] }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ (!empty($data['product_type'])) ? $data['product_type'] : $data['product_type_ext'] }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ (!empty($data['issue_name'])) ? $data['issue_name'] : $data['complaint_type'] }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('Y-m-d') }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $data['time_and_ext'] }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($sqlFormQueueInTime)->format('Y-m-d') }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($sqlFormQueueInTime)->format('h:i:s a') }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $queue_duration }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $QueueSLATime }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $status }}</td>
                        <td {!! $styleColor !!} class="text-center">{{ $data['created_by'] }}</td>
                        
                        <td {!! $styleColor !!} class="text-center">

                            @IF(!empty($data['access_by']))
                                {{$data['access_by']}}
                            @ELSE
                                <?php
                                $lastAccess = "";
                                if (!empty($data['last_comment'])) {
                                    $lastAccess = $data['last_comment']['user_id'];
                                }
                                ?>
                                @IF(!empty($lastAccess))
                                    {{$lastAccess}}
                                @ELSE
                                    {{$data['created_by']}}
                                @ENDIF
                            @ENDIF

                        </td>

                        <td {!! $styleColor !!} class="text-center">
                            @IF($form_status == 10)
                                <p class="no-padding-margin">Hold by <strong>{{$data['access_by']}}</strong></p>
                                @ability('superadmin,admin','revokeAssignedRequest')
                                    <?php
                                    $searchUrl = url('/Supports/unassign/'.encrypt($data['reference_number']).'?reqFrom=unhold');
                                    if (!empty($searchDataForView)) {
                                        $searchUrl .= '&'.$getUrlQuery;
                                    }
                                    ?>
                                    <a href="{{$searchUrl}}" style="color:red">Un-Hold</a>
                                @endability

                            @ELSEIF($form_status != 11)
                                @IF(!empty($data['access_by']))
                                    <p class="no-padding-margin">Assigned to <strong>{{$data['access_by']}}</strong></p>
                                    @ability('superadmin,admin','revokeAssignedRequest')
                                    <?php
                                    $searchUrl = url('/Supports/unassign/'.encrypt($data['reference_number']));
                                    if (!empty($searchDataForView)) {
                                        $searchUrl .= '?'.$getUrlQuery;
                                    }
                                    ?>
                                    <a href="{{$searchUrl}}" style="color:red">Un-Assign</a>
                                    @endability
                                @ELSE
                                    <?php /* $searchUrl = url('/Supports/assign/'.encrypt($data['reference_number'])); if (!empty($searchDataForView)) {$searchUrl .= '?'.$getUrlQuery; } <a href="{{$searchUrl}}">Assign</a> */ ?>
                                @ENDIF
                            @ENDIF
                        </td>
                    </tr>

                @ENDFOREACH

            @ENDIF
            </tbody>

            <tfoot>

                @IF(!empty($complaintDataObj))
                @IF($complaintDataObj->total() > $complaintDataObj->perPage())
                    <tr><td class="text-right vcenter no-padding-margin-tb" colspan="14">{{ $complaintDataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
                @ENDIF
            @ENDIF

            </tfoot>
        </table>
    </div>

@ELSEIF($searchDataForView['active_tab']== \App\Enum\IssueTypeEnum::NON_CUSTOMER)
    <div class="table-responsive" id="handlerid">
        <table class="table table-striped w-auto bordered">
            <thead>
            <tr style="background-color: #DFF0D8">
                <th class="text-center"><a href="{{$currentUrl.'reference.reference_number'}}"><i class="{{($existingOrderBy == 'reference.reference_number') ? $orderByIcon:$orderByDefIcon }}"></i> Ticket No </a></th>
                <th class="text-center"><a href="{{$currentUrl.'non_customers.customer_name'}}"><i class="{{($existingOrderBy == 'non_customers.customer_name') ? $orderByIcon:$orderByDefIcon }}"></i> Name </a></th>
                <th class="text-center"><a href="{{$currentUrl.'non_customers.mobile_number'}}"><i class="{{($existingOrderBy == 'non_customers.mobile_number') ? $orderByIcon:$orderByDefIcon }}"></i> Mobile No. </a></th>
                <th class="text-center"><a href="{{$currentUrl.'non_customers.customer_email'}}"><i class="{{($existingOrderBy == 'non_customers.customer_email') ? $orderByIcon:$orderByDefIcon }}"></i> Email </a></th>
                <th class="text-center"><a href="{{$currentUrl.'non_customers.customer_dob'}}"><i class="{{($existingOrderBy == 'non_customers.customer_dob') ? $orderByIcon:$orderByDefIcon }}"></i> DOB </a></th>
                <th class="text-center"><a href="{{$currentUrl.'non_customers.customer_profession'}}"><i class="{{($existingOrderBy == 'non_customers.customer_profession') ? $orderByIcon:$orderByDefIcon }}"></i> Profession </a></th>
                <th class="text-center"><a href="{{$currentUrl.'reference.date'}}"><i class="{{($existingOrderBy == 'reference.date') ? $orderByIcon:$orderByDefIcon }}"></i> Log Date </a></th>
                <th class="text-center"><a href="{{$currentUrl.'non_customers.time_and_ext'}}"><i class="{{($existingOrderBy == 'non_customers.time_and_ext') ? $orderByIcon:$orderByDefIcon }}"></i> Log Time </a></th>
                <th class="text-center">In Date</th>
                <th class="text-center">In Time</th>
                <th class="text-center">Duration (H:M)</th>
                <th class="text-center">SLA Time (M)</th>
                <th class="text-center"><a href="{{$currentUrl.'reference.form_status'}}"><i class="{{($existingOrderBy == 'reference.form_status') ? $orderByIcon:$orderByDefIcon }}"></i> Status</a></th>
                <th class="text-center"><a href="{{$currentUrl.'reference.created_by'}}"><i class="{{($existingOrderBy == 'reference.created_by') ? $orderByIcon:$orderByDefIcon }}"></i> Logger</a></th>
                <th class="text-center">Last Worked by</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @IF(!empty($nonCustomerData['data']))

                @FOREACH($nonCustomerData['data'] as $data)

                    {{--@php $work = $workflow_list->workflowStage($data['reference_number']);

                    $sla_user=false;
                    if(\Illuminate\Support\Facades\Auth::user()->hasRole([\App\Enum\RoleEnum::LOGGER,\App\Enum\RoleEnum::EXECUTIVE])){
                        $sla_user=true;
                    }
                    $touch = $work['touch'];
                    $sla = $work['sla'];
                    $hold = $work['hold'];
                    $attach = $work['attach'];
                    $attach_item = $work['attach_item'];
                    @endphp--}}

                    <?php

                    $commentDatas = getCommentsInDateTimeRef($data['reference_number']);
                    $data['last_comment'] = $commentDatas['last_comment'];
                    $data['in_date_time'] = $commentDatas['in_date_time'];
                        
                    $form_status = $data['form_status'];
                    if($form_status == 8 || $form_status == 0 || $form_status == null) {
                        $status = "Pending";
                    } else if ($form_status == 11) {
                        $status = "Close";
                    } else if ($form_status == -1) {
                        $status = "Reject";
                    } else if ($form_status == 10) {
                        $status = "Hold";
                    } else{
                      $status = "Wip";
                    }

                    $acces_d = date("d-m-Y h:i:s A", $data['date'] );
                    if($form_status != 0 && !empty($data['access_date'])) { // changed.....
                        $acces_d = date("d-m-Y h:i:s A", $data['access_date'] );
                    }
                    $acces_date = strtotime($acces_d." +3 days");

                    $current_date = date("d-m-Y h:i:s A");
                    $last_date = strtotime($current_date);


                    $styleColor = "";

					/*
					if( $acces_date > $last_date){
                        $styleColor = "";
                    } else {
                        $styleColor = "style='color:#FF0000;'";
                    }
					*/

                    $sqlFormQueueInTime = 0;
                    if (!empty($data['in_date_time'])) {
                        $sqlFormQueueInTime = $data['in_date_time']['time'];
                    }

                    /*$lastAccessDate = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d')  : $data['UNXTIME'];
                    $lastAccessTime = (!empty($data['last_comment'])) ? Carbon::parse($data['last_comment']['created_at'])->format('H:i:s')  : $data['time_and_ext'];*/

                    $lastAccessDayHour = 0;
                    $lastAccessDayMinutes = 0;
                    $lastAccessDaySeconds = 0;

                    $todaysDayHour = 0;
                    $todaysDayMinutes = 0;
                    $todaysDaySeconds = 0;

                    /*$lastAccessDate = (!empty($data['last_comment'])) ? \Illuminate\Support\Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d');
                    $lastAccessTime = (!empty($data['last_comment'])) ? \Carbon\Carbon::parse($data['last_comment']['created_at'])->format('H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('H:i:s');
                    $lastAccessDateTime = (!empty($data['last_comment'])) ? \Carbon\Carbon::parse($data['last_comment']['created_at'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d H:i:s');*/

                    $lastAccessDate = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('Y-m-d')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d');
                    $lastAccessTime = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('H:i:s');
                    $lastAccessDateTime = (!empty($data['in_date_time'])) ? Carbon::createFromTimestamp($data['in_date_time']['time'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($data['date'])->format('Y-m-d H:i:s');


                    // $totalWorkingDaysOnThisReq = (!empty($data['in_date_time'])) ? $data['in_date_time']['total_working_days2']  : $data['total_working_days'] ;

                    if (!empty($data['in_date_time'])) {
                        $totalWorkingDaysOnThisReq = getWorkingDaysRef(date('Y-m-d',$data['in_date_time']['time']));
                    } else {
                        $totalWorkingDaysOnThisReq = getWorkingDaysRef(date('Y-m-d',$data['date']));
                    }

                    $totalWorkingHoursOnThisReq = (!empty($totalWorkingDaysOnThisReq)) ? $totalWorkingDaysOnThisReq * 8 : 0;


                    $lastAccessDateForCalc = str_replace('-', '', $lastAccessDate);
                    $lastAccessDateTimeNumb = str_replace("-", "", str_replace(" ", "", str_replace(":", "", $lastAccessDateTime)));


                    $lastAccessDateForCalcMin = $lastAccessDateForCalc.$dataForView['office_from'];
                    $lastAccessDateForCalcMax = $lastAccessDateForCalc.$dataForView['office_to'];


                    date_default_timezone_set('Asia/Dhaka');
                    $todaysDateCalc = date("Ymd");
                    $todaysDateTimeCalc = date("YmdHis");
                    $todaysDateCalcMin = $todaysDateCalc.$dataForView['office_from'];
                    $todaysDateCalcMax = $todaysDateCalc.$dataForView['office_to'];

                    $todaysDateTime = date('Y-m-d H:i:s');
                    
                    $todaysDate = date('Y-m-d');
                    if (!empty($workingDays[$lastAccessDate])) {
                        if (($lastAccessDateTimeNumb >= $lastAccessDateForCalcMin && $lastAccessDateTimeNumb <= $lastAccessDateForCalcMax)) {
                            $lastAccessDateTimeObj = new DateTime($lastAccessDateTime);

                            if ($lastAccessDate != $todaysDate) {
                                $lastAccessDateLastObj = new DateTime($lastAccessDate.' '.$dataForView['office_to_str']);
                            } else {
                                if ($todaysDateTimeCalc >= $todaysDateCalcMin && $todaysDateTimeCalc <= $todaysDateCalcMax) {
                                    $lastAccessDateLastObj = new DateTime($todaysDateTime);
                                } else {
                                    $lastAccessDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_to_str']);
                                }
                            }

                            $interval = $lastAccessDateTimeObj->diff($lastAccessDateLastObj);

                            $lastAccessDayHour = $interval->format('%h');
                            $lastAccessDayMinutes = $interval->format('%i');
                            $lastAccessDaySeconds = $interval->format('%s');
                        }
                    }
                    if(!empty($workingDays[$todaysDate])) {

                        if (($todaysDateTimeCalc >= $todaysDateCalcMin && $todaysDateTimeCalc <= $todaysDateCalcMax) && ($lastAccessDate != $todaysDate)) {
                            $todaysDateTimeObj = new DateTime($todaysDateTime);
                            $todaysDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_from_str']);

                            $interval = $todaysDateTimeObj->diff($todaysDateLastObj);

                            $todaysDayHour = $interval->format('%h');
                            $todaysDayMinutes = $interval->format('%i');
                            $todaysDaySeconds = $interval->format('%s');
                        } elseif (($todaysDateTimeCalc > $todaysDateCalcMax) && ($lastAccessDate != $todaysDate)) {
                            $todaysDateTimeObj = new DateTime($todaysDate.' '.$dataForView['office_to_str']);
                            $todaysDateLastObj = new DateTime($todaysDate.' '.$dataForView['office_from_str']);

                            $interval = $todaysDateTimeObj->diff($todaysDateLastObj);

                            $todaysDayHour = $interval->format('%h');
                            $todaysDayMinutes = $interval->format('%i');
                            $todaysDaySeconds = $interval->format('%s');
                        }
                    }

                    $totalHoursOnThisQueue = $lastAccessDayHour + $totalWorkingHoursOnThisReq + $todaysDayHour;
                    $totalMinutesOnThisQueue = $lastAccessDayMinutes + $todaysDayMinutes;
                    $totalSecondsOnThisQueue = $lastAccessDaySeconds + $todaysDaySeconds;

                    $queueDurationInMinutes = ($totalHoursOnThisQueue * 60) + $totalMinutesOnThisQueue;

                    $totalHoursOnThisQueue = sprintf("%02d", $totalHoursOnThisQueue);
                    $totalMinutesOnThisQueue = sprintf("%02d", $totalMinutesOnThisQueue);
                    $totalSecondsOnThisQueue = sprintf("%02d", $totalSecondsOnThisQueue);

                    $queue_duration = $totalHoursOnThisQueue.':'.$totalMinutesOnThisQueue;

                    $issue_flow_type = $data['flow_type'];
                    $sla_maker = $data['sla_maker'];
                    $sla_checker = $data['sla_checker'];

                    $sla_forward_blink = $forwardTime - $blinkTime;
                    /* For Forward and other */
                    if (($queueDurationInMinutes > $sla_forward_blink) && ($queueDurationInMinutes < $forwardTime)) {
                        $styleColor = "style='font-weight:bold;color:#008000;'";
                    } elseif ($queueDurationInMinutes > $forwardTime) {
                        $styleColor = "style='font-weight:bold;color:#FF0000;'";
                    }
                    ?>

                        <tr>
                            <td {!! $styleColor !!} class="text-center">
                                <?php
                                $detailsUrl = url('/Supports/NonCustomer/'.encrypt($data['reference_number']));
                                $searchDataForView['qd'] = encrypt($queueDurationInMinutes);
                                if (!empty($searchDataForView)) {
                                    $getUrlQuery = http_build_query($searchDataForView);
                                    $detailsUrl .= '?'.$getUrlQuery;
                                }
                                ?>
                                <a href="{{ $detailsUrl }}">{{ $data['reference_number'] }}</a>
                            </td>
                            <td {!! $styleColor !!} class="text-left">{{ $data['customer_name'] }}</td>
                            <td {!! $styleColor !!} class="text-left">{{ $data['mobile_number'] }}</td>
                            <td {!! $styleColor !!} class="text-left">{{ $data['customer_email'] }}</td>
							<td {!! $styleColor !!} class="text-left">{{ $data['customer_dob'] }}</td>
							<td {!! $styleColor !!} class="text-left">{{ $data['customer_profession'] }}</td>
                            <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('Y-m-d') }}</td>
							<td {!! $styleColor !!} class="text-center">{{ $data['time_and_ext'] }}</td>
                            <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($sqlFormQueueInTime)->format('Y-m-d') }}</td>
                            <td {!! $styleColor !!} class="text-center">{{ \Carbon\Carbon::createFromTimestamp($sqlFormQueueInTime)->format('h:i:s a') }}</td>
                            <td {!! $styleColor !!} class="text-center">{{ $queue_duration }}</td>
                            <td {!! $styleColor !!} class="text-center">{{ $forwardTime }}</td>
                            {{-- <td {!! $styleColor !!} class="text-center">{{ $lastAccessDate }}</td>--}}


                            <td {!! $styleColor !!} class="text-center">{{ $status }}</td>
                            <td {!! $styleColor !!} class="text-center">{{ $data['created_by'] }}</td>
                            
                            <td {!! $styleColor !!} class="text-center">

                            @IF(!empty($data['access_by']))
                                {{$data['access_by']}}
                            @ELSE
                                <?php
                                $lastAccess = "";
                                if (!empty($data['last_comment'])) {
                                    $lastAccess = $data['last_comment']['user_id'];
                                }
                                ?>
                                @IF(!empty($lastAccess))
                                    {{$lastAccess}}
                                @ELSE
                                    {{$data['created_by']}}
                                @ENDIF
                            @ENDIF

                        </td>

                            <td {!! $styleColor !!} class="text-center">
                                @IF($form_status == 10)
                                    <p class="no-padding-margin">Hold by <strong>{{$data['access_by']}}</strong></p>
                                    @ability('superadmin,admin','revokeAssignedRequest')
                                    <?php
                                    $searchUrl = url('/Supports/unassign/'.encrypt($data['reference_number']).'?reqFrom=unhold');
                                    if (!empty($searchDataForView)) {
                                        $searchUrl .= '&'.$getUrlQuery;
                                    }
                                    ?>
                                    <a href="{{$searchUrl}}" style="color:red">Un-Hold</a>
                                    @endability

                                @ELSEIF($form_status != 11)
                                    @IF(!empty($data['access_by']))
                                        <p class="no-padding-margin">Assigned to <strong>{{$data['access_by']}}</strong></p>
                                        @ability('superadmin,admin','revokeAssignedRequest')
                                        <?php
                                        $searchUrl = url('/Supports/unassign/'.encrypt($data['reference_number']));
                                        if (!empty($searchDataForView)) {
                                            $searchUrl .= '?'.$getUrlQuery;
                                        }
                                        ?>
                                        <a href="{{$searchUrl}}" style="color:red">Un-Assign</a>
                                        @endability
                                    @ELSE
                                        <?php /* $searchUrl = url('/Supports/assign/'.encrypt($data['reference_number'])); if (!empty($searchDataForView)) {$searchUrl .= '?'.$getUrlQuery; } <a href="{{$searchUrl}}">Assign</a> */ ?>
                                    @ENDIF
                                @ENDIF
                            </td>
                        </tr>

                @ENDFOREACH

            @ENDIF
            </tbody>

            <tfoot>

                @IF(!empty($nonCustomerDataObj))
                @IF($nonCustomerDataObj->total() > $nonCustomerDataObj->perPage())
                    <tr><td class="text-right vcenter no-padding-margin-tb" colspan="14">{{ $nonCustomerDataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
                @ENDIF
            @ENDIF
                
            </tfoot>

            {{--<tfoot>
            <tr>
                <th class="text-center">Ticket No</th>
                <th class="text-center">Customer Name</th>
                <th class="text-center">Mobile Number</th>
                <th class="text-center">Customer Email</th>
				<th class="text-center">Customer Profession</th>
				<th class="text-center">Customer DOB</th>                
                <th class="text-center">Status</th>
                <th class="text-center">Logger</th>
                <th class="text-center">Action</th>
            </tr>
            </tfoot>--}}
        </table>
    </div>
@ENDIF

<div class="clearfix">&nbsp;</div>

<?php
$handlerUrl = url('Supports/handler?');
$handlerStaticParam = '';

unset($_GET['cmmn_pgntion']);
unset($_GET['cmmn_search']);

if (!empty($_GET)) {
    $handlerStaticParam .= '&'.http_build_query($_GET);
}
?>

<script type="text/javascript">
$(".form-type").on('ifChecked', function(event){
    $('form#handler-search').submit();
});

        var service_category = $('#service_category').val(); 
        getGetComplaintOptions(service_category);
        $('#service_category').on('change', function(){
            var service_category = $('#service_category').val();          
            getGetComplaintOptions(service_category);            
        });
    
    function getGetComplaintOptions(service_category) {
        var postBackCompType = "{{(!empty($searchDataForView['service_type']))? $searchDataForView['service_type']: '0'}}";
        
        //alert(base_url+'/get-cat-wise-services/'+ service_category);
        if (service_category) {
            $.ajax({
                url: base_url+'/get-cat-wise-services/'+ service_category,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('#request_type').html('<option value="">Select Service Type</option>');
                    $.each(data, function (key, value) {
                        var selectedForPb = "";

                        if (postBackCompType == value.id) {
                            selectedForPb = "selected";
                        }

                        $('#request_type').append('<option value="' + value.id + '" '+selectedForPb+' >' + value.name + '</option>');
                    });
                }
            });
        }
    };

$(document).on('click','.btnSearch',function(event){
    var commonPagination = $('.commonPagination').val();
    var commonSearchBar = $('.commonSearchBar').val();

    var handlerUrl = "{!! $handlerUrl; !!}";
    var handlerStaticParam = "{!! $handlerStaticParam !!}";
        handlerStaticParam += "&cmmn_pgntion="+commonPagination+"&cmmn_search="+commonSearchBar;
        handlerStaticParam = handlerStaticParam.substring(1);

    var handlerFullUrl = encodeURI(handlerUrl+handlerStaticParam);
    cUWRefrh('', handlerFullUrl);

    overlay('show');

    $('#handlerid').load(handlerFullUrl + " #handlerid", function(){
         overlay('hide');
    });    

    // $("#handlerid").load(handlerFullUrl + " #handlerid");
});


$(document).on('change','.commonPagination',function(event){
  $('.btnSearch').trigger('click');
});

$(".commonSearchBar").keyup(function(event){
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    $('.btnSearch').trigger('click');
  }
});

function cUWRefrh(t,e){""==t&&(t=$("head title").html()),document.title=t,window.history.pushState({state:t},"",e)}

/*var browserPrefixes = ['moz', 'ms', 'o', 'webkit'], isVisible = true; // internal flag, defaults to true // get the correct attribute name function getHiddenPropertyName(prefix) {return (prefix ? prefix + 'Hidden' : 'hidden'); } // get the correct event name function getVisibilityEvent(prefix) {return (prefix ? prefix : '') + 'visibilitychange'; } // get current browser vendor prefix function getBrowserPrefix() {for (var i = 0; i < browserPrefixes.length; i++) {if(getHiddenPropertyName(browserPrefixes[i]) in document) {// return vendor prefix return browserPrefixes[i]; } } // no vendor prefix needed return null; } // bind and handle events var browserPrefix = getBrowserPrefix(), hiddenPropertyName = getHiddenPropertyName(browserPrefix), visibilityEventName = getVisibilityEvent(browserPrefix); function onVisible() {// prevent double execution if(isVisible) {return; } //location.reload(); // change flag value isVisible = true; } function onHidden() {// prevent double execution if(!isVisible) {return; } // change flag value isVisible = false; } function handleVisibilityChange(forcedFlag) {// forcedFlag is a boolean when this event handler is triggered by a // focus or blur eventotherwise it's an Event object if(typeof forcedFlag === "boolean") {if(forcedFlag) {return onVisible(); } return onHidden(); } if(document[hiddenPropertyName]) {return onHidden(); } return onVisible(); } document.addEventListener(visibilityEventName, handleVisibilityChange, false); // extra event listeners for better behaviour document.addEventListener('focus', function() {handleVisibilityChange(true); }, false); document.addEventListener('blur', function() {handleVisibilityChange(false); }, false); window.addEventListener('focus', function() {handleVisibilityChange(true); }, false); window.addEventListener('blur', function() {handleVisibilityChange(false); }, false);*/
</script>
@endsection
