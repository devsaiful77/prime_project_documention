@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
	<table class="table table-bordered table-condensed commonDataTableAllAsc">
		<thead style="background-color: #337ab7;">
		<tr>
			<th style="color: white" class="text-center vcenter wordwrap ">Ticket Number</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Card / Acc Number</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Customer Name</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Product Type</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Service Request Type</th>
			<th style="color: white" class="text-center vcenter wordwrap no-padding-margin-tb">Maker</th>

			<th style="color: white" class="text-center vcenter wordwrap ">Log Date</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Log Time</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Current Position</th>
			<th style="color: white" class="text-center vcenter wordwrap ">TaT(H:M)</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Segment Code</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Status</th>
			<th style="color: white" class="text-center vcenter wordwrap ">Close Date</th>
		</tr>
		</thead>
		<tbody>
			@php //prd($dataForView) @endphp
		@IF(!empty($dataForView))
			@FOREACH($dataForView AS $data)
				<?php

				$stime = date('Y-m-d H:i:s', (int)$data->date);
				$etime = ($data->form_status == 11)? date('Y-m-d H:i:s', (int)$data->access_date) : date('Y-m-d H:i:s');

                $duration = $queueDuration->queueDurationCalculator($stime, $etime);

                $str_arr = preg_split ("/\:/", $duration);

                $form_status = $data->form_status;
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
				
                @if(($str_arr[0]*60)+$str_arr[1] > "1440")
						
						<tr style="background-color:#E9967A">
						
					@else
						<tr>
					@endif

					<td class="text-center vcenter no-padding-margin-tb">
						<a href="{{ url('/Supports/WFormReportDetails/'.encrypt($data->reference_number)) }}" target="_blank">{{ $data->reference_number }}</a>
						
					</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data->account_number }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data->customer_name }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data->product_type)) ? $data->product_type : $data->product_type_ext }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data->form_type }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ $data->created_by }}</td>
					
					<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data->date)->format('d-m-Y') }}</td>
					<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data->date)->format('h:i:s a') }}</td>
					<td class="text-center vcenter no-padding-margin-tb">
					
					@if($data->form_status == 11)
		                    Form Close at {{ $data->name }}&nbsp;[{{($data->unit_id==1)? "Maker":"Checker"}}]
		            @else
		                {{ $data->name }}&nbsp;[{{($data->unit_id==1)? "Maker":"Checker"}}]
		            @endif

					</td>
					<td class="text-center vcenter no-padding-margin-tb">{{$duration}}</td>
					
					<td class="text-center vcenter no-padding-margin-tb">{{$data->segment}}</td>

					<td class="text-center vcenter no-padding-margin-tb">{{ $status }}</td>
					<td class="text-center vcenter no-padding-margin-tb">
						@IF($data->form_status == 11)
							{{ $data->last_access_dt }}						
						@ENDIF
					</td>
				</tr>
			@ENDFOREACH
		@ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="9">Data not available</th></tr>
		@ENDIF
		</tbody>
		
	</table>
</div>
