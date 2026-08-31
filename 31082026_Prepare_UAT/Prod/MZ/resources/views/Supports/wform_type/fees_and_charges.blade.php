<tr>
    <th class="vcenter">Charge Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('charge_type', [null=>'Please Select'] +  $allWformMasterData['charge_type'], (!empty($dataForView['charge_type'])) ? $dataForView['charge_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('charge_type')) <div class="error-message">{{ $errors->first('charge_type') }}</div> @ENDIF
    </td>
    <th class="vcenter">Reason<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('fees_and_charge_reason',(!empty($dataForView["fees_and_charge_reason"])) ? $dataForView["fees_and_charge_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Reason*'
          ]);
        !!}
        @IF($errors->has('fees_and_charge_reason')) <div class="error-message">{{ $errors->first('fees_and_charge_reason') }}</div> @ENDIF
    </td>
</tr>
