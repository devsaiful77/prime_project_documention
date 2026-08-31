@php    use Carbon\Carbon;    @endphp
@inject('queueDuration','App\Services\UtilService')
@inject('workflow_list','App\Services\WorkFlowService')
@inject('flow_type','App\Services\WorkFlowService')
<div class="table-responsive">
	<table class="table table-bordered table-condensed">
		<thead style="background-color: #337ab7;">
		<tr>
			<th style="color: white" class="text-center vcenter wordwrap ">Ticket Number</th>
			<th style="color: white" class="text-center vcenter wordwrap no-padding-margin-tb">Maker</th>
			@if($searchDataForView['date_type'] == 'action_date')
				<th style="color: white" class="text-center vcenter nowrap ">Action Date</th>
				<th style="color: white" class="text-center vcenter nowrap ">Action Time</th>
			@else
				<th style="color: white" class="text-center vcenter nowrap ">Log Date</th>
				<th style="color: white" class="text-center vcenter nowrap ">Log Time</th>
			@endif
			<th style="color: white" class="text-center vcenter wordwrap ">Current Position</th>
			<th style="color: white" class="text-center vcenter wordwrap ">TaT(D:H:M:S)</th>
            @if($searchDataForView["form_type"] != 'noncustomer')
            <th style="color: white" class="text-center vcenter nowrap ">Remaining <br> SLA (M)</th>
			@endif
			<th style="color: white" class="text-center vcenter wordwrap ">Status</th>
			@if($searchDataForView["form_type"] != 'noncustomer')
			<th style="color: white" class="text-center vcenter wordwrap ">Card / Acc Number</th>
			@endif
			<th style="color: white" class="text-center vcenter wordwrap ">Customer Name</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Mobile Number</th>
			<th style="color: white" class="text-center vcenter nowrap ">Customer <br> Email</th>
			@if($searchDataForView["form_type"] != 'noncustomer')
			<th style="color: white" class="text-center vcenter wordwrap ">Product Type</th>
			@endif
			@if($searchDataForView["form_type"] != 'noncustomer')
			<th style="color: white" class="text-center vcenter nowrap ">Customer Number</th>
			@endif

			@IF( $searchDataForView["form_type"] == 'wform')
			<th style="color: white" class="text-center vcenter wordwrap ">Notes</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Service Request Type</th>
			@elseif ($searchDataForView["form_type"] == 'complaint')
			<th style="color: white" class="text-center vcenter wordwrap ">Complaint Details</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Complaint Type</th>
			@else

			@endif
			@if($searchDataForView["form_type"] != 'noncustomer')
                @if($searchDataForView['issueTypeExcel'] == 1)
                    @if(!empty($dataForView['data']))
                        @foreach($dataForView['data'] AS $data)
                            @php
                                $extraField = $data['extra_field'];
                                $extraFieldArr = array();
                                if (!empty($extraField)) {
                                    $extraFieldArr = json_decode($extraField,true);
                                }
                            @endphp
                        @endforeach
                        @IF(!empty($extraFieldArr))
                            @if(isset($issue_id) && $issue_id != 1103 && $issue_id != 1105)
                                @foreach($extraFieldArr AS $extFld)
                                    <th class="text-center vcenter wordwrap ">{{ key($extFld) }}</th>
                                @endforeach
                            @endif
                        @ENDIF
                    @endif
                @else
                <th style="color: white" class="text-center vcenter wordwrap ">AF1</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF2</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF3</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF4</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF5</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF6</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF7</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF8</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF9</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF10</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF11</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF12</th>
                <th style="color: white" class="text-center vcenter wordwrap ">AF13</th>
                @endif
            @endif
		</tr>
		</thead>
		<tbody>
			<?php
                // echo '<pre>'; print_r($searchDataForView); print_r($dataForView); die;
            ?>
		@IF(!empty($dataForView['data']))
			@FOREACH($dataForView['data'] AS $data)
                @php
                    $form_status = $data['form_status'];
                    if($form_status == 8 || $form_status == 0 || $form_status == null) {
                        $status = "Pending";
                    } elseif($form_status == 2) {
                        $status = "Wip";
                    } else if ($form_status == 11) {
                        $status = "Close";
                    } else if ($form_status == 10) {
                        $status = "Hold";
                    } else {
                      $status = "Wip";
                    }
                    if($searchDataForView["form_type"] != 'noncustomer'){
                    $commentDatas = getCommentsInDateTimeRef($data['reference_number']);
                    $data['last_comment'] = $commentDatas['last_comment'];
                    $data['in_date_time'] = $commentDatas['in_date_time'];

                    $flow_type_name = $flow_type->getFlowTypeCheck($data['reference_number']);
                    if(empty($flow_type_name))
                        {
                            $flow_type_name ='';
                        }
                    if($flow_type_name==\App\Enum\FlowEnum::REGULAR)
                    $work = $workflow_list->workflowStage($data['reference_number']);
                    $acces_d = date("d-m-Y h:i:s A", $data['date'] );
                    if($form_status != 0 && !empty($data['access_date'])) { // changed.....
                        $acces_d = date("d-m-Y h:i:s A", $data['access_date'] );
                    }
                    $acces_date = strtotime($acces_d." +3 days");

                    $current_date = date("d-m-Y h:i:s A");
                    $last_date = strtotime($current_date);

                    $lastAccessDayHour = 0;
                    $lastAccessDayMinutes = 0;
                    $lastAccessDaySeconds = 0;

                    $todaysDayHour = 0;
                    $todaysDayMinutes = 0;
                    $todaysDaySeconds = 0;


                    $sqlFormQueueInTime = 0;
                    if (!empty($data['in_date_time'])) {
                        $sqlFormQueueInTime = $data['in_date_time']['time'];
                    }
                    $isSendBack = (!empty($data['in_date_time']))? $data['in_date_time']['issendback']:0;


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
                    $QueueSLATime = 0;
                    if ($issue_flow_type == "regular") {
                        if ($data['unit_id'] == 2) {
                            $QueueSLATime = $sla_checker;
                        } else {
                            $QueueSLATime = $sla_maker;
                        }

                    }

                    $remaining = floor($QueueSLATime) - $queueDurationInMinutes;
                    if ($remaining > 0) {
                        $remaining = $remaining;
                    } else {
                        $remaining = 0;
                    }
                    }
                    $stime = date('Y-m-d H:i:s', (int)$data['date']);
                    $etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');

                    $duration = $queueDuration->queueDurationCalculator($stime, $etime);

                    $str_arr = preg_split ("/\:/", $duration);
                    //pr($str_arr);
                    //echo $data['reference_number'].'----'.$stime.'--'.$etime.'--'.$duration;


                @endphp
                @if(($str_arr[0]*60)+$str_arr[1] > "1440")
					@if($searchDataForView["form_type"] != 'complaint')
						<tr style="background-color:#E9967A">
					@else
						<tr>
					@endif
				@else
					<tr>
				@endif

					@IF( $searchDataForView["form_type"] == 'wform')
					<td class="text-center vcenter no-padding-margin-tb">
						<a href="{{ url('/Supports/WFormReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
					</td>
					@ELSEIF( $searchDataForView["form_type"] == 'complaint')
					<td class="text-center vcenter no-padding-margin-tb">
						<a href="{{ url('/Supports/ComplaintReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
					</td>
					@ELSEIF( $searchDataForView["form_type"] == 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">
						<a href="{{ url('/Supports/NonCustomerReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
					</td>
					@ELSE

					@ENDIF

					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['created_by_name'])) ? $data['created_by_name'] : $data['created_by'] }} {{$data['source_maker']!=null? "(".$data['source_maker'].")" : ""}}</td>
					@if($searchDataForView['date_type'] == 'action_date')
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('d-m-Y') }}</td>
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('H:i:s') }}</td>
					@else
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('d-m-Y') }}</td>
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('H:i:s') }}</td>
					@endif

					<td class="text-center vcenter no-padding-margin-tb">
					@if($data['form_status'] == 11)
					Form Close at {{ $data['name'] }}&nbsp;
                    {{ !empty($data['access_by']) ? '-'.$data['access_by'] : '' }}
                    [{{($data['unit_id']==1)? "Maker":"Checker"}}]
            		@else
						{{ (!empty($data['access_by'])) ? $data['access_by'] : '' }}&nbsp;
						{{ $data['name'] }}&nbsp;{{ ($data['unit_id'] == 1) ? "[Maker]" : "[Checker]" }}
					@endif
					</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $duration }}</td>
                    @if( $searchDataForView["form_type"] != 'noncustomer')
                    <td class="text-center vcenter no-padding-margin-tb">{{ $remaining }}</td>
					@endif
					<td class="text-center vcenter no-padding-margin-tb">{{ $status }}</td>
					<!-- Information Section  -->
					@IF( $searchDataForView["form_type"] == 'wform')
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['account_number'] }}</td>
					@ELSEIF( $searchDataForView["form_type"] == 'complaint')
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['account_number'] }}</td>
					@ELSEIF( $searchDataForView["form_type"] == 'noncustomer')

					@ENDIF
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['customer_name'] }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['mobile_number'] }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['email_address'])) ? $data['email_address'] : '' }}</td>
					@if( $searchDataForView["form_type"] != 'noncustomer')
						<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type_name'])) ? $data['product_type_name'] : $data['product_type_ext'] }}</td>
					@endif
					@if($searchDataForView["form_type"] != 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['SIF_Number'])) }}</td>
					@endif

					<!-- Action Section -->
					@if( $searchDataForView["form_type"] != 'noncustomer')

						@IF( $searchDataForView["form_type"] == 'wform')
							<td class="text-center vcenter no-padding-margin-tb">{{ $data['notes'] }}</td>
						@ELSE
							<td class="text-center vcenter no-padding-margin-tb">{{ $data['complaint_details'] }}</td>
						@ENDIF
						<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['form_type'] }}</td>
					@endif
                    @if( $searchDataForView["form_type"] != 'noncustomer')
                        @php
                            $extraField = $data['extra_field'];
                            $extraFieldArr = array();
                            if (!empty($extraField)) {
                                $extraFieldArr = json_decode($extraField,true);
                            }
                        @endphp
                        @if($searchDataForView['issueTypeExcel'] == 1)
                            @IF(!empty($extraFieldArr))
                                @if(isset($issue_id) && $issue_id != 1103 && $issue_id != 1105)
                                    @FOREACH($extraFieldArr AS $extFld)
                                        <td style="" class="text-center vcenter no-padding-margin-tb">{{ current($extFld) }}</td>
                                    @ENDFOREACH
                                @endif
                            @ENDIF
                        @else
                            @IF(!empty($extraFieldArr))
                                @if(isset($issue_id) && $issue_id != 1103 && $issue_id != 1105)
                                    @FOREACH($extraFieldArr AS $extFld)
                                        @php //prd(current($extFld)); @endphp
                                        <td class="text-center vcenter no-padding-margin-tb">{{ key($extFld)}}:{{current($extFld)}}</td>
                                    @ENDFOREACH
                                @endif
                            @ENDIF
                        @endif
                    @endif
				</tr>
			@ENDFOREACH
		@ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="14">Data not available</th></tr>
		@ENDIF
		</tbody>
		<tfoot>
		@IF(!empty($dataObj))
			@IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right vcenter no-padding-margin-tb" colspan="14">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF
        @ENDIF
        </tfoot>
	</table>
</div>
