<?php
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i=1;
$x=1;
$y=1;
$z=1;

$passportArr = array();
$currentYearArr = array();
$nextYearArr = array();

$exitPassportArr = array();
$exitCurrentYearArr = array();
$exitNextYearArr = array();
$exitMQArr = array();
$exitCInfo = array();

if (!empty($exits_data)){
    if (isset($exits_data['P'])){
        $exitPassportArr = $exits_data['P'];
    }
    if (isset($exits_data['C'])){
        $exitCurrentYearArr = $exits_data['C'];
    }
    if (isset($exits_data['N'])){
        $exitNextYearArr = $exits_data['N'];
    }
    if (isset($exits_data['MQ'])){
        $exitMQArr = $exits_data['MQ'];
    }
    if (isset($exits_data['CInfo'])){
        $exitCInfo = $exits_data['CInfo'];
    }
}

if (!empty($iris_fields)){
    // Fieldset group id Local (23,24,25), UAT ()
    if (isset($iris_fields[23])){
        $passportArr = $iris_fields[23];
    }
    if (isset($iris_fields[24])){
        $currentYearArr = $iris_fields[24];
    }
    if (isset($iris_fields[25])){
        $nextYearArr = $iris_fields[25];
    }
    if (isset($iris_fields[27])){
        $MQpassportArr = $iris_fields[27];
    }
    if (isset($iris_fields[28])){
        $MQArr = $iris_fields[28];
    }
}

?>

<input type="hidden" name="customer_info" value="{{ $exitCInfo }}">

