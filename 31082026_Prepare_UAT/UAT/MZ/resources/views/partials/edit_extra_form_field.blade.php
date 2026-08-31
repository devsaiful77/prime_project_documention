<?php
/**
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com>.
 * User: Tanay
 * Date: 8/14/2020
 * Time: 12:48 PM
 */
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i = 1;
$count = 0;
?>
<table width="100%">
    <tr>
        <td colspan="4" width="100%">
            <table class="table table-condensed" style="background-color:#BBE1E2">
                <colgroup>
                    <col width="15%"></col>
                    <col width="35%"></col>
                    <col width="15%"></col>
                    <col width="35%"></col>
                </colgroup>

                @php
                    if(!empty($issue_fields)){
                        $count = count($issue_fields);
                    }else{
                    $count = 0;
                }
                    //echo $count;
                @endphp

                @if($count==0)
                    <tr>
                        <th class="vcenter" colspan="4">There is no Issue Data. Please check in Issue Data Config or
                            consult with the admin.
                        </th>
                    </tr>
                @endif

                @if($count>0)
                    <tr>
                        <th class="vcenter" style="font-color: #68AFF6">Issue Data
                        <th>
                        <td class="vcenter"></td>
                        <th class="vcenter">
                        <th>
                        <td class="vcenter"></td>
                    </tr>
                @endif

                @if(!empty($issue_fields))
                    @foreach($issue_fields as $key=>$r)

                        @if($i == 1)
                            <tr>
                                @endif
                                @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)

                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td class="vcenter">
                                        @php $e_value=''; @endphp
                                        @foreach(json_decode($exits_data->extra_field) as $e_field)
                                            @foreach($e_field as $key=>$e)
                                                @if($key==$r->label_name)
                                                    @php $e_value=$e;@endphp
                                                @endif
                                            @endforeach
                                        @endforeach

                                        <input type="{{ $r->field_type }}" class="form-control"
                                               name="{{ $r->field_name }}" value="{{old($r->field_name,$e_value)}}"
                                               placeholder="{{ $r->placeholder }}">
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>


				@elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required == 1){{'*'}}@else @endif</span></th>
                                    <td class="vcenter">
                                        @php $e_value = ''; @endphp
                                        @if(isset($exits_data->extra_field) && !empty($exits_data->extra_field))
                                            @foreach(json_decode($exits_data->extra_field) as $e_field)
                                                @foreach($e_field as $key => $e)
                                                    @if($key == $r->label_name)
                                                        @php $e_value = $e; @endphp
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif

                                        <input type="file" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}">

                                     

                                        @if($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @endif
                                    </td>



                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)
                                    @php $d_value=''; @endphp

                                    @foreach(json_decode($exits_data->extra_field) as $d_field)

                                        @foreach($d_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $d_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach

                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}}@else @endif</span></th>
                                    <td class="vcenter"> @php
                                            $options = explode(",", $r->options);
                                            $input_dropdown = '<select name="' . $r->field_name . '" class="form-control">';
                                            foreach ($options as $k => $option) {
                                                $selected = "";
                                                $option_name = $option;
                                                if (str_contains($option_name,'~')) {
                                                    $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                                }
                                                if (str_contains($d_value,'~')) {
                                                    $d_value = substr($d_value, strpos($d_value, "~") + 1);
                                                }
                                                if ($option_name == old($r->field_name,$d_value)) {
                                                    $selected = " selected";
                                                }
                                                $input_dropdown .= '<option value="' . $option . '" '.$selected.' >' . $option_name . '</option>';
                                            }
                                            $input_dropdown .= '</select>';
                                        @endphp
                                        {!! $input_dropdown !!}
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::RADIO)
                                    @php $r_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $r_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td>
                                        @php
                                            $options = explode(",", $r->options);
                                            $input_radio .= '<ul>';
                                            foreach ($options as $k => $option) {
                                            $selected  = "";
                                            if($option == old($r->field_name,$r_value)){
                                                $selected  = "checked";
                                            }
                                            $input_radio .= '<li><input type="radio" class="green" name="' . $r->field_name . '" value="' . $option . '" '.$selected.'><label>' . $option . '</label></li>';
                                            }
                                            $input_radio .= '</ul>';
                                        @endphp
                                        {!! $input_radio !!}
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA)
                                    @php $t_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $t_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td>
                                        <textarea class="form-control" name="{{ $r->field_name }}"
                                                  placeholder="{{ $r->placeholder }}"
                                                  maxlength="{{ $r->maximum_length }}">{{old($r->field_name,$t_value)}}</textarea>
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
                                    @php $a_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $a_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td class="vcenter">
                                        <input type="{{ $r->field_type }}" class="form-control"
                                               name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                               maxlength="{{ $r->maximum_length }}"
                                               value="{{old($r->field_name,$a_value)}}">
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)
                                    @php $c_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $c_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td>
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
                                                    if($value == old($r->field_name,$c_value)){
                                                        $selected  = "checked";
                                                    }
                                                    $input_checkbox .= '<li><label><input type="checkbox" name="' . $r->field_name . '" value="' . $value . '" '.$selected.'>' . $option . '</label></li>';

                                            }
                                            $input_checkbox .= '</ul>';
                                        @endphp
                                        {!! $input_checkbox !!}
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                                    @php $d_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $d_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td class="vcenter">
                                        {{-- <input type="{{ $r->field_type }}" class="form-control date"
                                               name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                               maxlength="{{ $r->maximum_length }}"
                                               value="{{old($r->field_name,$d_value)}}" max="9999-12-31"> --}}
                                               <input type="text" class="form-control datePicker js-date" name="{{ $r->field_name }}"   value="{{old($r->field_name,$d_value)}}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy">
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
                                        @if($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @endif
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                                    @php $d_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $d_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td class="vcenter">
                                        <input type="text" class="form-control datepickerNext" name="{{ $r->field_name }}" value="{{old($r->field_name,$d_value)}}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy">
                                        <script>
                                            $(document).ready(function () {
                                                $('.datepickerNext').datepicker({
                                                    minDate: 1,
                                                    dateFormat: 'dd-mm-yy',
                                                    showButtonPanel: true,
                                                    changeYear: true,
                                                    changeMonth: true,
                                                    yearRange: "1900:2050",
                                                });
                                            });
                                            /*var input = document.querySelectorAll('.js-date')[0];
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
                                            dateInputMask(input);*/
                                        </script>
                                        @if($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @endif
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                                    @php $d_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $d_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td class="vcenter">
                                        <input type="text" class="form-control datepickerPrev" name="{{ $r->field_name }}" value="{{old($r->field_name,$d_value)}}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy">
                                        <script>
                                            $(document).ready(function () {
                                                $('.datepickerPrev').datepicker({
                                                    defaultDate: 0,
                                                    maxDate: 0,
                                                    dateFormat: 'dd-mm-yy',
                                                    changeYear: true,
                                                    changeMonth: true,
                                                    showButtonPanel: true,
                                                    yearRange: "1900:2050",
                                                });
                                            });
                                            /*var input = document.querySelectorAll('.js-date')[0];
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
                                            dateInputMask(input);*/
                                        </script>
                                        @if($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @endif
                                    </td>


                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)
                                    @php $n_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $n_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td class="vcenter">
                                        <input type="text" class="form-control" name="{{ $r->field_name }}"
                                               placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}"
                                               value="{{old($r->field_name,$n_value)}}">
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                                    @php $n_value=''; @endphp
                                    @foreach(json_decode($exits_data->extra_field) as $e_field)
                                        @foreach($e_field as $key=>$e)
                                            @if($key==$r->label_name)
                                                @php $n_value=$e;@endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1)
                                                {{'*'}}
                                            @else @endif</span></th>
                                    <td class="vcenter">
                                        <input type="text" class="form-control" name="{{ $r->field_name }}"
                                            placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}"
                                            value="{{old($r->field_name,$n_value)}}">
                                        @IF($errors->has($r->field_name))
                                            <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                        @ENDIF
                                    </td>

                                @else
                                @endif

                                @if($i == 2)
                            </tr>
                            <?php $i = 0;?>
                        @elseif($count == 1)
                            @if($i == 1)
                                <th>&nbsp;</th>
                                <td>&nbsp;</td>
                                </tr>
                                @else
                                    </tr>
                            @endif
                        @endif

                        <?php $i++; $count--;?>

                    @endforeach
                @endif

            </table>
        </td>
    </tr>
</table>
