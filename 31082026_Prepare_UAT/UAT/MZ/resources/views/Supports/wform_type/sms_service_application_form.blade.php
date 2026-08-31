<tr>
    <th class="vcenter">Mobile Number updated<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('mobile_updated', [null=>'Please Select'] +  UNSERIALIZE(CONFIRMATION), (!empty($dataForView['mobile_updated'])) ? $dataForView['mobile_updated'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('mobile_updated')) <div class="error-message">{{ $errors->first('mobile_updated') }}</div> @ENDIF
    </td>
    <th class="vcenter">System captured mobile number<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('captured_mobile',(!empty($dataForView["captured_mobile"])) ? $dataForView["captured_mobile"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'System captured mobile number*'
          ]);
        !!}
        @IF($errors->has('captured_mobile')) <div class="error-message">{{ $errors->first('captured_mobile') }}</div> @ENDIF
    </td>
</tr>
