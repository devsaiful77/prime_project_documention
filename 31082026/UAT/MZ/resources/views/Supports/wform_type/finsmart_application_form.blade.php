<tr>
    <th class="vcenter">E-mail address updated<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('email_updated', [null=>'Please Select'] +  UNSERIALIZE(CONFIRMATION), (!empty($dataForView['email_updated'])) ? $dataForView['email_updated'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('email_updated')) <div class="error-message">{{ $errors->first('email_updated') }}</div> @ENDIF
    </td>
    <th class="vcenter">System captured Email address<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('captured_email',(!empty($dataForView["captured_email"])) ? $dataForView["captured_email"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'System captured Email address*'
          ]);
        !!}
        @IF($errors->has('captured_email')) <div class="error-message">{{ $errors->first('captured_email') }}</div> @ENDIF
    </td>
</tr>
<tr>
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
    <td class="vcenter" colspan="2"></td>
</tr>
