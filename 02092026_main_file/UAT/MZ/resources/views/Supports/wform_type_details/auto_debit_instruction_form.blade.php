<tr>
    <th class="vcenter">Auto Debit Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['auto_debit_type'])) ? $dataForView['w_form_type']['auto_debit_type'] : "" }} </td>
    <th class="vcenter"> GP/Robi/Banglalink/Airtel/Citycell no </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["cell_phone"])) ? $dataForView['w_form_type']["cell_phone"] : '' }} </td>
    <th class="vcenter">Qubee / Banglalion Account no</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["qubee_account_no"])) ? $dataForView['w_form_type']["qubee_account_no"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Alico Policy Number</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["alico_policy_number"])) ? $dataForView['w_form_type']["alico_policy_number"] : '' }} </td>
    <th class="vcenter">Beneficiary Name</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["beneficiary_name"])) ? $dataForView['w_form_type']["beneficiary_name"] : '' }} </td>
    <th class="vcenter">Beneficiary Account Number</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["beneficiary_account_number"])) ? $dataForView['w_form_type']["beneficiary_account_number"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Billing Date</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["billing_date"])) ? $dataForView['w_form_type']["billing_date"] : '' }} </td>
    <th class="vcenter">Package Name (ISP)</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["package_name"])) ? $dataForView['w_form_type']["package_name"] : '' }} </td>
    <td colspan="2"></td>
</tr>