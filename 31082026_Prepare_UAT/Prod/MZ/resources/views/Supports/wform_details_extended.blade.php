<?php $i=1;$x=1;$y=1;$z=1;
$w_form_type_history = "";
//pr($dataForView);
?>
@if(!empty($dataForView['w_form_type']['extra_field']))

	@php $extra_fields = (array)json_decode($dataForView['w_form_type']['extra_field'], true);
		//prd($extra_fields);
		$count = count($extra_fields);
		$w_form_type_history = \App\WFormTypeHistory::where('reference_number',$dataForView['reference_number'])->get();
	@endphp
    @if(!empty($dataForView['issue_id'] == 1103) || !empty($dataForView['issue_id'] == 1105))
        <tr>
            <th class="quotd" colspan="6">
                <div class="row">
                    <div class="col-md-6"><h5>Issue Data</h5></div>
                    <div class="col-md-6 text-right">
                        @if(Auth::user()->hasRole(['superadmin', 'admin', 'maker']))
                            @if(!empty($dataForView['access_by']) && (Auth::user()->user_id == $dataForView['access_by']))
                                <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-bs-toggle="modal" data-bs-target="#issueModal" class="text-right modalissuebar"><i class="fa fa-edit"></i> </a>
                            @endif
                        @endif
                        <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-bs-toggle="modal" data-bs-target="#issueHistoryModal" class="text-right modalissuebar"><i class="fa fa-list"></i> </a>
                    </div>
                </div>
            </th>
        </tr>

        @if(!empty($extra_fields["P"]))
            <tr class="quotd">
                <th class="quotd" colspan="6"><h5>Passport</h5></th>
            </tr>
            @php
             unset($extra_fields["P"]['request_type']);
             unset($extra_fields["P"]['customer_id']);
             unset($extra_fields["P"]['response']);
             $count1 = count($extra_fields["P"]);
            @endphp
            @foreach($extra_fields["P"] as $key=>$r)
                @php $m_value=false; unset($r['api_key']) @endphp
                @foreach($r as $key1=>$value)
                    @if(!empty($w_form_type_history))
                        @foreach($w_form_type_history as $history)
                            @php
                                 $history = $history->toArray();
                                 $historyDecode = (array)json_decode($history['extra_field'], true);
                                 unset($historyDecode["P"]['request_type']);
                                 unset($historyDecode["P"]['customer_id']);
                                 unset($historyDecode["P"]['response']);
                            @endphp
                            @foreach($historyDecode['P'] as $em_field)
                                @php unset($em_field['api_key']); @endphp
                                @foreach($em_field as $key => $e)
                                    @if($key==$key1)
                                        @if($e!=$value)
                                            @php $m_value=true;@endphp
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                    @endif

                    @if($x==1)
                        <tr class="quotd">
                            @endif

                            <th class="quotd">{{ $key1 }}</th>
                            <td class="quotd" @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>

                            @if($x == 3)
                        </tr>
                            <?php $x=0;?>
                    @elseif($count1 == 1)
                        @if($x == 1)
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            </tr>
                        @elseif($x == 2)
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            </tr>
                            @else
                                </tr>
                        @endif
                    @endif
                        <?php $x++; $count1--;?>
                @endforeach
            @endforeach
        @endif

        @if(!empty($extra_fields["C"]))
            <tr class="quotd">
                <th class="quotd" colspan="6"><h5>Current Year</h5></th>
            </tr>
            @php
                $CrequestType = $extra_fields["C"]['request_type'];
                unset($extra_fields["C"]['request_type']);
                unset($extra_fields["C"]['quota_id']);
                unset($extra_fields["C"]['customer_info']);
                unset($extra_fields["C"]['response']);
                $count2 = count($extra_fields["C"]);
            @endphp
            @foreach($extra_fields["C"] as $key=>$r)
                @php $m_value=false; $apikey = explode(':', $r['api_key']); unset($r['api_key']) @endphp
                @foreach($r as $key1=>$value)
                    @if(!empty($w_form_type_history))
                        @foreach($w_form_type_history as $history)
                            @php
                                $history = $history->toArray();
                                $historyDecode = (array)json_decode($history['extra_field'], true);
                                unset($historyDecode["C"]['request_type']);
                                unset($historyDecode["C"]['customer_info']);
                                unset($historyDecode["C"]['response']);
                                unset($historyDecode["C"]['quota_id']);
                            @endphp
                            @foreach($historyDecode['C'] as $em_field)
                                @php unset($em_field['api_key']); @endphp
                                @foreach($em_field as $key => $e)
                                    @if($key==$key1)
                                        @if($e!=$value)
                                            @php $m_value=true;@endphp
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                    @endif

                    @if($y==1)
                        <tr class="quotd">
                            @endif

                            @if($apikey[0] == 'isActive' || $apikey[0] == 'ecomIsActive' || $apikey[0] == 'ecomThrActive')
                                @if($value == 1)
                                    @php $value = 'Active'; @endphp
                                @else
                                    @php $value = 'Inactive'; @endphp
                                @endif
                            @endif

                            @if($CrequestType == 'ADD')
                                @if($apikey[0] != 'unUsagePercentage')
                                    <th class="quotd">{{ $key1 }}</th>
                                    <td class="quotd" @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>
                                @else
                                    <th class="quotd"></th>
                                    <td class="quotd"></td>
                                @endif
                            @else
                                @if($apikey[0] != 'limitUsagePercentage' && $apikey[0] != 'limitStartDate' && $apikey[0] != 'limitEndDate')
                                    <th class="quotd">{{ $key1 }}</th>
                                    <td class="quotd" @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>
                                @else
                                    <th class="quotd"></th>
                                    <td class="quotd"></td>
                                @endif
                            @endif

                            @if($y == 3)
                        </tr>
                            <?php $y=0;?>
                    @elseif($count2 == 1)
                        @if($y == 1)
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            </tr>
                        @elseif($y == 2)
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            </tr>
                            @else
                                </tr>
                        @endif
                    @endif
                        <?php $y++; $count2--;?>
                @endforeach
            @endforeach
        @endif

        @if(!empty($extra_fields["N"]))
            <tr class="quotd">
                <th class="quotd" colspan="6"><h5>Next Year</h5></th>
            </tr>
            @php
                $NrequestType = $extra_fields["N"]['request_type'];
                unset($extra_fields["N"]['request_type']);
                unset($extra_fields["N"]['quota_id']);
                unset($extra_fields["N"]['customer_info']);
                unset($extra_fields["N"]['response']);
                $count3 = count($extra_fields["N"]);
            @endphp
            @foreach($extra_fields["N"] as $key=>$r)
                @php $m_value=false; $apikey = explode(':', $r['api_key']); unset($r['api_key']) @endphp
                @foreach($r as $key1=>$value)
                    @if(!empty($w_form_type_history))
                        @foreach($w_form_type_history as $history)
                            @php
                                $history = $history->toArray();
                                $historyDecode = (array)json_decode($history['extra_field'], true);
                                unset($historyDecode["N"]['request_type']);
                                unset($historyDecode["N"]['customer_info']);
                                unset($historyDecode["N"]['response']);
                                unset($historyDecode["N"]['quota_id']);
                            @endphp
                            @foreach($historyDecode['N'] as $em_field)
                                @php unset($em_field['api_key']); @endphp
                                @foreach($em_field as $key => $e)
                                    @if($key==$key1)
                                        @if($e!=$value)
                                            @php $m_value=true;@endphp
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                    @endif

                    @if($z==1)
                        <tr>
                            @endif

                            @if($apikey[0] == 'isActive' || $apikey[0] == 'ecomIsActive' || $apikey[0] == 'ecomThrActive')
                                @if($value == 1)
                                    @php $value = 'Active'; @endphp
                                @else
                                    @php $value = 'Inactive'; @endphp
                                @endif
                            @endif

                            @if($NrequestType == 'ADD')
                                @if($apikey[0] != 'unUsagePercentage')
                                    <th class="quotd">{{ $key1 }}</th>
                                    <td class="quotd" @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>
                                @else
                                    <th class="quotd"></th>
                                    <td class="quotd"></td>
                                @endif
                            @else
                                @if($apikey[0] != 'limitUsagePercentage' && $apikey[0] != 'limitStartDate' && $apikey[0] != 'limitEndDate')
                                    <th class="quotd">{{ $key1 }}</th>
                                    <td class="quotd" @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>
                                @else
                                    <th class="quotd"></th>
                                    <td class="quotd"></td>
                                @endif
                            @endif

                            @if($z == 3)
                        </tr>
                            <?php $z=0;?>
                    @elseif($count3 == 1)
                        @if($z == 1)
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            </tr>
                        @elseif($z == 2)
                            <th class="quotd">&nbsp;</th>
                            <td class="quotd">&nbsp;</td>
                            </tr>
                            @else
                                </tr>
                        @endif
                    @endif
                        <?php $z++; $count3--;?>
                @endforeach
            @endforeach
        @endif

        @if(!empty($extra_fields["MQ"]))
        <tr class="quotd">
            <th class="quotd" colspan="6"><h5>Medical Quota</h5></th>
        </tr>
        @php
            $MQrequestType = $extra_fields["MQ"]['request_type'];
            unset($extra_fields["MQ"]['request_type']);
            unset($extra_fields["MQ"]['quota_id']);
            unset($extra_fields["MQ"]['customer_info']);
            unset($extra_fields["MQ"]['response']);
            $count3 = count($extra_fields["MQ"]);
        @endphp
        @foreach($extra_fields["MQ"] as $key=>$r)
            @php $m_value=false; $apikey = explode(':', $r['api_key']); unset($r['api_key']) @endphp
            @foreach($r as $key1=>$value)
                @if(!empty($w_form_type_history))
                    @foreach($w_form_type_history as $history)
                        @php
                            $history = $history->toArray();
                            $historyDecode = (array)json_decode($history['extra_field'], true);
                            unset($historyDecode["MQ"]['request_type']);
                            unset($historyDecode["MQ"]['customer_info']);
                            unset($historyDecode["MQ"]['response']);
                            unset($historyDecode["MQ"]['quota_id']);
                        @endphp
                        @foreach($historyDecode['MQ'] as $em_field)
                            @php unset($em_field['api_key']); @endphp
                            @foreach($em_field as $key => $e)
                                @if($key==$key1)
                                    @if($e!=$value)
                                        @php $m_value=true;@endphp
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                @endif
                @if($z==1)
                    <tr class="quotd">
                        @endif

                        @if($apikey[0] == 'isActive' || $apikey[0] == 'ecomIsActive' || $apikey[0] == 'ecomThrActive')
                            @if($value == 1)
                               @php $value = 'Active'; @endphp
                            @else
                                @php $value = 'Inactive'; @endphp
                            @endif
                        @endif

                        @if($MQrequestType == 'ADD')
                            @if($apikey[0] != 'unUsagePercentage')
                                <th class="quotd">{{ $key1 }}</th>
                                <td class="quotd" @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>
                            @else
                                <th class="quotd"></th>
                                <td class="quotd"></td>
                            @endif
                        @else
                            @if($apikey[0] != 'limitUsagePercentage' && $apikey[0] != 'limitStartDate' && $apikey[0] != 'limitEndDate')
                                <th class="quotd">{{ $key1 }}</th>
                                <td class="quotd" @if($m_value=='true') style="background-color:#97333352" @endif>{{ (isset($value))? $value:"" }}</td>
                            @else
                                <th class="quotd"></th>
                                <td class="quotd"></td>
                            @endif
                        @endif

                        @if($z == 3)
                    </tr>
                        <?php $z=0;?>
                @elseif($count3 == 1)
                    @if($z == 1)
                        <th class="quotd">&nbsp;</th>
                        <td class="quotd">&nbsp;</td>
                        <th class="quotd">&nbsp;</th>
                        <td class="quotd">&nbsp;</td>
                        </tr>
                    @elseif($z == 2)
                        <th class="quotd">&nbsp;</th>
                        <td class="quotd">&nbsp;</td>
                        </tr>
                        @else
                            </tr>
                    @endif
                @endif
                    <?php $z++; $count3--;?>
            @endforeach
        @endforeach
    @endif

    @else
        <tr>
            <th colspan="6">
                <div class="row">
                    <div class="col-md-6"><h5>Issue Data</h5></div>
                    <div class="col-md-6 text-right">
                    @if(Auth::user()->hasRole(['superadmin', 'admin', 'maker']))
                        @if(!empty($dataForView['access_by']) && (Auth::user()->user_id == $dataForView['access_by']))
                                <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-bs-toggle="modal" data-bs-target="#issueModal" class="text-right modalissuebar"><i class="fa fa-edit"></i> </a>
                        @endif
                    @endif
                        {{-- <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-toggle="modal" data-target="#issueHistoryModal" class="text-right modalissuebar"><i class="fa fa-list"></i> </a> --}}
                        <a href="#" data-bs-toggle="modal" data-bs-target="#issueHistoryModal" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" class="text-right modalissuebar">
                            <i class="fa fa-list"></i>
                        </a>

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

                    @php

                        //pr($key1);
                        //pr($value);

                    @endphp


                    <th>{{ $key1 }}</th>
                    <td @if($m_value=='true') style="background-color:#97333352" @endif>
                        {{ isset($value) ? (is_array($value) ? implode(', ', $value) : $value) : "" }}
                    </td>

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
                    @else
                    </tr>
                    @endif
                @endif

                <?php $i++; $count--;?>

            @endforeach
        @endforeach
        @endif

    @endif


