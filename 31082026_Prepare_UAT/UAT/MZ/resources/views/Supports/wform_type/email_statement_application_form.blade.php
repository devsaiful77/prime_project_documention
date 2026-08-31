<tr>
    <th class="vcenter">E-mail address updated<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('email_updated', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['email_updated'])) ? $dataForView['email_updated'] : "", ['class'=>'form-control']) }}
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