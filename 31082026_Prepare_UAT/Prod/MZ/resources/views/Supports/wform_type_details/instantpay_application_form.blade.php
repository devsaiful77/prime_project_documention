<tr>
    <th class="vcenter">Txn Amount</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["txn_amount"])) ? $dataForView['w_form_type']["txn_amount"] : '' }} </td>
    
    <th class="vcenter">Txn Date</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["txn_date"])) ? $dataForView['w_form_type']["txn_date"] : '' }} </td>
    <th class="vcenter">Tenor </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['tenor'])) ? $dataForView['w_form_type']['tenor'] : "" }} </td>
</tr>
<tr>
    <th class="vcenter">Rate</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["ezypay_amount"])) ? $dataForView['w_form_type']["ezypay_amount"] : '' }} </td>
    <th class="vcenter">Processing Fee </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['txn_type'])) ? $dataForView['w_form_type']['txn_type'] : "" }} </td>
    <td colspan="2"></td>
</tr>
