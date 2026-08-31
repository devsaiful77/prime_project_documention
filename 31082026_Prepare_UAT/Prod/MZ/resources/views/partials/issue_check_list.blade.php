<?php
/**
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com>.
 * User: Tanay
 * Date: 7/15/2020
 * Time: 3:05 AM
 */
?>
<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 5/27/2020.
 */
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i=1;
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
            $count = count($check_lists);
            //echo $count;
        @endphp

		@if($count>0)
			<tr>
			 <th class="vcenter" style="font-color: #68AFF6">Check List</th>
             <td class="vcenter"></td>
			 <th class="vcenter"></th>
             <td class="vcenter"></td>
          </tr>
		@endif

		<?php //echo '<pre>'; print_r($check_lists);?>

        @foreach($check_lists as $key=>$r)
            @if($i == 1)
                <tr>
			@endif

                        <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                        <td class="vcenter">
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
