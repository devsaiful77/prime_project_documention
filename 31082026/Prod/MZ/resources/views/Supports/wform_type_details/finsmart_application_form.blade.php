<tr>
    <th class="vcenter">E-mail address updated</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['email_updated'])) ? $dataForView['w_form_type']['email_updated'] : "" }} </td>
    <th class="vcenter">System captured Email address</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["captured_email"])) ? $dataForView['w_form_type']["captured_email"] : '' }} </td>
    <th class="vcenter">System captured mobile number</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["captured_mobile"])) ? $dataForView['w_form_type']["captured_mobile"] : '' }} </td>
</tr>
