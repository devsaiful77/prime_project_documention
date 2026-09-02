<tr>
    <th class="vcenter">TDR Type </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['tdr_type'])) ? $dataForView['w_form_type']['tdr_type'] : "" }} </td>
    <th class="vcenter"> Amount </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["amount"])) ? $dataForView['w_form_type']["amount"] : '' }} </td>
    <th class="vcenter">Tenor</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["tdr_tenor"])) ? $dataForView['w_form_type']["tdr_tenor"] : '' }} </td>
</tr>
<tr>
    
    <th class="vcenter">Mode of Payment </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['mode_of_payment'])) ? $dataForView['w_form_type']['mode_of_payment'] : "" }} </td>
    <td colspan="4"></td>
</tr>