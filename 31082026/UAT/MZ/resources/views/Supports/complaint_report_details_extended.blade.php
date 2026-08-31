<?php $i=1;
$w_form_type_history = "";
?>
@if(!empty($dataForView['extra_field']))

	@php $extra_fields = (array)json_decode($dataForView['extra_field']);
		$count = count($extra_fields);
		$w_form_type_history = \App\ComplaintFormTypeHistory::where('reference_number',$dataForView['reference_number'])->get();
	@endphp

	<tr>
        <th colspan="6">
            <div class="row">
                <div class="col-md-6">
                    <h5>Issue Data</h5>
                </div>
                <div class="col-md-6 text-right">
                    <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-toggle="modal" data-target="#issueHistoryModal" class="text-right"><i class="fa fa-list"></i> </a>
                </div>
            </div>
        </th>
	</tr>

	@foreach($extra_fields as $key=>$r)
		@php $m_value=false; @endphp
		@foreach($r as $key1=>$value)

			@if(!empty($w_form_type_history))
            @foreach($w_form_type_history as $history)
                @foreach(json_decode($history->extra_field) as $em_field)

                    @foreach($em_field as $key=>$e)
                       @if($key==$key1)
                           @if($e!=$value)
                            @php $m_value=true;@endphp
                           @endif
                       @endif
                    @endforeach
                @endforeach
            @endforeach
            @endif

			@if($i==1)
				<tr>
			@endif

				@php //$a = get_service_request_value($dataForView['complaint_type']); @endphp
				@php //$b = get_service_request_label($dataForView['complaint_type']); @endphp

				<th>{{ $key1 }}</th>
				<td @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>

			@if($i == 3)
			</tr>
			<?php $i=0;?>
			@elseif($count == 1)
				@if($i == 1)
					<th>&nbsp;</th>
					<td>&nbsp;</td>
					<th>&nbsp;</th>
					<td>&nbsp;</td>
					</tr>
				@elseif($i == 2)
					<th>&nbsp;</th>
					<td>&nbsp;</td>
					</tr>
				@endif
			@endif

			<?php $i++; $count--;?>

		@endforeach

	@endforeach
@endif

<?php $j=1; ?>
@if(!empty($dataForView['check_list']))

	@php $check_list = (array)json_decode($dataForView['check_list']);
		$count1 = count($check_list);
	@endphp

	<tr>
		<th colspan="6">
            <div class="row">
                <div class="col-md-6">
                    <h5>Check List</h5>
                </div>
                @if(empty($dataForView['extra_field']))
                <div class="col-md-6 text-right">
                    <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-toggle="modal" data-target="#issueHistoryModal" class="text-right"><i class="fa fa-list"></i> </a>
                </div>
                @endif
            </div>
		</th>
	</tr>

	@foreach($check_list as $key=>$r)
		@php $cl_value=false; @endphp
		@foreach($r as $key1=>$value)

			@if(!empty($w_form_type_history))
                @foreach($w_form_type_history as $history)
                @foreach(json_decode($history->check_list) as $ck_field)

                    @foreach($ck_field as $key=>$ck)
                        @if($key==$key1)
                            @if($ck!=$value)
                                @php $cl_value=true;@endphp
                            @endif
                        @endif
                    @endforeach
                    @endforeach
                @endforeach
            @endif

			@if($j==1)
				<tr>
			@endif

				@php //$a = get_checklist_request_value($dataForView['complaint_type']); @endphp
				@php //$b = get_checklist_request_label($dataForView['complaint_type']); @endphp

				<th>{{ $key1 }}</th>
				<td @if($cl_value=='true') style="background-color:#97333352" @endif>{{ (!empty($value))? $value:"No" }}</td>

			@if($j == 3)
			</tr>
			<?php $j=0;?>
			@elseif($count1 == 1)
				@if($j == 1)
					<th>&nbsp;</th>
					<td>&nbsp;</td>
					<th>&nbsp;</th>
					<td>&nbsp;</td>
					</tr>
				@elseif($j == 2)
					<th>&nbsp;</th>
					<td>&nbsp;</td>
					</tr>
				@endif
			@endif

			<?php $j++; $count1--;?>

		@endforeach

	@endforeach
@endif

