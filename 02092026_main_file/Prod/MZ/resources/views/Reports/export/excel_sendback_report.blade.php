@php //prd($dataForView->toArray()) @endphp
@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
	<table class="table table-bordered table-condensed">
		<thead style="background-color: #337ab7;">
		<tr>
			<th class="text-center vcenter wordwrap ">Ticket Number</th>
			<th class="text-center vcenter wordwrap ">Card / Acc Number</th>
			<th class="text-center vcenter wordwrap ">Customer Name</th>
			<th class="text-center vcenter wordwrap ">Product Type</th>
			@if( $searchDataForView["form_type"] == 'wform')
			<th class="text-center vcenter wordwrap ">Service Request Type</th>
			@else
			<th class="text-center vcenter wordwrap ">Complaint Type</th>
			@endif
			<th class="text-center vcenter wordwrap no-padding-margin-tb">Maker</th>
			@if($searchDataForView['date_type'] == 'action_date')
				<th class="text-center vcenter nowrap ">Action Date</th>
				<th class="text-center vcenter nowrap ">Action Time</th>
			@else
				<th class="text-center vcenter nowrap ">Log Date</th>
				<th class="text-center vcenter nowrap ">Log Time</th>
			@endif
			<th class="text-center vcenter wordwrap ">Current Position</th>
			<th class="text-center vcenter wordwrap ">TaT(D:H:M:S)</th>
			<th class="text-center vcenter wordwrap ">Segment Code</th>
			<th class="text-center vcenter wordwrap ">Priority</th>
			@if( $searchDataForView["form_type"] == 'complaint')
				<th class="text-center vcenter wordwrap ">Is Justified</th>
			@endif
			<th class="text-center vcenter wordwrap ">Status</th>
			<th class="text-center vcenter wordwrap ">Close Date</th>
			<?php
			/*
			<th class="text-center vcenter wordwrap ">Send SMS?</th>
			<th class="text-center vcenter wordwrap ">Send Mail?</th>
			*/
			?>
			<th class="text-center vcenter wordwrap ">Time &amp; Ext</th>
			<th class="text-center vcenter wordwrap ">Customer Number</th>
			<th class="text-center vcenter wordwrap ">Customer DOB</th>
			@IF( $searchDataForView["form_type"] == 'wform')
			<th class="text-center vcenter wordwrap ">Notes</th>
			@elseif ($searchDataForView["form_type"] == 'complaint')
			<th class="text-center vcenter wordwrap ">Complaint Details</th>
			@endif
			<th class="text-center vcenter wordwrap ">Close Comments</th>

		</tr>
		</thead>
		<tbody>
			<?php  //echo '<pre>'; print_r($searchDataForView); print_r($dataForView); die;?>
		@IF(!empty($dataForView))
			@FOREACH($dataForView AS $data)
				<?php
				$getCommentInfo = getCommentsActionTimeAndCloseComment($data['reference_number']);
				$data['action_time'] = $getCommentInfo['action_time'];
				$data['close_comments'] = $getCommentInfo['close_comments'];

				$stime = date('Y-m-d H:i:s', (int)$data['date']);
				$etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');

                $duration = $queueDuration->queueDurationCalculator($stime, $etime);
                // $smsEmailStatus = $queueDuration->getMailSMSStatus($data['reference_number']);

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
                ?>
				<tr>

					@IF( $searchDataForView["form_type"] == 'wform')
					<td class="text-center vcenter no-padding-margin-tb">
						<a href="{{ url('/Supports/WFormReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
					</td>
					<td class="text-center vcenter no-padding-margin-tb">     '{{ $data['account_number'] }}</td>
					@ELSEIF( $searchDataForView["form_type"] == 'complaint')
					<td class="text-center vcenter no-padding-margin-tb">
						<a href="{{ url('/Supports/ComplaintReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
					</td>
					<td class="text-center vcenter no-padding-margin-tb">'{{ $data['account_number'] }}</td>
					@ELSEIF( $searchDataForView["form_type"] == 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">
						<a href="{{ url('/Supports/NonCustomerReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
					</td>
					<td class="text-center vcenter no-padding-margin-tb">N/A</td>
					@ELSE
					<td class="text-center vcenter no-padding-margin-tb">N/A</td>
					<td class="text-center vcenter no-padding-margin-tb">N/A</td>

					@ENDIF

					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['customer_name']) }}</td>

					@if( $searchDataForView["form_type"] != 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type_name'])) ? htmlentities($data['product_type_name']) : htmlentities($data['product_type_ext']) }}</td>

					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? htmlentities($data['category_name']) : htmlentities($data['form_type']) }}</td>
					@else
					<td class="text-center vcenter no-padding-margin-tb"></td>
					<td class="text-center vcenter no-padding-margin-tb"></td>
					@endif
					<td class="text-center vcenter no-padding-margin-tb"> {{ (!empty($data['created_by_name'])) ? htmlentities($data['created_by_name']) : htmlentities($data['created_by']) }}  {{$data['source_maker']!=null? "(".$data['source_maker'].")" : ""}}</td>
					@if($searchDataForView['date_type'] == 'action_date')
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('d-m-Y') }}</td>
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('H:i:s') }}</td>
					@else
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('d-m-Y') }}</td>
						<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('H:i:s') }}</td>
					@endif

					<td class="text-center vcenter no-padding-margin-tb">

					@if($data['form_status'] == 11)
		                    Form Close at {{ htmlentities($data['name']) }}&nbsp;
		                    {{ !empty($data['access_by']) ? '-'.$data['access_by'] : '' }}
		                    [{{($data['unit_id']==1)? "Maker":"Checker"}}]
		            @else
                        {{ !empty($data['access_by']) ? htmlentities($data['access_by']) : htmlentities($data['name']) }}
                        &nbsp;[{{($data['unit_id']==1)? "Maker":"Checker"}}]
		            @endif


					</td>
					<td class="text-center vcenter no-padding-margin-tb">{{$duration}}</td>
					@if( $searchDataForView["form_type"] != 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['segment']) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['priority']) }}</td>
					@if( $searchDataForView["form_type"] == 'complaint')
						<td class="text-center vcenter no-padding-margin-tb">{{$data['is_justified_name']}}</td>
					@endif
					@else
					<td class="text-center vcenter no-padding-margin-tb"></td>
					<td class="text-center vcenter no-padding-margin-tb"></td>
					@endif
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($status) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">
						@IF($data['form_status'] == 11)
							{{ $data['last_access_dt'] }}
						@ENDIF
					</td>
					<?php
					/*
					<td class="text-center vcenter no-padding-margin-tb">{{ $smsEmailStatus['is_send_sms'] }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $smsEmailStatus['is_send_mail'] }}</td>
					*/
					?>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['time_and_ext'] }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['SIF_Number'] }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data['date_of_birth'] }}</td>
                    @IF( $searchDataForView["form_type"] == 'wform')
                        <td class="text-center vcenter no-padding-margin-tb">{{ $data['notes'] }}</td>
                    @ELSE
                        <td class="text-center vcenter no-padding-margin-tb">{{ $data['complaint_details'] }}</td>
                    @ENDIF
					<td class="text-center vcenter no-padding-margin-tb">
						@IF($data['form_status'] == 11)
							{{ (!empty($data['close_comments'])) ? $data['close_comments'] : ''}}
						@ENDIF
					</td>

				</tr>
			@ENDFOREACH
		@ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="14">Data not available</th></tr>
		@ENDIF
		</tbody>
	</table>
</div>
