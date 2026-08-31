<tr>
    <th class="vcenter">Txns number in year<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('txns_number_in_year',(!empty($dataForView["txns_number_in_year"])) ? $dataForView["txns_number_in_year"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Txns number in year*'
          ]);
        !!}
        @IF($errors->has('txns_number_in_year')) <div class="error-message">{{ $errors->first('txns_number_in_year') }}</div> @ENDIF
    </td>
    <th class="vcenter">Charge Amount<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('charge_amount',(!empty($dataForView["charge_amount"])) ? $dataForView["charge_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Charge Amount*'
          ]);
        !!}
        @IF($errors->has('charge_amount')) <div class="error-message">{{ $errors->first('charge_amount') }}</div> @ENDIF
    </td>
</tr>

<tr>
    <th class="vcenter">Renewal Fee Date<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('renewal_fee_date',(!empty($dataForView["renewal_fee_date"])) ? $dataForView["renewal_fee_date"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Renewal Fee Date*'
          ]);
        !!}
        @IF($errors->has('renewal_fee_date')) <div class="error-message">{{ $errors->first('renewal_fee_date') }}</div> @ENDIF
    </td>
    <td  colspan="2"> </td>
</tr>