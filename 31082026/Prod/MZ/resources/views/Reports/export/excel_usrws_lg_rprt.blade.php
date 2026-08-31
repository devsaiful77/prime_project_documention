@php //prd($dataForView) 
@endphp
@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
	<table class="table table-bordered table-condensed">
		<thead style="background-color: #337ab7;">
		<tr>
			<th class="text-center vcenter wordwrap ">Ticket Number</th>
			<th class="text-center vcenter wordwrap ">Account Number/Customer ID</th>
			<th class="text-center vcenter wordwrap ">Customer Name</th>
			<th class="text-center vcenter wordwrap ">Product Type</th>
			@IF( $searchDataForView["form_type"] == 'wform')
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
			<th class="text-center vcenter wordwrap ">Status</th>
			<th class="text-center vcenter wordwrap ">Last Work By</th>
			<th class="text-center vcenter wordwrap ">Close Date</th>
		</tr>
		</thead>
		<tbody>
		@IF(!empty($dataForView))
			@FOREACH($dataForView AS $data)
				<?php
                $commentDatas = getCommentsInDateTimeRef($data['reference_number']);
                $data['last_comment'] = $commentDatas['last_comment'];
                $data['in_date_time'] = $commentDatas['in_date_time'];
				$getCommentInfo = getCommentsActionTimeAndCloseComment($data['reference_number']);
				$data['action_time'] = $getCommentInfo['action_time'];
				$stime = date('Y-m-d H:i:s', (int)$data['date']);
				$etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');

                $duration = $queueDuration->queueDurationCalculator($stime, $etime);

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

                if (empty($data['form_type'])) {
                	$data['form_type'] = "N/A";
                }
                ?>
				<tr>
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
						<td class="text-center vcenter no-padding-margin-tb">N/A</td>
					@ENDIF

					<td class="text-center vcenter no-padding-margin-tb">'{{ (!empty($data['account_number'])) ? $data['account_number']: 'N/A' }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['customer_name']) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type_name'])) ? htmlentities($data['product_type_name']): 'N/A' }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? htmlentities($data['category_name']) : htmlentities($data['form_type']) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['created_by_name'])) ? htmlentities($data['created_by_name']) : htmlentities($data['created_by']) }} {{$data['source_maker']!=null? "(".$data['source_maker'].")" : ""}}</td>
					@if ($searchDataForView['date_type'] == 'action_date')
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('d-m-Y') }}
						</td>
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('H:i:s') }}
						</td>
                    @else
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('d-m-Y') }}
						</td>
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('H:i:s') }}
						</td>
                    @endif
					<td class="text-center vcenter no-padding-margin-tb">

					@if($data['form_status'] == 11)
						Form Close at {{ htmlentities($data['name']) }}&nbsp;[{{($data['unit_id']==1)? "Maker":"Checker"}}]
		            @else
		                {{ htmlentities($data['name']) }}&nbsp;[{{($data['unit_id']==1)? "Maker":"Checker"}}]
		            @endif

					</td>
					<td class="text-center vcenter no-padding-margin-tb">{{$duration}}</td>
					@if( $searchDataForView["form_type"] != 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['segment']) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['priority']) }}</td>
					@else
					<td class="text-center vcenter no-padding-margin-tb"></td>
					<td class="text-center vcenter no-padding-margin-tb"></td>
					@endif
					<td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($status) }}</td>
					<td class="text-center vcenter no-padding-margin-tb">
                        @php
                            $lastAccess = "";
                            if (!empty($data['last_comment'])) {
                                $lastAccess = $data['last_comment']['user_id'];
                            }
                        @endphp
                        {{ !empty($lastAccess) ? $lastAccess : $data['created_by'] }}
                    </td>
					<td class="text-center vcenter no-padding-margin-tb">
						@IF($data['form_status'] == 11)
							{{ $data['last_access_dt'] }}
						@ENDIF
					</td>
				</tr>
			@ENDFOREACH
		@ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="9">Data not available</th></tr>
		@ENDIF
		</tbody>
		{{--<tfoot>
		@IF(!empty($dataObj))
			@IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right vcenter no-padding-margin-tb" colspan="9">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF
        @ENDIF
        </tfoot>--}}
	</table>
</div>
