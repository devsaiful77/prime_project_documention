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
$i=1;
$count = 0;
?>
<table width="100%">
<td colspan="4" width="100%">
    <table class="table table-condensed" style="background-color:#BBE1E2">
        <colgroup>
            <col width="15%"></col>
            <col width="35%"></col>
            <col width="15%"></col>
            <col width="35%"></col>
        </colgroup>

        @php
            if(!empty($check_lists)){
                $count = count($check_lists);
            }else{
            $count = 0;
        }
            //echo $count.'----------';
            //prd($check_lists);
        @endphp

        @if($count==0)
            <tr>
            <th class="vcenter" colspan="4">There is no Check List Data. Please check in Issue Check List Config or consult with the admin.</th>
        </tr>
        @endif

        @if($count>0)
            <tr>
                <th class="vcenter" style="font-color: #68AFF6">Check List<th>
                <td class="vcenter"></td>
                <th class="vcenter"><th>
                <td class="vcenter"></td>
            </tr>
        @endif

        @if(!empty($check_lists))
        @foreach($check_lists as $key=>$r)
            @if($i == 1)
                <tr>
                    @endif

                    <th class="vcenter">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span></th>
                    <td class="vcenter">
                        @php $r_value=''; @endphp

                        @foreach(json_decode($exits_data->check_list) as $e_field)

                            @foreach($e_field as $key=>$e)

                                @if($key==$r->label_name)
                                    @php $r_value=$e;@endphp
                                @endif
                            @endforeach
                        @endforeach

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
                                    if($value == old($r->field_name,$r_value)){
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
        @endif

    </table>
</td>
</table>
