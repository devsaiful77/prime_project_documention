<tr>
    <th class="vcenter">Correspondence<span class="required">*</span> </th>
    <td class="vcenter">
        <label class="radio-inline" style="padding-left: 0;">
            {{ Form::radio('category1', 'Residence',(old('category1') == 'Residence'), array('class'=>'address_type')) }}
            Residence
        </label>
        <label class="radio-inline">
            {{ Form::radio('category1', 'Office',(old('category1') == 'Office'), array('class'=>'address_type')) }}
            Office
        </label>
        @IF($errors->has('category1')) <div class="error-message">{{ $errors->first('category1') }}</div> @ENDIF
    </td>
    <td colspan="2"></td>
</tr>

<tr>
    <th class="vcenter" rowspan="4" style="vertical-align:top !important;">Residence Address<span class="is_res_addr_required required">*</span></th>
    <td class="vcenter" rowspan="4" style="vertical-align:top !important;">
        {!!
          Form::text('data_to_be_insert',(!empty($dataForView["data_to_be_insert"])) ? $dataForView["data_to_be_insert"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Residence Address'
          ]);
        !!}
        @IF($errors->has('data_to_be_insert')) <div class="error-message">{{ $errors->first('data_to_be_insert') }}</div> @ENDIF
    </td>
    <th class="vcenter" rowspan="4" style="vertical-align:top !important;" >Office Address<span class="is_ofc_addr_required required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('cust_desig',(!empty($dataForView["cust_desig"])) ? $dataForView["cust_desig"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Designation'
          ]);
        !!}
        @IF($errors->has('cust_desig')) <div class="error-message">{{ $errors->first('cust_desig') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <td class="vcenter">
        {!!
          Form::text('cust_dept',(!empty($dataForView["cust_dept"])) ? $dataForView["cust_dept"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Department'
          ]);
        !!}
        @IF($errors->has('cust_dept')) <div class="error-message">{{ $errors->first('cust_dept') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <td class="vcenter">
        {!!
          Form::text('cust_comp',(!empty($dataForView["cust_comp"])) ? $dataForView["cust_comp"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Company Name'
          ]);
        !!}
        @IF($errors->has('cust_comp')) <div class="error-message">{{ $errors->first('cust_comp') }}</div> @ENDIF
    </td>
</tr>

<tr>
    <td class="vcenter">
        {!!
          Form::text('cust_ofc_addr',(!empty($dataForView["cust_ofc_addr"])) ? $dataForView["cust_ofc_addr"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Detail Address'
          ]);
        !!}
        @IF($errors->has('cust_ofc_addr')) <div class="error-message">{{ $errors->first('cust_ofc_addr') }}</div> @ENDIF
    </td>
</tr>
