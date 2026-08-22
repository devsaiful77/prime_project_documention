<?php
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i = 1;
$issueId = '';
if (!empty($issue_id)) {
    $issueId = $issue_id;
}

$customCount = 0;
$divisor = 2;
//dd($issueId);
?>

<td colspan="4" width="100%" class="none-bottom-border">
    @if ($issueId == 1192)
        <a href="#" id="pullData" class="btn btn-sm btn-warning my-4">Pull Data</a>
    @endif
    <table class="table table-condensed">
        <colgroup>
            <col width="15%">
            </col>
            <col width="35%">
            </col>
            <col width="15%">
            </col>
            <col width="35%">
            </col>
        </colgroup>
        @php
            $count = count($issue_fields);

        @endphp

        @foreach ($issue_fields as $single)
            @if ($single['fieldset_title'] == '')
                @php
                    $closTr = '';
                    $qty = count($single['fields']);
                    if ($qty == 1) {
                        $closTr = '</tr>';
                    }
                @endphp


                @foreach ($single['fields'] as $key => $r)
                    @php
                        $customCount++;
                    @endphp
                    @if ($customCount % $divisor != 0)
                        @php
                            $PApiKey = $r['api_key'];
                            $PApiKeyArr = explode(':', $PApiKey);
                            $PApiKeyId = $PApiKeyArr[0];
                        @endphp

                        <tr>

                            @if ($r->field_type == \App\Enum\FieldTypeEnum::TEXT)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}" value="{{ old($r->field_name) }}"
                                        placeholder="{{ $r->placeholder }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DROPDOWN)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php
                                        $options = explode(',', $r->options);
                                        $count = count($options);
                                        $input_dropdown =
                                            '<select name="' .
                                            $r->field_name .
                                            '" class="form-control DependantFields" data-id="' .
                                            $r->id .
                                            '"> <option value="">Please Select</option>';
                                        foreach ($options as $k => $option) {
                                            $selected = '';
                                            $option_name = $option;
                                            $old = old($r->field_name);
                                            if (str_contains($option_name, '~')) {
                                                $option_name = substr($option_name, strpos($option_name, '~') + 1);
                                            }
                                            if (!empty($old) && str_contains($old, '~')) {
                                                $old = substr($old, strpos($old, '~') + 1);
                                            }
                                            if ($option_name == $old) {
                                                $selected = 'selected';
                                            }
                                            if ($count == 1) {
                                                $selected = 'selected';
                                            }
                                            $input_dropdown .=
                                                '<option value="' .
                                                $option .
                                                '" ' .
                                                $selected .
                                                ' >' .
                                                $option_name .
                                                '</option>';
                                        }
                                        $input_dropdown .= '</select>';
                                    @endphp
                                    {!! $input_dropdown !!}
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::RADIO)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php $options = explode(",", $r->options); @endphp
                                    @foreach ($options as $key => $option)
                                        @php
                                            $selected = '';
                                            if ($option == old($r->field_name)) {
                                                $selected = 'checked';
                                            }
                                        @endphp

                                        <div class="form-check d-inline-block me-2">
                                            <input class="form-check-input" type="radio" name="{{ $r->field_name }}"
                                                value="{{ $option }}" {{ $selected }}>
                                            <label class="form-check-label">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="{{ $r->field_name }}">
                                    <textarea class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" id="{{ $PApiKeyId }}" @if($r->is_readonly == 1) readonly @endif>{{ old($r->field_name) }}</textarea>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::ADDRESS)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control"
                                        name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        id="{{ $PApiKeyId }}" maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::CHECKBOX)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="{{ $r->field_name }}">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(',', $r->options);
                                        //prd($options);
                                        $input_checkbox .= '<ul>';

                                        foreach ($options as $k => $option) {
                                            $value = $option;
                                            if (empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected = '';
                                            if ($value == old($r->field_name)) {
                                                $selected = 'checked';
                                            }
                                            $input_checkbox .=
                                                '<li><label><input type="checkbox" name="' .
                                                $r->field_name .
                                                '" value="' .
                                                $value .
                                                '" ' .
                                                $selected .
                                                '>' .
                                                $option .
                                                '</label></li>';
                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    {{-- <input type="{{ $r->field_type }}" class="form-control date" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" max="9999-12-31"> --}}
                                    <input type="text" class="form-control datePicker js-date"
                                        name="{{ $r->field_name }}" id="{{ $PApiKeyId }}"
                                        value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10"
                                        placeholder="dd-mm-yyyy" readonly>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
                                    <script>
                                        $(document).ready(function() {
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
                                                if (e.keyCode < 47 || e.keyCode > 57) {
                                                    e.preventDefault();
                                                }
                                                var len = elm.value.length;
                                                // If we're at a particular place, let the user type the slash
                                                // i.e., 12/12/1212
                                                if (len !== 1 || len !== 3) {
                                                    if (e.keyCode == 47) {
                                                        e.preventDefault();
                                                    }
                                                }
                                                // If they don't add the slash, do it for them...
                                                if (len === 2) {
                                                    elm.value += '-';
                                                }
                                                // If they don't add the slash, do it for them...
                                                if (len === 5) {
                                                    elm.value += '-';
                                                }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::PREV_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerPrev"
                                        name="{{ $r->field_name }}" value="{{ old($r->field_name) }}"
                                        placeholder="dd-mm-yyyy" readonly />
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
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
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::NEXT_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerNext"
                                        name="{{ $r->field_name }}" value="{{ old($r->field_name) }}"
                                        placeholder="dd-mm-yyyy" readonly />
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
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
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::NUMBER)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="number" class="form-control" name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}">
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DECIMAL)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control" name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}">
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>

                            {{-- file --}}
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="file" class="form-control" name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}">
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @else
                            @endif
                            @php
                                $closTr;
                            @endphp
                    @else
                            @if ($r->field_type == \App\Enum\FieldTypeEnum::TEXT)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control"
                                        id="{{ $PApiKeyId }}" name="{{ $r->field_name }}"
                                        value="{{ old($r->field_name) }}" placeholder="{{ $r->placeholder }}">
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DROPDOWN)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php
                                        $options = explode(',', $r->options);
                                        $count = count($options);
                                        $input_dropdown =
                                            '<select name="' .
                                            $r->field_name .
                                            '" class="form-control DependantFields" data-id="' .
                                            $r->id .
                                            '"> <option value="">Please Select</option>';
                                        foreach ($options as $k => $option) {
                                            $selected = '';
                                            $option_name = $option;
                                            $old = old($r->field_name);
                                            if (str_contains($option_name, '~')) {
                                                $option_name = substr($option_name, strpos($option_name, '~') + 1);
                                            }
                                            if (!empty($old) && str_contains($old, '~')) {
                                                $old = substr($old, strpos($old, '~') + 1);
                                            }

                                            if ($r->field_name != 'bidding_month') {
                                                if ($option_name == $old) {
                                                    $selected = 'selected';
                                                }
                                            }

                                            if ($count == 1) {
                                                $selected = 'selected';
                                            }
                                            $input_dropdown .=
                                                '<option value="' .
                                                $option .
                                                '" ' .
                                                $selected .
                                                ' >' .
                                                $option_name .
                                                '</option>';
                                        }
                                        $input_dropdown .= '</select>';
                                    @endphp
                                    {!! $input_dropdown !!}
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::RADIO)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    @php
                                        $options = explode(',', $r->options);
                                        $input_radio = '<ul>';

                                        foreach ($options as $k => $option) {
                                            $checked = old($r->field_name) == $option ? 'checked' : '';

                                            $input_radio .= '<li>
                                                <input type="radio" name="'.$r->field_name.'" value="'.$option.'" '.$checked.'>
                                                <label>'.$option.'</label>
                                            </li>';
                                        }

                                        $input_radio .= '</ul>';
                                    @endphp
                                    {!! $input_radio !!}
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="{{ $r->field_name }}">
                                    <textarea class="form-control" name="{{ $r->field_name }}" id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}">{{ old($r->field_name) }}</textarea>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::ADDRESS)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control"
                                        id="{{ $PApiKeyId }}" name="{{ $r->field_name }}"
                                        placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}">
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::CHECKBOX)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="{{ $r->field_name }}">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(',', $r->options);
                                        //prd($options);
                                        $input_checkbox .= '<ul>';

                                        foreach ($options as $k => $option) {
                                            $value = $option;
                                            if (empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected = '';
                                            if ($value == old($r->field_name)) {
                                                $selected = 'checked';
                                            }
                                            $input_checkbox .=
                                                '<li><label><input type="checkbox" name="' .
                                                $r->field_name .
                                                '" value="' .
                                                $value .
                                                '" ' .
                                                $selected .
                                                '>' .
                                                $option .
                                                '</label></li>';
                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    {{-- <input type="{{ $r->field_type }}" class="form-control date" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" max="9999-12-31"> --}}
                                    <input type="text" class="form-control datePicker js-date"
                                        id="{{ $PApiKeyId }}" name="{{ $r->field_name }}"
                                        value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10"
                                        placeholder="dd-mm-yyyy" readonly>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
                                    <script>
                                        $(document).ready(function() {
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
                                                if (e.keyCode < 47 || e.keyCode > 57) {
                                                    e.preventDefault();
                                                }
                                                var len = elm.value.length;
                                                // If we're at a particular place, let the user type the slash
                                                // i.e., 12/12/1212
                                                if (len !== 1 || len !== 3) {
                                                    if (e.keyCode == 47) {
                                                        e.preventDefault();
                                                    }
                                                }
                                                // If they don't add the slash, do it for them...
                                                if (len === 2) {
                                                    elm.value += '-';
                                                }
                                                // If they don't add the slash, do it for them...
                                                if (len === 5) {
                                                    elm.value += '-';
                                                }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::PREV_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerPrev"
                                        name="{{ $r->field_name }}" value="{{ old($r->field_name) }}"
                                        placeholder="dd-mm-yyyy" readonly />
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
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
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::NEXT_DATE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerNext"
                                        name="{{ $r->field_name }}" value="{{ old($r->field_name) }}"
                                        placeholder="dd-mm-yyyy" readonly />
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
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
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::NUMBER)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="number" class="form-control" id="{{ $PApiKeyId }}"
                                        name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}"  @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DECIMAL)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="text" class="form-control" id="{{ $PApiKeyId }}"
                                        name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>

                            {{-- file --}}
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
                                <th class="vcenter {{ $r->field_name }}">{{ $r->label_name }}<span
                                        class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></th>
                                <td class="vcenter {{ $r->field_name }}">
                                    <input type="file" class="form-control" id="{{ $PApiKeyId }}"
                                        name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </td>

                            @else
                            @endif

                        </tr>
                    @endif
                @endforeach
            @endif

            @if ($single['fieldset_title'] != '')
                <fieldset  class="scheduler-border"
                    @if(!empty($single['fieldset_id']))
                        id="{{ $single['fieldset_id'] }}"
                    @endif
                    style="color:#000;border: 1px solid #81b8ef;padding: 20px; margin-top: 10px !important;">
                    <div class="scheduler-border">{{ $single['fieldset_title'] }}:</div>
                    <div class="row">
                        @foreach ($single['fields'] as $key => $r)
                            @php
                                $PApiKey = $r['api_key'];
                                $PApiKeyArr = explode(':', $PApiKey);
                                $PApiKeyId = $PApiKeyArr[0];
                            @endphp
                            @if ($r->field_type == \App\Enum\FieldTypeEnum::TEXT)
                                <div class="{{ $r->field_name }} vcenter col-2 font-weight-bold mb-2 ">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="{{ $r->field_name }} vcenter col-4 mb-2">
                                    <input type="{{ $r->field_type }}" class="form-control"
                                        id="{{ $PApiKeyId }}" name="{{ $r->field_name }}"
                                        value="{{ old($r->field_name) }}" placeholder="{{ $r->placeholder }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DROPDOWN)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    @php
                                        $options = explode(',', $r->options);
                                        $count = count($options);
                                        $input_dropdown =
                                            '<select name="' .
                                            $r->field_name .
                                            '" class="form-control DependantFields"  data-id="' .
                                            $r->id .
                                            '"> <option value="">Please Select</option>';
                                        foreach ($options as $k => $option) {
                                            $selected = '';
                                            $option_name = $option;
                                            $old = old($r->field_name);
                                            if (str_contains($option_name, '~')) {
                                                $option_name = substr($option_name, strpos($option_name, '~') + 1);
                                            }
                                            if (!empty($old) && str_contains($old, '~')) {
                                                $old = substr($old, strpos($old, '~') + 1);
                                            }
                                            if ($option_name == $old) {
                                                $selected = 'selected';
                                            }
                                            if ($count == 1) {
                                                $selected = 'selected';
                                            }
                                            $input_dropdown .=
                                                '<option value="' .
                                                $option .
                                                '" ' .
                                                $selected .
                                                ' >' .
                                                $option_name .
                                                '</option>';
                                        }
                                        $input_dropdown .= '</select>';
                                    @endphp
                                    {!! $input_dropdown !!}
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::RADIO)

                            <div class="row mb-2">

                                <!-- Label -->
                                <div class="col-4 vcenter font-weight-bold {{ $r->field_name }}">
                                    {{ $r->label_name }}
                                    <span class="required">
                                        @if ($r->is_required == 1)
                                            *
                                        @endif
                                    </span>
                                </div>

                                <!-- Radio Options -->
                                <div class="col-8 vcenter {{ $r->field_name }}">
                                    @php
                                        $options = explode(',', $r->options);
                                    @endphp


                                    <ul class="list-unstyled mb-0">
                                        @foreach ($options as $option)

                                            <li class="mb-1">
                                                <input type="radio"
                                                    name="{{ $r->field_name }}"
                                                    value="{{ $option }}"
                                                    {{ old($r->field_name) == $option ? 'checked' : '' }}>
                                                <label>{{ $option }}</label>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
                                </div>

                            </div>




                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::TEXTAREA)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="col-4 mb-2 {{ $r->field_name }}">
                                    <textarea class="form-control" name="{{ $r->field_name }}" id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" @if($r->is_readonly == 1) readonly @endif>{{ old($r->field_name) }}</textarea>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::ADDRESS)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    <input type="{{ $r->field_type }}" class="form-control"
                                        id="{{ $PApiKeyId }}" name="{{ $r->field_name }}"
                                        placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::CHECKBOX)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="col-4 mb-2 {{ $r->field_name }}">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(',', $r->options);
                                        //prd($options);
                                        $input_checkbox .= '<ul>';

                                        foreach ($options as $k => $option) {
                                            $value = $option;
                                            if (empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected = '';
                                            if ($value == old($r->field_name)) {
                                                $selected = 'checked';
                                            }
                                            $input_checkbox .=
                                                '<li><label><input type="checkbox" name="' .
                                                $r->field_name .
                                                '" value="' .
                                                $value .
                                                '" ' .
                                                $selected .
                                                '>' .
                                                $option .
                                                '</label></li>';
                                        }
                                        $input_checkbox .= '</ul>';
                                    @endphp
                                    {!! $input_checkbox !!}
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DATE)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter mb-2 col-4 {{ $r->field_name }}">
                                    {{-- <input type="{{ $r->field_type }}" class="form-control date" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" max="9999-12-31"> --}}
                                    <input type="text" class="form-control datePicker js-date"
                                        id="{{ $PApiKeyId }}" name="{{ $r->field_name }}"
                                        value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10"
                                        placeholder="dd-mm-yyyy" readonly>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
                                    <script>
                                        $(document).ready(function() {
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
                                                if (e.keyCode < 47 || e.keyCode > 57) {
                                                    e.preventDefault();
                                                }
                                                var len = elm.value.length;
                                                // If we're at a particular place, let the user type the slash
                                                // i.e., 12/12/1212
                                                if (len !== 1 || len !== 3) {
                                                    if (e.keyCode == 47) {
                                                        e.preventDefault();
                                                    }
                                                }
                                                // If they don't add the slash, do it for them...
                                                if (len === 2) {
                                                    elm.value += '-';
                                                }
                                                // If they don't add the slash, do it for them...
                                                if (len === 5) {
                                                    elm.value += '-';
                                                }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </div>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::PREV_DATE)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter mb-2 col-4 {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerPrev"
                                        name="{{ $r->field_name }}" value="{{ old($r->field_name) }}"
                                        autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
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
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::NEXT_DATE)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter mb-2 col-4 {{ $r->field_name }}">
                                    <input type="text" class="form-control datepickerNext"
                                        name="{{ $r->field_name }}" value="{{ old($r->field_name) }}"
                                        autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
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
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::NUMBER)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    <input type="number" class="form-control" id="{{ $PApiKeyId }}"
                                        name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::DECIMAL)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    <input type="text" class="form-control" id="{{ $PApiKeyId }}"
                                        name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>

                            {{-- file --}}
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
                                <div class="vcenter font-weight-bold col-2 mb-2 {{ $r->field_name }}">
                                    {{ $r->label_name }}<span class="required">
                                        @if ($r->is_required == 1)
                                            {{ '*' }}
                                        @else
                                        @endif
                                    </span></div>
                                <div class="vcenter col-4 mb-2 {{ $r->field_name }}">
                                    <input type="file" class="form-control" id="{{ $PApiKeyId }}"
                                        name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}" value="{{ old($r->field_name) }}" @if($r->is_readonly == 1) readonly @endif>
                                    @if ($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @ENDIF
                                </div>


                            @else
                            @endif

                            @if ($i == 2)
                                <?php $i = 0; ?>
                            @endif
                            <?php $i++;
                            $count--; ?>
                        @endforeach


                        {{-- For BpId if issue_id 1192 --}}
                        @if ($issueId == 1192)
                            @if(($single['fieldset_id'] ?? null) === 'BPID_first_applicant')
                                <input type="hidden" name="nominee_count" id="nomineeCount" value="0">
                                <input type="hidden" name="applicant_count" id="applicantCount" value="0">
                            @endif
                        @endif
                        {{-- For BpId if issue_id 1192 --}}


                    </div>
                </fieldset>
            @endif

            <input type="hidden" name="conditional_value" id="conditionalHidden"
                value="{{ old('conditional_value') }}">
        @endforeach
    </table>
</td>


{{-- BP ID Form --}}
@if ($issueId == 1192)
<script>



    // by default hidden second nominee
    $(document).ready(function () {
        $("input[type='checkbox'][name='bp_type']").each(function () {
            let value = $(this).val();
            // generate disabled checkbox + hidden input
            let replacedHtml = `
                <input type="checkbox" value="${value}" disabled checked>
                <input type="hidden" name="bp_type" value="${value}">
            `;

            $(this).replaceWith(replacedHtml);
        });
    });
</script>
@endif



<script>

    

    $('#pullData').on('click', function(e) {
        e.preventDefault();

        let account_number = $("#account_number").val();
	//console.log(account_number)

        $.ajax({
            url: "{{ url('bpid/calling-account-api') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                account_number: account_number
            },
            beforeSend: function() {
                overlay('show');
            },
	
            success: function(data) {
                overlay('hide');

                // $('#applicantType').val('First');
                if (data.data.length != 0) {
                    $.each(data.data, function(Key, Value) {
                        $("#" + Key).val(Value);
                    });

                    // Show/Hide BPID applicants based on applicantCount
                    showHideBpidApplicants(data.data.applicantCount);

                    // Show/Hide BPID nominees based on nomineeCount
                    showHideBpidNominees(data.data.nomineeCount);

                }
                else{
                    alert("Sorry! We couldn't find any data for this account number.");
                }


            },
            error: function(xhr, status, error) {
		console.log(xhr, status, error);
                overlay('hide');
		
		let errorMessage = "Account API (1192) Error: ";
		
		if(xhr.responseJSON && xhr.responseJSON.message){
		    errorMessage = xhr.responseJSON.message;
		}
		
                alert(errorMessage);
            },
        });
    });


    // Function to show/hide BPID applicants based on count
    function showHideBpidApplicants(applicantCount) {
        // Hide all BPID applicant sections first
        $('#BPID_fourth_applicant, #BPID_third_applicant, #BPID_second_applicant').hide();

        // Show based on count
        if (applicantCount >= 2) {
            $('#BPID_second_applicant').show();
        }
        if (applicantCount >= 3) {
            $('#BPID_third_applicant').show();
        }
        if (applicantCount >= 4) {
            $('#BPID_fourth_applicant').show();
        }

        // Also set applicant count dropdown if exists
        if ($('#applicantCount').length) {
            $('#applicantCount').val(applicantCount);
        }
    }

    // Function to show/hide BPID nominees based on count
    function showHideBpidNominees(nomineeCount) {
        // Hide all BPID nominee sections first
        $('#BPID_second_nominee, #BPID_third_nominee, #BPID_fourth_nominee').hide();

        // Show based on count
        if (nomineeCount >= 2) {
            $('#BPID_second_nominee').show();
        }
        if (nomineeCount >= 3) {
            $('#BPID_third_nominee').show();
        }
        if (nomineeCount >= 4) {
            $('#BPID_fourth_nominee').show();
        }

        // Also set nominee count dropdown if exists
        if ($('#nomineeCount').length) {
            $('#nomineeCount').val(nomineeCount);
        }
    }


</script>


