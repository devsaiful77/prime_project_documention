<?php
/**
 * User:Muajjam
 * Email:muajjam.imu@gmail@gmail.com
 * Created by Muajjam<tanayroy12@gmail.com> on 08/08/24.
 */
 $input_checkbox = '';
 $input_radio = '';
 $input_dropdown = '';
 $i=1;
?>

	<td colspan="4" width="100%">
    <table class="table table-condensed" style="background-color:#ffffff">
        <colgroup>
          <col width="15%"></col>
          <col width="35%"></col>
          <col width="15%"></col>
          <col width="35%"></col>
        </colgroup>
@php
$count = count($issue_fields);
//echo $count;
@endphp

@foreach($issue_fields as $key=>$r)
	@if($i == 1)
        <tr>
	@endif
    @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)

    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
    <td class="vcenter">
        <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
        @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
    </td>


    @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)

        <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
        <td class="vcenter">    @php
                $options = explode(",", $r->options);
                $input_dropdown = '<select name="' . $r->field_name . '" class="form-control">';
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
                    $input_dropdown .= '<option value="' . $option . '" '.$selected.' >' . $option_name . '</option>';
                }
                $input_dropdown .= '</select>';
            @endphp
            {!! $input_dropdown !!}
            @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
        </td>


        @elseif($r->field_type==\App\Enum\FieldTypeEnum::RADIO)

            <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
            <td>
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

                <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                <td>
                    <textarea  class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}">{{old($r->field_name)}}</textarea>
                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                </td>

        @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)

                <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                <td class="vcenter">
                    <input type="{{ $r->field_type }}" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                </td>


        @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)

                <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
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

                <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                <td class="vcenter">
                    {{-- <input type="{{ $r->field_type }}" class="form-control date" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" max="9999-12-31"> --}}
                    <input type="text" class="form-control datePicker js-date" name="{{ $r->field_name }}"   value="{{ old($r->field_name) }}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy">
                    @if($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @endif
                    <script>
                        $(document).ready(function () {
                            $('.datePicker').datepicker({
                                dateFormat: 'dd-mm-yy',
                                changeYear: true,
                                changeMonth: true
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


        @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)

                <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                <td class="vcenter">
                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                </td>

        @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)

                <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                <td class="vcenter">
                    <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}">
                    @IF($errors->has($r->field_name)) <div class="error-message">{{ $errors->first($r->field_name) }}</div> @ENDIF
                </td>


        @else
    @endif

	@if($i == 2)
		</tr>
	<?php $i=0;?>
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


    </table>
    </td>
