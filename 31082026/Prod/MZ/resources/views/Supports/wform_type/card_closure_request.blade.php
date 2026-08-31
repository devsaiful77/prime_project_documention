<tr>
    <th class="vcenter">Card Block<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('card_block', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['card_block'])) ? $dataForView['card_block'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('card_block')) <div class="error-message">{{ $errors->first('card_block') }}</div> @ENDIF
    </td>
    <th class="vcenter">Closure Reason<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('closure_reason',(!empty($dataForView["closure_reason"])) ? $dataForView["closure_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Closure Reason'
          ]);
        !!}
        @IF($errors->has('closure_reason')) <div class="error-message">{{ $errors->first('closure_reason') }}</div> @ENDIF
    </td>
</tr>

<tr>
    <th class="vcenter">Debit Balance</th>
    <td class="vcenter">
        {!!
          Form::text('debit_balance',(!empty($dataForView["debit_balance"])) ? $dataForView["debit_balance"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Debit Balance'
          ]);
        !!}
    </td>
    <th class="vcenter">Credit Balance</th>
    <td class="vcenter">
        {!!
          Form::text('credit_balance',(!empty($dataForView["credit_balance"])) ? $dataForView["credit_balance"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Credit Balance'
          ]);
        !!}
    </td>
</tr>

