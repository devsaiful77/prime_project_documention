<tr>
    <th class="vcenter">Request Type<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('request_type', [null=>'Please Select'] +  $allWformMasterData['cle_request'], (!empty($dataForView['request_type'])) ? $dataForView['request_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('request_type')) <div class="error-message">{{ $errors->first('request_type') }}</div> @ENDIF
    </td>
    <th class="vcenter">Existing Limit<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('existing_limit',(!empty($dataForView["existing_limit"])) ? $dataForView["existing_limit"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Existing Limit*'
          ]);
        !!}
        @IF($errors->has('existing_limit')) <div class="error-message">{{ $errors->first('existing_limit') }}</div> @ENDIF
    </td>
</tr>

<tr>
    <th class="vcenter">Proposed Limit</th>
    <td class="vcenter">
        {!!
          Form::text('proposed_limit',(!empty($dataForView["proposed_limit"])) ? $dataForView["proposed_limit"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Proposed Limit'
          ]);
        !!}
    </td>
    <td class="vcenter" colspan="2">&nbsp;</td>
</tr>

