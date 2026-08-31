<tr>
    <th class="vcenter">Reversal Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('reversal_type', [null=>'Please Select'] +  $allWformMasterData['reversal_request'], (!empty($dataForView['reversal_type'])) ? $dataForView['reversal_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('reversal_type')) <div class="error-message">{{ $errors->first('reversal_type') }}</div> @ENDIF
    </td>
    <th class="vcenter">Reversal Reason<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('reversal_reason',(!empty($dataForView["reversal_reason"])) ? $dataForView["reversal_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Reversal Reason**'
          ]);
        !!}
        @IF($errors->has('reversal_reason')) <div class="error-message">{{ $errors->first('reversal_reason') }}</div> @ENDIF
    </td>

</tr>
<tr>
    <th class="vcenter">Note if other reason</th>
    <td class="vcenter">
        {!!
          Form::text('note_if_other_reason',(!empty($dataForView["note_if_other_reason"])) ? $dataForView["note_if_other_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Note if other reason'
          ]);
        !!}
    </td>
    <th class="vcenter">Reversal Amount<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('reversal_amount',(!empty($dataForView["reversal_amount"])) ? $dataForView["reversal_amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Reversal Amount*'
          ]);
        !!}
        @IF($errors->has('reversal_amount')) <div class="error-message">{{ $errors->first('reversal_amount') }}</div> @ENDIF
    </td>
</tr>
