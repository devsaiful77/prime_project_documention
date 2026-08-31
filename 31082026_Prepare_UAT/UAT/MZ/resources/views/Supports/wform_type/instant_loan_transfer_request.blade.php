<tr>
    <th class="vcenter">Instant Loan Amount<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('loan_amount',(!empty($dataForView["loan_amount"])) ? $dataForView["loan_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Instant Loan Amount*'
          ]);
        !!}
        @IF($errors->has('loan_amount')) <div class="error-message">{{ $errors->first('loan_amount') }}</div> @ENDIF
    </td>
    <th class="vcenter">Tenor<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('tenor', [null=>'Please Select'] +  $allWformMasterData['tenor'], (!empty($dataForView['tenor'])) ? $dataForView['tenor'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('tenor')) <div class="error-message">{{ $errors->first('tenor') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Beneficiary Name<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('beneficiary_name',(!empty($dataForView["beneficiary_name"])) ? $dataForView["beneficiary_name"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Beneficiary Name*'
          ]);
        !!}
        @IF($errors->has('beneficiary_name')) <div class="error-message">{{ $errors->first('beneficiary_name') }}</div> @ENDIF
    </td>
    <th class="vcenter">Beneficiary Bank<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('bnfcr_bank',(!empty($dataForView["bnfcr_bank"])) ? $dataForView["bnfcr_bank"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Beneficiary Bank *'
          ]);
        !!}
        @IF($errors->has('bnfcr_bank')) <div class="error-message">{{ $errors->first('bnfcr_bank') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Beneficiary Account Number<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('beneficiary_account_number',(!empty($dataForView["beneficiary_account_number"])) ? $dataForView["beneficiary_account_number"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Beneficiary Account Number*'
          ]);
        !!}
        @IF($errors->has('beneficiary_account_number')) <div class="error-message">{{ $errors->first('beneficiary_account_number') }}</div> @ENDIF
    </td>
    <th class="vcenter">Branch Name</th>
    <td class="vcenter">
        {!!
          Form::text('branch_name',(!empty($dataForView["branch_name"])) ? $dataForView["branch_name"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Branch Name'
          ]);
        !!}
    </td>
</tr>
<tr>
    <th class="vcenter">Routing Number<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('routing_no',(!empty($dataForView["routing_no"])) ? $dataForView["routing_no"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Routing Number*'
          ]);
        !!}
        @IF($errors->has('routing_no')) <div class="error-message">{{ $errors->first('routing_no') }}</div> @ENDIF
    </td>
    <th class="vcenter">Transfer Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('transfer_type', [null=>'Please Select'] +  $allWformMasterData['transfer_type'], (!empty($dataForView['transfer_type'])) ? $dataForView['transfer_type'] : "", 
            [
                'class'=>'form-control'
            ]) 
        }}
        @IF($errors->has('transfer_type')) <div class="error-message">{{ $errors->first('transfer_type') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Card Expiry Date<span class="required">*</span> </th>
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
    <th class="vcenter">Rate<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('amount',(!empty($dataForView["amount"])) ? $dataForView["amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Rate*'
          ]);
        !!}
        @IF($errors->has('amount')) <div class="error-message">{{ $errors->first('amount') }}</div> @ENDIF
    </td>
</tr>