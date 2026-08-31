<tr>
    <th class="vcenter">Txn Amount<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('txn_amount',(!empty($dataForView["txn_amount"])) ? $dataForView["txn_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Txn Amount*'
          ]);
        !!}
        @IF($errors->has('txn_amount')) <div class="error-message">{{ $errors->first('txn_amount') }}</div> @ENDIF
    </td>
    <th class="vcenter">Txn Date<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('txn_date',(!empty($dataForView["txn_date"])) ? $dataForView["txn_date"] : '' ,[
            'class' => 'form-control datePicker',
            'readonly'=>'true',
            'autocomplete'=>'off',
            'placeholder'=>'Txn Date*'
          ]);
        !!}
        @IF($errors->has('txn_date')) <div class="error-message">{{ $errors->first('txn_date') }}</div> @ENDIF
    </td>

 
</tr>
<tr>
    <th class="vcenter">Tenor<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('tenor', [null=>'Please Select'] +  $allWformMasterData['tenor'], (!empty($dataForView['tenor'])) ? $dataForView['tenor'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('tenor')) <div class="error-message">{{ $errors->first('tenor') }}</div> @ENDIF
    </td>
    <th class="vcenter">Rate<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('ezypay_amount',(!empty($dataForView["ezypay_amount"])) ? $dataForView["ezypay_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Rate*'
          ]);
        !!}
        @IF($errors->has('ezypay_amount')) <div class="error-message">{{ $errors->first('ezypay_amount') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Precessing Fee<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('txn_type', [null=>'Please Select'] +  $allWformMasterData['instant_pay_process_fee'], (!empty($dataForView['txn_type'])) ? $dataForView['txn_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('txn_type')) <div class="error-message">{{ $errors->first('txn_type') }}</div> @ENDIF
    </td>
    <td colspan="2"></td>    
</tr>
