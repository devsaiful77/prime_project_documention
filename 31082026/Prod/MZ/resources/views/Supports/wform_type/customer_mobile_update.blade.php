<tr>
    <th class="vcenter">Correspondence<span class="required">*</span> </th>
    <td class="vcenter">
        <label class="radio-inline" style="padding-left: 0;">
            {{ Form::radio('category2', 'Personal',(old('category2') == 'Personal'), array('class'=>'address_type')) }}
            Personal
        </label>
        <label class="radio-inline">
            {{ Form::radio('category2', 'Official',(old('category2') == 'Official'), array('class'=>'address_type')) }}
            Official
        </label>
        @IF($errors->has('category2')) <div class="error-message">{{ $errors->first('category2') }}</div> @ENDIF
    </td>
    <td colspan="2"></td>
</tr>

<tr>
    <th class="vcenter">Personal Mobile<span class="is_res_addr_required required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('data_to_be_insert',(!empty($dataForView["data_to_be_insert"])) ? $dataForView["data_to_be_insert"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Personal Mobile'
          ]);
        !!}
        @IF($errors->has('data_to_be_insert')) <div class="error-message">{{ $errors->first('data_to_be_insert') }}</div> @ENDIF
    </td>
    <th class="vcenter">Official Mobile<span class="is_ofc_addr_required required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('data_tobe_insert2',(!empty($dataForView["data_tobe_insert2"])) ? $dataForView["data_tobe_insert2"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Official Mobile'
          ]);
        !!}
        @IF($errors->has('data_tobe_insert2')) <div class="error-message">{{ $errors->first('data_tobe_insert2') }}</div> @ENDIF
    </td>
</tr>

