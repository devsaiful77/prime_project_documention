<tr>
    <th class="vcenter">Available Reward Point<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('available_reward_point',(!empty($dataForView['available_reward_point'])) ? $dataForView['available_reward_point'] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Available Reward Point'
          ]);
        !!}
        @IF($errors->has('available_reward_point')) <div class="error-message">{{ $errors->first('available_reward_point') }}</div> @ENDIF
    </td>
    <th class="vcenter">Point To Reedem<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('point_to_reedem',(!empty($dataForView['point_to_reedem'])) ? $dataForView['point_to_reedem'] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Point To Reedem*'
          ]);
        !!}
        @IF($errors->has('point_to_reedem')) <div class="error-message">{{ $errors->first('point_to_reedem') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Product Code<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('product_code',(!empty($dataForView['product_code'])) ? $dataForView['product_code'] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Product Code'
          ]);
        !!}
        @IF($errors->has('product_code')) <div class="error-message">{{ $errors->first('product_code') }}</div> @ENDIF
    </td>
    <th class="vcenter">Reward Description<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('reward_description',(!empty($dataForView['reward_description'])) ? $dataForView['reward_description'] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Reward Description*'
          ]);
        !!}
        @IF($errors->has('reward_description')) <div class="error-message">{{ $errors->first('reward_description') }}</div> @ENDIF
    </td>
</tr>