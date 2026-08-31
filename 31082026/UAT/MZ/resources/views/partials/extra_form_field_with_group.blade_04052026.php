<?php
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i=1;

$customCount = 0;
$divisor = 2;
?>

<td colspan="4" width="100%" class="none-bottom-border">
    <table class="table table-condensed">
        <colgroup>
            <col width="15%"></col>
            <col width="35%"></col>
            <col width="15%"></col>
            <col width="35%"></col>
        </colgroup>
        @php
            $count = count($issue_fields);

        @endphp

        @foreach($issue_fields as $single)
            @if($single['fieldset_title'] == "")

                @php
                    $closTr = '';
                    $qty = count($single['fields']);
                    if($qty == 1){
                        $closTr = '</tr>';
                    }
                @endphp


                @foreach($single['fields'] as $key=>$r)

                @php
                    $customCount ++;
                @endphp
                @if($customCount % $divisor != 0)
                    <tr>

                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php
                                        $options = explode(",", $r->options);
                                        $count = count($options);
                                        $input_dropdown = '<select name="' . $r->field_name . '" class="form-control DependantFields" data-id="'.$r->id.'"> <option value="">Please Select</option>';
                                        foreach ($options as $k => $option) {
                                            $selected  = "";
                                            $option_name = $option;
                                            $old = old($r->field_name);
                                            if (str_contains($option_name,'~')) {
                                                $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                            }
                                            if (!empty($old) && str_contains($old,'~')) {
                                                $old = substr($old, strpos($old, "~") + 1);
                                            }
                                            if($option_name == $old){
                                                $selected  = "selected";
                                            }
                                            if($count == 1){
                                                $selected  = "selected";
                                            }
                                            $input_dropdown .= '<option value="' . $option . '" '.$selected.' >' . $option_name . '</option>';
                                        }
                                        $input_dropdown .= '</select>';
                                    @endphp
                                    {!! $input_dropdown !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::RADIO)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php $options = explode(",", $r->options);
                                        $input_radio .= '<ul>';
                                        foreach ($options as $k => $option) {
                                        $selected  = "";
                                        if($option == old($r->field_name)){
                                            $selected  = "selected";
                                        }
                                        $input_radio .= '<li><input type="radio" name="' . $r->field_name . '" value="' . $option . '" '.$selected.'><label>' . $option . '</label></li>';
                                        }
                                        $input_radio .= '</ul>';
                                    @endphp
                                    {!! $input_radio !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="{{ $r->field_name }}">
                                    <textarea  class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}">{{old($r->field_name)}}</textarea>
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="{{ $r->field_name }}">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(",", $r->options);
                                        //prd($options);
                                        $input_checkbox .= '<ul>';

                                        foreach ($options as $k => $option) {
                                                $value = $option;
                                                if(empty($option)) {
                                                    $value = 'Yes';
                                                }
                                                $selected  = "";
                                                if($value == old($r->field_name)){
                                                    $selected  = "checked";
                                                }
                                                $input_checkbox .= '<li><label><input type="checkbox" name="' . $r->field_name . '" value="' . $value . '" '.$selected.'>' . $option . '</label></li>';

                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    {{-- <input type="{{ $r->field_type }}" class="form-control date" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" max="9999-12-31"> --}}
                                    <input type="text" class="form-control datePicker js-date" name="{{ $r->field_name }}"   value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(document).ready(function () {
                                            $('.datePicker').datepicker({
                                                dateFormat: 'dd-mm-yy',
                                                changeYear: true,
                                                changeMonth: true,
                                                yearRange: "1900:2050",
                                            });
                                        });
                                        var input = document.querySelectorAll('.js-date')[0];
                                        var dateInputMask = function dateInputMask(elm) {
                                            elm.addEventListener('keypress', function(e) {
                                                if(e.keyCode < 47 || e.keyCode > 57) {
                                                    e.preventDefault();
                                                }
                                                var len = elm.value.length;
                                                // If we're at a particular place, let the user type the slash
                                                // i.e., 12/12/1212
                                                if(len !== 1 || len !== 3) {
                                                    if(e.keyCode == 47) {
                                                        e.preventDefault();
                                                    }
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 2) {
                                                    elm.value += '-';
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 5) {
                                                    elm.value += '-';
                                                }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </td>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerPrev" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}" placeholder="dd-mm-yyyy" readonly/>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(function() {
                                        $(".datepickerPrev").datepicker({
                                            defaultDate: 0,
                                            maxDate: 0,
                                            dateFormat: 'dd-mm-yy',
                                            showButtonPanel: true,
                                            changeYear: true,
                                            changeMonth: true,
                                            yearRange: "1900:2050",
                                        });
                                        });
                                    </script>
                                </td>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerNext" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}" placeholder="dd-mm-yyyy" readonly/>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(function() {
                                        $(".datepickerNext").datepicker({
                                            minDate: 1,
                                            dateFormat: 'dd-mm-yy',
                                            changeYear: true,
                                            changeMonth: true,
                                            showButtonPanel: true,
                                            yearRange: "1900:2050",
                                        });
                                        });
                                    </script>
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @else
                            @endif
                    @php
                        $closTr;
                    @endphp

                @else

                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php
                                        $options = explode(",", $r->options);
                                        $count = count($options);
                                        $input_dropdown = '<select name="' . $r->field_name . '" class="form-control DependantFields" data-id="'.$r->id.'"> <option value="">Please Select</option>';
                                        foreach ($options as $k => $option) {
                                            $selected  = "";
                                            $option_name = $option;
                                            $old = old($r->field_name);
                                            if (str_contains($option_name,'~')) {
                                                $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                            }
                                            if (!empty($old) && str_contains($old,'~')) {
                                                $old = substr($old, strpos($old, "~") + 1);
                                            }
                                            if($option_name == $old){
                                                $selected  = "selected";
                                            }
                                            if($count == 1){
                                                $selected  = "selected";
                                            }
                                            $input_dropdown .= '<option value="' . $option . '" '.$selected.' >' . $option_name . '</option>';
                                        }
                                        $input_dropdown .= '</select>';
                                    @endphp
                                    {!! $input_dropdown !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::RADIO)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php $options = explode(",", $r->options);
                                        $input_radio .= '<ul>';
                                        foreach ($options as $k => $option) {
                                        $selected  = "";
                                        if($option == old($r->field_name)){
                                            $selected  = "selected";
                                        }
                                        $input_radio .= '<li><input type="radio" name="' . $r->field_name . '" value="' . $option . '" '.$selected.'><label>' . $option . '</label></li>';
                                        }
                                        $input_radio .= '</ul>';
                                    @endphp
                                    {!! $input_radio !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="{{ $r->field_name }}">
                                    <textarea  class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}">{{old($r->field_name)}}</textarea>
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="{{ $r->field_name }}">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(",", $r->options);
                                        //prd($options);
                                        $input_checkbox .= '<ul>';

                                        foreach ($options as $k => $option) {
                                                $value = $option;
                                                if(empty($option)) {
                                                    $value = 'Yes';
                                                }
                                                $selected  = "";
                                                if($value == old($r->field_name)){
                                                    $selected  = "checked";
                                                }
                                                $input_checkbox .= '<li><label><input type="checkbox" name="' . $r->field_name . '" value="' . $value . '" '.$selected.'>' . $option . '</label></li>';

                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    {{-- <input type="{{ $r->field_type }}" class="form-control date" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" max="9999-12-31"> --}}
                                    <input type="text" class="form-control datePicker js-date" name="{{ $r->field_name }}"   value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(document).ready(function () {
                                            $('.datePicker').datepicker({
                                                dateFormat: 'dd-mm-yy',
                                                changeYear: true,
                                                changeMonth: true,
                                                yearRange: "1900:2050",
                                            });
                                        });
                                        var input = document.querySelectorAll('.js-date')[0];
                                        var dateInputMask = function dateInputMask(elm) {
                                            elm.addEventListener('keypress', function(e) {
                                                if(e.keyCode < 47 || e.keyCode > 57) {
                                                    e.preventDefault();
                                                }
                                                var len = elm.value.length;
                                                // If we're at a particular place, let the user type the slash
                                                // i.e., 12/12/1212
                                                if(len !== 1 || len !== 3) {
                                                    if(e.keyCode == 47) {
                                                        e.preventDefault();
                                                    }
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 2) {
                                                    elm.value += '-';
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 5) {
                                                    elm.value += '-';
                                                }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </td>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerPrev" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}" placeholder="dd-mm-yyyy" readonly/>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(function() {
                                        $(".datepickerPrev").datepicker({
                                            defaultDate: 0,
                                            maxDate: 0,
                                            dateFormat: 'dd-mm-yy',
                                            showButtonPanel: true,
                                            changeYear: true,
                                            changeMonth: true,
                                            yearRange: "1900:2050",
                                        });
                                        });
                                    </script>
                                </td>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerNext" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}" placeholder="dd-mm-yyyy" readonly/>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(function() {
                                        $(".datepickerNext").datepicker({
                                            minDate: 1,
                                            dateFormat: 'dd-mm-yy',
                                            changeYear: true,
                                            changeMonth: true,
                                            showButtonPanel: true,
                                            yearRange: "1900:2050",
                                        });
                                        });
                                    </script>
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)

                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </td>


                            @else
                            @endif

                    </tr>
                @endif




                @endforeach
            @endif

            @if($single['fieldset_title'] != "")
                <fieldset style="color:#000;border: 1px solid #81b8ef;padding: 20px; margin-bottom: 10px">
                    <legend class="scheduler-border" style="font-family: Verdana,Geneva,sans-serif;color:#fff;background-color:#4297e2;font-size: 15px !important;padding: 2px 8px 2px 8px">{{ $single['fieldset_title'] }}:</legend>
                    <div class="row">
                        @foreach($single['fields'] as $key=>$r)

                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)

                                <div class="{{ $r->field_name }} vcenter col-2 font-weight-bold mb-2 ">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="{{ $r->field_name }} vcenter col-4 mb-2">
                                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    @php
                                        $options = explode(",", $r->options);
                                        $count = count($options);
                                        $input_dropdown = '<select name="' . $r->field_name . '" class="form-control DependantFields"  data-id="'.$r->id.'"> <option value="">Please Select</option>';
                                        foreach ($options as $k => $option) {
                                            $selected  = "";
                                            $option_name = $option;
                                            $old = old($r->field_name);
                                            if (str_contains($option_name,'~')) {
                                                $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                            }
                                            if (!empty($old) && str_contains($old,'~')) {
                                                $old = substr($old, strpos($old, "~") + 1);
                                            }
                                            if($option_name == $old){
                                                $selected  = "selected";
                                            }
                                            if($count == 1){
                                                $selected  = "selected";
                                            }
                                            $input_dropdown .= '<option value="' . $option . '" '.$selected.' >' . $option_name . '</option>';
                                        }
                                        $input_dropdown .= '</select>';
                                    @endphp
                                    {!! $input_dropdown !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::RADIO)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="col-4 mb-2 {{ $r->field_name }}">
                                    @php $options = explode(",", $r->options);
                                        $input_radio .= '<ul>';
                                        foreach ($options as $k => $option) {
                                        $selected  = "";
                                        if($option == old($r->field_name)){
                                            $selected  = "selected";
                                        }
                                        $input_radio .= '<li><input type="radio" name="' . $r->field_name . '" value="' . $option . '" '.$selected.'><label>' . $option . '</label></li>';
                                        }
                                        $input_radio .= '</ul>';
                                    @endphp
                                    {!! $input_radio !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="col-4 mb-2 {{ $r->field_name }}">
                                    <textarea  class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}">{{old($r->field_name)}}</textarea>
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>


                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="col-4 mb-2 {{ $r->field_name }}">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(",", $r->options);
                                        //prd($options);
                                        $input_checkbox .= '<ul>';

                                        foreach ($options as $k => $option) {
                                                $value = $option;
                                                if(empty($option)) {
                                                    $value = 'Yes';
                                                }
                                                $selected  = "";
                                                if($value == old($r->field_name)){
                                                    $selected  = "checked";
                                                }
                                                $input_checkbox .= '<li><label><input type="checkbox" name="' . $r->field_name . '" value="' . $value . '" '.$selected.'>' . $option . '</label></li>';

                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="vcenter mb-2 col-4 {{ $r->field_name }}">
                                    {{-- <input type="{{ $r->field_type }}" class="form-control date" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" max="9999-12-31"> --}}
                                    <input type="text" class="form-control datePicker js-date" name="{{ $r->field_name }}"   value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(document).ready(function () {
                                            $('.datePicker').datepicker({
                                                dateFormat: 'dd-mm-yy',
                                                changeYear: true,
                                                changeMonth: true,
                                                yearRange: "1900:2050",
                                            });
                                        });
                                        var input = document.querySelectorAll('.js-date')[0];
                                        var dateInputMask = function dateInputMask(elm) {
                                            elm.addEventListener('keypress', function(e) {
                                                if(e.keyCode < 47 || e.keyCode > 57) {
                                                    e.preventDefault();
                                                }
                                                var len = elm.value.length;
                                                // If we're at a particular place, let the user type the slash
                                                // i.e., 12/12/1212
                                                if(len !== 1 || len !== 3) {
                                                    if(e.keyCode == 47) {
                                                        e.preventDefault();
                                                    }
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 2) {
                                                    elm.value += '-';
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 5) {
                                                    elm.value += '-';
                                                }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </div>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="vcenter mb-2 col-4 {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerPrev" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(function() {
                                            $(".datepickerPrev").datepicker({
                                                defaultDate: 0,
                                                maxDate: 0,
                                                dateFormat: 'dd-mm-yy',
                                                showButtonPanel: true,
                                                changeYear: true,
                                                changeMonth: true,
                                                yearRange: "1900:2050",
                                            });
                                        });
                                    </script>
                                </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="vcenter mb-2 col-4 {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerNext" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                                    <script>
                                        $(function() {
                                            $(".datepickerNext").datepicker({
                                                minDate: 1,
                                                dateFormat: 'dd-mm-yy',
                                                changeYear: true,
                                                changeMonth: true,
                                                showButtonPanel: true,
                                                yearRange: "1900:2050",
                                            });
                                        });
                                    </script>
                                </div>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>

                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)

                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                                </div>
                            @else
                            @endif

                            @if($i == 2)

                                    <?php $i=0;?>
                            @endif
                                <?php $i++; $count--;?>
                        @endforeach
                    </div>
                </fieldset>
            @endif
        @endforeach
    </table>
</td>
