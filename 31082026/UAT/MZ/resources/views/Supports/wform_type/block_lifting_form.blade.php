<tr>
    <th class="vcenter">Block Reason</th>
    <td class="vcenter">
        {!!
          Form::text('block_reason',(!empty($dataForView["block_reason"])) ? $dataForView["block_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Block Reason'
          ]);
        !!}
    </td>
    <th class="vcenter">Reinstate Reason<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('reinstate_reason',(!empty($dataForView["reinstate_reason"])) ? $dataForView["reinstate_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Reinstate Reason'
          ]);
        !!}
        @IF($errors->has('reinstate_reason')) <div class="error-message">{{ $errors->first('reinstate_reason') }}</div> @ENDIF
    </td>
</tr>
