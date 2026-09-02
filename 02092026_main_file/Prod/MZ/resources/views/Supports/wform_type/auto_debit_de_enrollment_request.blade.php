<tr>
    <th class="vcenter">Auto Debit Type<span class="required">*</span></th>
    <td class="vcenter"> 
    	{{ Form::select('auto_debit_type', [null=>'Please Select'] +  $allWformMasterData['auto_debit_type'], (!empty($dataForView['auto_debit_type'])) ? $dataForView['auto_debit_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('auto_debit_type')) <div class="error-message">{{ $errors->first('auto_debit_type') }}</div> @ENDIF
    </td>
    <th class="vcenter"> Auto Debit Partner Name<span class="required">*</span></th>
    <td class="vcenter">
    	{!!
          Form::text('debit_partner_name',(!empty($dataForView["debit_partner_name"])) ? $dataForView["debit_partner_name"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Auto Debit Partner Name'
          ]);
        !!}
        @IF($errors->has('debit_partner_name')) <div class="error-message">{{ $errors->first('debit_partner_name') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Account Number<span class="required">*</span></th>
    <td class="vcenter">
    	{!!
          Form::text('acount_number2',(!empty($dataForView["acount_number2"])) ? $dataForView["acount_number2"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Account Number'
          ]);
        !!}
        @IF($errors->has('acount_number2')) <div class="error-message">{{ $errors->first('acount_number2') }}</div> @ENDIF
    </td>
    <th class="vcenter">Billing Date<span class="required">*</span></th>
    <td class="vcenter">
    	{!!
          Form::text('billing_date',(!empty($dataForView["billing_date"])) ? $dataForView["billing_date"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Billing Date'
          ]);
        !!}
        @IF($errors->has('billing_date')) <div class="error-message">{{ $errors->first('billing_date') }}</div> @ENDIF
    </td>
</tr>