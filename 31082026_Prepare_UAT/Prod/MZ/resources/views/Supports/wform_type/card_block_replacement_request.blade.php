<tr>
    <th class="vcenter">Replacement Reason<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('replacement_reason', [null=>'Please Select'] +  $allWformMasterData['replacement_reason'], (!empty($dataForView['replacement_reason'])) ? $dataForView['replacement_reason'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('replacement_reason')) <div class="error-message">{{ $errors->first('replacement_reason') }}</div> @ENDIF
    </td>
    <th class="vcenter"> Replacement Required<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('replacement_required', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['replacement_required'])) ? $dataForView['replacement_required'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('replacement_required')) <div class="error-message">{{ $errors->first('replacement_required') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Charge<span class="required">*</span></th>
    <td class="vcenter"> 
        {{ Form::select('charge', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['charge'])) ? $dataForView['charge'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('charge')) <div class="error-message">{{ $errors->first('charge') }}</div> @ENDIF
    </td>
    <th class="vcenter">Card Block<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('card_block', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['card_block'])) ? $dataForView['card_block'] : "", ['class'=>'form-control']) }} 
        @IF($errors->has('card_block')) <div class="error-message">{{ $errors->first('card_block') }}</div> @ENDIF
    </td>
</tr>
