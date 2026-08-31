<tr>
    <th class="vcenter">Billing Date From</th>
    <td class="vcenter"> 
    	{!!
          Form::text('billing_date_from',(!empty($dataForView["billing_date_from"])) ? $dataForView["billing_date_from"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Billing Date From'
          ]);
        !!}
    </td>
    <th class="vcenter">Billing Date To </th>
    <td class="vcenter">
        {!!
          Form::text('billing_date_to',(!empty($dataForView["billing_date_to"])) ? $dataForView["billing_date_to"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Billing Date To'
          ]);
        !!}
    </td>
</tr>
