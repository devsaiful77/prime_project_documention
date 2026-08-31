<tr>
    <th class="vcenter">Date From<span class="required">*</span> </th>
    <td class="vcenter">
        {!!
          Form::text('date_from',(!empty($dataForView["date_from"])) ? $dataForView["date_from"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Date From*'
          ]);
        !!}
        @IF($errors->has('date_from')) <div class="error-message">{{ $errors->first('date_from') }}</div> @ENDIF
    </td>
    <th class="vcenter">Date To<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('date_to',(!empty($dataForView["date_to"])) ? $dataForView["date_to"] : '' ,[
            'class' => 'form-control datePicker',
            'autocomplete'=>'off',
            'readonly'=>'true',
            'placeholder'=>'Date To*'
          ]);
        !!}
        @IF($errors->has('date_to')) <div class="error-message">{{ $errors->first('date_to') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">Charge<span class="required">*</span></th>
    <td class="vcenter">
        {!!
          Form::text('duplicate_charge',(!empty($dataForView["duplicate_charge"])) ? $dataForView["duplicate_charge"] : '' ,[
            'class' => 'intNumber form-control',
            'autocomplete'=>'off',
            'placeholder'=>'Charge'
          ]);
        !!}
        @IF($errors->has('duplicate_charge')) <div class="error-message">{{ $errors->first('duplicate_charge') }}</div> @ENDIF
    </td>
    <th class="vcenter">Delivery Option<span class="required">*</span></th>
    <td class="vcenter">
        {{ Form::select('delivery_option', [null=>'Please Select'] +  $allWformMasterData['delivery_option'], (!empty($dataForView['delivery_option'])) ? $dataForView['delivery_option'] : "", ['class'=>'form-control']) }}
        @IF($errors->has('delivery_option')) <div class="error-message">{{ $errors->first('delivery_option') }}</div> @ENDIF
    </td>
</tr>
<tr>
    <th class="vcenter">E-mail Address<span class="required">*</span></th>
    <td class="vcenter">
        {!!     
          Form::text('email_address',(!empty($dataForView['def_email_addr'])) ? urldecode($dataForView['def_email_addr']) : '' ,[
            'class' => 'form-control',
            'autocomplete'=>'off',
            'placeholder'=>'E-mail Address*'
          ]);
        !!}
        @IF($errors->has('email_address')) <div class="error-message">{{ $errors->first('email_address') }}</div> @ENDIF
    </td>
    <td class="vcenter" colspan="2">&nbsp;</td>
</tr>

<script type="text/javascript">
$(document).ready(function(event){
    $(document).off("input keypress paste", ".capital-form");
    $(document).on("input keypress paste", ".capital-form", function(event) {
        var inputVal = $(this).val();
        var convertedWord = __wordConv(inputVal,'cp');
        $(this).val(convertedWord);
    });
    $(document).off("keypress", ".intNumber");
    $(document).on("keypress", ".intNumber", function(event) {
        var charCode = event.keyCode || event.which;
        if ((charCode < 48 || charCode > 58) && (charCode < 2534
        || charCode > 2543) && charCode!=8 && charCode !=37 && charCode !=39 && charCode !=9 ) {
            
            event.preventDefault();
        } // prevent if not number/dot
    });
    
    $(".select2").select2({placeholder: "নির্বাচন করুন"});
    $(".select2_plugin").select2({});
});
</script>