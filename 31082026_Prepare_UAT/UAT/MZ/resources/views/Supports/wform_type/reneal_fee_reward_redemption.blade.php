<tr>
    <th class="vcenter">Credit card type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('cr_card_type', [null=>'Please Select'] +  $allWformMasterData['credit_card_type'], (!empty($dataForView['credit_card_type'])) ? $dataForView['credit_card_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('cr_card_type')) <div class="error-message">{{ $errors->first('cr_card_type') }}</div> @ENDIF
    </td>
    <th class="vcenter">Points to be Redeem<span class="required">*</span></th>
    <td class="vcenter">
         {!!
          Form::text('points_to_be_redeem',(!empty($dataForView["points_to_be_redeem"])) ? $dataForView["points_to_be_redeem"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Points to be Redeem*'
          ]);
        !!}
        @IF($errors->has('points_to_be_redeem')) <div class="error-message">{{ $errors->first('points_to_be_redeem') }}</div> @ENDIF
    </td>

</tr>
<tr>
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
    <th class="vcenter">Renewal Fee Date<span class="required">*</span> </th>
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
</tr>
