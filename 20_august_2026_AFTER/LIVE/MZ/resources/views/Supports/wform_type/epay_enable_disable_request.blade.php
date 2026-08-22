<tr>
    <th class="vcenter">Reason<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('epay_enable_reason',(!empty($dataForView["epay_enable_reason"])) ? $dataForView["epay_enable_reason"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Reason*'
          ]);
        !!}
        @IF($errors->has('epay_enable_reason')) <div class="error-message">{{ $errors->first('epay_enable_reason') }}</div> @ENDIF
    </td>
    <th class="vcenter">Enable/Disable<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('epay_enable', [null=>'Please Select'] +  unserialize(DISABLEENABLE), (!empty($dataForView['epay_enable'])) ? $dataForView['epay_enable'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('epay_enable')) <div class="error-message">{{ $errors->first('epay_enable') }}</div> @ENDIF
    </td>
</tr>