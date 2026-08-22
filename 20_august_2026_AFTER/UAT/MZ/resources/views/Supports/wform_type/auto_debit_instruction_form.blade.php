<tr>
    <th class="vcenter">Auto Debit Type<span class="required">*</span></th>
    <td class="vcenter"> 
    	{{ Form::select('auto_debit_type', [null=>'Please Select'] +  $allWformMasterData['auto_debit_type'], (!empty($dataForView['auto_debit_type'])) ? $dataForView['auto_debit_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('auto_debit_type')) <div class="error-message">{{ $errors->first('auto_debit_type') }}</div> @ENDIF
    </td>
    <th class="vcenter"> GP/Robi/Banglalink/Airtel/Citycell no </th>
    <td class="vcenter">
    	{!!
          Form::text('cell_phone',(!empty($dataForView["cell_phone"])) ? $dataForView["cell_phone"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Cell No'
          ]);
        !!}
    </td>
</tr>
<tr>
    <th class="vcenter">Qubee / Banglalion Account no</th>
    <td class="vcenter">
        {!!
          Form::text('qubee_account_no',(!empty($dataForView["qubee_account_no"])) ? $dataForView["qubee_account_no"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Qubee / Banglalion Account no'
          ]);
        !!}
    </td>
    <th class="vcenter">Alico Policy Number</th>
    <td class="vcenter">
        {!!
          Form::text('alico_policy_number',(!empty($dataForView["alico_policy_number"])) ? $dataForView["alico_policy_number"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Alico Policy Number'
          ]);
        !!}
    </td>
</tr>
<tr>
    <th class="vcenter">Beneficiary Name<span class="required">*</span></th>
    <td class="vcenter">
    	{!!
          Form::text('beneficiary_name',(!empty($dataForView["beneficiary_name"])) ? $dataForView["beneficiary_name"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Beneficiary Name'
          ]);
        !!}
        @IF($errors->has('beneficiary_name')) <div class="error-message">{{ $errors->first('beneficiary_name') }}</div> @ENDIF
    </td>
    <th class="vcenter">Beneficiary Account Number<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('beneficiary_account_number',(!empty($dataForView["beneficiary_account_number"])) ? $dataForView["beneficiary_account_number"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Beneficiary Account Number'
          ]);
        !!}
        @IF($errors->has('beneficiary_account_number')) <div class="error-message">{{ $errors->first('beneficiary_account_number') }}</div> @ENDIF
    </td>
</tr>
<tr>
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
    <th class="vcenter">Package Name (ISP)</th>
    <td class="vcenter">
        {!!
          Form::text('package_name',(!empty($dataForView["package_name"])) ? $dataForView["package_name"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Package Name (ISP)'
          ]);
        !!}
    </td>
</tr>