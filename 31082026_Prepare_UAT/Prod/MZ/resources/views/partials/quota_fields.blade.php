
<?php

use App\SubgroupInfo;

$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i = 1;
$x = 1;
$y = 1;

$passportArr = array();
$currentYearArr = array();
$nextYearArr = array();

// Fieldset group id Local (23,24,25), UAT ()
if (isset($iris_fields[23])) {
    $passportArr = $iris_fields[23];
}
if (isset($iris_fields[24])) {
    $currentYearArr = $iris_fields[24];
}
if (isset($iris_fields[25])) {
    $nextYearArr = $iris_fields[25];
}

$currentYearShowHide = "";
$nextYearShowHide = "";
if (!empty(old('currentYearShowHide'))) {
    $currentYearShowHide = old('currentYearShowHide');
}
if (!empty(old('nextYearShowHide'))) {
    $nextYearShowHide = old('nextYearShowHide');
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
         aria-labelledby="manipulateModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 text-center" id="responseMgs">
                    <h6 id="passportMgs"></h6>
                    <h6 id="TQMgs"></h6>
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


    <input type="hidden" name="currentYearShowHide" value="{{!empty(old('currentYearShowHide')) ? old('currentYearShowHide') : 0}}">
    <input type="hidden" name="nextYearShowHide" value="{{!empty(old('nextYearShowHide')) ? old('nextYearShowHide') : 0}}">
    <input type="hidden" name="is_manipulate_btn_click" value="{{old('is_manipulate_btn_click')}}">
    <input type="hidden" name="productType" value="1">

    {{--old value--}}
    <input type="hidden" name="cYear_request_type" value="{{old('cYear_request_type')}}">
    <input type="hidden" name="cYear_quota_id" value="{{old('cYear_quota_id')}}">
    <input type="hidden" name="cYear_response" value="{{old('cYear_response')}}">
    <input type="hidden" name="customer_info" class="customer-info" value="{{old('customer_info')}}">
    <input type="hidden" name="nYear_request_type" value="{{old('nYear_request_type')}}">
    <input type="hidden" name="nYear_quota_id" value="{{old('nYear_quota_id')}}">
    <input type="hidden" name="nYear_response" value="{{old('nYear_response')}}">



    <h5>Passport</h5>
    <table class="table quota-table">
        <thead>
        <h6 class="text-danger manipulate-error-msg"></h6>
        @php
            $count1 = count($passportArr);
        @endphp

        @if(!empty($passportArr))
            <input type="hidden" class="passport-input" name="passport[request_type]" value="{{old('passport.request_type')}}" id="passportRequestType">
            <input type="hidden" class="passport-input" name="passport[customer_id]" id="passportCustomerId" value="{{ old('passport.customer_id') }}">
            <input type="hidden" class="passport-input" name="passport[p_response]" id="passportResponse" value="{{ old('passport.p_response') }}">
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

                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                            <th class="quotd vcenter" width="25%">{{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span></th>
                            <td class="quotd vcenter" width="25%">
                                <select class="quota-input passport-input" id="{{$PApiKeyId}}"
                                        name="passport[{{ $r['field_name'] }}]">

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
                                    <div
                                        class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>

                            {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <th class="quotd vcenter " width="25%"><p class="">{{ $r['label_name'] }}</p></th>
                                <td class="quotd vcenter" width="25%">
                                    <textarea name="{{ $r['field_name'] }}" type="{{ $r['field_type'] }}" maxlength="{{ $r['maximum_length'] }}" class="quota-input passport-input form-control" placeholder="{{ $r['placeholder'] }}" id="{{$PApiKeyId}}">{{old('passport.' . $r['field_name'])}}</textarea>
                                </td> --}}
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
                                    <div
                                        class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
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
                                       id="{{$PApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
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
                                <input type="text" class="quota-input passport-input datepickerNextQT"
                                       name="passport[{{ $r['field_name'] }}]"
                                       value="{{ old('passport.' . $r['field_name']) }}" placeholder="{{ $r['placeholder'] }}"
                                       id="{{$PApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('passport.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('passport.' . $r['field_name']) }}</div>
                                @ENDIF
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
    <table class="py-2 ">
        <nav class="navbar navbar-expand-lg navbar-light quota-main-nav" id="quotaSelection">
            <strong class="quota-brand" href="#">Quota</strong>
            <div class="collapse navbar-collapse">
                <div class="navbar-nav text-center quota-navbar">
                    <span class="please_select py-1" data-option="please_select">Please Select</span>
                    @if( $currentYearShowHide == 1 && $nextYearShowHide == 1 )
                        <span class="year-color py-1 quota-nav-selected" role="button" data-option="both_year"
                              id="both_year">Both Year</span>
                        <span class="year-color py-1" role="button" data-option="next_year"
                              id="next_year">Next Year</span>
                        <span class="year-color py-1" role="button" data-option="current_year" id="current_year">Current Year</span>
                    @elseif( $currentYearShowHide == 1 )
                        <span class="year-color py-1 quota-nav-selected" role="button" data-option="current_year"
                              id="current_year">Current Year</span>
                        <span class="year-color py-1" role="button" data-option="next_year"
                              id="next_year">Next Year</span>
                        <span class="year-color py-1" role="button" data-option="both_year"
                              id="both_year">Both Year</span>
                    @elseif( $nextYearShowHide == 1 )
                        <span class="year-color py-1 quota-nav-selected" role="button" data-option="next_year"
                              id="next_year">Next Year</span>
                        <span class="year-color py-1" role="button" data-option="current_year" id="current_year">Current Year</span>
                        <span class="year-color py-1" role="button" data-option="both_year"
                              id="both_year">Both Year</span>
                    @else
                        <span class="year-color py-1" role="button" data-option="next_year"
                              id="next_year">Next Year</span>
                        <span class="year-color py-1" role="button" data-option="current_year" id="current_year">Current Year</span>
                        <span class="year-color py-1" role="button" data-option="both_year"
                              id="both_year">Both Year</span>
                    @endif

                </div>
            </div>
        </nav>
    </table>
    <table class="table quota-table @if(!empty($currentYearShowHide) && $currentYearShowHide == 1)  @else hidden @endif"
           id="currentYear">
        <thead>
        <tr class="quotd vcenter">
            <th class="quotd vcenter"><h6>Current Year</h6></th>
        </tr>

        @php
            $count2 = count($currentYearArr);
        @endphp

        @if(!empty($currentYearArr))
            <input type="hidden" name="currentYear[request_type]" class="current-input" value="{{old('currentYear.request_type')}}" id="quotaCurrentYearRequest">
            <input type="hidden" name="currentYear[quota_id]" class="current-input" value="{{old('currentYear.quota_id')}}" id="currentYearQuotaId">
            {{--<input type="hidden" name="currentYear[customer_info]" class="current-input customer-info" value="{{old('currentYear.customer_info')}}">--}}
            <input type="hidden" name="currentYear[response]" class="current-input c_response" value="{{old('currentYear.response')}}">
            @foreach($currentYearArr as $key => $r)
                @php
                    $CYApiKey = $r['api_key'];
                    $CYApiKeyArr = explode(":", $CYApiKey);
                    $CYApiKeyId = $CYApiKeyArr[0];
                @endphp
                @if($y == 1)
                    <tr>
                        @endif

                        @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'C_'.$CYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input current-input"
                                       name="currentYear[{{ $r['field_name']}}]"
                                       value="{{old('currentYear.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}">
                                @IF($errors->has('currentYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'C_'.$CYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <select class="quota-input current-input  @if($CYApiKeyId == 'isActive' || $CYApiKeyId == 'ecomIsActive' || $CYApiKeyId == 'ecomThrActive') dropdown-readonly @endif" id="{{'C_'.$CYApiKeyId}}"
                                        name="currentYear[{{ $r['field_name'] }}]" readonly="">

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
                                            $old = old('currentYear.' . $r['field_name']);
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
                                @IF($errors->has('currentYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>

                            {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <th class="quotd vcenter " width="25%"><p class="">{{ $r['label_name'] }}</p></th>
                                <td class="quotd vcenter" width="25%">
                                    <textarea name="{{ $r['field_name'] }}" type="{{ $r['field_type'] }}" maxlength="{{ $r['maximum_length'] }}" class="quota-input form-control" placeholder="{{ $r['placeholder'] }}" id="{{$apiKeyId}}">{{old($r['field_name'])}}</textarea>
                                </td> --}}
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'C_'.$CYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input current-input"
                                       name="currentYear[{{ $r['field_name']}}]"
                                       value="{{ old('currentYear.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}">
                                @IF($errors->has('currentYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'C_'.$CYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input datePickerQT js-date current-input"
                                       name="currentYear[{{ $r['field_name']}}]"
                                       value="{{ old('currentYear.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}" autocomplete="off"
                                       maxlength="10">
                                @IF($errors->has('currentYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div>
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
                                <div class="{{'C_'.$CYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input datepickerNextQT js-date current-input"
                                       name="currentYear[{{ $r['field_name']}}]"
                                       value="{{ old('currentYear.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}" autocomplete="off"
                                       maxlength="10">
                                @IF($errors->has('currentYear.' . $r['field_name']))
                                    <div class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div>
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
                                <div class="{{'C_'.$CYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input current-input"
                                       name="currentYear[{{ $r['field_name']}}]" placeholder="{{ $r['placeholder'] }}"
                                       maxlength="{{ $r['maximum_length'] }}"
                                       value="{{ old('currentYear.' . $r['field_name']); }}" id="{{'C_'.$CYApiKeyId}}">
                                @IF($errors->has('currentYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'C_'.$CYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" name="currentYear[{{ $r['field_name']}}]"
                                       class="quota-input current-input"
                                       maxlength="{{ $r['maximum_length'] }}"
                                       value="{{ old('currentYear.' . $r['field_name']); }}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'C_'.$CYApiKeyId}}">
                                @IF($errors->has('currentYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('currentYear.' . $r['field_name']) }}</div>
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
    <table class="table quota-table @if(!empty($nextYearShowHide) && $nextYearShowHide == 1)  @else hidden @endif mt-4"
           id="nextYear">
        <thead>
        <tr class="quotd vcenter">
            <th class="quotd vcenter"><h6>Next Year</h6></th>
        </tr>

        @php
            $count3 = count($nextYearArr);
        @endphp

        @if(!empty($nextYearArr))
            <input type="hidden" name="nextYear[request_type]" class="next-input" value="{{old('nextYear.request_type')}}" id="quotaNextYearRequest">
            <input type="hidden" name="nextYear[quota_id]" class="next-input" id="nextYearQuotaId" value="{{old('nextYear.quota_id')}}">
            {{--<input type="hidden" name="nextYear[customer_info]" class="next-input customer-info" value="{{old('nextYear.customer_info')}}">--}}
            <input type="hidden" name="nextYear[response]" class="next-input n_response" value="{{old('nextYear.response')}}">
            @foreach($nextYearArr as $key => $r)
                @php
                    $NYApiKey = $r['api_key'];
                    $NYApiKeyArr = explode(":", $NYApiKey);
                    $NYApiKeyId = $NYApiKeyArr[0];
                @endphp
                @if($x == 1)
                    <tr>
                        @endif

                        @if($r['field_type'] == \App\Enum\FieldTypeEnum::TEXT || $r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'N_'.$NYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input next-input"
                                       name="nextYear[{{ $r['field_name'] }}]"
                                       value="{{old('nextYear.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}">
                                @IF($errors->has('nextYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DROPDOWN)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'N_'.$NYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <select class="quota-input next-input  @if($NYApiKeyId == 'isActive' || $NYApiKeyId == 'ecomIsActive' || $NYApiKeyId == 'ecomThrActive') dropdown-readonly @endif" id="{{'N_'.$NYApiKeyId}}"
                                        name="nextYear[{{ $r['field_name'] }}]"
                                        @if($NYApiKeyId == 'isActive' || $NYApiKeyId == 'ecomIsActive' || $NYApiKeyId == 'ecomThrActive') ZAIHAD @endif>

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
                                            $old = old('nextYear.' . $r['field_name']);
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
                                @IF($errors->has('nextYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>

                            {{-- @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <th class="quotd vcenter " width="25%">{{ $r['label_name'] }}</th>
                                <td class="quotd vcenter" width="25%">
                                    <textarea name="{{ $r['field_name'] }}" type="{{ $r['field_type'] }}" maxlength="{{ $r['maximum_length'] }}" class="quota-input form-control" placeholder="{{ $r['placeholder'] }}" id="{{$NYApiKeyId}}">{{old($r['field_name'])}}</textarea>
                                </td> --}}
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::ADDRESS)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'N_'.$NYApiKeyId}}">
                                {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                        {{'*'}}
                                    @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input next-input"
                                       name="nextYear[{{ $r['field_name'] }}]"
                                       value="{{old('nextYear.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}">
                                @IF($errors->has('nextYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DATE)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'N_'.$NYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="text" class="quota-input datePickerQT js-date next-input"
                                       name="nextYear[{{ $r['field_name'] }}]"
                                       value="{{old('nextYear.' . $r['field_name'])}}" placeholder="{{ $r['placeholder'] }}"
                                       id="{{'N_'.$NYApiKeyId}}" autocomplete="off" maxlength="10">
                                @IF($errors->has('nextYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div>
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
                                 <div class="{{'N_'.$NYApiKeyId}}">
                                     {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                             {{'*'}}
                                         @else @endif</span>
                                 </div>
                             </th>
                             <td class="quotd vcenter" width="25%">
                                 <input type="text" class="quota-input datepickerNextQT js-date next-input"
                                        name="nextYear[{{ $r['field_name'] }}]"
                                        value="{{old('nextYear.' . $r['field_name'])}}" placeholder="{{ $r['placeholder'] }}"
                                        id="{{'N_'.$NYApiKeyId}}" autocomplete="off" maxlength="10">
                                 @IF($errors->has('nextYear.' . $r['field_name']))
                                     <div class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div>
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
                                <div class="{{'N_'.$NYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" class="quota-input next-input"
                                       name="nextYear[{{ $r['field_name'] }}]" placeholder="{{ $r['placeholder'] }}"
                                       maxlength="{{ $r['maximum_length'] }}"
                                       value="{{old('nextYear.' . $r['field_name'])}}" id="{{'N_'.$NYApiKeyId}}">
                                @IF($errors->has('nextYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div>
                                @ENDIF
                            </td>
                        @elseif($r['field_type'] == \App\Enum\FieldTypeEnum::DECIMAL)
                            <th class="quotd vcenter" width="25%">
                                <div class="{{'N_'.$NYApiKeyId}}">
                                    {{ $r['label_name'] }} <span class="required">@if($r['is_required']==1)
                                            {{'*'}}
                                        @else @endif</span>
                                </div>
                            </th>
                            <td class="quotd vcenter" width="25%">
                                <input type="{{ $r['field_type'] }}" name="nextYear[{{ $r['field_name'] }}]"
                                       class="quota-input next-input" maxlength="{{ $r['maximum_length'] }}"
                                       value="{{old('nextYear.' . $r['field_name'])}}"
                                       placeholder="{{ $r['placeholder'] }}" id="{{'N_'.$NYApiKeyId}}">
                                @IF($errors->has('nextYear.' . $r['field_name']))
                                    <div
                                        class="error-message">{{ $errors->first('nextYear.' . $r['field_name']) }}</div>
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
                        <?php $x = 0; ?>
                @elseif($count3 == 1)
                    @if($x == 1)
                        <th class="quotd vcenter" width="25%">&nbsp;</th>
                        <td class="quotd vcenter" width="25%">&nbsp;</td>
                        </tr>
                        @else
                            </tr>
                    @endif
                @endif
                    <?php $x++; $count3--; ?>
            @endforeach
        @endif
        </thead>
    </table>
</td>

<script>
    $(document).ready(function () {
        var manipulateBtnClick = "{{ !empty(old('is_manipulate_btn_click')) ? old('is_manipulate_btn_click') : 0 }}";
        var currentYearShowHide = "{{ $currentYearShowHide }}";
        var nextYearShowHide = "{{ $nextYearShowHide }}";
        var currentYearRequestType = "{{ !empty(old('currentYear.request_type')) ? old('currentYear.request_type') : 'ADD' }}";
        var nextYearRequestType = "{{ !empty(old('nextYear.request_type')) ? old('nextYear.request_type') : 'ADD' }}";

        @if(!empty(old('cYear_request_type')))
            $("#quotaCurrentYearRequest").val('{{ old("cYear_request_type") }}');
        @endif
        @if(!empty(old('cYear_quota_id')))
            $("#currentYearQuotaId").val('{{ old('cYear_quota_id') }}');
        @endif
        @if(!empty(old('cYear_response')))
            $(".c_response").val('{{ old('cYear_response') }}');
        @endif
        @if(!empty(old('customer_info')))
            $(".customer-info").val('{{ old('customer_info') }}');
        @endif
        @if(!empty(old('nYear_request_type')))
            $("#quotaNextYearRequest").val('{{ old('nYear_request_type') }}');
        @endif
        @if(!empty(old('nYear_quota_id')))
            $("#nextYearQuotaId").val('{{ old('nYear_quota_id') }}');
        @endif
        @if(!empty(old('nYear_response')))
            $(".n_response").val('{{ old('nYear_response') }}');
        @endif

        if(currentYearRequestType == 'ADD') {
            $('.C_unUsagePercentage').addClass('d-none');
            $('#C_unUsagePercentage').addClass('d-none');
        } else {
            $('.C_limitUsagePercentage').addClass('d-none');
            $('#C_limitUsagePercentage').addClass('d-none');
            $('#C_limitStartDate').addClass('invisible');
            $('#C_limitEndDate').addClass('invisible');
            $('.C_limitStartDate').addClass('invisible');
            $('.C_limitEndDate').addClass('invisible');
        }
        if(nextYearRequestType == 'ADD') {
            $('.N_unUsagePercentage').addClass('d-none');
            $('#N_unUsagePercentage').addClass('d-none');
        } else {
            $('.N_limitUsagePercentage').addClass('d-none');
            $('#N_limitUsagePercentage').addClass('d-none');
            $('#N_limitStartDate').addClass('invisible');
            $('#N_limitEndDate').addClass('invisible');
            $('.N_limitStartDate').addClass('invisible');
            $('.N_limitEndDate').addClass('invisible');
        }

        if (currentYearShowHide == 1 && nextYearShowHide == 1) {
            $('.current-input').prop('disabled', false);
            $('.next-input').prop('disabled', false);
        } else if (currentYearShowHide == 0 && nextYearShowHide == 0) {
            $('.current-input').prop('disabled', true);
            $('.next-input').prop('disabled', true);
        } else if (currentYearShowHide == 1) {
            $('.current-input').prop('disabled', false);
            $('.next-input').prop('disabled', true);
        } else if (nextYearShowHide == 1) {
            $('.next-input').prop('disabled', false);
            $('.current-input').prop('disabled', true);
        }

        if (manipulateBtnClick == 0) {
            $('.manipulate-error-msg').text('Note: Please first click Pull Data Button');
            $('#quotaSelection').addClass('hidden');
            $('.passport-input').prop('disabled', true);
            $('.current-input').prop('disabled', true);
            $('.next-input').prop('disabled', true);
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
                            $('.irisCheck').prop('disabled', false);
                            $('#InquiryIRISData').removeClass('d-none');
                            $('.customer-info').val(item.encryptCustomerInfo);

                            if (item.passportInfo.length === 0 || (item.currentYearTravelQuota.length === 0 && item.nextYearTravelQuota.length === 0 && item.medicalQuota.length === 0)) {
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

                            if (item.currentYearTravelQuota.length === 0 && item.nextYearTravelQuota.length === 0 && item.medicalQuota.length === 0) {
                                $('#TQMgs').html('Quota Not Found!')
                            }

                            $('.quota-navbar span').removeClass('quota-nav-selected');
                            $('#currentYear').addClass('hidden');
                            $('.manipulate-error-msg').addClass('hidden');
                            $('.current-input').prop('disabled', true);
                            $('.next-input').prop('disabled', true);
                            $('.passport-input').prop('disabled', false);

                            // For Current Year TQ
                            if (item.currentYearTravelQuota.length != 0) {
                                $('#currentYear').removeClass('hidden');
                                $('#current_year').addClass('quota-nav-selected').insertAfter('.please_select');
                                $('#quotaCurrentYearRequest').val('MOD');
                                $('#currentYearQuotaId').val(item.currentYearTravelQuota.quotaId);
                                $('.c_response').val(item.encryptCurrentYearTravelQuota);
                                $('.current-input').prop('disabled', false);
                                $('.next-input').prop('disabled', true);
                                $("[name='currentYearShowHide']").val(1);
                                $("[name='nextYearShowHide']").val(0);
                                $('.C_limitUsagePercentage').addClass('d-none');
                                $('#C_limitUsagePercentage').addClass('d-none');
                                $('.C_unUsagePercentage').removeClass('d-none');
                                $('#C_unUsagePercentage').removeClass('d-none');
                                $('#C_limitStartDate').addClass('invisible');
                                $('#C_limitEndDate').addClass('invisible');
                                $('.C_limitStartDate').addClass('invisible');
                                $('.C_limitEndDate').addClass('invisible');

                                $('[name="cYear_request_type"]').val('MOD');
                                $('[name="cYear_quota_id"]').val(item.currentYearTravelQuota.quotaId);
                                $('[name="cYear_response"]').val(item.encryptCurrentYearTravelQuota);
                            } else {
                                $('#quotaCurrentYearRequest').val('ADD');
                                $('.customer-info').val(item.encryptCustomerInfo);
                                $('.C_unUsagePercentage').addClass('d-none');
                                $('#C_unUsagePercentage').addClass('d-none');

                                $('[name="cYear_request_type"]').val('ADD');
                                $('[name="cYear_customer_info"]').val(item.encryptCustomerInfo);
                            }

                            // For Next Year TQ
                            if (item.nextYearTravelQuota.length != 0) {
                                $('#nextYear').removeClass('hidden');
                                $('#next_year').addClass('quota-nav-selected').insertAfter('.please_select');
                                $('#quotaNextYearRequest').val('MOD');
                                $('#nextYearQuotaId').val(item.nextYearTravelQuota.quotaId);
                                $('.n_response').val(item.encryptNextYearTravelQuota);
                                $('.next-input').prop('disabled', false);
                                $("[name='currentYearShowHide']").val(0);
                                $("[name='nextYearShowHide']").val(1);
                                $('.N_limitUsagePercentage').addClass('d-none');
                                $('#N_limitUsagePercentage').addClass('d-none');
                                $('.N_unUsagePercentage').removeClass('d-none');
                                $('#N_unUsagePercentage').removeClass('d-none');
                                $('#N_limitStartDate').addClass('invisible');
                                $('#N_limitEndDate').addClass('invisible');
                                $('.N_limitStartDate').addClass('invisible');
                                $('.N_limitEndDate').addClass('invisible');

                                $('[name="nYear_request_type"]').val('MOD');
                                $('[name="nYear_quota_id"]').val(item.nextYearTravelQuota.quotaId);
                                $('[name="nYear_response"]').val(item.encryptNextYearTravelQuota);
                            } else {
                                $('#quotaNextYearRequest').val('ADD');
                                $('.customer-info').val(item.encryptCustomerInfo);
                                $('.N_unUsagePercentage').addClass('d-none');
                                $('#N_unUsagePercentage').addClass('d-none');

                                $('[name="nYear_request_type"]').val('ADD');
                                $('[name="nYear_customer_info"]').val(item.encryptCustomerInfo);
                            }

                            // For Both Year TQ
                            if (item.currentYearTravelQuota.length != 0 && item.nextYearTravelQuota.length != 0) {
                                $('#both_year').addClass('quota-nav-selected').insertAfter('.please_select');
                                $('.next-input').prop('disabled', false);
                                $('#next_year').removeClass('quota-nav-selected');
                                $('#current_year').removeClass('quota-nav-selected');
                                $('.current-input').prop('disabled', false);
                                $("[name='currentYearShowHide']").val(1);
                                $("[name='nextYearShowHide']").val(1);
                            }
                            // passport
                            $.each(item.passportInfo, function (pKey, pValue) {
                                console.log(pKey)
                                if (pKey === "passportIsssueplace") { // Corrected key comparison
                                    $("#" + pKey).val(pValue.toUpperCase());
                                } else {
                                    $("#" + pKey).val(pValue);
                                }
                            });

                            // TQ Current year
                            $.each(item.currentYearTravelQuota, function (pKey, pValue) {
                                $("#C_" + pKey).val(pValue);
                            });

                            // TQ Next year
                            $.each(item.nextYearTravelQuota, function (pKey, pValue) {
                                $("#N_" + pKey).val(pValue);
                            });

                        } else {
                            $('#manipulateModal').modal('show');
                            $('#responseMgs').html('Please re-check your masked debit card number and account number!');
                            $('.current-input').prop('disabled', true);
                            $('.next-input').prop('disabled', true);
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
                    setTimeout(function () {
                        window.location.href = "{{ url("Supports/home") }}";
                    }, 4000);
                }
            });
        })


        $('#InquiryIRISData').on('click', function (e) {
            e.preventDefault();
            let passport = $("#passportResponse").val();
            let customerInfo = $(".customer-info").val();
            let CQuota = $(".c_response").val();
            let NQuota = $(".n_response").val();
            let url = "{{ route('iris.inquiryData') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    passport: passport,
                    customerInfo: customerInfo,
                    CQuota: CQuota,
                    NQuota: NQuota,
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


        $('#both_year').on('click', function () {
            $('#loading').addClass('loader-none');
            $(this).addClass('quota-nav-selected');
            $('#current_year').removeClass('quota-nav-selected');
            $('#next_year').removeClass('quota-nav-selected');
            $(this).insertAfter('.please_select');
            $('#currentYear').removeClass('hidden quota-nav-selected');
            $('#nextYear').removeClass('hidden quota-nav-selected');
            $('.current-input').prop('disabled', false);
            $('.next-input').prop('disabled', false);
            $("[name='currentYearShowHide']").val(1);
            $("[name='nextYearShowHide']").val(1);
        });

        $('#current_year').on('click', function () {
            $('#currentYear').removeClass('hidden');
            $(this).addClass('quota-nav-selected');
            $('#next_year').removeClass('quota-nav-selected');
            $('#both_year').removeClass('quota-nav-selected');
            $(this).insertAfter('.please_select');
            $('#nextYear').addClass('hidden');
            $('.current-input').prop('disabled', false);
            $('.next-input').prop('disabled', true);
            $("[name='currentYearShowHide']").val(1);
            $("[name='nextYearShowHide']").val(0);
        });

        $('#next_year').on('click', function () {
            $(this).addClass('quota-nav-selected');
            $(this).insertAfter('.please_select');
            $('#current_year').removeClass('quota-nav-selected');
            $('#both_year').removeClass('quota-nav-selected');
            $('#nextYear').removeClass('hidden');
            $('#currentYear').addClass('hidden');
            $('.next-input').prop('disabled', false);
            $('.current-input').prop('disabled', true);
            $("[name='currentYearShowHide']").val(0);
            $("[name='nextYearShowHide']").val(1);
        });
    });
</script>
</td>
