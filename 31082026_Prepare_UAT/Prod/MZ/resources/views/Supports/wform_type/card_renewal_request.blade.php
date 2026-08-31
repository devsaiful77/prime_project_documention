<tr>
    <th class="vcenter">Renewal Type<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('renewal_type', [null=>'Please Select'] +  $allWformMasterData['renewal_request'], (!empty($dataForView['renewal_type'])) ? $dataForView['renewal_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('renewal_type')) <div class="error-message">{{ $errors->first('renewal_type') }}</div> @ENDIF
    </td>
    <th class="vcenter">Card Expiry Date<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('card_expiry_date',(!empty($dataForView["card_expiry_date"])) ? $dataForView["card_expiry_date"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Card Expiry Date*'
          ]);
        !!}
        @IF($errors->has('card_expiry_date')) <div class="error-message">{{ $errors->first('card_expiry_date') }}</div> @ENDIF
    </td>
</tr>

