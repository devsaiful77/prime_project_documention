<tr>
    <th class="vcenter">Loan Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['loan_type'])) ? $dataForView['w_form_type']['loan_type'] : "" }} </td>
    <th class="vcenter">Closure Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['closure_type'])) ? $dataForView['w_form_type']['closure_type'] : "" }} </td>
    <th class="vcenter">Loan Closure Reason </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["loan_closure_reason"])) ? $dataForView['w_form_type']["loan_closure_reason"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">LLID </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["llid"])) ? $dataForView['w_form_type']["llid"] : '' }} </td>
    <th class="vcenter">Loan outstanding Amount</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["loan_outstanding_amount"])) ? $dataForView['w_form_type']["loan_outstanding_amount"] : '' }} </td>
    <th class="vcenter">EMI Amount</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["emi_amount"])) ? $dataForView['w_form_type']["emi_amount"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Current contact number</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["current_contact_number"])) ? $dataForView['w_form_type']["current_contact_number"] : '' }} </td>
    <td class="vcenter" colspan="4"></td>
</tr>
