<tr>
    <th class="vcenter">EzyPay Closure Reason</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["closure_reason"])) ? $dataForView['w_form_type']["closure_reason"] : '' }} </td>
    <th class="vcenter">Tenor </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['tenor'])) ? $dataForView['w_form_type']['tenor'] : "" }} </td>
    <th class="vcenter">EzyPay Amount</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["ezypay_amount"])) ? $dataForView['w_form_type']["ezypay_amount"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Number of EMI Paid </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["emi_paid"])) ? $dataForView['w_form_type']["emi_paid"] : '' }} </td>
    <th class="vcenter">Principal Outstanding</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["principle_outstanding"])) ? $dataForView['w_form_type']["principle_outstanding"] : '' }} </td>
    <th class="vcenter" colspan="2"></th>
</tr>
