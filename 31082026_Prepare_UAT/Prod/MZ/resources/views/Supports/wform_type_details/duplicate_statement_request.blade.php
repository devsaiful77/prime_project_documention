<tr>
    <th class="vcenter">Date From </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["date_from"])) ? $dataForView['w_form_type']["date_from"] : '' }} </td>
    <th class="vcenter">Date To</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["date_to"])) ? $dataForView['w_form_type']["date_to"] : '' }} </td>
    <th class="vcenter">Charge</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["duplicate_charge"])) ? $dataForView['w_form_type']["duplicate_charge"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Delivery Option</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['delivery_option'])) ? $dataForView['w_form_type']['delivery_option'] : "" }} </td>
    <th class="vcenter">E-mail Address</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["email_address"])) ? $dataForView['w_form_type']["email_address"] : '' }} </td>
    <td class="vcenter" colspan="2">&nbsp;</td>
</tr>
