<tr>
    <th class="vcenter">Credit card type</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['credit_card_type'])) ? $dataForView['w_form_type']['credit_card_type'] : "" }} </td>
    <th class="vcenter">Points to be Redeem</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["points_to_be_redeem"])) ? $dataForView['w_form_type']["points_to_be_redeem"] : '' }} </td>
    <th class="vcenter">Charge Amount </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["charge_amount"])) ? $dataForView['w_form_type']["charge_amount"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Renewal Fee Date </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["renewal_fee_date"])) ? $dataForView['w_form_type']["renewal_fee_date"] : '' }} </td>
    <td colspan="4"></td>
</tr>