<?php $j=1; ?>
@if(!empty($dataForView['w_form_type']['check_list']))

	@php $check_list = (array)json_decode($dataForView['w_form_type']['check_list']);
		$count1 = count($check_list);
	@endphp

	<tr>
		<th colspan="6">
            <div class="row">
                <div class="col-md-6"><h5>Check List</h5></div>
                <div class="col-md-6 text-right">
                    @if(empty($dataForView['w_form_type']['extra_field']))
                    @if(Auth::user()->hasRole(['superadmin', 'admin', 'maker']))
                    @if(!empty($dataForView['access_by']) && (Auth::user()->user_id == $dataForView['access_by']))
                    <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-bs-toggle="modal" data-bs-target="#issueModal" class="text-right"><i class="fa fa-edit"></i> </a>
                    @endif
                    @endif
                    <a href="#" data-reference="{{ $dataForView['reference_number'] }}" data-id="{{ $dataForView['main_id'] }}" data-bs-toggle="modal" data-bs-target="#issueHistoryModal" class="text-right"><i class="fa fa-list"></i> </a>
                    @endif
                </div>
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

				@php //$a = get_checklist_request_value($dataForView['main_id']); @endphp
				@php //$b = get_checklist_request_label($dataForView['main_id']); @endphp

				<th>{{ $key1 }}</th>
                    <td @if($cl_value=='true') style="background-color:#97333352" @endif>{{ (!empty($value))? $value:"No" }}</span></td>

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

