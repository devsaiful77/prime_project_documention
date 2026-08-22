@extends('layouts.root')
@section('content')
<script src="{{ URL::asset('public/js/latest-v/flatpickr-4.6.13.min.js') }}"></script>

    {!! Form::open(['method'=>'post', 'action' => ['SupportsController@submitNonCustomer'] , 'enctype' => 'multipart/form-data']); !!}
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

    <div class="curved-inner-pro py-2">
        <div class="curved-ctn">
            <h2>New Non Customer Form</h2>
        </div>
    </div>
    <div class="pb-5">
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
                <th class="vcenter">Customer Name<span class="required">*</span></th>
                <td class="vcenter">
                    {!! Form::text('customer_name',(!empty($dataForView["customer_name"])) ? $dataForView["customer_name"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Name']); !!}
					@IF($errors->has('customer_name')) <div class="error-message">{{ $errors->first('customer_name') }}</div> @ENDIF
                </td>
                <th class="vcenter">Customer Address</th>
                <td class="vcenter">

                    {!! Form::text('customer_address',(!empty($dataForView["customer_address"])) ? $dataForView["customer_address"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Address']); !!}

                </td>


            </tr><!-- Account/Card No & Customer Name -->
            <tr>

				<th class="vcenter">Mobile Number<span class="required">*</span></th>
                <td class="vcenter">
                    {!! Form::text('mobile_number',(!empty($dataForView["mobile_number"])) ? $dataForView["mobile_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Mobile Number']); !!}
                    @IF($errors->has('mobile_number')) <div class="error-message">{{ $errors->first('mobile_number') }}</div> @ENDIF

                </td>

				<th class="vcenter">Customer Email</th>
                <td class="vcenter">
                     {!! Form::email('customer_email',(!empty($dataForView["customer_email"])) ? $dataForView["customer_email"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Email']); !!}
                </td>


            </tr> <!-- Tin Verified & Caller ID-->


			<tr>

                <th class="vcenter">DoB (dd/mm/yyyy)</th>
                <td class="vcenter">
                     {!! Form::text('customer_dob',(!empty($dataForView["customer_dob"])) ? $dataForView["customer_dob"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'dd/mm/yyyy']); !!}
                </td>

                <th class="vcenter">Time &amp; Ext<span class="required">*</span></th>
                <td class="vcenter">
                    {!! Form::text('time_and_ext',(!empty($dataForView["time_and_ext"])) ? $dataForView["time_and_ext"] : date("h:i:s a") ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Time &amp; Ext*', 'readonly']); !!}
                    @IF($errors->has('time_and_ext')) <div class="error-message">{{ $errors->first('time_and_ext') }}</div> @ENDIF
                </td>

            </tr>

			<tr>
                <th class="vcenter">Profession</th>
                <td class="vcenter">
                    <select class="form-control" name="customer_profession">
                        <option value="">Select Profession</option>
                            @inject('allProfession','App\Services\UtilService')
                            @php
                                $pBCustProfession = old('customer_profession');
                            @endphp
                            @foreach($allProfession->getAllProfession() as $profession)

                            @php
                                $selectedCustProfession = "";
                                if($pBCustProfession == $profession->id) {
                                  $selectedCustProfession = "selected";
                                }
                            @endphp
                        <option value="{{ $profession->id }}" {{$selectedCustProfession}}>{{$profession->name}}</option>
                        @endforeach
                    </select>
                </td>

                <th class="vcenter">Employment Address</th>
                <td class="vcenter">
                    {!! Form::text('employment_address',(!empty($dataForView["employment_address"])) ? $dataForView["employment_address"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Employment Address']); !!}
                </td>

			</tr>

            <tr>

                <th class="vcenter">Salary / Income</th>
                <td class="vcenter">
                    {!! Form::number('salary_income',(!empty($dataForView["salary_income"])) ? $dataForView["salary_income"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Salary / Income']); !!}

                </td>

                <th class="vcenter">Length of Service/Business</th>
                <td class="vcenter">

                    {!! Form::number('service_length',(!empty($dataForView["service_length"])) ? $dataForView["service_length"] : '' ,['class' => 'form-control', 'label'=>false, 'maxlength'=>3, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Length of Service/Business (In Month)']); !!}
                    @IF($errors->has('service_length')) <div class="error-message">{{ $errors->first('service_length') }}</div> @ENDIF

                </td>

            </tr>

            <tr>

                <th class="vcenter">Request Type<span class="required">*</span></th>
                <td class="vcenter">
                    <select class="form-control" name="request_type">
                        <option value="">Select Request Type</option>
                            @inject('allRequestType','App\Services\UtilService')
                            @php
                                $pBRequestType = old('request_type');
                            @endphp
                            @foreach($allRequestType->getAllRequestType() as $requesttype)

                        @php
                            $selectedRequestType = "";
                            if($pBRequestType == $requesttype->id) {
                              $selectedRequestType = "selected";
                            }
                        @endphp

                        <option value="{{ $requesttype->id }}" {{$selectedRequestType}}>{{$requesttype->name}}</option>
                        @endforeach
                    </select>
                    @IF($errors->has('request_type')) <div class="error-message">{{ $errors->first('request_type') }}</div> @ENDIF
                </td>

                <th class="vcenter">Sales Lead</th>
                <td class="vcenter">
                    <select class="form-control" name="sales_lead">
                        <option value="">Select Sales Lead</option>
                            @inject('allSalesLead','App\Services\UtilService')
                             @php
                                $pBSalesLead = old('sales_lead');
                            @endphp
                            @foreach($allSalesLead->getAllSalesLead() as $saleslead)

                        @php
                            $selectedSalesLead = "";
                            if($pBSalesLead == $saleslead->id) {
                              $selectedSalesLead = "selected";
                            }
                        @endphp
                        <option value="{{ $saleslead->id }}" {{$selectedSalesLead}}>{{$saleslead->name}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
            <th class="vcenter">Other Bank Loan</th>
              <td class="vcenter">
                {{ Form::select('other_bank_loan', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['other_bank_loan'])) ? $dataForView['other_bank_loan'] : '', ['class'=>'form-control']) }}
              </td>
              <th class="vcenter">Other Bank Credit Card</th>
              <td class="vcenter">
                {{ Form::select('other_bank_credit_card', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['other_bank_credit_card'])) ? $dataForView['other_bank_credit_card'] : '', ['class'=>'form-control']) }}
              </td>
          </tr>

            <tr>

                <th class="vcenter">Details<span class="required">*</span></th>
                <td class="vcenter">
                    {!! Form::textarea('details',(!empty($dataForView["details"])) ? $dataForView["details"] : ''  ,['rows'=>2, 'class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'placeholder'=>'Details']); !!}
                    @IF($errors->has('details')) <div class="error-message">{{ $errors->first('details') }}</div> @ENDIF
                </td>

                <th class="vcenter">Forward To<span class="required">*</span></th>
                <td class="vcenter">
                    <select class="form-control" name="forward_to">
                        <option value="">Select Group</option>
                        @inject('allGroups','App\Services\UtilService')
                            @php
                                $pBForwardTo = old('forward_to');
                            @endphp
                        @foreach($allGroups->getAllGroupList() as $group)

                            @php
                            $selectedForwardTo = "";
                            if($pBForwardTo == $group->id) {
                              $selectedForwardTo = "selected";
                            }
                            @endphp
                        <option value="{{ $group->id }}" {{$selectedForwardTo}}>{{$group->name}}</option>
                        @endforeach
                    </select>
					@IF($errors->has('forward_to')) <div class="error-message">{{ $errors->first('forward_to') }}</div> @ENDIF
                </td>


            </tr> <!-- Complaint Detail and Caller ID -->


        </table>
		</div>
		</fieldset>
    <div class="clearfix"></div>
    <?php
    $dataForView["active_tab"] = "complaint";
    $additionalParams = (!empty($dataForView)) ? '?'.http_build_query($dataForView) : "";
    ?>
    {{ Form::hidden('additionalParams',$additionalParams) }}

    {!!
      Form::submit((!empty($id)) ? 'Update':'Submit',array(
        'class'=>'btn btn-primary gradient',
        'title'=>'Add',
        'onclick'=>"overlay('show');",
        'escape'=>false
      ));
    !!}
    <?php $closeUrl = url('/Supports/home'); //$closeUrl = url('/Supports/home').$additionalParams ?>
    <!--
    <button type="button" class="btn btn-danger gradient" onclick="window.close();">Close</button> -->
    <a href="{{$closeUrl}}" class="btn btn-danger gradient">Close</a>
    <button class="btn btn-success printBtn" type="button">Print</button>
    </div>
    {!! Form::close(); !!}
    <div class="clearfix">&nbsp;</div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".printBtn").on('click',function(event){
                var allinfo = {_token:_token};
                $(':input').each(function(event){
                    var inputtype = $(this).prop('type');
                    var notallowed = ['hidden','file','submit','button'];
                    if(jQuery.inArray(inputtype, notallowed) == -1) {
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
                      allinfo[placeholder] = value;
                    }
                });
                $.ajax({
                    url: "{{url('/Supports/PrintForm/noncustomer')}}",
                    type: "POST",
                    dataType: "html",
                    data: allinfo,
                    beforeSend: function(){
                        overlay('show');
                    },
                    success: function(data) {
                      overlay('hide');
                      url = "{{url('/Supports/PrintForm/noncustomer')}}";
                      window.open(url);
                    },
                    error: function(data){
                      overlay('hide');
                      customAlert('Error','Something went wrong. Please Contact with Administrator','red');
                    }
                });
              });
        });
    </script>
@endsection
