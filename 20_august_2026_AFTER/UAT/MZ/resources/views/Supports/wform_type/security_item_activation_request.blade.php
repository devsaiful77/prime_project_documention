<tr>
    <th class="vcenter">Security Item Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('security_item_type', [null=>'Please Select'] +  $allWformMasterData['security_item_type'], (!empty($dataForView['security_item_type'])) ? $dataForView['security_item_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('security_item_type')) <div class="error-message">{{ $errors->first('security_item_type') }}</div> @ENDIF
    </td>
    <td colspan="2"></td>
</tr>
