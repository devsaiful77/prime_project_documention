<tr>
    <th class="vcenter">Charge Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['charge_type'])) ? $dataForView['w_form_type']['charge_type'] : "" }} </td>
    <th class="vcenter">Reason </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["fees_and_charge_reason"])) ? $dataForView['w_form_type']["fees_and_charge_reason"] : '' }} </td>
    <td colspan="2"></td>
</tr>
