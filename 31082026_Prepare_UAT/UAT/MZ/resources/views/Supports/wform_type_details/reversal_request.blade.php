<tr>
    <th class="vcenter">Reversal Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['reversal_type'])) ? $dataForView['w_form_type']['reversal_type'] : "" }} </td>
    <th class="vcenter">Reversal Reason</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["reversal_reason"])) ? $dataForView['w_form_type']["reversal_reason"] : '' }} </td>
	<th class="vcenter">Note if other reason</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["note_if_other_reason"])) ? $dataForView['w_form_type']["note_if_other_reason"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Reversal Amount </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["reversal_amount"])) ? $dataForView['w_form_type']["reversal_amount"] : '' }} </td>
    <td colspan="4"></td>
</tr>
