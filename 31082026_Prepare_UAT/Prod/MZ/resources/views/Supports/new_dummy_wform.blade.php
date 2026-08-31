@extends('layouts.root')
@section('content')
{!! Form::open(['method'=>'post', 'action' => ['SupportsController@submitDummyWform'] , 'enctype' => 'multipart/form-data']); !!}
    {!! Form::token(); !!}
  <style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance:textfield;
    }
  </style>

	<div class="curved-inner-pro">
        <div class="curved-ctn">
            <h2 style="padding:10px">New Service Request Form</h2>
        </div>
    </div>

    <div>

		<fieldset class="scheduler-border" style="background-color:#f1f1f1">
		<legend class="scheduler-border" style="font-family: Verdana,Geneva,sans-serif;color:#FF4500;background-color:#ffffff">Information</legend>
		<div class="table-responsive">
		<table class="table table-condensed">
			<colgroup>
			  <col width="15%"></col>
			  <col width="35%"></col>
			  <col width="15%"></col>
			  <col width="35%"></col>
			</colgroup>


		{{-- <tr> <th class="vcenter">Branch Checker?</th> <td class="vcenter"> <div class="fm-checkbox"> <label class="radio-inline"> <input type="radio" name="branch_checker" class="i-checks" value="1"> <i></i> <strong>Yes</strong> </label> <label class="radio-inline"> <input type="radio" name="branch_checker" class="i-checks" value="0" checked> <i></i> <strong>No</strong> </label> </div> </td> <th class="vcenter">&nbsp;</th> <td class="vcenter"> &nbsp; </td> </tr><!-- Branch Checker --> --}}
        <tr>
          <th class="vcenter">Account/ID No</th>
          <td class="vcenter">
            {!! Form::text('account_number',(!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'autofocus'=>'true', 'placeholder'=>'Account/ID No', 'readonly']);
            !!}
            @IF($errors->has('account_number')) <div class="error-message">{{ $errors->first('account_number') }}</div> @ENDIF
          </td>
          <th class="vcenter">Customer Name</th>
          <td class="vcenter">
            {!! Form::text('customer_name',(!empty($dataForView["customer_name"])) ? $dataForView["customer_name"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Name', 'readonly']); !!}
            @IF($errors->has('customer_name')) <div class="error-message">{{ $errors->first('customer_name') }}</div> @ENDIF
          </td>
        </tr><!-- Account/Card No & Customer Name -->

        <tr>
          <th class="vcenter">Mobile Number</th>
          <td class="vcenter">
            {!! Form::text('mobile_number',(!empty($dataForView["mobile_number"])) ? $dataForView["mobile_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Mobile Number', 'readonly']); !!}

            @IF($errors->has('mobile_number')) <div class="error-message">{{ $errors->first('mobile_number') }}</div> @ENDIF


          </td>
          <th class="vcenter">Customer Email</th>
          <td class="vcenter">

              {!! Form::text('def_email_addr',(!empty($dataForView["def_email_addr"])) ? $dataForView["def_email_addr"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Email', 'readonly']); !!}

              @IF($errors->has('def_email_addr')) <div class="error-message">{{ $errors->first('def_email_addr') }}</div> @ENDIF

              {{--
              <div class="form-control">
              {{(!empty($dataForView['def_email_addr'])) ? urldecode($dataForView['def_email_addr']) : ''}}</div>
              --}}
          </td>
        </tr> <!-- Mobile No & Customer Email -->

        <tr>
          <th class="vcenter">Product Type<span class="required">*</span></th>
          <td class="vcenter">

{{--            <select class="form-control" name="product_type" id="product_type">--}}
{{--              @inject('product_type','App\Services\ProductTypeService')--}}
{{--              {!! $product_type->getProductTypeByID(old($dataForView["account_type"],(!empty($dataForView["account_type"])) ? $dataForView["account_type"] : '')) !!}--}}
{{--            </select>--}}
            {{ Form::select('product_type', [null=>'Please Select'] +  $allProductTypeData, (!empty($dataForView['product_type'])) ? $dataForView['product_type'] : "", ['class'=>'form-control']) }}
            @IF($errors->has('product_type')) <div class="error-message">{{ $errors->first('product_type') }}</div> @ENDIF
          </td>
		  <th class="vcenter">Time &amp; Ext<span class="required">*</span></th>
          <td class="vcenter">
            {!! Form::text('time_and_ext',(!empty($dataForView["time_and_ext"])) ? $dataForView["time_and_ext"] : date("h:i:s a") ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Time &amp; Ext*', 'readonly']); !!}
            @IF($errors->has('time_and_ext')) <div class="error-message">{{ $errors->first('time_and_ext') }}</div> @ENDIF
          </td>

        </tr> <!-- Product Type & Priority -->


        <tr>

		<th class="vcenter">Customer Number</th>
                <td class="vcenter">
                    {!! Form::text('SIF_Number',(!empty($dataForView["CIF_number"])) ? $dataForView["CIF_number"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Number', 'readonly']); !!}
					@IF($errors->has('SIF_Number')) <div class="error-message">{{ $errors->first('SIF_Number') }}</div> @ENDIF
                </td>

      <th class="vcenter">Customer DOB</th>
                <td class="vcenter">
                    {!! Form::text('date_of_birth',(!empty($dataForView["date_of_birth"])) ? $dataForView["date_of_birth"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer DOB', 'readonly']); !!}
                </td>

        </tr> <!-- Tin Verified & Caller ID-->
		<tr>
      <th class="vcenter">Segment Code</th>
                <td class="vcenter">
                    {!! Form::text('segment',(!empty($dataForView["SegmentCode"])) ? $dataForView["SegmentCode"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Segment Code', 'readonly']); !!}
                </td>
                <th class="vcenter"></th>
                <td class="vcenter">
                  {{ Form::hidden('cb_fin_acctno', (!empty($dataForView["cb_fin_acctno"])) ? $dataForView["cb_fin_acctno"] : "") }}

                  {{ Form::hidden('card_status', (!empty($dataForView["card_status"])) ? $dataForView["card_status"] : "") }}
                 </td>
    </tr>

		</table>
		</div>
		</fieldset>


		<fieldset class="scheduler-border" style="background-color:#ffffff">
		<legend class="scheduler-border" style="font-family: Verdana,Geneva,sans-serif;color:#FF4500;background-color:#ffffff">Action</legend>
		<div class="table-responsive">
      <table class="table table-condensed">
        <colgroup>
          <col width="15%"></col>
          <col width="35%"></col>
          <col width="15%"></col>
          <col width="35%"></col>
        </colgroup>

		<tr>
          <th class="vcenter">Priority</th>
          <td class="vcenter">
            {{ Form::select('priority', [null=>'Select Priority'] +  unserialize(PRIORITY), (!empty($dataForView['priority'])) ? $dataForView['priority'] : "", ['class'=>'form-control']) }}
          </td>
          <th class="vcenter">Source</th>
          <td class="vcenter">
            {{ Form::select('source', [null=>'Select Source'] +  $allSourceData, (!empty($dataForView['source'])) ? $dataForView['source'] : "", ['class'=>'form-control']) }}
          </td>

        </tr> <!-- Time & Ext and Source -->

        {{--<tr>
          <th class="vcenter">Date of Birth</th>
          <td class="vcenter">
            {{ Form::select('date_of_birth', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['date_of_birth'])) ? $dataForView['date_of_birth'] : "", ['class'=>'form-control']) }}
          </td>
          <th class="vcenter">Mother's Name Verified</th>
          <td class="vcenter">
            {{ Form::select('mother_name', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['mother_name'])) ? $dataForView['mother_name'] : "", ['class'=>'form-control']) }}
          </td>
        </tr> <!-- Date of Birth & Mother's Name Verified -->
        <tr>
          <th class="vcenter">Father's Name Verified</th>
          <td class="vcenter">
            {{ Form::select('father_name', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['father_name'])) ? $dataForView['father_name'] : "", ['class'=>'form-control']) }}
          </td>
          <th class="vcenter">Mobile No</th>
          <td class="vcenter">
            {{ Form::select('mobile_number2', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['mobile_number2'])) ? $dataForView['mobile_number2'] : "", ['class'=>'form-control']) }}
          </td>
        </tr> <!-- Father's Name Verified & Mobile No -->--}}
        <tr>
		{{--<th class="vcenter">Address<span class="is_addr_required required">*</span></th>
          <td class="vcenter">
            {{ Form::select('address', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['address'])) ? $dataForView['address'] : "", ['class'=>'form-control']) }}

            @IF($errors->has('address')) <div class="error-message">{{ $errors->first('address') }}</div> @ENDIF
		</td>--}}

		<th class="vcenter">Tin Verified</th>
          <td class="vcenter">
            {{ Form::select('tin_verified', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['tin_verified'])) ? $dataForView['tin_verified'] : "", ['class'=>'form-control']) }}
          </td>
          <th class="vcenter">Static Verified</th>
          <td class="vcenter">
            {{ Form::select('static_verified', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['static_verified'])) ? $dataForView['static_verified'] : "", ['class'=>'form-control']) }}
          </td>

        </tr> <!-- Address & Other Static -->

        <tr>

		  <th class="vcenter">Notes</th>
          <td class="vcenter">
            {!! Form::textarea('notes',(!empty($dataForView["notes"])) ? $dataForView["notes"] : ''  ,['rows'=>2, 'class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'placeholder'=>'Notes']); !!}
          </td>
		  <th class="vcenter">Dynamic Verified</th>
          <td class="vcenter">
            {{ Form::select('dynamic_verified', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['dynamic_verified'])) ? $dataForView['dynamic_verified'] : "", ['class'=>'form-control']) }}
          </td>
          {{--<th class="vcenter">Other Dynamic</th>
          <td class="vcenter">
            {!! Form::text('other2',(!empty($dataForView["other2"])) ? $dataForView["other2"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Other Dynamic']); !!}
          </td>
		  </tr> <!-- Dynamic Question & Other Dynamic -->--}}
        <tr>
          <th class="vcenter">Caller ID</th>
          <td class="vcenter">
            {!! Form::number('caller_id',(!empty($dataForView["caller_id"])) ? $dataForView["caller_id"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Caller ID']); !!}
            @IF($errors->has('caller_id')) <div class="error-message">{{ $errors->first('caller_id') }}</div> @ENDIF
          </td>
          {{-- <th>TARA/Non TARA</th>
          <td>
            {{ Form::select('is_tara', unserialize(TARATYPE), (!empty($dataForView['is_tara'])) ? $dataForView['is_tara'] : 0, ['class'=>'form-control']) }}
          </td> --}}
        </tr>
        <tr>

          <th class="vcenter">Service Category</th>
          <td class="vcenter">

      @inject('allServiceCategory','App\Services\UtilService')
            @php
              $serviceCategoryResult = $allServiceCategory->getAllServiceCategory();
              $pBServiceCat = old('service_category');
            @endphp

      <select class="form-control wFormType" name="service_category" id="service_category">
              <option value="">Select Category</option>
              @foreach($serviceCategoryResult as $allSerCategory)
        @php
        $selectedServiceCat = "";
        if($pBServiceCat == $allSerCategory->id) {
          $selectedServiceCat = "selected";
        }
        @endphp
                <option value="{{ $allSerCategory->id }}" {{$selectedServiceCat}}> {{ $allSerCategory->name }} </option>
              @endforeach
            </select>
          </td>


          <th class="vcenter">Service Sub Request Type<span class="required">*</span></th>
          <td class="vcenter">
            @inject('allForms','App\Services\UtilService')
            <select class="form-control wFormType" name="w_form_type" id="request_type">
              <option value="">Select Service</option>
            </select>
            @IF($errors->has('w_form_type')) <div class="error-message">{{ $errors->first('w_form_type') }}</div> @ENDIF
          </td>

        </tr> <!--  Notes & WForm Type -->
        <tr id="issue_extra">
        @php
            $pbServType="";
          if(!empty(old('w_form_type'))) {
            $service_request = $pbServType = old('w_form_type');
            $issue_fields = App\IssueConfig::where('issue_id',$service_request)->get();
			      $check_lists = App\IssueCheckListConfig::where('issue_id',$service_request)->get();
          }
        @endphp
        @include('partials.extra_form_field')

        </tr>
          <tr id="issue_check_list">
              @include('partials.issue_check_list')
          </tr>

		<tr>
  		<th></th>
  		<td></td>
      <th class="vcenter">Attachments <div class="clearfix"></div><small class="error-message">(Max file size is 3 MB)</small></th>
      <td class="vcenter" id="attachment_item">
        @include('partials.maker_attachment_item')
        {{--{!! Form::file('file_name[]', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file', 'multiple'=>'multiple')); !!}--}}
      </td>
		</tr>
	  @IF($errors->has('file_name.*'))
      <tr>
        <th colspan="3"></th>
        <th> <div class="error-message">{{ $errors->first('file_name.*') }}</div> </th>
      </tr>
    @ENDIF

		{{--<tr>
          <th class="vcenter" colspan="2"></th>

          <th class="vcenter">Attachments</th>
          <td class="vcenter">
            {!! Form::file('file_name[]', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file', 'multiple'=>'multiple')); !!}
          </td>
        </tr> <!-- Attachment -->--}}
      </table>
	  </div>
	  </fieldset>

    </div>

{!! Form::close(); !!}
<div class="clearfix">&nbsp;</div>

@endsection
@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $(".printBtn").on('click',function(event){
        var allinfo = {_token:_token};
        var ischecklist = 0;
        $(':input').each(function(event){
            var inputtype = $(this).prop('type');
            var notallowed = ['hidden','file','submit','button'];

            if(jQuery.inArray(inputtype, notallowed) == -1) {
              var tmpplaceholder = $(this).closest('td').closest('tr').prev('tr').find('th').text();
                if(tmpplaceholder == 'Check List'){
                  ischecklist = 1;
                }
                if(ischecklist == 1){
                  return;
                }
              //console.log(tmpplaceholder);
              var value = $(this).val();
                  value = (value) ? value : 'N/A';

              if (inputtype == 'checkbox' || inputtype == 'radio') {
                if ($(this).is(":checked")) {
                  value  =  $(this).val();
                } else {
                  value = 'No';
                }
              }
              if (inputtype == 'select-one' || inputtype == 'select-multiple'  ) {
                value = $(this).find('option:selected').text();
                if(value == 'Select'){
                    value = 'N/A';
                  }
              }
              var placeholder = $(this).closest('td').prev('th').text();
                //alert(placeholder);
              allinfo[placeholder] = value;
            }
        });
        $.ajax({
            url: "{{url('/Supports/PrintForm/service')}}",
            type: "POST",
            dataType: "html",
            data: allinfo,
            beforeSend: function(){
                overlay('show');
            },
            success: function(data) {
              overlay('hide');
              url = "{{url('/Supports/PrintForm/service')}}";
              window.open(url);
            },
            error: function(data){
              overlay('hide');
              customAlert('Error','Something went wrong. Please Contact with Administrator','red');
            }
        });
      });


      $('#request_type').on('change', function(){
            //console.log('op');
            var issue_id = $('#request_type').val();
            var type="wform";
            if(issue_id.length > 0){
                $.post('{{ url('issue-extra-form') }}', {_token:'{{ csrf_token() }}', issue_id:issue_id}, function(data){
                    //alert(data);
                    $('#issue_extra').html(data);

                });
            }
            else{
                $('#issue_extra').html(null);
            }
        });
        $('#request_type').on('change', function(){
            //console.log('op');
            var issue_id = $('#request_type').val();
            var type="wform";
            if(issue_id.length > 0){
                $.post('{{ url('issue-check-list') }}', {_token:'{{ csrf_token() }}', issue_id:issue_id}, function(data){
                    //alert(data);
                    $('#issue_check_list').html(data);

                });
            }
            else{
                $('#issue_check_list').html(null);
            }
        });


        var service_category = $('#service_category').val();
        var product_type = $('#product_type').val();

        getGetComplaintOptions(service_category,product_type);
        $('#service_category').on('change', function(){
            var service_category = $('#service_category').val();
            var product_type = $('#product_type').val();
            getGetComplaintOptions(service_category,product_type);

        });

    function getGetComplaintOptions(service_category,product_type) {
        var postBackCompType = "{{$pbServType}}";

        var complaint_id = postBackCompType;
        var type="wform";


        //alert(base_url+'/get-category-wise-service/'+ product_type+'/'+ service_category);
        if (service_category) {
            $.ajax({
                url: base_url+'/get-cat-wise-services/'+ service_category,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('#request_type').html('<option value="">Select Service Type</option>');
                    $.each(data, function (key, value) {
                        var selectedForPb = "";

                        if (postBackCompType == value.id) {
                            selectedForPb = "selected";
                        }

                        $('#request_type').append('<option value="' + value.id + '" '+selectedForPb+' >' + value.name + '</option>');
                    });
                }
            });
        }
    };

    });
  </script>
@endsection
