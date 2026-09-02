<?php

use App\SubgroupInfo;

$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i = 1;
$x = 1;
$y = 1;

$passportArr = array();
$medicalQuotaArr = array();

// Local Fieldset group ID (27,28) UAT null & LIVE null
if (isset($iris_fields[27])) {
    $passportArr = $iris_fields[27];
}
if (isset($iris_fields[28])) {
    $medicalQuotaArr = $iris_fields[28];
}

$medicalQuotaShowHide = "";

if (!empty(old('medicalQuotaShowHide'))) {
    $medicalQuotaShowHide = old('medicalQuotaShowHide');
}

$subgroup_id = Auth::user()->user_unit->subgroup_info_id;
$group_info_id = SubgroupInfo::find($subgroup_id);

$isInquiry = isIRISInquiry($issue_id, $group_info_id->group_info_id);

?>
<td colspan="4" width="100%">

    <a href="#" id="manipulateData" class="btn btn-sm btn-warning my-4">Pull Data</a>
    @if($isInquiry)
        <a href="#" id="InquiryIRISData" class="btn btn-sm btn-info my-4 d-none">Inquiry IRIS Data</a>
    @endif
    <!-- Modal -->
    <div class="modal fade" id="manipulateModal" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 text-center" id="responseMgs">
                    <h6 id="passportMgs"></h6>
                    <h6 id="MQMgs"></h6>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="InquiryModal" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="IRISApiModalLabel">IRIS Inquiry Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" id="InquiryData">
                    <!-- Modal content goes here -->
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="medicalQuotaShowHide" value="{{!empty(old('medicalQuotaShowHide')) ? old('medicalQuotaShowHide') : 0}}">
    <input type="hidden" name="is_manipulate_btn_click" value="{{old('is_manipulate_btn_click')}}">
    <input type="hidden" name="customer_info" class="customer-info" value="{{old('customer_info')}}">
    <input type="hidden" name="productType" value="0">

    <h5>Passport</h5>
    <table class="table quota-table">
        <thead>
        <h6 class="text-danger manipulate-error-msg"></h6>
        @php
            $count1 = count($passportArr);
        @endphp

        @if(!empty($passportArr))
            <input type="hidden" class="passport-input" name="passport[request_type]" value="{{old('passport.request_type')}}" id="passportRequestType">
            <input type="hidden" class="passport-input" name="passport[customer_id]" value="{{ old('passport.customer_id') }}" id="passportCustomerId">
            <input type="hidden" class="passport-input" name="passport[p_response]" value="{{ old('passport.p_response') }}" id="passportResponse">
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
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required'] == 1){{'*'}}@else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input passport-input"
                                       name="passport[{{ $r['field_name'] }}]"
                                       value="{{old('passport.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>

                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1){{'*'}}@else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <select class="quota-input passport-input" id="{{$PApiKeyId}}" name="passport[{{ $r['field_name'] }}]">
                                    @php
                                        $options = explode(",", $r['options']);
                                    @endphp
                                    {{--@if(count($options) != 1)
                                        <option value="">Please Select</option>
                                    @endif--}}
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
                                            if($option2 == $old){
                                                $selected  = "selected";
                                            }

                                        @endphp
                                        <option value="{{ $option2 }}" {{$selected}}>{{ $option_name }}</option>
                                    @endforeach
                                </select>
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input passport-input"
                                       name="passport[{{ $r['field_name'] }}]"
                                       value="{{old('passport.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input passport-input datePickerQT"
                                       name="passport[{{ $r['field_name'] }}]"
                                       value="{{ old('passport.' . $r['field_name']) }}" placeholder="{{ $r['placeholder'] }}"
                                       id="{{$PApiKeyId}}" autocomplete="off" maxlength="10" >
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
                                <script>
                                    $(document).ready(function () {
                                        $('.datePickerQT').datepicker({
                                            dateFormat: 'yy/mm/dd',
                                            changeYear: true,
                                            changeMonth: true,
                                            yearRange: "1900:2050",
                                        });
                                    });
                                </script>
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NEXT_DATE)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input passport-input datepickerMQ"
                                       name="passport[{{ $r['field_name'] }}]"
                                       value="{{ old('passport.' . $r['field_name']) }}" placeholder="{{ $r['placeholder'] }}"
                                       id="{{$PApiKeyId}}" autocomplete="off" maxlength="10" >
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
                                <script>
                                    $(document).ready(function () {
                                        $('.datepickerMQ').datepicker({
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
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input passport-input"
                                       name="passport[{{ $r['field_name'] }}]"
                                       placeholder="{{ $r['placeholder'] }}" maxlength="{{ $r['maximum_length'] }}"
                                       value="{{old('passport.' . $r['field_name'])}}" id="{{$PApiKeyId}}">
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" name="passport[{{ $r['field_name'] }}]"
                                       class="quota-input passport-input" maxlength="{{ $r['maximum_length'] }}"
                                       value="{{old('passport.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
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
                                <th class="quotd vcenter" width="25%">{{ $r['label_name'] }}</th>
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
                        <?php $i = 0; ?>
                @elseif($count1 == 1)
                    @if($i == 1)
                        <th class="quotd vcenter" width="25%">&nbsp;</th>
                        <td class="quotd vcenter" width="25%">&nbsp;</td>
                        </tr>
                        @else
                            </tr>
                    @endif
                @endif
                    <?php $i++; $count1--; ?>
            @endforeach
        @endif
        </thead>
    </table>
    <style>
        .dropdown-readonly {pointer-events: none; background: #e8e8e8;}
    </style>
    <table class="table quota-table @if(!empty($medicalQuotaShowHide) && $medicalQuotaShowHide == 1)  @else hidden @endif" id="medicalQuotaWrap">
        <thead>
        <tr class="quotd vcenter">
            <th class="quotd vcenter"><h6>Medical Quota</h6></th>
        </tr>

        @php
            $count2 = count($medicalQuotaArr);
        @endphp

        @if(!empty($medicalQuotaArr))
            <input type="hidden" name="medicalQuota[request_type]" class="mq-input" value="{{old('medicalQuota.request_type')}}" id="medicalQuotaRequest">
            <input type="hidden" name="medicalQuota[quota_id]" class="mq-input" value="{{old('medicalQuota.quota_id')}}" id="medicalQuotaQuotaId">
            {{--<input type="hidden" name="medicalQuota[customer_info]" class="mq-input customer-info" value="{{old('medicalQuota.customer_info')}}">--}}
            <input type="hidden" name="medicalQuota[response]" class="mq-input mq_response" value="{{old('medicalQuota.response')}}">
            @foreach($medicalQuotaArr as $key => $r)
                @php
                    $MQApiKey = $r['api_key'];
                    $MQApiKeyArr = explode(":", $MQApiKey);
                    $MQApiKeyId = $MQApiKeyArr[0];
                @endphp
                @if($y == 1)
                    <tr>
                        @endif

                        @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input mq-input"
                                       name="medicalQuota[{{ $r['field_name']}}]"
                                       value="{{old('medicalQuota.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{$MQApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                    {{'*'}}
                                @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <select class="quota-input mq-input @if($MQApiKeyId == 'isActive' || $MQApiKeyId == 'ecomIsActive' || $MQApiKeyId == 'ecomThrActive') dropdown-readonly @endif" id="{{$MQApiKeyId}}" name="medicalQuota[{{ $r['field_name'] }}]">
                                    @php
                                        $options = explode(",", $r['options']);
                                    @endphp
                                    {{--@if(count($options) != 1)
                                        <option value="">Please Select</option>
                                    @endif--}}
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
                                            if($option2 == $old){
                                                $selected  = "selected";
                                            }
                                        @endphp
                                        <option value="{{ $option2 }}" {{$selected}}>{{ $option_name }}</option>
                                    @endforeach
                                </select>
                                @IF($errors->has('medicalQuota.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>

                            {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <th class="quotd vcenter " width="25%"><p class="">{{ $r['label_name'] }}</p></th>
                                <td class="quotd vcenter" width="25%">
                                    <textarea name="{{ $r['field_name'] }}" type="{{ $r['field_type'] }}" maxlength="{{ $r['maximum_length'] }}" class="quota-input form-control" placeholder="{{ $r['placeholder'] }}" id="{{$apiKeyId}}">{{old($r['field_name'])}}</textarea>
                                </td> --}}
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input mq-input"
                                       name="medicalQuota[{{ $r['field_name']}}]"
                                       value="{{ old('medicalQuota.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input datePickerQT js-date mq-input"
                                       name="medicalQuota[{{ $r['field_name']}}]"
                                       value="{{ old('medicalQuota.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$MQApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('medicalQuota.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                            <script>
                                $(document).ready(function () {
                                    $('.datePickerQT').datepicker({
                                        dateFormat: 'yy/mm/dd',
                                        changeYear: true,
                                        changeMonth: true,
                                        yearRange: "1900:2050",
                                    });
                                });
                            </script>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::NEXT_DATE)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input datepickerNextQT js-date mq-input"
                                       name="medicalQuota[{{ $r['field_name']}}]"
                                       value="{{ old('medicalQuota.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$MQApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('medicalQuota.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div>
                                @ENDIF
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
                            <th class="quotd vcenter" width="25%">
                                <div class="{{$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1){{'*'}}@else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input mq-input"
                                       name="medicalQuota[{{ $r['field_name']}}]" placeholder="{{ $r['placeholder'] }}"
                                       maxlength="{{ $r['maximum_length'] }}"
                                       value="{{ old('medicalQuota.' . $r['field_name']); }}" id="{{$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{$MQApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" name="medicalQuota[{{ $r['field_name']}}]"
                                       class="quota-input mq-input"
                                       maxlength="{{ $r['maximum_length'] }}"
                                       value="{{ old('medicalQuota.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{$MQApiKeyId}}">
                                @IF($errors->has('medicalQuota.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('medicalQuota.' . $r['field_name']) }}</div>
                                @ENDIF
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
                                                $input_checkbox .= '<li><label><input type="checkbox" name="' . $r['field_type'] . '" value="' . $value . '" '.$selected.' id="'.$MQApiKeyId.'">' . $option . '</label></li>';

                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                </td>

                            @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::RADIO)
                                <th class="quotd vcenter" width="25%">{{ $r['label_name'] }}</th>
                                <td class="quotd vcenter" width="25%">
                                    @php $options = explode(",", $r['options']);
                                        $input_radio .= '<ul>';
                                        foreach ($options as $k => $option) {
                                        $selected  = "";
                                        if($option == old($r['field_type'])){
                                            $selected  = "selected";
                                        }
                                        $input_radio .= '<li><input type="radio" name="' . $r['field_type'] . '" value="' . $option . '" '.$selected.' id="'.$MQApiKeyId.'" ><label>' . $option . '</label></li>';
                                        }
                                        $input_radio .= '</ul>';
                                    @endphp
                                        {!! $input_radio !!}
                                </td> --}}
                        @else
                        @endif

                        @if($y == 2)
                    </tr>
                        <?php $y = 0; ?>
                @elseif($count2 == 1)
                    @if($y == 1)
                        <th class="quotd vcenter" width="25%">&nbsp;</th>
                        <td class="quotd vcenter" width="25%">&nbsp;</td>
                        </tr>
                        @else
                            </tr>
                    @endif
                @endif
                    <?php $y++; $count2--; ?>
            @endforeach
        @endif
        </thead>
    </table>
</td>

<script>
    $(document).ready(function () {

        var manipulateBtnClick = "{{ !empty(old('is_manipulate_btn_click')) ? old('is_manipulate_btn_click') : 0 }}";
        var mQuotaRequestType = "{{ !empty(old('medicalQuota.request_type')) ? old('medicalQuota.request_type') : 'ADD' }}";
        var medicalQuotaShowHide = "{{ $medicalQuotaShowHide }}";

        if(mQuotaRequestType == 'ADD') {
            $('.unUsagePercentage').addClass('d-none');
            $('#unUsagePercentage').addClass('d-none');
        } else {
            $('.limitUsagePercentage').addClass('d-none');
            $('#limitUsagePercentage').addClass('d-none');
            $('#limitStartDate').addClass('invisible');
            $('#limitEndDate').addClass('invisible');
            $('.limitStartDate').addClass('invisible');
            $('.limitEndDate').addClass('invisible');
        }

        if (medicalQuotaShowHide == 1) {
            $('.mq-input').prop('disabled', false);
        } else {
            $('.mq-input').prop('disabled', true);
        }

        if (manipulateBtnClick == 0) {
            $('.manipulate-error-msg').text('Note: Please first click manipulate data Button');
            $('.passport-input').prop('disabled', true);
            $('.mq-input').prop('disabled', true);
            $('#InquiryIRISData').addClass('d-none');
        } else {
            $('#InquiryIRISData').removeClass('d-none');
        }

        $('#manipulateData').on('click', function (e) {
            e.preventDefault();
            let mask_card_number = '{{$acc_number}}';
            let account_number = '{{$account_number}}';
            var url = "{{ route('iris.get_data') }}";
            $.ajax({
                url: url,
                method: 'GET',
                data: {mask_card_number: mask_card_number, account_number: account_number},
                _token: '{{ csrf_token() }}',
                beforeSend: function () {
                    $('.loading').removeClass('hidden');
                },
                success: function (data) {
                    $('#quotaSelection').removeClass('hidden');
                    $("[name='is_manipulate_btn_click']").val(1);
                    $("[name='response']").val(data);
                    setTimeout(function () {
                        $('.loading').addClass('hidden');
                    }, 800);

                    $.each(data, function (key, item) {

                        if (item.error.code == '000') {
                            $('.customer-info').val(item.encryptCustomerInfo);
                            $('.irisCheck').prop('disabled', false);
                            $('#InquiryIRISData').removeClass('d-none');
                            if (item.passportInfo.length === 0 || item.medicalQuota.length === 0) {
                                $('#manipulateModal').modal('show')
                            }

                            if (item.passportInfo.length === 0) {
                                $('#passportMgs').html('Passport Not Found!');
                                $('#passportRequestType').val('ADD');
                                $('#passportCustomerId').val(item.customerInfo.customer.customerId);
                            } else {
                                $('#passportRequestType').val('MOD');
                                $('#passportCustomerId').val(item.customerInfo.customer.customerId);
                                $('#passportResponse').val(item.encryptPassportInfo);
                            }

                            if (item.medicalQuota.length === 0) {
                                $('#MQMgs').html('Medical Quota Not Found!')
                            }

                            $('.manipulate-error-msg').addClass('hidden');
                            $('.mq-input').prop('disabled', false);
                            $('.passport-input').prop('disabled', false);

                            // For medical quota
                            if (item.medicalQuota.length != 0) {
                                $('#medicalQuotaWrap').removeClass('hidden');
                                $('#medicalQuotaRequest').val('MOD');
                                $('#medicalQuotaQuotaId').val(item.medicalQuota.quotaId);
                                $('.mq_response').val(item.encryptMedicalQuota);
                                $('.mq-input').prop('disabled', false);
                                $("[name='medicalQuotaShowHide']").val(1);
                                $('.customer-info').val(item.encryptCustomerInfo);
                                $('.limitUsagePercentage').addClass('d-none');
                                $('#limitUsagePercentage').addClass('d-none');
                                $('.unUsagePercentage').removeClass('d-none');
                                $('#unUsagePercentage').removeClass('d-none');
                                $('#limitStartDate').addClass('invisible');
                                $('#limitEndDate').addClass('invisible');
                                $('.limitStartDate').addClass('invisible');
                                $('.limitEndDate').addClass('invisible');
                            } else {
                                $('#medicalQuotaWrap').removeClass('hidden');
                                $('#medicalQuotaRequest').val('ADD');
                                $("[name='medicalQuotaShowHide']").val(1);
                                $('.customer-info').val(item.encryptCustomerInfo);
                                $('.limitUnUsagePercentage').val(0).addClass('d-none');
                                $('#limitUnUsagePercentage').val(0).addClass('d-none');
                            }

                            // passport
                            /*$.each(item.passportInfo, function (pKey, pValue) {
                                $("#" + pKey).val(pValue);
                            });*/

                            // passport
                            $.each(item.passportInfo, function (pKey, pValue) {
                                if (pKey === "passportIsssueplace") { // Corrected key comparison
                                    $("#" + pKey).val(pValue.toUpperCase());
                                } else {
                                    $("#" + pKey).val(pValue);
                                }
                            });

                            // Medical Quota
                            $.each(item.medicalQuota, function (pKey, pValue) {
                                $("#" + pKey).val(pValue);
                            });

                        } else {
                            $('#manipulateModal').modal('show');
                            $('#responseMgs').html('Please re-check your masked debit card number and account number!');
                            $('.mq-input').prop('disabled', true);
                            $('.passport-input').prop('disabled', true);
                            setTimeout(function () {
                                window.location.href = "{{ url("Supports/home") }}";
                            }, 4000);
                        }
                    });
                },
                error: function (error) {

                    setTimeout(function () {
                        $('.loading').addClass('hidden');
                    }, 800);

                    $('#manipulateModal').modal('show')
                    $('#responseMgs').html('Something went wrong. Please Contact with Administrator!');
                    setTimeout(function() {
                        window.location.href = "{{ url("Supports/home") }}";
                    }, 4000);
                }
            });
        })


        $('#InquiryIRISData').on('click', function (e) {
            e.preventDefault();
            let passport = $("#passportResponse").val();
            let customerInfo = $(".customer-info").val();
            let MQQuota = $(".mq_response").val();
            let url = "{{ route('iris.inquiryData') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    passport: passport,
                    customerInfo: customerInfo,
                    MQQuota: MQQuota,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    $('.loading').removeClass('hidden');
                },
                success: function (response) {
                    setTimeout(function () {
                        $('.loading').addClass('hidden');
                    }, 800);

                    $('#InquiryModal').modal('show');
                    $('#InquiryData').html(response.data);

                },
                error: function (xhr, status, error) {
                    setTimeout(function () {
                        $('.loading').addClass('hidden');
                    }, 800);
                    $('#InquiryModal').modal('show');
                    $('#InquiryData').html('Something went wrong!');
                }
            });
        });


    });
</script>
</td>
