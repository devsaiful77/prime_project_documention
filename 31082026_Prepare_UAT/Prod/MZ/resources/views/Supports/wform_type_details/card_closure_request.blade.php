<tr>
    <th class="vcenter">Card Block</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['card_block'])) ? $dataForView['w_form_type']['card_block'] : "" }} </td>
    <th class="vcenter">Closure Reason</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["closure_reason"])) ? $dataForView['w_form_type']["closure_reason"] : '' }} </td>
    <th class="vcenter">Debit Balance</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["debit_balance"])) ? $dataForView['w_form_type']["debit_balance"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Credit Balance</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["credit_balance"])) ? $dataForView['w_form_type']["credit_balance"] : '' }} </td>
    <td colspan="4"></td>
</tr>

