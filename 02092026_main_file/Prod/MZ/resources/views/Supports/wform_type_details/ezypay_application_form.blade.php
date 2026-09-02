<tr>
    <th class="vcenter">Txn Amount</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["txn_amount"])) ? $dataForView['w_form_type']["txn_amount"] : '' }} </td>
    <th class="vcenter">Txn Type </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['txn_type'])) ? $dataForView['w_form_type']['txn_type'] : "" }} </td>
    <th class="vcenter">Txn Date</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["txn_date"])) ? $dataForView['w_form_type']["txn_date"] : '' }} </td>
</tr>
<tr>
    <th class="vcenter">Tenor </th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']['tenor'])) ? $dataForView['w_form_type']['tenor'] : "" }} </td>
    <th class="vcenter">Transaction Details</th>
    <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["transaction_details"])) ? $dataForView['w_form_type']["transaction_details"] : '' }} </td>
    @if($dataForView['master_id'] == 26 || $dataForView['depricate_wform_type'] == "EZY Pay Application Form" )
        <th class="vcenter">Rate</th>
        <td class="vcenter"> {{ (!empty($dataForView['w_form_type']["ezypay_amount"])) ? $dataForView['w_form_type']["ezypay_amount"] : '' }} </td>
    @else
    <th class="vcenter" colspan="2"></th>
    @endif

</tr>
