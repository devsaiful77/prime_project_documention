<tr>
    <th class="vcenter">Reason</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["epay_enable_reason"])) ? $dataForView['w_form_type']["epay_enable_reason"] : '' }} </td>
    <th class="vcenter">Enable/Disable </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['epay_enable'])) ? $dataForView['w_form_type']['epay_enable'] : "" }} </td>
    <td colspan="2"></td>
</tr>