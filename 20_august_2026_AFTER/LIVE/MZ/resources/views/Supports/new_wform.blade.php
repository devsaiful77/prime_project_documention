@extends('layouts.admin')
@section('content')
    <script src="{{ URL::asset('public/js/latest-v/flatpickr-4.6.13.min.js') }}"></script>

    {!! Form::open(['method'=>'post', 'action' => ['SupportsController@submitWform'] , 'enctype' => 'multipart/form-data','id' => 'mainForm',]); !!}
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
            -moz-appearance: textfield;
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

        .form-check label{
            padding: 0 !important
        }

        .manage-button {
            display: flex;
            gap: 10px;
            align-items: center;
        }
    </style>

    <div class="curved-inner-pro py-2">
        <div class="curved-ctn">
            <h2>New Service Request Form</h2>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-block">
            <strong>{{ session('error') }}</strong>
        </div>
    @endif

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
                                    @php if(Auth::user()->user_unit->subgroup_info_id==3){ @endphp
                                    <th class="vcenter">Caller ID<span class="required">*</span></th>
                                    @php }else{ @endphp
                                    <th class="vcenter">Caller ID</th>
                                    @php } @endphp
                                    <td class="vcenter">
                                        {!! Form::number('caller_id',(!empty($dataForView["caller_id"])) ? $dataForView["caller_id"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Caller ID']); !!}
                                        @IF($errors->has('caller_id'))
                                            <div class="error-message">{{ $errors->first('caller_id') }}</div>
                                        @ENDIF
                                    </td>
                                    <!-- <th>TARA/Non TARA</th>
                                    <td>
                                        {{ Form::select('is_tara', unserialize(TARATYPE), (!empty($dataForView['is_tara'])) ? $dataForView['is_tara'] : 0, ['class'=>'form-control']) }}
                                    </td> -->
                                </tr>
                                <tr>

                                    <th class="vcenter">Service Category <span class="required">*</span></th>
                                    <td class="vcenter">

                                        @inject('allServiceCategory','App\Services\UtilService')
                                        @php
                                            $serviceCategoryResult = $allServiceCategory->getServiceCategory($dataForView["account_type"]);
                                            $pBServiceCat = old('service_category');
                                        @endphp

                                        <select class="form-control wFormType select2" name="service_category" id="service_category">
                                            <option value="">Select Category</option>
                                            @foreach($serviceCategoryResult as $allSerCategory)
                                                @php
                                                    $selectedServiceCat = "";
                                                    if($pBServiceCat == $allSerCategory->id) {
                                                      $selectedServiceCat = "selected";
                                                    }
                                                @endphp
                                                <option
                                                    value="{{ $allSerCategory->id }}" {{$selectedServiceCat}}> {{ $allSerCategory->name }} </option>
                                            @endforeach
                                        </select>
                                    </td>


                                    <th class="vcenter">Service Sub Request Type<span class="required">*</span></th>
                                    <td class="vcenter">
                                        @inject('allForms','App\Services\UtilService')
                                        @php
                                            $allIssue= $allForms->getAllWformWithProd($dataForView["account_type"]);
                                        @endphp

                                        {{--{{ Form::select('w_form_type', [null=>'Please Select'] +  $allUnitItemData, (!empty($dataForView['w_form_type'])) ? $dataForView['w_form_type'] : "", ['class'=>'form-control wFormType']) }}--}}
                                        <select class="form-control wFormType select2" name="w_form_type" id="request_type">
                                            <option value="">Select Service</option>

                                            {{--@foreach($allIssue as $issues)
                                                @php
                                                    $selected = "";
                                                    if(old('w_form_type') == $issues->master_id) {
                                                      $selected = "selected";
                                                    }
                                                @endphp
                                                    old('w_form_type')
                                                  <option value="{{ $issues->master_id }}" <?php (!empty($dataForView['w_form_type'])) ? $dataForView['w_form_type'] : "" ?> {{$selected}} > {{ $issues->name }}</option>
                                            @endforeach--}}

                                        </select>
                                        @IF($errors->has('w_form_type'))
                                            <div class="error-message">{{ $errors->first('w_form_type') }}</div>
                                        @ENDIF
                                    </td>

                                </tr>

                                {{--@include('Supports/wform_extended')--}}

                                <tr id="issue_extra">
                                    @php
                                        $pbServType = "";
                                    if(!empty(old('w_form_type'))) {
                                        $service_request = $pbServType = old('w_form_type');
                                        $issue_fields = App\Services\FieldSetGroupService::getFieldSet($service_request);
                                        $check_lists = App\IssueCheckListConfig::where('issue_id',$service_request)->get();
                                        //iris field
                                        $iris_fields = App\IssueConfig::where('issue_id', $service_request)->with('fieldsetGroup')->get()->groupBy('fieldset_group_id')->toArray();
                                    }
                                    @endphp

                                    @if($pbServType == 1103)
                                        @include('partials.quota_fields', ['iris_fields' => $iris_fields, 'acc_number' => $dataForView['acc_number'], 'account_number' => $dataForView['account_number'], 'issue_id' => $pbServType])
                                    @elseif($pbServType == 1105)
                                        @include('partials.m_quota_fields', ['iris_fields' => $iris_fields, 'acc_number' => $dataForView['acc_number'], 'account_number' => $dataForView['account_number'], 'issue_id' => $pbServType])
                                    @else
                                        @include('partials.extra_form_field_with_group',['issue_id' => $pbServType])
                                    @endif

                                </tr>

                                {{-- auction form --}}
                                <tr id="auction-form-request">

                                </tr>

                                <tr id="issue_check_list">
                                    @include('partials.issue_check_list')
                                </tr>

                                {{-- attachment items --}}
                                <tr id="attachment_items_wrapper">
                                    <!-- <th>
                                        <a href="#" data-id="" class="btn btn-warning" id="isInquiryApi" style="display: none;">Inquiry API</a>
                                    </th>
                                    <td></td> -->
                                    <th class="vcenter">Attachments
                                        <div class="clearfix"></div>
                                        <small class="error-message">(Max file size is {{ $fileSizeLimit }} MB)</small></th>
                                    <td class="vcenter" id="attachment_item">
                                        {{--@include('partials.maker_attachment_item')--}}
                                        <div class="clearfix pb-2"></div>

                                        <img id="imgInWformPage" alt="W-Form image" width="100" height="100" style="display:none;" />
                                        <a id="downloadBtnWformPage" href="#" style="display:none;" download>Download File</a>

                                        {!! Form::file('file_name[]', $attributes = array(
                                            'class' => 'form-control',
                                            'label' => false,
                                            'type' => 'file',
                                            'multiple' => 'multiple',
                                            'onchange' => "
                                                var file = this.files[0];
                                                var img = document.getElementById('imgInWformPage');
                                                var downloadBtn = document.getElementById('downloadBtnWformPage');
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
                                @IF($errors->has('file_name.*'))
                                    <tr>
                                        <th colspan="3"></th>
                                        <th>
                                            <div class="error-message">{{ $errors->first('file_name.*') }}</div>
                                        </th>
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
            </div>

            {{-- Sidebar Customer Information --}}
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="table-responsive">
                    <fieldset class="scheduler-border" style="background-color: #f5f5f5">
                        <div class="scheduler-border">Customer's Information</div>
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <colgroup>
                                    <col width="40%"></col>
                                    <col width="60%"></col>
                                </colgroup>


                                {{-- <tr> <th class="vcenter">Branch Checker?</th> <td class="vcenter"> <div class="fm-checkbox"> <label class="radio-inline"> <input type="radio" name="branch_checker" class="i-checks" value="1"> <i></i> <strong>Yes</strong> </label> <label class="radio-inline"> <input type="radio" name="branch_checker" class="i-checks" value="0" checked> <i></i> <strong>No</strong> </label> </div> </td> <th class="vcenter">&nbsp;</th> <td class="vcenter"> &nbsp; </td> </tr><!-- Branch Checker --> --}}
                                @if ($dataForView["account_type"] != 1 && $dataForView["account_type"] != 3)
                                    <tr>
                                        <th class="vcenter borderright">Account/ID No</th>
                                        <td class="vcenter">
                                            {{-- {!! Form::text('account_number',(!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'autofocus'=>'true', 'placeholder'=>'Account/ID No', 'readonly']);!!} --}}
                                            {!! Form::text('account_number',(!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' ,['class' => 'form-control', 'id' => 'account_number', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'autofocus'=>'true', 'placeholder'=>'Account/ID No', 'readonly']);!!}
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
                                    {{--<tr>
                                        <th class="vcenter borderright">Account/ID No</th>
                                        <td class="vcenter">
                                            {!! Form::text('account_number',(!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'autofocus'=>'true', 'placeholder'=>'Account/ID No', 'readonly']);!!}
                                            @IF($errors->has('account_number')) <div class="error-message">{{ $errors->first('account_number') }}</div> @ENDIF
                                        </td>
                                    </tr>--}}
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
                                        {!! Form::text('segment',(!empty($dataForView["SegmentCode"])) ? $dataForView["SegmentCode"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Segment Code', 'readonly']); !!}
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
                                            {!! Form::text('cc_card_status',(!empty($dataForView["cardStatus"])) ? $dataForView["cardStatus"] : "" ,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Card Status', 'readonly']); !!}
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


        {{-- manage button start --}}
        <div class="manage-button">
            <?php
            $dataForView["active_tab"] = "wform";
            $additionalParams = (!empty($dataForView)) ? '?' . http_build_query($dataForView) : "";
            ?>
            {{ Form::hidden('additionalParams',$additionalParams) }}

            @if($dataForView["account_type"] == 1)
                {{--pr($dataForView)--}}
                @if($dataForView['card_status'] == 'C' || $dataForView['card_status'] == 'I' || $dataForView['card_status'] == 'S' || (empty($dataForView['card_status'])))
                    {!!
                    Form::submit((!empty($id)) ? 'Update':'Submit',array(
                    'class'=>'btn btn-primary gradient irisCheck',
                    'title'=>'Add',
                    'onclick'=>"overlay('show');",
                    'escape'=>false
                    ));
                    !!}
                @else
                    <div class="form-group">
                        <div class="input-group">
                            <div class="alert alert-danger">Card Status is
                                "{{ (!empty($dataForView['card_status']))? $dataForView['card_status'] : "Blank" }}". This
                                request is not allowed to Log.!!!
                            </div>
                        </div>
                    </div>
                @endif

            @else
                {!!
                Form::submit((!empty($id)) ? 'Update':'Submit',array(
                    'class'=>'btn btn-primary gradient irisCheck',
                    'title'=>'Add',
                    'onclick'=>"overlay('show');",
                    'escape'=>false
                ));
                !!}

            @endif

            <?php $closeUrl = url('/Supports/home'); ?>
            <a href="{{$closeUrl}}" class="btn btn-danger gradient">Close</a>
            <button class="btn btn-success printBtn" type="button">Print</button>

            <span class="btn btn-info printBpIdBtn d-none" type="button">BP ID</span>
            <button class="btn btn-info printAuctionRequestBtn d-none" type="button">Auction Request</button>
        </div>
        {{-- manage button  --}}



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

    <div class="loading hidden">
        <div class='uil-ring-css' style='transform:scale(0.79);'>
            <div></div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#BPID_fourth_applicant, #BPID_third_applicant, #BPID_second_applicant').hide();
            $('#BPID_second_nominee, #BPID_third_nominee, #BPID_fourth_nominee').hide();
        });

        $(document).ready(function () {
            var account_number = "{{ !empty(old('account_number')) ? old('account_number') : '' }}";

            if (account_number){
                getBpIdwithTreasury(account_number);
            }

            $(".printBtn").on('click', function (event) {
                var allinfo = {_token: _token};
                // var ischecklist = 0;
                $(':input').each(function (event) {
                    var inputtype = $(this).prop('type');
                    var notallowed = ['hidden', 'file', 'submit', 'button'];

                    if (jQuery.inArray(inputtype, notallowed) == -1) {
                        // var tmpplaceholder = $(this).closest('td').closest('tr').prev('tr').find('th').text();

                        // if (tmpplaceholder === 'Check List') {
                        //     return true;
                        // }

                        //console.log(tmpplaceholder);
                        var value = $(this).val();
                        value = (value) ? value : 'N/A';

                        if (inputtype == 'checkbox' || inputtype == 'radio') {
                            if ($(this).is(":checked")) {
                                value = $(this).val();
                            } else {
                                value = 'No';
                            }
                        }
                        if (inputtype == 'select-one' || inputtype == 'select-multiple') {
                            value = $(this).find('option:selected').text();
                            if (value == 'Select') {
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
                    beforeSend: function () {
                        overlay('show');
                    },
                    success: function (data) {
                        overlay('hide');
                        url = "{{url('/Supports/PrintForm/service')}}";
                        window.open(url);
                    },
                    error: function (data) {
                        overlay('hide');
                        customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
                    }
                });
            });

            $('#request_type').on('change', function () {
                var issue_id = $('#request_type').val();
                var type = "wform";
                var group_id = '{{user_permission()->group_info_id}}'
                if (issue_id.length > 0) {
                    $.post('{{ url('workflow-attachment') }}', {
                        _token: '{{ csrf_token() }}',
                        complaint_id: issue_id,
                        type: type,
                        group_id: group_id
                    }, function (data) {
                        // console.log(data)
                        $('#attachment_item').html(data);

                    });
                } else {
                    //$('#attachment_item').html(null);
                }
            });

            $('#request_type').on('change', function () {
                localStorage.removeItem('secondNomineeVisible');
                $('.irisCheck').prop('disabled', false);
                var issue_id = $('#request_type').val();
                var type = "wform";
                var group_id = '{{user_permission()->group_info_id}}';
                let account_number = '{{$dataForView['account_number']}}';
                let acc_number = '{{$dataForView['acc_number']}}';
                overlay('show');
                if (issue_id.length > 0) {
                    $.post('{{ url('issue-extra-form') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        acc_number: acc_number,
                        account_number: account_number,
                    }, function (data) {
                        if (issue_id == 1103 || issue_id == 1105){
                            $('.irisCheck').prop('disabled', true);
                        }

                        //alert(data);
                        $('#issue_extra').html(data);

                        //$('#BPID_fourth_applicant, #BPID_third_applicant, #BPID_second_applicant, #BPID_second_nominee, #BPID_third_nominee, #BPID_fourth_nominee').hide();


                        $('.manipulate-error-msg').text('Note: Please first click manipulate data Button');
                        $('#quotaSelection').addClass('hidden');
                        $('.passport-input').prop('disabled', true);
                        $('.current-input').prop('disabled', true);
                        $('.mq-input').prop('disabled', true);
                        $('.next-input').prop('disabled', true);
                        // auction form request
                        if(issue_id ==  1449){
                            getBpIdwithTreasury(account_number);
                        }
                        onChangeRequestType(issue_id);
                        setTimeout(function() {
                            overlay('hide');
                        }, 900);
                    });
                } else {
                    $('#issue_extra').html(null);
                    overlay('hide');
                }
            });


            // Auction get treasury bill and bound
            function getBpIdwithTreasury(account_number){
                $.post('{{ url('issue-bpid-with-treasury') }}', {
                        _token: '{{ csrf_token() }}',
                        account_number: account_number,
                    }, function (data) {
                    // Bind Bpid Data
                    let bpid = data.bpId;
                    $('#bpId').val(bpid.bp_id);
                    $('#accountNumber').val(bpid.account_number);
                    $('#branchName').val(bpid.branch_name);
                    $('#accountTitle').val(bpid.account_title);
                    $('#firstAppMobile').val(bpid.contact_no_1);
                    $('#firstAppEmail').val(bpid.email_1);

                    $('#secondAppMobile').val(bpid.contact_no_2);
                    $('#secondAppEmail').val(bpid.email_2);

                    $('#thirdAppMobile').val(bpid.contact_no_3);
                    $('#thirdAppEmail').val(bpid.email_3);

                    $('#fourthAppMobile').val(bpid.contact_no_4);
                    $('#fourthAppEmail').val(bpid.email_4);


                });
            }


            $('#request_type').on('change', function () {
                //console.log('op');
                var issue_id = $('#request_type').val();
                var type = "wform";
                var group_id = '{{user_permission()->group_info_id}}';
                if (issue_id.length > 0) {
                    $.post('{{ url('issue-check-list') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id
                    }, function (data) {
                        //alert(data);
                        $('#issue_check_list').html(data);



                    });
                } else {
                    $('#issue_check_list').html(null);
                }
            });




            var service_category = $('#service_category').val();
            var product_type = $('#product_type').val();
            getGetComplaintOptions(service_category, product_type);
            $('#service_category').on('change', function () {
                var service_category = $('#service_category').val();
                var product_type = $('#product_type').val();
                getGetComplaintOptions(service_category, product_type);
            });

            function getGetComplaintOptions(service_category, product_type) {
                var issue_id = "{{ old('w_form_type') }}";
                var type = "wform";
                var group_id = '{{user_permission()->group_info_id}}'
                if (issue_id.length > 0) {
                    $.post('{{ url('workflow-attachment') }}', {
                        _token: '{{ csrf_token() }}',
                        complaint_id: issue_id,
                        type: type,
                        group_id: group_id
                    }, function (data) {
                        $('#attachment_item').html(data);

                    });
                }
                if (issue_id.length > 0) {
                    $.ajax({
                        url: base_url + '/CIFModification/is-inquiry-api/' + issue_id,
                        type: "GET",
                        success: function (data) {
                            if (data == 1) {
                                $("#isInquiryApi").show();
                                $('#isInquiryApi').data('id', issue_id);
                            } else {
                                $("#isInquiryApi").hide();
                                $('#isInquiryApi').data('id', null);
                            }
                        }
                    });
                }
                if (service_category) {
                    $.ajax({
                        url: base_url + '/get-category-wise-service/' + product_type + '/' + service_category,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#request_type').html('<option value="">Select Service Type</option>');
                            $.each(data, function (key, value) {
                                var selectedForPb = "";

                                if (issue_id == value.id) {
                                    selectedForPb = "selected";
                                }

                                $('#request_type').append('<option value="' + value.id + '" ' + selectedForPb + ' >' + value.name + '</option>');
                            });
                        }
                    });
                }
            }

            $('#isInquiryApi').on('click', function () {
                var ref_no = null;
                var issue_id = $(this).data('id');
                var acc_no = "{{ (!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' }}";
                var cif_no = "{{ (!empty($dataForView["CIF_number"])) ? $dataForView["CIF_number"] : '' }}";
                if (!issue_id) {
                    customAlert('Service Request not found', 'Please choose Service Sub Request Type', 'red');
                } else if (!cif_no) {
                    customAlert('Error', 'Customer number not found', 'red');
                } else if (!acc_no) {
                    customAlert('Error', 'Account number not found', 'red');
                } else {
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
                        // console.log(response);
                        overlay('hide');
                        if (response.status === 1) {
                            if ($.isEmptyObject(response.data)) {
                                customAlert('Error', 'Inquiry data not found', 'red');
                            } else {
                                $('#inquiryModal').modal('show');
                                var helpers = '';
                                if (response.data.lienenqury) {
                                    var lienresponse = response.data.lienenqury;
                                    delete response.data.lienenqury;
                                    if (lienresponse.length > 0) {
                                        for (var i = 0; i < lienresponse.length; i++) {
                                            $.each(lienresponse[i], function (key, value) {
                                                helpers += '<div class="col-lg-6">' + key + '</div><div class="col-lg-6">' + value + '</div>';
                                            });
                                            helpers += '<div class="col-lg-12">&nbsp;</div>';
                                        }
                                    }
                                }
                                $.each(response.data, function (key, value) {
                                    helpers += '<div class="col-lg-6">' + key + '</div><div class="col-lg-6">' + value + '</div>';
                                });
                                document.getElementById('inquiryData').innerHTML = helpers;
                            }
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
            }
        });

        let issue_id = "{{ old('w_form_type') }}";
        onChangeRequestType(issue_id);

        /* Conditional Field Script Start */

        let conditional_value = "{{ old('conditional_value') }}";
        let value = $('.DependantFields').val();
        let id = $('.DependantFields').data('id');

        $(document.body).on('change', '.DependantFields', function() {
            overlay('show');
            let issue_id = $('#request_type').val();
            let value = $(this).val();
            let id = $(this).data('id');
            if (value && value.length > 0) {
                // console.log(1)
                issueDependantFields(issue_id,value,id);
            } else {
                // console.log(2)
                issueDependant(issue_id,id)
            }
        });


        function applyRequestTypeUI() {
            let sub_request_id = $('#request_type').val() || issue_id;
            onChangeRequestType(sub_request_id);

            // 1. Attachment hide/show
            if (sub_request_id === "1449" || sub_request_id === "1450") {
                $('#attachment_items_wrapper').hide();
            } else {
                $('#attachment_items_wrapper').show();
            }

            // 2. Button mapping
            const btnMap = {
                '1450': '.printBpIdBtn',
                '1449': '.printAuctionRequestBtn',
            };

            // hide all buttons
            $('.printBpIdBtn, .printAuctionRequestBtn')
                .addClass('d-none')
                .removeClass('d-block');

            // show matched
            if (btnMap[sub_request_id]) {
                $(btnMap[sub_request_id])
                    .removeClass('d-none')
                    .addClass('d-block');
            }
        }

        // Change event
        window.onload = function () {
            applyRequestTypeUI();
        };

        // also handle change
        $(document).on('change', '#request_type', applyRequestTypeUI);



        function onChangeRequestType(issue_id) {
            if (issue_id) {
                $.ajax({
                    url: "{{ url('/issue/conditional') }}"+"/"+issue_id,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $.each(response, function (key, val) {
                            let value = val.field_name;
                            if (conditional_value) {
                                let valueArray = conditional_value.split(',');

                                if (valueArray.includes(value)) {
                                    $('.' + value).show();
                                } else {
                                    $('.' + value).hide();
                                }
                            } else {
                                $('.' + value).hide();
                            }
                        });
                    }
                });
            }
        }

        function issueDependantFields(issue_id,value,id) {
            if (issue_id,value,id) {
                $.ajax({
                    url: "{{ url('/issue/dependant/fields') }}"+"/"+issue_id+"/"+value+"/"+id,
                    type: "GET",
                    dataType: "json",
                    success: function (dependantResponse) {
                        if (dependantResponse && dependantResponse.length > 0) {
                            $.ajax({
                                url: "{{ url('/issue/conditional/fields') }}/" + issue_id + "/" + id,
                                type: "GET",
                                dataType: "json",
                                success: function (conditionalResponse) {
                                    // console.log('Conditionals:', conditionalResponse);
                                    overlay('hide');
                                    // Extract field names
                                    let dependantFields = dependantResponse.map(item => item.field_name);

                                    $.each(conditionalResponse, function (key, val) {
                                        let field = val.field_name;
                                        if (dependantFields.includes(field)) {
                                            $('.' + field).show();
                                            addValueToHiddenInput(field);
                                        } else {
                                            removeValueFromHiddenInput(field);
                                            $('.' + field).hide();
                                        }
                                    });
                                },
                                error: function () {
                                    overlay('hide');
                                    console.error("Failed to fetch conditional fields.");
                                }
                            });
                        } else {
                            // console.log(90)
                            overlay('hide');
                            issueDependant(issue_id,id)
                        }
                    }
                });
            }
        }

        function issueDependant(issue_id,id) {
            if (issue_id,id) {
                $.ajax({
                    url: "{{ url('/issue/conditional/fields') }}"+"/"+issue_id+"/"+id,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        overlay('hide');
                        $.each(response, function (key, val) {
                            let value = val.field_name;
                            $('.'+value+'').hide();
                            removeValueFromHiddenInput(value);
                        });
                    }
                });
            }
        }

        function addValueToHiddenInput(newValue) {
            let input = $('#conditionalHidden');
            let current = input.val().split(',').filter(v => v); // existing values, removing empty

            if (!current.includes(newValue)) {
                current.push(newValue);
                input.val(current.join(','));
            }
        }

        function removeValueFromHiddenInput(valueToRemove) {
            let input = $('#conditionalHidden');
            let current = input.val().split(',').filter(v => v); // remove empty strings

            current = current.filter(v => v !== valueToRemove);
            input.val(current.join(','));
        }

        /*Script End*/

    </script>


    {{-- BPId and auction form request --}}
    <script>
        $(function () {
            const $form = $('#mainForm');
            const originalAction = $form.attr('action');
            // Auction Form Request
            $(".printAuctionRequestBtn").on('click', function (e) {
                e.preventDefault();

                let form = $('#mainForm');

                let fields = {
                    //bp_id: "BP ID is required.",
                    account_number: "Account Number is required.",
                    branch_name: "Branch Name is required.",
                    account_title: "Account Title is required.",
                    treasury_type: "Treasury Type is required.",
                    bidding_month: "Bidding Month is required.",
                    bidding_date: "Bidding Date is required.",
                    bidding_amount: "Bidding Amount is required.",
                    bidding_type: "Bidding Type is required.",
                };

                for (const key in fields) {
                    // Check input first
                    let value = form.find(`input[name='${key}']`).val()?.trim();
                    if (!value) {
                        value = form.find(`select[name='${key}']`).val();
                    }
                    if (!value) {
                        alert(fields[key]);
                        return false;
                    }
                }
                form.attr('target', '_blank');
                form.attr('action', "{{ url('/Supports/printAuctionRequestBtn/service') }}");
                form.submit(); // send request directly
                // reset to original (important)
                form.removeAttr('target');
                form.attr('action', originalAction);
            });

            // BPID
            $('.printBpIdBtn').on('click', function(e){
                e.preventDefault();


                // Get values
                var applicantCount = $('#applicantCount').val() || 0;
                var nomineeCount = $('#nomineeCount').val() || 0;

                // Convert to integer
                applicantCount = parseInt(applicantCount);
                nomineeCount = parseInt(nomineeCount);

                // Check conditions
                if (applicantCount <= 0 || nomineeCount <= 0) {
                    alert('Please fill applicant and nominee information first.');
                    return false;
                }

                // Check minimum requirements
                if (applicantCount < 1) {
                    alert('At least 1 applicant is required.');
                    return false;
                }

                if (nomineeCount < 1) {
                    alert('At least 1 nominee is required.');
                    return false;
                }

                $form.attr('target', '_blank');
                $form.attr('action', "{{ url('/Supports/printBpIdBtn/service') }}");
                $form.submit();

                // reset to original (important)
                $form.removeAttr('target');
                $form.attr('action', originalAction);
            });

            $('.irisCheck').on('click', function(){
                // ensure normal submit uses original action and no target
                $form.removeAttr('target');
                $form.attr('action', originalAction);
            });

        });
    </script>


    <script>
        // Auction form competitve rate validation
        $(document).on('input', '#competitiveRate', function () {
            const num = Math.min(14, this.value.replace(/\D/g, '') || 0);
            this.value = num;
        });

        // Nominee Percentage Validation START
        function validateNomineePercentage() {
            const count = +$('#nomineeCount').val() || 1;
            const $p1 = $('#percentagePayable');
            const $p2 = $('#secPercentagePayable');

            let p1 = Math.min(+($p1.val() || 0), 100);
            let p2 = Math.min(+($p2.val() || 0), 100);

            $p1.val(p1);
            if (count === 1) {
                $p2.val('');
                return;
            }
            const maxP2 = 100 - p1;
            if (p2 > maxP2) p2 = Math.max(maxP2, 0);
            $p2.val(p2);
        }

        // Numbers only
        $(document).on('input', '#percentagePayable, #secPercentagePayable', function () {
            this.value = this.value.replace(/\D/g, '');
        });

        // Recalculate
        $(document).on('input change', '#percentagePayable, #secPercentagePayable, #nomineeCount', validateNomineePercentage);
        // Nominee Percentage Validation END

    </script>




@endsection

