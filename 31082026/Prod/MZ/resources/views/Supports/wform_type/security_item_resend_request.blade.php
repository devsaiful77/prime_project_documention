<tr>
    <th class="vcenter">Security Item Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('security_item_type', [null=>'Please Select'] +  $allWformMasterData['security_item_type'], (!empty($dataForView['security_item_type'])) ? $dataForView['security_item_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('security_item_type')) <div class="error-message">{{ $errors->first('security_item_type') }}</div> @ENDIF
    </td>
    <th class="vcenter">Resend To <span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('resend_to', [null=>'Please Select'] +  $allWformMasterData['resendto'], (!empty($dataForView['resend_to'])) ? $dataForView['resend_to'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('resend_to')) <div class="error-message">{{ $errors->first('resend_to') }}</div> @ENDIF
    </td>
</tr>
