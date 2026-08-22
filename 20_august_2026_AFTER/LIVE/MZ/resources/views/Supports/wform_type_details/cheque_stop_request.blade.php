<tr>
    <th class="vcenter">Reason for stop </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["reason_for_stop"])) ? $dataForView['w_form_type']["reason_for_stop"] : '' }}
    </td>
    <th class="vcenter">Issuance Bank </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['issuance_bank'])) ? $dataForView['w_form_type']['issuance_bank'] : "" }} </td>
    <th class="vcenter">Cheque Amount</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["cheque_amount"])) ? $dataForView['w_form_type']["cheque_amount"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Cheque Date </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["cheque_date"])) ? $dataForView['w_form_type']["cheque_date"] : '' }} </td>
    <th class="vcenter">Cheque Serial Number</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["cheque_serial_number"])) ? $dataForView['w_form_type']["cheque_serial_number"] : '' }} </td>
    <td colspan="2"> </td>
</tr>
