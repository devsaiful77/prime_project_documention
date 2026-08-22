@IF($dataForView['depricate_wform_type'] == "Security Item Resend Request" )
<tr>
    <th class="vcenter">Security Item Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['security_item_type'])) ? $dataForView['w_form_type']['security_item_type'] : "" }} </td>
    <th class="vcenter">Resend To </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['resend_to'])) ? $dataForView['w_form_type']['resend_to'] : "" }} </td>
    <td colspan="2"></td>
</tr>
@ELSE
<tr>
    <th class="vcenter">Resend To </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['resend_to'])) ? $dataForView['w_form_type']['resend_to'] : "" }} </td>
    <td colspan="4"></td>
</tr>
@ENDIF