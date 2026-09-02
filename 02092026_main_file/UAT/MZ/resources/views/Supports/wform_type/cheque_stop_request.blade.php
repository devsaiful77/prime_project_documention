<tr>
    <th class="vcenter">Reason for stop<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('reason_for_stop',(!empty($dataForView["reason_for_stop"])) ? $dataForView["reason_for_stop"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Reason for stop*'
          ]);
        !!}
        @IF($errors->has('reason_for_stop')) <div class="error-message">{{ $errors->first('reason_for_stop') }}</div> @ENDIF
    </td>
    <th class="vcenter">Issuance Bank<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('issuance_bank', [null=>'Please Select'] +  $allWformMasterData['issuance_bank'], (!empty($dataForView['issuance_bank'])) ? $dataForView['issuance_bank'] : "", ['class'=>'form-control']) }} 
        @IF($errors->has('issuance_bank')) <div class="error-message">{{ $errors->first('issuance_bank') }}</div> @ENDIF
    </td>
</tr>

<tr>
    <th class="vcenter">Cheque Amount</th>
    <td class="vcenter">
        {!!
          Form::text('cheque_amount',(!empty($dataForView["cheque_amount"])) ? $dataForView["cheque_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Cheque Amount*'
          ]);
        !!}
    </td>
    <th class="vcenter">Cheque Date<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('cheque_date',(!empty($dataForView["cheque_date"])) ? $dataForView["cheque_date"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Cheque Date*'
          ]);
        !!}
        @IF($errors->has('cheque_date')) <div class="error-message">{{ $errors->first('cheque_date') }}</div> @ENDIF
    </td>
</tr>

<tr>
    <th class="vcenter">Cheque Serial Number<span class="required">*</span></th>
    <td class="vcenter"> 
        {!!
          Form::text('cheque_serial_number',(!empty($dataForView["cheque_serial_number"])) ? $dataForView["cheque_serial_number"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Cheque Serial Number*'
          ]);
        !!}
        @IF($errors->has('cheque_serial_number')) <div class="error-message">{{ $errors->first('cheque_serial_number') }}</div> @ENDIF
    </td>
    <td colspan="2"> </td>
</tr>
