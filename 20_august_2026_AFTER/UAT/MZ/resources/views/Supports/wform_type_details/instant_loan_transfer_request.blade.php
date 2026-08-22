<tr>
    <th class="vcenter">Instant Loan Amount </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["loan_amount"])) ? $dataForView['w_form_type']["loan_amount"] : '' }} </td>
    <th class="vcenter">Tenor </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['tenor'])) ? $dataForView['w_form_type']['tenor'] : "" }} </td>
    <th class="vcenter">Beneficiary Name </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["beneficiary_name"])) ? $dataForView['w_form_type']["beneficiary_name"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Beneficiary Bank </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["bnfcr_bank"])) ? $dataForView['w_form_type']["bnfcr_bank"] : '' }} </td>
    <th class="vcenter">Beneficiary Account Number </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["beneficiary_account_number"])) ? $dataForView['w_form_type']["beneficiary_account_number"] : '' }} </td>
    <th class="vcenter">Branch Name</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["branch_name"])) ? $dataForView['w_form_type']["branch_name"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Routing Number </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["routing_no"])) ? $dataForView['w_form_type']["routing_no"] : '' }} </td>
    <th class="vcenter">Transfer Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['transfer_type'])) ? $dataForView['w_form_type']['transfer_type'] : "" }} </td>
    <th class="vcenter">Card Expiry Date </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["card_expiry_date"])) ? $dataForView['w_form_type']["card_expiry_date"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Rate </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["amount"])) ? $dataForView['w_form_type']["amount"] : '' }} </td>
    <td colspan="4"></td>
</tr>
