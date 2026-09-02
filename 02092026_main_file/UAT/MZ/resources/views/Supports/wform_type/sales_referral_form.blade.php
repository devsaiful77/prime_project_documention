<tr>
    <th class="vcenter">Profession</th>
    <td class="vcenter">
        {{ Form::select('profession', [null=>'Please Select'] +  $allWformMasterData['profession'], (!empty($dataForView['profession'])) ? $dataForView['profession'] : "", ['class'=>'form-control']) }}
    </td>
    <th class="vcenter">Designation &amp; Company</th>
    <td class="vcenter">
        {!!
          Form::text('designation_company',(!empty($dataForView['designation_company'])) ? $dataForView['designation_company'] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Designation &amp; Company'
          ]);
        !!}
    </td>
</tr>
<tr>
    <th class="vcenter">Salary Amount or Credit Turnover<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('salary_amount_credit_turnover',(!empty($dataForView['salary_amount_credit_turnover'])) ? $dataForView['salary_amount_credit_turnover'] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Salary Amount or Credit Turnover'
          ]);
        !!}
        @IF($errors->has('salary_amount_credit_turnover')) <div class="error-message">{{ $errors->first('salary_amount_credit_turnover') }}</div> @ENDIF
    </td>
    <th class="vcenter">Contact Mobile number<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('contact_mobile_number',(!empty($dataForView['contact_mobile_number'])) ? $dataForView['contact_mobile_number'] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Contact Mobile number*'
          ]);
        !!}
        @IF($errors->has('contact_mobile_number')) <div class="error-message">{{ $errors->first('contact_mobile_number') }}</div> @ENDIF
    </td>
</tr>