<tr>
    <th class="vcenter">Resend To <span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('resend_to', [null=>'Please Select'] +  $allWformMasterData['resendto'], (!empty($dataForView['resend_to'])) ? $dataForView['resend_to'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('resend_to')) <div class="error-message">{{ $errors->first('resend_to') }}</div> @ENDIF
    </td>
    <td colspan="2"></td>
</tr>
