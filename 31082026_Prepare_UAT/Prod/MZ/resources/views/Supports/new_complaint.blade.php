@extends('layouts.admin')
@section('content')
<script src="{{ URL::asset('public/js/latest-v/flatpickr-4.6.13.min.js') }}"></script>

{!! Form::open(['method'=>'post', 'action' => ['SupportsController@submitComplaint'] , 'enctype' => 'multipart/form-data']); !!}
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
    #issue_extra .none-bottom-border,
    #issue_check_list .none-bottom-border{
        border-bottom-width: 0;
        padding: 0;
    }
    #issue_extra .none-bottom-border .table-condensed,
    #issue_check_list .none-bottom-border .table-condensed{
        margin: 0;
    }
    .navbar{
        display: none;
    }
  </style>

	<div class="curved-inner-pro py-2">
        <div class="curved-ctn">
            <h2>New Complaint Form</h2>
        </div>
    </div>
    @php
        use App\Setting;
        $settings = Setting::first();
        if (!empty($settings) && !empty($settings->file_size_limit)) {
            $fileSizeLimit = (int) $settings->file_size_limit / 1024;
        } else {
            $fileSizeLimit = 10240 / 1024;
        }
    @endphp
<div class="pb-5">
	<div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div class="table-responsive">
                <fieldset class="scheduler-border" style="background-color:#f5f5f5">
                    <div class="scheduler-border">Action Fields</div>
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

                    </tr> <!-- Priority and Time & Ext -->

                     <!-- Complaint Detail and Caller ID -->
                     <!-- Source & Repeat Complaint -->
                    <tr>
                      <th class="vcenter">Tin Verified</th>
                      <td class="vcenter">
                        {{ Form::select('tin_verified', [null=>'Please Select'] +  unserialize(CONFIRMATION), (!empty($dataForView['tin_verified'])) ? $dataForView['tin_verified'] : "", ['class'=>'form-control']) }}
                      </td>

                      <th class="vcenter">Repeat Complaint</th>
                      <td class="vcenter">
                        {{ Form::select('repeat_complaint', unserialize(CONFIRMATION), (!empty($dataForView['repeat_complaint'])) ? $dataForView['repeat_complaint'] : "No", ['class'=>'form-control']) }}
                      </td>

                      {{--<th class="vcenter">Amount</th>
                      <td class="vcenter">
                        {!! Form::text('amount',(!empty($dataForView["amount"])) ? $dataForView["amount"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Amount']); !!}
                        @IF($errors->has('amount')) <div class="error-message">{{ $errors->first('amount') }}</div> @ENDIF
                      </td>--}}

                    </tr> <!-- Tin Verified  & Amount -->

                    <tr>
                      <th class="vcenter">Complaint Detail<span class="required">*</span></th>
                      <td class="vcenter">
                        {!! Form::textarea('complaint_details',(!empty($dataForView["complaint_details"])) ? $dataForView["complaint_details"] : ''  ,['rows'=>2, 'class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'placeholder'=>'Complaint Detail']); !!}
                        @IF($errors->has('complaint_details')) <div class="error-message">{{ $errors->first('complaint_details') }}</div> @ENDIF
                      </td>

                      @php if(Auth::user()->user_unit->subgroup_info_id==3){ @endphp
                        <th class="vcenter">Caller ID<span class="required">*</span></th>
                      @php }else{ @endphp
                        <th class="vcenter">Caller ID</th>
                      @php } @endphp
                      <td class="vcenter">
                        {!! Form::number('caller_id',(!empty($dataForView["caller_id"])) ? $dataForView["caller_id"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Caller ID']); !!}
                        @IF($errors->has('caller_id')) <div class="error-message">{{ $errors->first('caller_id') }}</div> @ENDIF
                      </td>
                    </tr>

                    <tr>
                    <th class="vcenter">Complaint Category  <span class="required">*</span></th>
                      <td class="vcenter">

                        @inject('allComplaintCategory','App\Services\UtilService')
                        @php
                          $complaintCategoryResult = $allComplaintCategory->getComplaintCategory($dataForView["account_type"]);
                          $pBComplaintCat = old('complaint_category');
                        @endphp

                        <select class="form-control wFormType" name="complaint_category" id="complaint_category">
                          <option value="">Select Category</option>
                          @foreach($complaintCategoryResult as $allComCategory)
                            @php
                            $selectedComplaintCat = "";
                            if($pBComplaintCat == $allComCategory->id) {
                                $selectedComplaintCat = "selected";
                            }
                            @endphp
                            <option value="{{ $allComCategory->id }}" {{$selectedComplaintCat}}> {{ $allComCategory->name }} </option>
                          @endforeach
                        </select>

                        {{-- Form::select('complaint_category', [null=>'Select Complaint Category'], (!empty($dataForView['complaint_category'])) ? $dataForView['complaint_category'] : "", ['class'=>'form-control']) --}}
                      </td>

                    <th class="vcenter">Complaint Sub Category<span class="required">*</span></th>
                      <td class="vcenter">

                        @inject('allForms','App\Services\UtilService')
                        @php
                          $allIssue= $allForms->getAllComplaintWithProd($dataForView["account_type"]);
                        @endphp

                        {{--{{ Form::select('w_form_type', [null=>'Please Select'] +  $allUnitItemData, (!empty($dataForView['w_form_type'])) ? $dataForView['w_form_type'] : "", ['class'=>'form-control wFormType']) }}--}}

                        <select class="form-control wFormType" name="complaint_type" id="complaint_type">
                        <option value="">Select Complaint</option>

                        </select>
                        {{--{{ Form::select('complaint_type', [null=>'Please Select'] +  $allUnitItemData, (!empty($dataForView['complaint_type'])) ? $dataForView['complaint_type'] : "", ['class'=>'form-control wFormType','id'=>'complaint_type']) }}--}}
                        @IF($errors->has('complaint_type')) <div class="error-message">{{ $errors->first('complaint_type') }}</div> @ENDIF
                      </td>

                      </tr>

                      <tr id="issue_extra">
                          @php
                            $pbCompType="";
                            if(!empty(old('complaint_type'))) {
                              $service_request = $pbCompType = old('complaint_type');
                              /*$issue_fields = App\IssueConfig::where('issue_id',$service_request)->get();*/
                              $issue_fields = App\Services\FieldSetGroupService::getFieldSet($service_request);
                              $check_lists = App\IssueCheckListConfig::where('issue_id',$service_request)->get();
                            }
                          @endphp
                        @include('partials.extra_form_field_with_group')
                      </tr>

                    <tr id="issue_check_list">
                          @include('partials.issue_check_list')
                    </tr>

                    <tr>
                        <!-- <th>
                            <a href="#" data-id="" class="btn btn-warning" id="isInquiryApi" style="display: none;">Inquiry API</a>
                        </th>
                        <td></td> -->
                        <th class="vcenter">Attachments
                            <div class="clearfix"></div>
                            <small class="error-message">(Max file size is {{ $fileSizeLimit }} MB)</small></th>
                        <td class="vcenter" id="attachment_item">
                            @include('partials.maker_attachment_item')
                            <div class="clearfix pb-2"></div>
                            <img id="imgInComPage" alt="W-Form image" width="100" height="100" style="display:none;" />
                            <a id="downloadBtnComPage" href="#" style="display:none;" download>Download File</a>

                            {!! Form::file('file_name[]', $attributes = array(
                                'class' => 'form-control',
                                'label' => false,
                                'type' => 'file',
                                'multiple' => 'multiple',
                                'onchange' => "
                                    var file = this.files[0];
                                    var img = document.getElementById('imgInComPage');
                                    var downloadBtn = document.getElementById('downloadBtnComPage');
                                    if (file) {
                                        if (file.type.startsWith('image/')) {
                                            // Show image preview
                                            img.style.display = 'block';
                                            img.src = window.URL.createObjectURL(file);
                                            downloadBtn.style.display = 'none';
                                            downloadBtn.href = '';
                                        } else {
                                            // Hide image and show download button
                                            img.style.display = 'none';
                                            img.src = '';
                                            downloadBtn.style.display = 'block';
                                            downloadBtn.href = window.URL.createObjectURL(file);
                                            downloadBtn.innerHTML = 'Download ' + file.name;
                                        }
                                    } else {
                                        // Hide both if no file is selected
                                        img.style.display = 'none';
                                        img.src = '';
                                        downloadBtn.style.display = 'none';
                                        downloadBtn.href = '';
                                    }"
                            )) !!}

                        </td>
                        {{--<td class="vcenter" id="issue_attachment_item"></td>--}}
                    </tr>
                    <!-- Attachments -->
                    @IF($errors->has('file_name.*'))
                      <tr>
                        <th colspan="3"></th>
                        <th> <div class="error-message">{{ $errors->first('file_name.*') }}</div> </th>
                      </tr>
                    @ENDIF


                  </table>
                  </div>
                </fieldset>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="table-responsive">
                <fieldset class="scheduler-border" style="background-color:#f5f5f5">
                    <div class="scheduler-border">Customer's Information</div>
                    <div class="table-responsive">
                    <table class="table table-condensed">
                        <colgroup>
                        <col width="40%"></col>
                        <col width="60%"></col>
                        </colgroup>

                        @if ($dataForView["account_type"] != 1 && $dataForView["account_type"] != 3)
                            <tr>
                                <th class="vcenter borderright">Account/ID No</th>
                                <td class="vcenter">
                                    {!! Form::text('account_number',(!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'autofocus'=>'true', 'placeholder'=>'Account/ID No', 'readonly']);!!}
                                    @IF($errors->has('account_number')) <div class="error-message">{{ $errors->first('account_number') }}</div> @ENDIF
                                </td>
                            </tr>
                            <tr>
                                <th class="vcenter borderright">Account Name</th>
                                <td class="vcenter">
                                    {!! Form::text('acc_name',(!empty($dataForView["acc_name"])) ? $dataForView["acc_name"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Account Name', 'readonly']); !!}
                                    @IF($errors->has('acc_name')) <div class="error-message">{{ $errors->first('acc_name') }}</div> @ENDIF
                                </td>

                            </tr><!-- Account/Card No & Customer Name -->
                            <tr>
                            <th class="vcenter borderright">Mobile Number</th>
                            <td class="vcenter">
                                {!! Form::text('mobile_number',(!empty($dataForView["mobile_number"])) ? $dataForView["mobile_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Mobile Number', 'readonly']); !!}
                                @IF($errors->has('mobile_number')) <div class="error-message">{{ $errors->first('mobile_number') }}</div> @ENDIF

                                {{-- Form::hidden('def_email_addr',(!empty($dataForView['def_email_addr'])) ? urldecode($dataForView['def_email_addr']) : '') --}}
                            </td>
                            </tr> <!-- Mobile Number & Email -->
                            <tr>
                            <th class="vcenter borderright">Customer Name</th>
                            <td class="vcenter">
                                {!! Form::text('customer_name',(!empty($dataForView["customer_name"])) ? $dataForView["customer_name"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Name', 'readonly']); !!}
                                @IF($errors->has('customer_name')) <div class="error-message">{{ $errors->first('customer_name') }}</div> @ENDIF
                                {{--
                                <div class="form-control disabled" disabled>{{(!empty($dataForView['def_email_addr'])) ? urldecode($dataForView['def_email_addr']) : ''}}</div>
                                --}}
                            </td>
                            </tr> <!-- Mobile Number & Email -->



                            <tr>
                            <th class="vcenter borderright">Product Type<span class="required">*</span></th>
                            <td class="vcenter">
                                <select class="form-control" name="product_type" id="product_type">
                                @inject('product_type','App\Services\ProductTypeService')
                                {!! $product_type->getProductTypeByID(old($dataForView["account_type"],(!empty($dataForView["account_type"])) ? $dataForView["account_type"] : '')) !!}
                                @IF($errors->has('product_type')) <div class="error-message">{{ $errors->first('product_type') }}</div> @ENDIF
                            </td>

                            </tr> <!-- Product Type and Complaint Type -->
                            <tr>
                            <th class="vcenter borderright">Customer Email</th>
                            <td class="vcenter">
                                {!! Form::text('def_email_addr',(!empty($dataForView["def_email_addr"])) ? $dataForView["def_email_addr"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Email', 'readonly']); !!}
                                @IF($errors->has('def_email_addr')) <div class="error-message">{{ $errors->first('def_email_addr') }}</div> @ENDIF
                            </td>

                            </tr> <!-- Product Type and Complaint Type -->

                            <tr>

                            <th class="vcenter borderright">Customer Number</th>
                                    <td class="vcenter">
                                        {!! Form::text('SIF_Number',(!empty($dataForView["CIF_number"])) ? $dataForView["CIF_number"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Number', 'readonly']); !!}
                                        @IF($errors->has('SIF_Number')) <div class="error-message">{{ $errors->first('SIF_Number') }}</div> @ENDIF
                                    </td>


                            </tr>
                            <tr>

                            <th class="vcenter borderright">Time &amp; Ext<span class="required">*</span></th>
                                    <td class="vcenter">
                                        {!! Form::text('time_and_ext',(!empty($dataForView["time_and_ext"])) ? $dataForView["time_and_ext"] : date("h:i:s a") ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Time &amp; Ext*', 'readonly']); !!}
                                        @IF($errors->has('time_and_ext')) <div class="error-message">{{ $errors->first('time_and_ext') }}</div> @ENDIF
                                    </td>

                            </tr>

                        @endif

                        @if ($dataForView["account_type"] == 1 || $dataForView["account_type"] == 3)
                            <tr>
                                <th class="vcenter borderright">Card Number</th>
                                <td class="vcenter">
                                    {!! Form::text('account_number',(!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'autofocus'=>'true', 'placeholder'=>'Account/ID No', 'readonly']);!!}
                                    @IF($errors->has('account_number')) <div class="error-message">{{ $errors->first('account_number') }}</div> @ENDIF
                                </td>
                            </tr>
                          <tr>
                              <th class="vcenter borderright">Card Product Name</th>
                              <td class="vcenter">
                                  {!! Form::text('acc_name',(!empty($dataForView["cardProductName"])) ? $dataForView["cardProductName"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Account Name', 'readonly']); !!}
                                  @IF($errors->has('acc_name')) <div class="error-message">{{ $errors->first('acc_name') }}</div> @ENDIF
                              </td>

                          </tr><!-- Account/Card No & Customer Name -->
                          <tr>
                          <th class="vcenter borderright">Mobile Number</th>
                          <td class="vcenter">
                              {!! Form::text('mobile_number',(!empty($dataForView["mobile_number"])) ? $dataForView["mobile_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Mobile Number', 'readonly']); !!}
                              @IF($errors->has('mobile_number')) <div class="error-message">{{ $errors->first('mobile_number') }}</div> @ENDIF

                              {{-- Form::hidden('def_email_addr',(!empty($dataForView['def_email_addr'])) ? urldecode($dataForView['def_email_addr']) : '') --}}
                          </td>
                          </tr> <!-- Mobile Number & Email -->
                          <tr>
                          <th class="vcenter borderright">Customer Name</th>
                          <td class="vcenter">
                              {!! Form::text('customer_name',(!empty($dataForView["accountTitle"])) ? $dataForView["accountTitle"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Name', 'readonly']); !!}
                              @IF($errors->has('customer_name')) <div class="error-message">{{ $errors->first('customer_name') }}</div> @ENDIF
                              {{--
                              <div class="form-control disabled" disabled>{{(!empty($dataForView['def_email_addr'])) ? urldecode($dataForView['def_email_addr']) : ''}}</div>
                              --}}
                          </td>
                          </tr> <!-- Mobile Number & Email -->



                          <tr>
                          <th class="vcenter borderright">Product Type<span class="required">*</span></th>
                          <td class="vcenter">
                              <select class="form-control" name="product_type" id="product_type">
                              @inject('product_type','App\Services\ProductTypeService')
                              {!! $product_type->getProductTypeByID(old($dataForView["account_type"],(!empty($dataForView["account_type"])) ? $dataForView["account_type"] : '')) !!}
                              @IF($errors->has('product_type')) <div class="error-message">{{ $errors->first('product_type') }}</div> @ENDIF
                          </td>

                          </tr> <!-- Product Type and Complaint Type -->
                          <tr>
                          <th class="vcenter borderright">Customer Email</th>
                          <td class="vcenter">
                              {!! Form::text('def_email_addr',(!empty($dataForView["email"])) ? $dataForView["email"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Email', 'readonly']); !!}
                              @IF($errors->has('def_email_addr')) <div class="error-message">{{ $errors->first('def_email_addr') }}</div> @ENDIF
                          </td>

                          </tr> <!-- Product Type and Complaint Type -->

                          <tr>

                          <th class="vcenter borderright">Client Code</th>
                                  <td class="vcenter">
                                      {!! Form::text('SIF_Number',(!empty($dataForView["clientCode"])) ? $dataForView["clientCode"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer Number', 'readonly']); !!}
                                      @IF($errors->has('SIF_Number')) <div class="error-message">{{ $errors->first('SIF_Number') }}</div> @ENDIF
                                  </td>


                          </tr>
                          <tr>

                          <th class="vcenter borderright">Time &amp; Ext<span class="required">*</span></th>
                                  <td class="vcenter">
                                      {!! Form::text('time_and_ext',(!empty($dataForView["time_and_ext"])) ? $dataForView["time_and_ext"] : date("h:i:s a") ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Time &amp; Ext*', 'readonly']); !!}
                                      @IF($errors->has('time_and_ext')) <div class="error-message">{{ $errors->first('time_and_ext') }}</div> @ENDIF
                                  </td>

                          </tr>
                        @endif

                        {{-- <tr>
                          <th class="vcenter borderright">Segment Code</th>
                                <td class="vcenter">
                                    {!! Form::text('segment',(!empty($dataForView["SegmentCode"])) ? $dataForView["SegmentCode"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Segment Code']); !!}
                                </td>

                        </tr> --}}

                        @if ($dataForView["account_type"] != 1 && $dataForView["account_type"] != 3)
                        <tr>
                                <th class="vcenter borderright">Customer DOB</th>
                                <td class="vcenter">
                                {!! Form::text('date_of_birth',(!empty($dataForView["date_of_birth"])) ? $dataForView["date_of_birth"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer DOB', 'readonly']); !!}

                                {{ Form::hidden('cb_fin_acctno', (!empty($dataForView["cb_fin_acctno"])) ? $dataForView["cb_fin_acctno"] : "") }}

                                {{ Form::hidden('card_status', (!empty($dataForView["card_status"])) ? $dataForView["card_status"] : "") }}

                                {{ Form::hidden('branch_code', (!empty($dataForView["branch_code"])) ? $dataForView["branch_code"] : "") }}
                                {{ Form::hidden('communication', (!empty($dataForView["communication"])) ? $dataForView["communication"] : "") }}
                                {{ Form::hidden('customer_nid', (!empty($dataForView["customer_nid"])) ? $dataForView["customer_nid"] : "") }}
                                {{ Form::hidden('passpor_number', (!empty($dataForView["passpor_number"])) ? $dataForView["passpor_number"] : "") }}
                                {{ Form::hidden('branchName', (!empty($dataForView["branchName"])) ? $dataForView["branchName"] : "") }}
                                </td>
                        </tr>
                        @endif

                        @if ($dataForView["account_type"] == 1 || $dataForView["account_type"] == 3)
                            {{--<tr>
                            <th class="vcenter borderright">Masked Card Number</th>
                            <td class="vcenter">
                                {!! Form::text('mask_card_no',(!empty($dataForView["mask_card_no"])) ? $dataForView["mask_card_no"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Masked Card Number']); !!}
                            </td>
                            </tr>--}}
                            <tr>
                                <th class="vcenter borderright">Card Status</th>
                                <td class="vcenter">
                                    {!! Form::text('',(!empty($dataForView["cardStatus"])) ? $dataForView["cardStatus"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Card Status', 'readonly']); !!}
                                </td>
                            </tr>

                            {{-- Hidden Fields with Table Row Structure --}}
                            <tr class="">
                                <th class="vcenter borderright">Customer DOB</th>
                                <td class="vcenter">
                                    {!! Form::text('date_of_birth',(!empty($dataForView["dob"])) ? $dataForView["dob"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Customer DOB', 'readonly']); !!}
                                </td>
                            </tr>

                            {{--<tr class="">
                                <th class="vcenter borderright">CB Fin Account No</th>
                                <td class="vcenter">
                                    {{ Form::text('cb_fin_acctno', (!empty($dataForView["cb_fin_acctno"])) ? $dataForView["cb_fin_acctno"] : "", ['class' => 'form-control']) }}
                                </td>
                            </tr>

                            <tr class="">
                                <th class="vcenter borderright">Card Status</th>
                                <td class="vcenter">
                                    {{ Form::text('card_status', (!empty($dataForView["card_status"])) ? $dataForView["card_status"] : "", ['class' => 'form-control']) }}
                                </td>
                            </tr>--}}

                            {{--<tr class="">
                                <th class="vcenter borderright">Branch Code</th>
                                <td class="vcenter">
                                    {{ Form::text('branch_code', (!empty($dataForView["branch_code"])) ? $dataForView["branch_code"] : "", ['class' => 'form-control']) }}
                                </td>
                            </tr>--}}

                            {{--<tr class="">
                                <th class="vcenter borderright">Communication</th>
                                <td class="vcenter">
                                    {{ Form::text('communication', (!empty($dataForView["communication"])) ? $dataForView["communication"] : "", ['class' => 'form-control']) }}
                                </td>
                            </tr>

                            <tr class="">
                                <th class="vcenter borderright">Customer NID</th>
                                <td class="vcenter">
                                    {{ Form::text('customer_nid', (!empty($dataForView["customer_nid"])) ? $dataForView["customer_nid"] : "", ['class' => 'form-control']) }}
                                </td>
                            </tr>

                            <tr class="">
                                <th class="vcenter borderright">Passport Number</th>
                                <td class="vcenter">
                                    {{ Form::text('passpor_number', (!empty($dataForView["passpor_number"])) ? $dataForView["passpor_number"] : "", ['class' => 'form-control']) }}
                                </td>
                            </tr>

                            <tr class="">
                                <th class="vcenter borderright">Branch Name</th>
                                <td class="vcenter">
                                    {{ Form::text('branchName', (!empty($dataForView["branchName"])) ? $dataForView["branchName"] : "", ['class' => 'form-control']) }}
                                </td>
                            </tr>--}}


                        @endif
                        {{--@if ($dataForView["account_type"] == 3)
                            <tr>
                                <th class="vcenter borderright">Account Status</th>
                                <td class="vcenter">
                                    {!! Form::text('account_status',(!empty($dataForView["account_status"])) ? $dataForView["account_status"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Account Status', 'readonly']); !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="vcenter borderright">Product Name - Description</th>
                                <td class="vcenter">
                                    {!! Form::text('product_desc',(!empty($dataForView["product_desc"])) ? $dataForView["product_desc"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Product Description', 'readonly']); !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="vcenter borderright">Inputted Masking Card No</th>
                                <td class="vcenter">
                                    {!! Form::text('inputted_masking_card',(!empty($dataForView["acc_number"])) ? $dataForView["acc_number"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Inputted Masking Card No', 'readonly']); !!}
                                </td>

                            </tr>
                            <tr>
                                <th class="vcenter borderright">Account Opening Branch</th>
                                <td class="vcenter">{!! Form::text('acc_opening_branch',(!empty($dataForView["acc_opening_branch"])) ? $dataForView["acc_opening_branch"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Account Opening Branch', 'readonly']); !!}</td>
                            </tr>
                        @endif--}}
                    </table>
                    </div>
                </fieldset>
            </div>
        </div>



    </div>
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
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inquiryModalLabel">Inquiry Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="inquiryData">
                    <div class="col-lg-6"></div>
                </div>
            </div>
        </div>
    </div>
</div>

  <script type="text/javascript">
    $(document).ready(function(){
      $(".printBtn").on('click',function(event){
        var allinfo = {_token:_token};
        // var ischecklist = 0;
        $(':input').each(function(event){
            var inputtype = $(this).prop('type');
            var notallowed = ['hidden','file','submit','button'];
            if(jQuery.inArray(inputtype, notallowed) == -1) {

            //   var tmpplaceholder = $(this).closest('td').closest('tr').prev('tr').find('th').text();
            //     if(tmpplaceholder == 'Check List'){
            //       ischecklist = 1;
            //     }
            //     if(ischecklist == 1){
            //       return;
            //     }

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
            url: "{{url('/Supports/PrintForm/complaint')}}",
            type: "POST",
            dataType: "html",
            data: allinfo,
            beforeSend: function(){
                overlay('show');
            },
            success: function(data) {
              overlay('hide');
              url = "{{url('/Supports/PrintForm/complaint')}}";
              window.open(url);
            },
            error: function(data){
              overlay('hide');
              customAlert('Error','Something went wrong. Please Contact with Administrator','red');
            }
        });
      });
      $('#complaint_type').on('change', function(){
        var complaint_id = $('#complaint_type').val();
        var type="complaint";
        var group_id= '{{user_permission()->group_info_id}}';
        if(complaint_id.length > 0){
          $.post('{{ url('workflow-attachment') }}', {_token:'{{ csrf_token() }}', complaint_id:complaint_id,type:type,group_id:group_id}, function(data){
            $('#attachment_item').html(data);

          });
        }
        else{
          //$('#attachment_item').html(null);
        }
      });
	  $('#complaint_type').on('change', function(){
        //console.log('op');
        var issue_id = $('#complaint_type').val();
        var type="complaint";
        var group_id= '{{user_permission()->group_info_id}}';
        if(issue_id.length > 0){
			//console.log('op');
          $.post('{{ url('issue-extra-form') }}', {_token:'{{ csrf_token() }}', issue_id:issue_id}, function(data){
			  //alert(data);
            $('#issue_extra').html(data);

          });
        }
        else{
          $('#issue_extra').html(null);
        }
      });


      $('#complaint_type').on('change', function(){
            //console.log('op');
            var issue_id = $('#complaint_type').val();
            var type="complaint";
            var group_id= '{{user_permission()->group_info_id}}';
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
        $('#complaint_type').on('change', function(){
            var issue_id = $('#complaint_type').val();
            if(issue_id.length > 0){
                $.ajax({
                    url: base_url+'/CIFModification/is-inquiry-api/'+ issue_id,
                    type: "GET",
                    success: function (data) {
                        if (data == 1) {
                            $("#isInquiryApi").show();
                            $('#isInquiryApi').data('id',issue_id);
                        } else {
                            $("#isInquiryApi").hide();
                            $('#isInquiryApi').data('id',null);
                        }
                    }
                });
            }
        });
        $('#isInquiryApi').on('click', function (){
            var issue_id = $(this).data('id');
            var acc_no = "{{ (!empty($dataForView["account_number"])) ? $dataForView["account_number"] : 0 }}";
            var ref_no = "{{ (!empty($dataForView["reference_number"])) ? $dataForView["reference_number"] : 0 }}";
            var cif_no = "{{ (!empty($dataForView["CIF_number"])) ? $dataForView["CIF_number"] : 0 }}";
            if(issue_id.length > 0) {
                inquiryAPI(issue_id, acc_no, ref_no, cif_no);
            }
        });
        function inquiryAPI(issue_id, acc_no, ref_no, cif_no) {
            $.ajax({
                url: base_url + '/CIFModification/inquiry/' + issue_id + '/' + acc_no + '/' + ref_no + '/' + cif_no,
                type: "GET",
                beforeSend: function () {
                    overlay('show');
                },
                success: function (response) {
                    console.log(response);
                    overlay('hide');
                    if(response.status === 1) {
                        $('#inquiryModal').modal('show');
                        var helpers = '';
                        $.each(response.data, function(key, value) {
                            helpers += '<div class="col-lg-6">'+key+'</div><div class="col-lg-6">'+value+'</div>';
                        });
                        document.getElementById('inquiryData').innerHTML = helpers;
                    } else {
                        customAlert('Error', response.data, 'red');
                    }
                },
                error: function (error) {
                    console.log(error);
                    overlay('hide');
                    customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
                }
            });
        };
        var complaint_category = $('#complaint_category').val();
        var product_type = $('#product_type').val();

        getGetComplaintOptions(complaint_category,product_type);
        $('#complaint_category').on('change', function(){
            var complaint_category = $('#complaint_category').val();
            var product_type = $('#product_type').val();
            getGetComplaintOptions(complaint_category,product_type);

        });

    function getGetComplaintOptions(complaint_category,product_type) {
        var postBackCompType = "{{$pbCompType}}";

        var complaint_id = postBackCompType;
        var type="complaint";
        var group_id= '{{user_permission()->group_info_id}}';
        if(complaint_id.length > 0){
          $.post('{{ url('workflow-attachment') }}', {_token:'{{ csrf_token() }}', complaint_id:complaint_id,type:type,group_id:group_id}, function(data){
            $('#attachment_item').html(data);

          });
        }
        //alert(base_url+'/get-category-wise-complaint/'+ product_type+'/'+ complaint_category);
        if (complaint_category) {
            $.ajax({
                url: base_url+'/get-category-wise-complaint/'+ product_type+'/'+ complaint_category,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('#complaint_type').html('<option value="">Select Complaint Type</option>');
                    $.each(data, function (key, value) {
                        var selectedForPb = "";

                        if (postBackCompType == value.id) {
                            selectedForPb = "selected";
                        }

                        $('#complaint_type').append('<option value="' + value.id + '" '+selectedForPb+' >' + value.name + '</option>');
                    });
                }
            });
        }
    };
    });
  </script>
@endsection
