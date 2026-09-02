<tr>
    <th class="vcenter">Branch Service Type<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('branch_service_type', [null=>'Please Select'] +  $allWformMasterData['branch_service_type_ast'], (!empty($dataForView['branch_service_type'])) ? $dataForView['branch_service_type'] : "", ['class'=>'form-control']) }}
        
        @IF($errors->has('branch_service_type')) <div class="error-message">{{ $errors->first('branch_service_type') }}</div> @ENDIF
    </td>
    <td class="vcenter" colspan="2"> &nbsp; </td>
</tr>
