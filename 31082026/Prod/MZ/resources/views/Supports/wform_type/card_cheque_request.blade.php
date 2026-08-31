<tr>
    <th class="vcenter">Existing User<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('existing_user', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['existing_user'])) ? $dataForView['existing_user'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('existing_user')) <div class="error-message">{{ $errors->first('existing_user') }}</div> @ENDIF
    </td>
    <th class="vcenter">Issuance Bank<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('issuance_bank', [null=>'Please Select'] +  $allWformMasterData['issuance_bank'], (!empty($dataForView['issuance_bank'])) ? $dataForView['issuance_bank'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('issuance_bank')) <div class="error-message">{{ $errors->first('issuance_bank') }}</div> @ENDIF
    </td>
</tr>