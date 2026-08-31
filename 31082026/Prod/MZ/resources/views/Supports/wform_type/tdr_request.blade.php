<tr>
    <th class="vcenter">TDR Type<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('tdr_type', [null=>'Please Select'] +  $allWformMasterData['tdr_type'], (!empty($dataForView['tdr_type'])) ? $dataForView['tdr_type'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('tdr_type')) <div class="error-message">{{ $errors->first('tdr_type') }}</div> @ENDIF
    </td>
    <th class="vcenter"> Amount<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('amount',(!empty($dataForView["amount"])) ? $dataForView["amount"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Amount*'
          ]);
        !!}
        @IF($errors->has('amount')) <div class="error-message">{{ $errors->first('amount') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Tenor<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('tdr_tenor',(!empty($dataForView["tdr_tenor"])) ? $dataForView["tdr_tenor"] : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Tenor*'
          ]);
        !!}
        @IF($errors->has('tdr_tenor')) <div class="error-message">{{ $errors->first('tdr_tenor') }}</div> @ENDIF
    </td>
    <th class="vcenter">Mode of Payment<span class="required">*</span> </th>
    <td class="vcenter">
        {{ Form::select('mode_of_payment', [null=>'Please Select'] +  $allWformMasterData['mode_of_payment'], (!empty($dataForView['mode_of_payment'])) ? $dataForView['mode_of_payment'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('mode_of_payment')) <div class="error-message">{{ $errors->first('mode_of_payment') }}</div> @ENDIF
    </td>
</tr>