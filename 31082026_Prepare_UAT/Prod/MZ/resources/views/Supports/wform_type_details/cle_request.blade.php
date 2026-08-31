<tr>
    <th class="vcenter">Request Type </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['request_type'])) ? $dataForView['w_form_type']['request_type'] : "" }} </td>
    <th class="vcenter">Existing Limit</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["existing_limit"])) ? $dataForView['w_form_type']["existing_limit"] : '' }} </td>
    <th class="vcenter">Proposed Limit</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["proposed_limit"])) ? $dataForView['w_form_type']["proposed_limit"] : ''}} </td>
</tr>

