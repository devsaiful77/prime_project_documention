<tr>
    <th class="vcenter">Card Pin Replacement<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('card_pin_replacement', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['card_pin_replacement'])) ? $dataForView['card_pin_replacement'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('card_pin_replacement')) <div class="error-message">{{ $errors->first('card_pin_replacement') }}</div> @ENDIF
    </td>
    <th class="vcenter">Replacement Reason<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('replacement_reason',(!empty($dataForView["replacement_reason"])) ? $dataForView["replacement_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Replacement Reason'
          ]);
        !!}
        @IF($errors->has('replacement_reason')) <div class="error-message">{{ $errors->first('replacement_reason') }}</div> @ENDIF
    </td>
</tr>

