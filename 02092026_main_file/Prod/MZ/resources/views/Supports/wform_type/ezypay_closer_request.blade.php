<tr>
    <th class="vcenter">EzyPay Closure Reason<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('closure_reason',(!empty($dataForView["closure_reason"])) ? $dataForView["closure_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'EzyPay Closure Reason*'
          ]);
        !!}
        @IF($errors->has('closure_reason')) <div class="error-message">{{ $errors->first('closure_reason') }}</div> @ENDIF
    </td>
    <th class="vcenter">Tenor<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('tenor', [null=>'Please Select'] +  $allWformMasterData['tenor'], (!empty($dataForView['tenor'])) ? $dataForView['tenor'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('tenor')) <div class="error-message">{{ $errors->first('tenor') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">EzyPay Amount<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('ezypay_amount',(!empty($dataForView["ezypay_amount"])) ? $dataForView["ezypay_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'EzyPay Amount*'
          ]);
        !!}
        @IF($errors->has('ezypay_amount')) <div class="error-message">{{ $errors->first('ezypay_amount') }}</div> @ENDIF
    </td>
    <th class="vcenter">Number of EMI Paid<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('emi_paid',(!empty($dataForView["emi_paid"])) ? $dataForView["emi_paid"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Number of EMI Paid*'
          ]);
        !!}
        @IF($errors->has('emi_paid')) <div class="error-message">{{ $errors->first('emi_paid') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Principal Outstanding<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('principle_outstanding',(!empty($dataForView["principle_outstanding"])) ? $dataForView["principle_outstanding"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Principal Outstanding*'
          ]);
        !!}
        @IF($errors->has('principle_outstanding')) <div class="error-message">{{ $errors->first('principle_outstanding') }}</div> @ENDIF
    </td>
    <th class="vcenter" colspan="2"></th>
</tr>
