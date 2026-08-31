<tr>
    <th class="vcenter">Loan Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('loan_type', [null=>'Please Select'] +  $allWformMasterData['loan_type'], (!empty($dataForView['loan_type'])) ? $dataForView['loan_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('loan_type')) <div class="error-message">{{ $errors->first('loan_type') }}</div> @ENDIF
    </td>
    <th class="vcenter">Closure Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('closure_type', [null=>'Please Select'] +  $allWformMasterData['closure_type'], (!empty($dataForView['closure_type'])) ? $dataForView['closure_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('closure_type')) <div class="error-message">{{ $errors->first('closure_type') }}</div> @ENDIF
    </td>

</tr>
<tr>
    <th class="vcenter">Loan Closure Reason<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('loan_closure_reason',(!empty($dataForView["loan_closure_reason"])) ? $dataForView["loan_closure_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Loan Closure Reason*'
          ]);
        !!}
        @IF($errors->has('loan_closure_reason')) <div class="error-message">{{ $errors->first('loan_closure_reason') }}</div> @ENDIF
    </td>
    <th class="vcenter">LLID<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('llid',(!empty($dataForView["llid"])) ? $dataForView["llid"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'LLID*'
          ]);
        !!}
        @IF($errors->has('llid')) <div class="error-message">{{ $errors->first('llid') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Loan outstanding Amount</th>
    <td class="vcenter">
        {!!
          Form::text('loan_outstanding_amount',(!empty($dataForView["loan_outstanding_amount"])) ? $dataForView["loan_outstanding_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Loan outstanding Amount*'
          ]);
        !!}
    </td>
    <th class="vcenter">EMI Amount</th>
    <td class="vcenter">
        {!!
          Form::text('emi_amount',(!empty($dataForView["emi_amount"])) ? $dataForView["emi_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'EMI Amount *'
          ]);
        !!}
    </td>
</tr>
<tr>
    <th class="vcenter">Current contact number</th>
    <td class="vcenter">
        {!!
          Form::text('current_contact_number',(!empty($dataForView["current_contact_number"])) ? $dataForView["current_contact_number"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Current contact number'
          ]);
        !!}
    </td>
    <td class="vcenter" colspan="2"></td>
</tr>
