@php //prd($dataForView) @endphp
@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
	<table class="table table-bordered table-condensed">
		<thead style="background-color: ;">
		<tr>
			<th style="color: " class="text-center vcenter wordwrap ">Ticket Number</th>
			<th style="color: " class="text-center vcenter wordwrap ">Card / Acc Number</th>
			<th style="color: " class="text-center vcenter wordwrap ">Customer Name</th>
			<th style="color: " class="text-center vcenter wordwrap ">Product Type</th>
			@IF( $searchDataForView["form_type"] == 'wform')
			<th style="color: " class="text-center vcenter wordwrap ">Service Request Type</th>
			@else
			<th style="color: " class="text-center vcenter wordwrap ">Complaint Type</th>
			@endif

			<th style="color: " class="text-center vcenter wordwrap no-padding-margin-tb">Maker</th>
			@if($searchDataForView['date_type'] == 'action_date')
				<th style="color: " class="text-center vcenter nowrap ">Action Date</th>
				<th style="color: " class="text-center vcenter nowrap ">Action Time</th>
			@else
				<th style="color: " class="text-center vcenter nowrap ">Log Date</th>
				<th style="color: " class="text-center vcenter nowrap ">Log Time</th>
			@endif
			<th style="color:" class="text-center vcenter wordwrap ">Current Position</th>

			<th style="color: " class="text-center vcenter wordwrap ">TaT(D:H:M:S)</th>
			<th style="color: " class="text-center vcenter wordwrap ">Status</th>
		</tr>
		</thead>
		<tbody>
			<?php  //echo '<pre>'; print_r($searchDataForView); print_r($dataForView); die;?>
		@IF(!empty($dataForView['data']))
			@FOREACH($dataForView['data'] AS $data)
				<?php
				$getCommentInfo = getCommentsActionTimeAndCloseComment($data['reference_number']);
				$data['action_time'] = $getCommentInfo['action_time'];
				$data['close_comments'] = $getCommentInfo['close_comments'];

				$stime = date('Y-m-d H:i:s', (int)$data['date']);
				$etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');

        $duration = $queueDuration->queueDurationCalculator($stime, $etime);
        // $smsEmailStatus = $queueDuration->getMailSMSStatus($data['reference_number']);

        $str_arr = preg_split ("/\:/", $duration);
        //pr($str_arr);
        //echo $data['reference_number'].'----'.$stime.'--'.$etime.'--'.$duration;

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

        $backgroundColor = "";
        if (($str_arr[0]*60)+$str_arr[1] > "1440") {
        	if ($searchDataForView["form_type"] != 'complaint') {
        		$backgroundColor = "background-color:#E9967A";
        	}
        }
        ?>

					<tr style="{{$backgroundColor}}">
						@IF( $searchDataForView["form_type"] == 'wform')
						<td class="text-center vcenter no-padding-margin-tb">
							<a href="{{ url('/Supports/WFormReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
						</td>
						<td class="text-center vcenter no-padding-margin-tb">{{ $data['account_number'] }}</td>
						@ELSEIF( $searchDataForView["form_type"] == 'complaint')
						<td class="text-center vcenter no-padding-margin-tb">
							<a href="{{ url('/Supports/ComplaintReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
						</td>
						<td class="text-center vcenter no-padding-margin-tb">{{ $data['account_number'] }}</td>
						@ELSEIF( $searchDataForView["form_type"] == 'noncustomer')
						<td class="text-center vcenter no-padding-margin-tb">
							<a href="{{ url('/Supports/NonCustomerReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
						</td>
						<td class="text-center vcenter no-padding-margin-tb"></td>
						@ENDIF
					</td>

					<td class="text-center vcenter no-padding-margin-tb">{{ $data['customer_name'] }}</td>

					@if( $searchDataForView["form_type"] != 'noncustomer')
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type_name'])) ? $data['product_type_name'] : $data['product_type_ext'] }}</td>

					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['form_type'] }}</td>
					@else
					<td class="text-center vcenter no-padding-margin-tb"></td>
					<td class="text-center vcenter no-padding-margin-tb"></td>
					@endif
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['created_by_name'])) ? $data['created_by_name'] : $data['created_by'] }} {{$data['source_maker']!=null? "(".$data['source_maker'].")" : ""}}</td>

					@if ($searchDataForView['date_type'] == 'action_date')
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('d-m-Y') }}</td>
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('H:i:s') }}</td>
                    @else
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('d-m-Y') }}</td>
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('H:i:s') }}</td>
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
					<td class="text-center vcenter no-padding-margin-tb">{{ $duration }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $status }}</td>
				</tr>
			@ENDFOREACH
		@ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="12">Data not available</th></tr>
		@ENDIF
		</tbody>
		<tfoot>
		@IF(!empty($dataObj))
			@IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right vcenter no-padding-margin-tb" colspan="12">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF
        @ENDIF
        </tfoot>
	</table>
</div>