@if(!empty($MQpassportArr) && !empty($exitPassportArr))
<h5>Passport</h5>
<table class="table quota-table">
    <thead>
        <h6 class="text-danger manipulate-error-msg"></h6>
        @php
            $count1 = count($MQpassportArr);
        @endphp
            <input type="hidden" class="passport-input" name="passport[request_type]" value="{{ $exitPassportArr['request_type'] }}" id="passportRequestType">
            <input type="hidden" class="passport-input" name="passport[customer_id]" id="passportCustomerId" value="{{ $exitPassportArr['customer_id'] }}">
            <input type="hidden" class="passport-input" name="passport[response]" id="passportCustomerId" value="{{ $exitPassportArr['response'] }}">
            @foreach($MQpassportArr as $key => $r)
                @php
                    $PApiKey = $r['api_key'];
                    $PApiKeyArr = explode(":", $PApiKey);
                    $PApiKeyId = $PApiKeyArr[0];
                @endphp
                @if($i == 1)
                    <tr>
                        @endif

                        @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                            @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']); unset($exitPassportArr['response']);@endphp
                            @foreach($exitPassportArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input passport-input" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                                @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                            </td>

                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                            @php $d_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']); unset($exitPassportArr['response']); @endphp
                            @foreach($exitPassportArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $d_value = $e;@endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <select class="quota-input passport-input" id="{{$PApiKeyId}}" name="passport[{{ $r['field_name'] }}]">

                                    @php
                                        $options = explode(",", $r['options']);
                                    @endphp
                                    @if(count($options) != 1)
                                        <option value="">Please Select</option>
                                    @endif
                                    @foreach($options as $k => $option)
                                        @php
                                            $selected  = "";
                                             $option_name = $option;
                                             $old = old('passport.' . $r['field_name']);
                                             if (str_contains($option_name,'~')) {
                                                 $option2 =  substr($option_name, 0, strpos($option_name, "~"));
                                                 $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                             }
                                             if (!empty($old) && str_contains($old,'~')) {
                                                 $old = substr($old, strpos($old, "~") + 1);
                                             }
                                             if($option2 == $d_value){
                                                 $selected  = "selected";
                                             }
                                        @endphp
                                        <option value="{{ $option }}" {{$selected}}>{{ $option_name }}</option>
                                    @endforeach
                                </select>
                                @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                            </td>

                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                            @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                            @foreach($exitPassportArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input passport-input" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                                @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                            @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                            @foreach($exitPassportArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input passport-input datePicker" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                                <script>
                                    $(document).ready(function () {
                                        $('.datePicker').datepicker({
                                            dateFormat: 'yy/mm/dd',
                                            changeYear: true,
                                            changeMonth: true,
                                            yearRange: "1900:2050",
                                        });
                                    });
                                </script>
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NEXT_DATE)
                            @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                            @foreach($exitPassportArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input passport-input datepickerNextQT" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                                <script>
                                    $(document).ready(function () {
                                        $('.datepickerNextQT').datepicker({
                                            minDate: 0,
                                            dateFormat: 'yy/mm/dd',
                                            showButtonPanel: true,
                                            changeYear: true,
                                            changeMonth: true,
                                            yearRange: "1900:2050",
                                        });
                                    });
                                </script>
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NUMBER)
                            @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                            @foreach($exitPassportArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input passport-input" name="passport[{{ $r['field_name'] }}]"
                                        placeholder="{{ $r['placeholder'] }}" maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" id="{{$PApiKeyId}}">
                                @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                            @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                            @foreach($exitPassportArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" name="passport[{{ $r['field_name'] }}]" class="quota-input passport-input" maxlength="{{ $r['maximum_length'] }}"
                                        value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                                        @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                        {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::CHECKBOX)
                            <th class="quotd vcenter" width="25%">
                                {{ $r['label_name'] }}
                            </th>
                            <td class="quotd vcenter" width="25%">
                                @php
                                    $options = '';
                                    $input_checkbox = '';
                                    $options = explode(",", $r['options']);
                                    $input_checkbox .= '<ul>';

                                    foreach ($options as $k => $option) {
                                            $value = $option;
                                            if(empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected  = "";
                                            if($value == old($r['field_type'])){
                                                $selected  = "checked";
                                            }
                                            $input_checkbox .= '<li><label><input type="checkbox" name="' . $r['field_type'] . '" value="' . $value . '" '.$selected.' id="'.$PApiKeyId.'">' . $option . '</label></li>';

                                    }
                                    $input_checkbox .= '</ul>';
                                @endphp
                                {!! $input_checkbox !!}
                            </td>

                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::RADIO)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                @php $options = explode(",", $r['options']);
                                    $input_radio .= '<ul>';
                                    foreach ($options as $k => $option) {
                                    $selected  = "";
                                    if($option == old($r['field_type'])){
                                        $selected  = "selected";
                                    }
                                    $input_radio .= '<li><input type="radio" name="' . $r['field_type'] . '" value="' . $option . '" '.$selected.' id="'.$PApiKeyId.'" ><label>' . $option . '</label></li>';
                                    }
                                    $input_radio .= '</ul>';
                                @endphp
                                    {!! $input_radio !!}
                            </td> --}}
                        @else
                        @endif

                        @if($i == 2)
                    </tr>
                        <?php $i=0;?>
                @elseif($count1 == 1)
                    @if($i == 1)
                        <th class="quotd vcenter" width="25%">&nbsp;</th>
                        <td class="quotd vcenter" width="25%">&nbsp;</td>
                        </tr>
                    @else
                        </tr>
                    @endif
                @endif
                <?php $i++; $count1--;?>
            @endforeach
    </thead>
</table>
@endif

@if(!empty($passportArr) && !empty($exitPassportArr))
    <h5>Passport</h5>
    <table class="table quota-table">
        <thead>
        <h6 class="text-danger manipulate-error-msg"></h6>
        @php
            $count1 = count($passportArr);
        @endphp
        <input type="hidden" class="passport-input" name="passport[request_type]" value="{{ $exitPassportArr['request_type'] }}" id="passportRequestType">
        <input type="hidden" class="passport-input" name="passport[customer_id]" id="passportCustomerId" value="{{ $exitPassportArr['customer_id'] }}">
        <input type="hidden" class="passport-input" name="passport[response]" id="passportCustomerId" value="{{ $exitPassportArr['response'] }}">
        @foreach($passportArr as $key => $r)
            @php
                $PApiKey = $r['api_key'];
                $PApiKeyArr = explode(":", $PApiKey);
                $PApiKeyId = $PApiKeyArr[0];
            @endphp
            @if($i == 1)
                <tr>
                    @endif

                    @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                        @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                        @foreach($exitPassportArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input passport-input" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                            @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                        </td>

                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                        @php $d_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                        @foreach($exitPassportArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $d_value = $e;@endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            <select class="quota-input passport-input" id="{{$PApiKeyId}}" name="passport[{{ $r['field_name'] }}]">

                                @php
                                    $options = explode(",", $r['options']);
                                @endphp
                                @if(count($options) != 1)
                                    <option value="">Please Select</option>
                                @endif
                                @foreach($options as $k => $option)
                                    @php
                                        $selected  = "";
                                         $option_name = $option;
                                         $old = old('passport.' . $r['field_name']);
                                         if (str_contains($option_name,'~')) {
                                             $option2 =  substr($option_name, 0, strpos($option_name, "~"));
                                             $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                         }
                                         if (!empty($old) && str_contains($old,'~')) {
                                             $old = substr($old, strpos($old, "~") + 1);
                                         }
                                         if($option2 == $d_value){
                                             $selected  = "selected";
                                         }
                                    @endphp
                                    <option value="{{ $option2 }}" {{$selected}}>{{ $option_name }}</option>
                                @endforeach
                            </select>
                            @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                        </td>

                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                        @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                        @foreach($exitPassportArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input passport-input" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                            @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                        @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                        @foreach($exitPassportArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            <input type="text" class="quota-input passport-input datePicker" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}" autocomplete="off" maxlength="10">
                            @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                            <script>
                                $(document).ready(function () {
                                    $('.datePicker').datepicker({
                                        dateFormat: 'yy/mm/dd',
                                        changeYear: true,
                                        changeMonth: true,
                                        yearRange: "1900:2050",
                                    });
                                });
                            </script>
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NEXT_DATE)
                        @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                        @foreach($exitPassportArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            <input type="text" class="quota-input passport-input js-date datepickerNextQT" name="passport[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}" autocomplete="off" maxlength="10">
                            @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                            <script>
                                $(document).ready(function () {
                                    $('.datepickerNextQT').datepicker({
                                        minDate: 0,
                                        dateFormat: 'yy/mm/dd',
                                        showButtonPanel: true,
                                        changeYear: true,
                                        changeMonth: true,
                                        yearRange: "1900:2050",
                                     });
                                });
                            </script>
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NUMBER)
                        @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']); unset($exitPassportArr['response']); @endphp
                        @foreach($exitPassportArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input passport-input" name="passport[{{ $r['field_name'] }}]"
                                   placeholder="{{ $r['placeholder'] }}" maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" id="{{$PApiKeyId}}">
                            @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                        @php $e_value = ''; unset($exitPassportArr['customer_id']); unset($exitPassportArr['request_type']);unset($exitPassportArr['response']); @endphp
                        @foreach($exitPassportArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" name="passport[{{ $r['field_name'] }}]" class="quota-input passport-input" maxlength="{{ $r['maximum_length'] }}"
                                   value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                            @IF($errors->has('passport.' . $r['field_name'])) <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                        {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::CHECKBOX)
                            <th class="quotd vcenter" width="25%">
                                {{ $r['label_name'] }}
                            </th>
                            <td class="quotd vcenter" width="25%">
                                @php
                                    $options = '';
                                    $input_checkbox = '';
                                    $options = explode(",", $r['options']);
                                    $input_checkbox .= '<ul>';

                                    foreach ($options as $k => $option) {
                                            $value = $option;
                                            if(empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected  = "";
                                            if($value == old($r['field_type'])){
                                                $selected  = "checked";
                                            }
                                            $input_checkbox .= '<li><label><input type="checkbox" name="' . $r['field_type'] . '" value="' . $value . '" '.$selected.' id="'.$PApiKeyId.'">' . $option . '</label></li>';

                                    }
                                    $input_checkbox .= '</ul>';
                                @endphp
                                {!! $input_checkbox !!}
                            </td>

                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::RADIO)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                @php $options = explode(",", $r['options']);
                                    $input_radio .= '<ul>';
                                    foreach ($options as $k => $option) {
                                    $selected  = "";
                                    if($option == old($r['field_type'])){
                                        $selected  = "selected";
                                    }
                                    $input_radio .= '<li><input type="radio" name="' . $r['field_type'] . '" value="' . $option . '" '.$selected.' id="'.$PApiKeyId.'" ><label>' . $option . '</label></li>';
                                    }
                                    $input_radio .= '</ul>';
                                @endphp
                                    {!! $input_radio !!}
                            </td> --}}
                    @else
                    @endif

                    @if($i == 2)
                </tr>
                    <?php $i=0;?>
            @elseif($count1 == 1)
                @if($i == 1)
                    <th class="quotd vcenter" width="25%">&nbsp;</th>
                    <td class="quotd vcenter" width="25%">&nbsp;</td>
                    </tr>
                    @else
                        </tr>
                @endif
            @endif
                <?php $i++; $count1--;?>
        @endforeach
        </thead>
    </table>
@endif
<style>
    .dropdown-readonly {pointer-events: none; background: #e8e8e8;}
</style>
@if(!empty($currentYearArr) && !empty($exitCurrentYearArr))
    @if($exitCurrentYearArr['request_type'] == 'MOD')
        <style>
            .C_limitUsagePercentage {display: none;}
            #C_limitUsagePercentage{display: none}
            .C_limitStartDate{display: none}
            #C_limitStartDate{display: none}
            .C_limitEndDate{display: none}
            #C_limitEndDate{display: none}
        </style>
    @else
        <style>
            .C_unUsagePercentage {display: none;}
            #C_unUsagePercentage{display: none}
            .C_limitAmount{display: none}
            #C_limitAmount{display: none}
        </style>
    @endif
<table class="table quota-table">
    <thead>
    <tr class="quotd vcenter">
        <th class="quotd vcenter"><h6>Current Year</h6></th>
    </tr>

    @php
        $count2 = count($currentYearArr);
    @endphp
    @if(!empty($currentYearArr))
        <input type="hidden" name="currentYear[request_type]" class="current-input" value="{{ $exitCurrentYearArr['request_type'] }}" id="quotaCurrentYearRequest">
        <input type="hidden" name="currentYear[quota_id]" class="current-input" value="{{ $exitCurrentYearArr['quota_id'] }}" id="currentYearQuotaId">
        <input type="hidden" name="currentYear[customer_info]" class="current-input customer-info" value="{{ $exitCurrentYearArr['customer_info'] }}">
        <input type="hidden" name="currentYear[response]" class="current-input" value="{{ $exitCurrentYearArr['response'] }}">
        @foreach($currentYearArr as $key => $r)
            @php
                $CYApiKey = $r['api_key'];
                $CYApiKeyArr = explode(":", $CYApiKey);
                $CYApiKeyId = $CYApiKeyArr[0];
                unset($exitCurrentYearArr['quota_id']);
                unset($exitCurrentYearArr['request_type']);
                unset($exitCurrentYearArr['customer_info']);
                unset($exitCurrentYearArr['response']);
            @endphp

            @if($y == 1)
                <tr>
                    @endif

                    @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                        @php $e_value = '';@endphp
                        @foreach($exitCurrentYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'C_'.$CYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input current-input" name="currentYear[{{ $r['field_name']}}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}">
                            @IF($errors->has('currentYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                        @php $d_value = ''; @endphp
                        @foreach($exitCurrentYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $d_value = $e;@endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'C_'.$CYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <select class="quota-input current-input @if($CYApiKeyId == 'isActive' || $CYApiKeyId == 'ecomIsActive' || $CYApiKeyId == 'ecomThrActive') dropdown-readonly @endif" id="{{'C_'.$CYApiKeyId}}" name="currentYear[{{ $r['field_name'] }}]">

                                @php
                                    $options = explode(",", $r['options']);
                                @endphp
                                @if(count($options) != 1)
                                    <option value="">Please Select</option>
                                @endif
                                @foreach($options as $k => $option)
                                    @php
                                        $selected  = "";
                                        $option_name = $option;
                                        $old = old('currentYear.' . $r['field_name']);
                                        if (str_contains($option_name,'~')) {
                                            $option2 =  substr($option_name, 0, strpos($option_name, "~"));
                                            $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                        }
                                        if (!empty($old) && str_contains($old,'~')) {
                                            $old = substr($old, strpos($old, "~") + 1);
                                        }
                                        if($option2 == $d_value){
                                            $selected  = "selected";
                                        }
                                    @endphp
                                    <option value="{{ $option2 }}" {{$selected}}>{{ $option_name }}</option>
                                @endforeach
                            </select>
                            @IF($errors->has('currentYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>

                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                        @php $e_value = ''; @endphp
                        @foreach($exitCurrentYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'C_'.$CYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input current-input" name="currentYear[{{ $r['field_name']}}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}">
                            @IF($errors->has('currentYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                        @php $e_value = ''; @endphp
                        @foreach($exitCurrentYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'C_'.$CYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="text" class="quota-input datePicker js-date current-input" name="currentYear[{{ $r['field_name']}}]" value="{{ $e_value }}"
                                    placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}" autocomplete="off" maxlength="10">
                            @IF($errors->has('currentYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                        <script>
                            $(document).ready(function () {
                                $('.datePicker').datepicker({
                                    dateFormat: 'yy/mm/dd',
                                    changeYear: true,
                                    changeMonth: true,
                                    yearRange: "1900:2050",
                                });
                            });
                        </script>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NEXT_DATE)
                        @php $e_value = ''; @endphp
                        @foreach($exitCurrentYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'C_'.$CYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="text" class="quota-input datepickerNextQT js-date current-input" name="currentYear[{{ $r['field_name']}}]" value="{{ $e_value }}"
                                    placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}" autocomplete="off" maxlength="10">
                            @IF($errors->has('currentYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                        <script>
                            $(document).ready(function () {
                                $('.datepickerNextQT').datepicker({
                                    minDate: 0,
                                    dateFormat: 'yy/mm/dd',
                                    showButtonPanel: true,
                                    changeYear: true,
                                    changeMonth: true,
                                    yearRange: "1900:2050",
                                });
                            });
                        </script>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NUMBER)
                        @php $e_value = ''; @endphp
                        @foreach($exitCurrentYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'C_'.$CYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input current-input" name="currentYear[{{ $r['field_name']}}]" placeholder="{{ $r['placeholder'] }}"
                                    maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" id="{{'C_'.$CYApiKeyId}}">
                            @IF($errors->has('currentYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                        @php $e_value = ''; @endphp
                        @foreach($exitCurrentYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'C_'.$CYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" name="currentYear[{{ $r['field_name']}}]" class="quota-input current-input"
                                    maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}">
                            @IF($errors->has('currentYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::CHECKBOX)
                        <th class="quotd vcenter" width="25%">
                            {{ $r['label_name'] }}
                        </th>
                        <td class="quotd vcenter" width="25%">
                            @php
                                $options = '';
                                $input_checkbox = '';
                                $options = explode(",", $r['options']);
                                $input_checkbox .= '<ul>';

                                foreach ($options as $k => $option) {
                                        $value = $option;
                                        if(empty($option)) {
                                            $value = 'Yes';
                                        }
                                        $selected  = "";
                                        if($value == old($r['field_type'])){
                                            $selected  = "checked";
                                        }
                                        $input_checkbox .= '<li><label><input type="checkbox" name="' . $r['field_type'] . '" value="' . $value . '" '.$selected.' id="'.$CYApiKeyId.'">' . $option . '</label></li>';

                                }
                                $input_checkbox .= '</ul>';
                            @endphp
                            {!! $input_checkbox !!}
                        </td>

                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::RADIO)
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            @php $options = explode(",", $r['options']);
                                $input_radio .= '<ul>';
                                foreach ($options as $k => $option) {
                                $selected  = "";
                                if($option == old($r['field_type'])){
                                    $selected  = "selected";
                                }
                                $input_radio .= '<li><input type="radio" name="' . $r['field_type'] . '" value="' . $option . '" '.$selected.' id="'.$CYApiKeyId.'" ><label>' . $option . '</label></li>';
                                }
                                $input_radio .= '</ul>';
                            @endphp
                                {!! $input_radio !!}
                        </td> --}}
                    @else
                    @endif

                    @if($y == 2)
                </tr>
                    <?php $y=0;?>
            @elseif($count2 == 1)
                @if($y == 1)
                    <th class="quotd vcenter" width="25%">&nbsp;</th>
                    <td class="quotd vcenter" width="25%">&nbsp;</td>
                    </tr>
                @else
                    </tr>
                @endif
            @endif
                <?php $y++; $count2--;?>
        @endforeach
    @endif
    </thead>
</table>
@endif

@if(!empty($nextYearArr) && !empty($exitNextYearArr))
    @if($exitNextYearArr['request_type'] == 'MOD')
        <style>
            .N_limitUsagePercentage {display: none;}
            #N_limitUsagePercentage{display: none}
            .N_limitStartDate{display: none}
            #N_limitStartDate{display: none}
            .N_limitEndDate{display: none}
            #N_limitEndDate{display: none}
        </style>
    @else
        <style>
            .N_unUsagePercentage {display: none;}
            #N_unUsagePercentage{display: none}
            .N_limitAmount{display: none}
            #N_limitAmount{display: none}
        </style>
    @endif
<table class="table quota-table" id="nextYear" >
    <thead>
    <tr  class="quotd vcenter">
        <th class="quotd vcenter"><h6>Next Year</h6></th>
    </tr>

    @php
        $count3 = count($nextYearArr);
    @endphp

    @if(!empty($nextYearArr))
        <input type="hidden" name="nextYear[request_type]" class="next-input" value="{{ $exitNextYearArr['request_type'] }}" id="quotaNextYearRequest">
        <input type="hidden" name="nextYear[quota_id]" class="next-input" id="nextYearQuotaId" value="{{ $exitNextYearArr['quota_id'] }}">
        <input type="hidden" name="nextYear[customer_info]" class="next-input customer-info" value="{{ $exitNextYearArr['customer_info'] }}">
        <input type="hidden" name="nextYear[response]" class="next-input" value="{{ $exitNextYearArr['response'] }}">
        @foreach($nextYearArr as $key => $r)
            @php
                $NYApiKey = $r['api_key'];
                $NYApiKeyArr = explode(":", $NYApiKey);
                $NYApiKeyId = $NYApiKeyArr[0];
                unset($exitNextYearArr['quota_id']);
                unset($exitNextYearArr['request_type']);
                unset($exitNextYearArr['customer_info']);
                unset($exitNextYearArr['response']);
            @endphp
            @if($x == 1)
                <tr>
                    @endif

                    @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                        @php $e_value = '';

                        @endphp
                        @foreach($exitNextYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input next-input" name="nextYear[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}">
                            @IF($errors->has('nextYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                        @php $e_value = '';@endphp
                        @foreach($exitNextYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $d_value = $e;@endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <select class="quota-input next-input @if($NYApiKeyId == 'isActive' || $NYApiKeyId == 'ecomIsActive' || $NYApiKeyId == 'ecomThrActive') dropdown-readonly @endif" id="{{'N_'.$NYApiKeyId}}" name="nextYear[{{ $r['field_name'] }}]">

                                @php
                                    $options = explode(",", $r['options']);
                                @endphp
                                @if(count($options) != 1)
                                    <option value="">Please Select</option>
                                @endif
                                @foreach($options as $k => $option)
                                    @php
                                        $selected  = "";
                                        $option_name = $option;
                                        $old = old('nextYear.' . $r['field_name']);
                                        if (str_contains($option_name,'~')) {
                                            $option2 =  substr($option_name, 0, strpos($option_name, "~"));
                                            $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                        }
                                        if (!empty($old) && str_contains($old,'~')) {
                                            $old = substr($old, strpos($old, "~") + 1);
                                        }
                                        if($option2 == $d_value){
                                            $selected  = "selected";
                                        }
                                    @endphp
                                    <option value="{{ $option2 }}" {{$selected}}>{{ $option_name }}</option>
                                @endforeach
                            </select>
                            @IF($errors->has('nextYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>

                    {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                        <th class="quotd vcenter " width="25%">{{ $r['label_name'] }}</th>
                        <td class="quotd vcenter" width="25%">
                            <textarea name="{{ $r['field_name'] }}" type="{{ $r['field_type'] }}" maxlength="{{ $r['maximum_length'] }}" class="quota-input form-control" placeholder="{{ $r['placeholder'] }}" id="{{$NYApiKeyId}}">{{old($r['field_name'])}}</textarea>
                        </td> --}}
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                        @php $e_value = ''; @endphp
                        @foreach($exitNextYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input next-input" name="nextYear[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}">
                            @IF($errors->has('nextYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                        @php $e_value = '';@endphp
                        @foreach($exitNextYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="text" class="quota-input datePicker js-date next-input" name="nextYear[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}" autocomplete="off" maxlength="10">
                            @IF($errors->has('nextYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                        <script>
                            $(document).ready(function () {
                                $('.datePicker').datepicker({
                                    dateFormat: 'yy/mm/dd',
                                    changeYear: true,
                                    changeMonth: true,
                                    yearRange: "1900:2050",
                                });
                            });
                        </script>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NEXT_DATE)
                        @php $e_value = '';@endphp
                        @foreach($exitNextYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="text" class="quota-input datepickerNextQT js-date next-input" name="nextYear[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}" autocomplete="off" maxlength="10">
                            @IF($errors->has('nextYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                        <script>
                            $(document).ready(function () {
                                $('.datepickerNextQT').datepicker({
                                    minDate: 0,
                                    dateFormat: 'yy/mm/dd',
                                    showButtonPanel: true,
                                    changeYear: true,
                                    changeMonth: true,
                                    yearRange: "1900:2050",
                                });
                            });
                        </script>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NUMBER)
                        @php $e_value = ''; @endphp
                        @foreach($exitNextYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" class="quota-input next-input" name="nextYear[{{ $r['field_name'] }}]" placeholder="{{ $r['placeholder'] }}" maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" id="{{'N_'.$NYApiKeyId}}">
                            @IF($errors->has('nextYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                        @php $e_value = ''; @endphp
                        @foreach($exitNextYearArr as $e_field)
                            @foreach($e_field as $key => $e)
                                @if($key == $r['label_name'])
                                    @php $e_value = $e; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        <th class="quotd vcenter" width="25%">
                            <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                            </div>
                        </th>
                        <td class="quotd vcenter" width="25%">
                            <input type="{{ $r['field_type'] }}" name="nextYear[{{ $r['field_name'] }}]" class="quota-input next-input" maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}">
                            @IF($errors->has('nextYear.' . $r['field_name'])) <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div> @ENDIF
                        </td>
                    {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::CHECKBOX)
                        <th class="quotd vcenter" width="25%">
                            {{ $r['label_name'] }}
                        </th>
                        <td class="quotd vcenter" width="25%">
                            @php
                                $options = '';
                                $input_checkbox = '';
                                $options = explode(",", $r['options']);
                                $input_checkbox .= '<ul>';

                                foreach ($options as $k => $option) {
                                        $value = $option;
                                        if(empty($option)) {
                                            $value = 'Yes';
                                        }
                                        $selected  = "";
                                        if($value == old($r['field_type'])){
                                            $selected  = "checked";
                                        }
                                        $input_checkbox .= '<li><label><input type="checkbox" name="' . $r['field_type'] . '" value="' . $value . '" '.$selected.' id="'.$NYApiKeyId.'">' . $option . '</label></li>';

                                }
                                $input_checkbox .= '</ul>';
                            @endphp
                            {!! $input_checkbox !!}
                        </td>

                    @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::RADIO)
                        <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span></th>
                        <td class="quotd vcenter" width="25%">
                            @php $options = explode(",", $r['options']);
                                $input_radio .= '<ul>';
                                foreach ($options as $k => $option) {
                                $selected  = "";
                                if($option == old($r['field_type'])){
                                    $selected  = "selected";
                                }
                                $input_radio .= '<li><input type="radio" name="' . $r['field_type'] . '" value="' . $option . '" '.$selected.' id="'.$apiKeyId.'" ><label>' . $option . '</label></li>';
                                }
                                $input_radio .= '</ul>';
                            @endphp
                                {!! $input_radio !!}
                        </td> --}}
                    @else
                    @endif

                    @if($x == 2)
                </tr>
                    <?php $x=0;?>
            @elseif($count3 == 1)
                @if($x == 1)
                    <th class="quotd vcenter" width="25%">&nbsp;</th>
                    <td class="quotd vcenter" width="25%">&nbsp;</td>
                    </tr>
                    @else
                        </tr>
                @endif
            @endif
                <?php $x++; $count3--;?>
        @endforeach
    @endif
    </thead>
</table>
@endif

@if(!empty($MQArr) && !empty($exitMQArr))
    @if($exitMQArr['request_type'] == 'MOD')
        <style>
            .MQ_limitUsagePercentage {display: none;}
            #MQ_limitUsagePercentage{display: none}
            .MQ_limitStartDate{display: none}
            #MQ_limitStartDate{display: none}
            .MQ_limitEndDate{display: none}
            #MQ_limitEndDate{display: none}
        </style>
    @else
        <style>
            .MQ_unUsagePercentage {display: none;}
            #MQ_unUsagePercentage{display: none}
           /* .MQ_limitAmount{display: none}
            #MQ_limitAmount{display: none}*/
        </style>
    @endif
    <table class="table quota-table" id="nextYear" >
        <thead>
        <tr  class="quotd vcenter">
            <th class="quotd vcenter"><h6>Medical Quota</h6></th>
        </tr>

        @php
            $count3 = count($MQArr);
        @endphp

        @if(!empty($MQArr))
            <input type="hidden" name="medicalQuota[request_type]" class="next-input" value="{{ $exitMQArr['request_type'] }}" id="quotaNextYearRequest">
            <input type="hidden" name="medicalQuota[quota_id]" class="next-input" id="nextYearQuotaId" value="{{ $exitMQArr['quota_id'] }}">
            <input type="hidden" name="medicalQuota[customer_info]" class="next-input customer-info" value="{{ $exitMQArr['customer_info'] }}">
            <input type="hidden" name="medicalQuota[response]" class="next-input" value="{{ $exitMQArr['response'] }}">
            @foreach($MQArr as $key => $r)
                @php
                    $MQApiKey = $r['api_key'];
                    $MQApiKeyArr = explode(":", $MQApiKey);
                    $MQApiKeyId = $MQApiKeyArr[0];
                    unset($exitMQArr['quota_id']);
                    unset($exitMQArr['request_type']);
                    unset($exitMQArr['customer_info']);
                    unset($exitMQArr['response']);
                @endphp
                @if($z == 1)
                    <tr>
                        @endif

                        @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                            @php $e_value = '';

                            @endphp
                            @foreach($exitMQArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'MQ_'.$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input next-input" name="medicalQuota[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'MQ_'.$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name'])) <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                            @php $e_value = '';@endphp
                            @foreach($exitMQArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $d_value = $e;@endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'MQ_'.$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <select class="quota-input next-input  @if($MQApiKeyId == 'isActive' || $MQApiKeyId == 'ecomIsActive' || $MQApiKeyId == 'ecomThrActive') dropdown-readonly @endif" id="{{'MQ_'.$MQApiKeyId}}" name="medicalQuota[{{ $r['field_name'] }}]">

                                    @php
                                        $options = explode(",", $r['options']);
                                    @endphp
                                    @if(count($options) != 1)
                                        <option value="">Please Select</option>
                                    @endif
                                    @foreach($options as $k => $option)
                                        @php
                                            $selected  = "";
                                            $option_name = $option;
                                            $old = old('medicalQuota.' . $r['field_name']);
                                            if (str_contains($option_name,'~')) {
                                                $option2 =  substr($option_name, 0, strpos($option_name, "~"));
                                                $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                            }
                                            if (!empty($old) && str_contains($old,'~')) {
                                                $old = substr($old, strpos($old, "~") + 1);
                                            }
                                            if($option2 == $d_value){
                                                $selected  = "selected";
                                            }
                                        @endphp
                                        <option value="{{ $option2 }}" {{$selected}}>{{ $option_name }}</option>
                                    @endforeach
                                </select>
                                @IF($errors->has('medicalQuota.' . $r['field_name'])) <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div> @ENDIF
                            </td>

                            {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <th class="quotd vcenter " width="25%">{{ $r['label_name'] }}</th>
                                <td class="quotd vcenter" width="25%">
                                    <textarea name="{{ $r['field_name'] }}" type="{{ $r['field_type'] }}" maxlength="{{ $r['maximum_length'] }}" class="quota-input form-control" placeholder="{{ $r['placeholder'] }}" idMQ"{{$MQApiKeyId}}">{{old($r['field_name'])}}</textarea>
                                </td> --}}
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                            @php $e_value = ''; @endphp
                            @foreach($exitMQArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'MQ_'.$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input next-input" name="medicalQuota[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'MQ_'.$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name'])) <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                            @php $e_value = '';@endphp
                            @foreach($exitMQArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'MQ_'.$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input datePicker js-date next-input" name="medicalQuota[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'MQ_'.$MQApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('medicalQuota.' . $r['field_name'])) <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                            <script>
                                $(document).ready(function () {
                                    $('.datePicker').datepicker({
                                        dateFormat: 'yy/mm/dd',
                                        changeYear: true,
                                        changeMonth: true,
                                        yearRange: "1900:2050",
                                    });
                                });
                            </script>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NEXT_DATE)
                            @php $e_value = '';@endphp
                            @foreach($exitMQArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'MQ_'.$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input datepickerNextQT js-date next-input" name="medicalQuota[{{ $r['field_name'] }}]" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'MQ_'.$MQApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('medicalQuota.' . $r['field_name'])) <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                            <script>
                                $(document).ready(function () {
                                    $('.datepickerNextQT').datepicker({
                                        minDate: 0,
                                        dateFormat: 'yy/mm/dd',
                                        showButtonPanel: true,
                                        changeYear: true,
                                        changeMonth: true,
                                        yearRange: "1900:2050",
                                    });
                                });
                            </script>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NUMBER)
                            @php $e_value = ''; @endphp
                            @foreach($exitMQArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'MQ_'.$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input next-input" name="medicalQuota[{{ $r['field_name'] }}]" placeholder="{{ $r['placeholder'] }}" maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" id="{{'MQ_'.$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name'])) <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                            @php $e_value = ''; @endphp
                            @foreach($exitMQArr as $e_field)
                                @foreach($e_field as $key => $e)
                                    @if($key == $r['label_name'])
                                        @php $e_value = $e; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'MQ_'.$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" name="medicalQuota[{{ $r['field_name'] }}]" class="quota-input next-input" maxlength="{{ $r['maximum_length'] }}" value="{{ $e_value }}" placeholder="{{ $r['placeholder'] }}" id="{{'MQ_'.$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name'])) <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div> @ENDIF
                            </td>
                            {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::CHECKBOX)
                                <th class="quotd vcenter" width="25%">
                                    {{ $r['label_name'] }}
                                </th>
                                <td class="quotd vcenter" width="25%">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(",", $r['options']);
                                        $input_checkbox .= '<ul>';

                                        foreach ($options as $k => $option) {
                                                $value = $option;
                                                if(empty($option)) {
                                                    $value = 'Yes';
                                                }
                                                $selected  = "";
                                                if($value == old($r['field_type'])){
                                                    $selected  = "checked";
                                                }
                                                $input_checkbox .= '<li><label><input type="checkbox" name="' . $r['field_type'] . '" value="' . $value . '" '.$selected.' idMQ"'.$MQApiKeyId.'">' . $option . '</label></li>';

                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                </td>

                            @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::RADIO)
                                <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span></th>
                                <td class="quotd vcenter" width="25%">
                                    @php $options = explode(",", $r['options']);
                                        $input_radio .= '<ul>';
                                        foreach ($options as $k => $option) {
                                        $selected  = "";
                                        if($option == old($r['field_type'])){
                                            $selected  = "selected";
                                        }
                                        $input_radio .= '<li><input type="radio" name="' . $r['field_type'] . '" value="' . $option . '" '.$selected.' id="'.$apiKeyId.'" ><label>' . $option . '</label></li>';
                                        }
                                        $input_radio .= '</ul>';
                                    @endphp
                                        {!! $input_radio !!}
                                </td> --}}
                        @else
                        @endif

                        @if($z == 2)
                    </tr>
                        <?php $z=0;?>
                @elseif($count3 == 1)
                    @if($z == 1)
                        <th class="quotd vcenter" width="25%">&nbsp;</th>
                        <td class="quotd vcenter" width="25%">&nbsp;</td>
                        </tr>
                        @else
                            </tr>
                    @endif
                @endif
                    <?php $z++; $count3--;?>
            @endforeach
        @endif
        </thead>
    </table>
@endif
