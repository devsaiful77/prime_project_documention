<tr>
    <th class="vcenter">Auto Debit Type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['auto_debit_type'])) ? $dataForView['w_form_type']['auto_debit_type'] : "" }} </td>
    <th class="vcenter">Auto Debit Partner Name</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["debit_partner_name"])) ? $dataForView['w_form_type']["debit_partner_name"] : "" }} </td>
    <th class="vcenter">Account Number</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["acount_number2"])) ? $dataForView['w_form_type']["acount_number2"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Billing Date</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["billing_date"])) ? $dataForView['w_form_type']["billing_date"] : '' }} </td>
    <td colspan="4"></td>
</tr>