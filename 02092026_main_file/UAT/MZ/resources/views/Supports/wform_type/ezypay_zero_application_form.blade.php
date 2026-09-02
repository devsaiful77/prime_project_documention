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
    <th class="vcenter">Txn Type<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('txn_type', [null=>'Please Select'] +  $allWformMasterData['ezy_pay_txn_type'], (!empty($dataForView['txn_type'])) ? $dataForView['txn_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('txn_type')) <div class="error-message">{{ $errors->first('txn_type') }}</div> @ENDIF
    </td>
</tr>
<tr>
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
    <th class="vcenter">Tenor<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('tenor', [null=>'Please Select'] +  $allWformMasterData['tenor'], (!empty($dataForView['tenor'])) ? $dataForView['tenor'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('tenor')) <div class="error-message">{{ $errors->first('tenor') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Transaction Details<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('transaction_details',(!empty($dataForView["transaction_details"])) ? $dataForView["transaction_details"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Transaction Details*'
          ]);
        !!}
        @IF($errors->has('transaction_details')) <div class="error-message">{{ $errors->first('transaction_details') }}</div> @ENDIF
    </td>
    <th class="vcenter" colspan="2"></th>
</tr>
