@php
 use Carbon\Carbon;
@endphp
@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>

                {{-- <th class="text-center vcenter wordwrap ">Complaint Lodge date</th>
                <th class="text-center vcenter wordwrap ">Complain source</th>
                <th class="text-center vcenter wordwrap ">Customer Email address</th>
                <th class="text-center vcenter wordwrap ">Complaint Hnadler (who assigned & closed the complaint)</th>
                <th class="text-center vcenter wordwrap ">Customer's Name</th>
                <th class="text-center vcenter wordwrap ">Contact number</th>
                <th class="text-center vcenter wordwrap ">Acc/Card No:</th>
                <th class="text-center vcenter wordwrap ">Complaint Status (resolved/ unresolved)</th>
                <th class="text-center vcenter wordwrap ">Reference/ ticket number</th>
                <th class="text-center vcenter wordwrap ">Nature of Complaints</th>
                <th class="text-center vcenter wordwrap ">Correspondence source</th>
                <th class="text-center vcenter wordwrap ">FI Branch ID</th>
                <th class="text-center vcenter wordwrap ">Resolve Date</th>
                <th class="text-center vcenter wordwrap ">AMOUNT INVOLVED</th>
                <th class="text-center vcenter wordwrap ">Root cause</th>
                <th class="text-center vcenter wordwrap ">Subject of email/ Complaint Subject</th>
                <th class="text-center vcenter wordwrap ">Action taken</th>
                <th class="text-center vcenter wordwrap ">Complaint summary</th>
                <th class="text-center vcenter wordwrap ">Days to resolve (Working Days)</th> --}}

                
                <th class="text-center vcenter wordwrap ">Ticket Number</th>
                <th class="text-center vcenter wordwrap no-padding-margin-tb">Source Maker</th>
                <th class="text-center vcenter wordwrap no-padding-margin-tb">Source Subgroup</th>
                @if($searchDataForView['date_type'] == 'action_date')
                    <th class="text-center vcenter nowrap ">Action Date</th>
                    <th class="text-center vcenter nowrap ">Action Time</th>
                @else
                    <th class="text-center vcenter nowrap ">Log Date</th>
                    <th class="text-center vcenter nowrap ">Log Time</th>
                @endif
                <th class="text-center vcenter wordwrap ">Current Position</th>
                <th class="text-center vcenter wordwrap ">TaT(D:H:M:S)</th>

                <th class="text-center vcenter wordwrap ">Status</th>
                <th class="text-center vcenter wordwrap ">Close Date</th>
                <th class="text-center vcenter wordwrap ">Closing Remarks</th>
                @if($searchDataForView["form_type"] != 'noncustomer')
                <th class="text-center vcenter wordwrap ">Card / Acc Number</th>
                @endif
                <th class="text-center vcenter wordwrap ">Customer Name</th>
                <th class="text-center vcenter wordwrap ">Account Name</th>
                <th class="text-center vcenter wordwrap ">Mobile Number</th>
                <th class="text-center vcenter wordwrap ">Customer Email</th>
                @if($searchDataForView["form_type"] != 'noncustomer')
                <th class="text-center vcenter wordwrap ">Product Type</th>
                <th class="text-center vcenter wordwrap ">Caller ID</th>
                @endif
                {{--<th class="text-center vcenter wordwrap ">Time &amp; Ext</th>--}}
                @if ($searchDataForView['form_type'] != 'noncustomer')
                <th class="text-center vcenter wordwrap ">Customer Number</th>
                <th class="text-center vcenter wordwrap ">Customer DOB</th>
                <th class="text-center vcenter wordwrap ">Segment Code</th>
                @endif
                @IF( $searchDataForView["form_type"] == 'wform')
                <th class="text-center vcenter wordwrap ">Notes</th>
                <th class="text-center vcenter wordwrap ">Service Request Type</th>
                @elseif ($searchDataForView["form_type"] == 'complaint')
                <th class="text-center vcenter wordwrap ">Complaint Details</th>
                <th class="text-center vcenter wordwrap ">Complaint Type</th>
                @else

                @endif
                @if($searchDataForView["form_type"] != 'noncustomer')
                    @if($searchDataForView['issueTypeExcel'] == 1)
                        @if(!empty($dataForView))
                            @foreach($dataForView AS $data)
                                @php
                                    $extraField = $data['extra_field'];
                                    $extraFieldArr = array();
                                    if (!empty($extraField)) {
                                        $extraFieldArr = json_decode($extraField,true);
                                    }
                                @endphp
                            @endforeach
                            @if(!empty($extraFieldArr))
                                @if($data['w_form_type'] != 1103 && $data['w_form_type'] != 1105)
                                    @foreach($extraFieldArr AS $extFld)
                                        <th class="text-center vcenter wordwrap ">{{ key($extFld) }}</th>
                                    @endforeach
                                @endif
                            @endif
                        @endif
                    @else
                        <th style="" class="text-center vcenter wordwrap ">AF1</th>
                        <th style="" class="text-center vcenter wordwrap ">AF2</th>
                        <th style="" class="text-center vcenter wordwrap ">AF3</th>
                        <th style="" class="text-center vcenter wordwrap ">AF4</th>
                        <th style="" class="text-center vcenter wordwrap ">AF5</th>
                        <th style="" class="text-center vcenter wordwrap ">AF6</th>
                        <th style="" class="text-center vcenter wordwrap ">AF7</th>
                        <th style="" class="text-center vcenter wordwrap ">AF8</th>
                        <th style="" class="text-center vcenter wordwrap ">AF9</th>
                        <th style="" class="text-center vcenter wordwrap ">AF10</th>
                        <th style="" class="text-center vcenter wordwrap ">AF11</th>
                        <th style="" class="text-center vcenter wordwrap ">AF12</th>
                        <th style="" class="text-center vcenter wordwrap ">AF13</th>
                        <th style="" class="text-center vcenter wordwrap ">AF14</th>
                        <th style="" class="text-center vcenter wordwrap ">AF15</th>
                        <th style="" class="text-center vcenter wordwrap ">AF16</th>
                        <th style="" class="text-center vcenter wordwrap ">AF17</th>
                        <th style="" class="text-center vcenter wordwrap ">AF18</th>
                        <th style="" class="text-center vcenter wordwrap ">AF19</th>
                        <th style="" class="text-center vcenter wordwrap ">AF20</th>
                        <th style="" class="text-center vcenter wordwrap ">AF21</th>
                        <th style="" class="text-center vcenter wordwrap ">AF22</th>
                        <th style="" class="text-center vcenter wordwrap ">AF23</th>
                        <th style="" class="text-center vcenter wordwrap ">AF24</th>
                        <th style="" class="text-center vcenter wordwrap ">AF25</th>
                    @endif
                @endif
            </tr>
        </thead>
        <tbody>
            @IF(!empty($dataForView))
            <?php
                // $settingsData = \Illuminate\Support\Facades\DB::table('settings')->first();
                // $workingHours = \Illuminate\Support\Facades\DB::table('working_hours')->first();
                // $workingHours = json_decode(json_encode($workingHours),true);
            ?>
			@FOREACH($dataForView AS $data)
				<?php
                    $getCommentInfo = getCommentsActionTimeAndCloseComment($data['reference_number']);
                    $data['action_time'] = $getCommentInfo['action_time'];
                    $data['close_comments'] = $getCommentInfo['close_comments'];

                $form_status = $data['form_status'];
                if($form_status == 8 || $form_status == 0 || $form_status == null) {
                    $status = "Pending";
                } elseif($form_status == 2) {
                    $status = "Wip";
                } else if ($form_status == 11) {
                    $status = "Close";
                } else if ($form_status == 10) {
                    $status = "Hold";
                } else if ($form_status == 12) {
                    $status = "Resolved";
                }
                 else {
                    $status = "Wip";
                }

				$stime = date('Y-m-d H:i:s', (int)$data['date']);
				$etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');

                $duration = $queueDuration->queueDurationCalculator($stime, $etime);

                $str_arr = preg_split ("/\:/", $duration);
                //pr($str_arr);
                //echo $data['reference_number'].'----'.$stime.'--'.$etime.'--'.$duration;

                ?>
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
					@ENDIF

					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['created_by_name'])) ? $data['created_by_name'] : $data['created_by'] }} </td>
					<td class="text-center vcenter no-padding-margin-tb">{{$data['source_maker'] != null ? $data['source_maker'] : ""}}</td>
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
                            {{ !empty($data['access_by']) ? $data['access_by'] : $data['name'] }}
                            &nbsp;[{{($data['unit_id']==1)? "Maker":"Checker"}}]
                        @endif


					</td>
					<td class="text-center vcenter no-padding-margin-tb">{{$duration}}</td>
					@if( $searchDataForView["form_type"] != 'noncustomer')
                    <?php
                    /*
                    <td class="text-center vcenter no-padding-margin-tb">{{ $remaining }}</td>
                    */
                    ?>

                    {{--<td class="text-center vcenter no-padding-margin-tb">{{$data['priority']}}</td>--}}
					@endif
					<td class="text-center vcenter no-padding-margin-tb">{{ $status }}</td>
					<td class="text-center vcenter no-padding-margin-tb">
						@IF($data['form_status'] == 11)
							{{ $data['last_access_dt'] }}
						@ENDIF
					</td>
                    <td class="text-center vcenter no-padding-margin-tb">
                        @IF($data['form_status'] == 11)
                            {{ (!empty($data['close_comments'])) ? $data['close_comments'] : ''}}
                        @ENDIF
                    </td>

					<!-- Information Section  -->
					@IF( $searchDataForView["form_type"] == 'wform')
					<td class="text-center vcenter no-padding-margin-tb"> '{{ $data['account_number'] }}</td>
					@ELSEIF( $searchDataForView["form_type"] == 'complaint')
					<td class="text-center vcenter no-padding-margin-tb"> '{{ $data['account_number'] }}</td>
					@ELSEIF( $searchDataForView["form_type"] == 'noncustomer')

					@ENDIF
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['customer_name']) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['acc_name']) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['mobile_number'] }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['email_address'] }}</td>
					@if( $searchDataForView["form_type"] != 'noncustomer')
						<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type_name'])) ? htmlentities($data['product_type_name']) : htmlentities($data['product_type_ext']) }}</td>
                        <td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['caller_id'])) ? htmlentities($data['caller_id']) : '-' }}</td>
					@endif
					{{--<td class="text-center vcenter no-padding-margin-tb">{{ $data['time_and_ext'] }}</td>--}}
					@if( $searchDataForView["form_type"] != 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['SIF_Number'] }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['date_of_birth'] }}</td>
                    <td class="text-center vcenter no-padding-margin-tb">{{ $data['segment'] }}</td>
					@endif
					<!-- Action Section -->
					@if( $searchDataForView["form_type"] != 'noncustomer')
						@IF( $searchDataForView["form_type"] == 'wform')
							<td class="text-center vcenter no-padding-margin-tb">{{ $data['notes'] }}</td>
						@ELSE
							<td class="text-center vcenter no-padding-margin-tb">{{ $data['complaint_details'] }}</td>
						@ENDIF
						<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['form_type'] }}</td>
					@else
						<td class="text-center vcenter no-padding-margin-tb"></td>
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
                                @if($data['w_form_type'] != 1103 && $data['w_form_type'] != 1105)
                                    @FOREACH($extraFieldArr AS $extFld)
                                        <td style="" class="text-center vcenter no-padding-margin-tb">'{{ current($extFld) }}</td>
                                    @ENDFOREACH
                                @endif
                            @ENDIF
                        @else
                            @IF(!empty($extraFieldArr))
                                @if($data['w_form_type'] != 1103 && $data['w_form_type'] != 1105)
                                    @FOREACH($extraFieldArr AS $extFld)
                                        @php //prd(current($extFld)); @endphp
                                        <td class="text-center vcenter no-padding-margin-tb">{{ key($extFld)}}:{{current($extFld)}}</td>
                                    @ENDFOREACH
                                @else
                                    @php
                                        $P = !empty($extraFieldArr['P']) ? $extraFieldArr['P'] : [];
                                        $C = !empty($extraFieldArr['C']) ? $extraFieldArr['C'] : [];
                                        $N = !empty($extraFieldArr['N']) ? $extraFieldArr['N'] : [];
                                        $MQ = !empty($extraFieldArr['MQ']) ? $extraFieldArr['MQ'] : [];
                                    @endphp

                                    @if(!empty($P))
                                        @php unset($P['request_type']);unset($P['customer_id']);unset($P['response']); @endphp
                                        @foreach($P AS $extFld)
                                            @php unset($extFld['api_key']); @endphp
                                            @foreach($extFld as $key => $value)
                                                <td class="text-center vcenter no-padding-margin-tb">{{ $key }}(Passport) : {{ $value }}</td>
                                            @endforeach
                                        @endforeach
                                    @endif

                                    @if(!empty($C))
                                        @php $CRQT = $C['request_type']; unset($C['request_type']);unset($C['quota_id']);unset($C['customer_info']); unset($C['response']); @endphp
                                        @foreach($C AS $extFld)
                                            @php $apikey = explode(':', $extFld['api_key']); unset($extFld['api_key']); @endphp
                                            @foreach($extFld as $key => $value)
                                                @if($apikey[0] == 'isActive' || $apikey[0] == 'ecomIsActive' || $apikey[0] == 'ecomThrActive')
                                                    @if($value == 1)
                                                        @php $value = 'Active'; @endphp
                                                    @else
                                                        @php $value = 'Inactive'; @endphp
                                                    @endif
                                                @endif
                                                @if($CRQT == 'ADD')
                                                    @if($apikey[0] != 'unUsagePercentage')
                                                        <td class="text-center vcenter no-padding-margin-tb">{{ $key }}(TQ-Current Year) : {{ $value }}</td>
                                                    @endif
                                                @else
                                                    @if($apikey[0] != 'limitUsagePercentage' && $apikey[0] != 'limitStartDate' && $apikey[0] != 'limitEndDate')
                                                        <td class="text-center vcenter no-padding-margin-tb">{{ $key }}(TQ-Current Year) : {{ $value }}</td>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif

                                    @if(!empty($N))
                                        @php $NRQT = $N['request_type']; unset($N['request_type']);unset($N['quota_id']);unset($N['customer_info']); unset($N['response']); @endphp
                                        @foreach($N AS $extFld)
                                            @php $apikey = explode(':', $extFld['api_key']); unset($extFld['api_key']); @endphp
                                            @foreach($extFld as $key => $value)
                                                @if($apikey[0] == 'isActive' || $apikey[0] == 'ecomIsActive' || $apikey[0] == 'ecomThrActive')
                                                    @if($value == 1)
                                                        @php $value = 'Active'; @endphp
                                                    @else
                                                        @php $value = 'Inactive'; @endphp
                                                    @endif
                                                @endif
                                                @if($NRQT == 'ADD')
                                                    @if($apikey[0] != 'unUsagePercentage')
                                                        <td class="text-center vcenter no-padding-margin-tb">{{ $key }}(TQ-Next Year) : {{ $value }}</td>
                                                    @endif
                                                @else
                                                    @if($apikey[0] != 'limitUsagePercentage' && $apikey[0] != 'limitStartDate' && $apikey[0] != 'limitEndDate')
                                                        <td class="text-center vcenter no-padding-margin-tb">{{ $key }}(TQ-Next Year) : {{ $value }}</td>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif

                                    @if(!empty($MQ))
                                        @php $MQRQT = $MQ['request_type']; unset($MQ['request_type']);unset($MQ['quota_id']);unset($MQ['customer_info']); unset($MQ['response']); @endphp
                                        @foreach($MQ AS $extFld)
                                            @php $apikey = explode(':', $extFld['api_key']); unset($extFld['api_key']); @endphp
                                            @foreach($extFld as $key => $value)
                                                @if($apikey[0] == 'isActive' || $apikey[0] == 'ecomIsActive' || $apikey[0] == 'ecomThrActive')
                                                    @if($value == 1)
                                                        @php $value = 'Active'; @endphp
                                                    @else
                                                        @php $value = 'Inactive'; @endphp
                                                    @endif
                                                @endif
                                                @if($MQRQT == 'ADD')
                                                    @if($apikey[0] != 'unUsagePercentage')
                                                        <td class="text-center vcenter no-padding-margin-tb">{{ $key }}(Medical Quota) : {{ $value }}</td>
                                                    @endif
                                                @else
                                                    @if($apikey[0] != 'limitUsagePercentage' && $apikey[0] != 'limitStartDate' && $apikey[0] != 'limitEndDate')
                                                        <td class="text-center vcenter no-padding-margin-tb">{{ $key }}(Medical Quota) : {{ $value }}</td>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif

                                @endif
                            @ENDIF
                        @endif
                    @endif
				</tr>
			@ENDFOREACH
		@ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="21">Data not available</th></tr>
		@ENDIF
        </tbody>
    </table>
</div>
