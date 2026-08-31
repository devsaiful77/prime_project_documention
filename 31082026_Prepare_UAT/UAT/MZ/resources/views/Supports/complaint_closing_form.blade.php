<style>

    .select2-dropdown.select2-dropdown--below, .select2.select2-container {
        width: 100% !important;
    }
    .select2-dropdown.select2-dropdown--below {
        width: 25rem !important;
    }
</style>
<fieldset class="scheduler-border">
    <div class="scheduler-border">
        <a class="colla" data-bs-toggle="collapse" data-bs-target="#collapseClosingForm" aria-expanded="true" aria-controls="collapseClosingForm" style="cursor: pointer; font-weight: bold; color:#ffffff;">
            Complaint Closing Form <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>

	<form action="{{ url('Supports/complaintClosingSubmit/'.encrypt($dataForView['reference_number'])) }}" enctype='multipart/form-data' method="post">
		@csrf
        {{ Form::hidden('mobile_number', $dataForView['mobile_number']) }}
        {{ Form::hidden('email_address', $dataForView['email_address']) }}
		<div class="table-responsive collapse" id="collapseClosingForm">
			<table class="table table-bordered table-condensed">
				<colgroup>
					<col width="15%">
					<col width="35%">
					<col width="15%">
					<col width="35%">
				</colgroup>
                <tr>
                    <th class="vcenter">Complaint Category  <span class="required">*</span></th>
                    <td class="vcenter">
                        @inject('allComplaintCategory','App\Services\UtilService')
                        @php
                            $complaintCategoryResult = $allComplaintCategory->getComplaintCategory($dataForView["account_type"]);
                            // $pBComplaintCat = old('complaint_category');
                            // $pBComplaintCat = $complaintClosingData->complaint_category;
                            $pBComplaintCat = old('complaint_category', $complaintClosingData->complaint_category ?? null);
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
			@IF($errors->has('complaint_category')) <div class="error-message">{{ $errors->first('complaint_category') }}</div> @ENDIF
                    </td>
                    <th class="vcenter">Complaint Sub Category<span class="required">*</span></th>
                    <td class="vcenter">
                        @inject('allForms','App\Services\UtilService')
                        @php
                            //$allIssue= $allForms->getAllComplaintWithProd($dataForView["account_type"]);
                            //$selectData = $complaintClosingData->complaint_type;
                          $allIssue = $allForms->getAllComplaintWithProd($dataForView["account_type"] ?? null);
                          $selectData = old('complaint_type', $complaintClosingData->complaint_type ?? null);                        @endphp
                        <select class="form-control wFormType" name="complaint_type" id="complaint_type">
                            <option value="">Select Complaint</option>
                        </select>
                        @IF($errors->has('complaint_type')) <div class="error-message">{{ $errors->first('complaint_type') }}</div> @ENDIF
                    </td>
                </tr>
                <tr id="issue_extra">
                    @php
                        $pbCompType="";
                    @endphp
                </tr>
                <tr id="issue_check_list">
                </tr>
                <select class="form-control" style="display: none;" name="product_type" id="product_type">
                    @inject('product_type','App\Services\ProductTypeService')
                    {!! $product_type->getProductTypeByID(old($dataForView["account_type"],(!empty($dataForView["account_type"])) ? $dataForView["account_type"] : '')) !!}
                    @IF($errors->has('product_type')) <div class="error-message">{{ $errors->first('product_type') }}</div> @ENDIF
                </select>

				{{-- <tr>
					<th>Responsible Branch/ Department name<span class="required">*</span></th>
					<td>
						@inject('allSubGroup','App\Services\UtilService')
	                    @php
                            $allSubGroupList = $allSubGroup->getAllSubGroupList();
                            if(!empty($complaintClosingData->subgroup_id)){
                                $pBSubGroup = explode(',',$complaintClosingData->subgroup_id);
                            } else {
                                $pBSubGroup = [];
                            }
                            $selectedsub = "";
                        @endphp
                        <select name="subgroup_id[]" class="form-control subgroup_select newselect2" multiple>
                            @foreach($allSubGroupList as $key => $value)
                                <option value="{{ $key }}"
                                @foreach($pBSubGroup as $k => $val)
                                    @if ($key == $val)
                                       selected
                                    @endif
                                @endforeach
                                >{{ $value }}</option>
                            @endforeach
                        </select>
                        @IF($errors->has('subgroup_id')) <div class="error-message">{{ $errors->first('subgroup_id') }}</div> @ENDIF
					</td>
					<th>Responsible employee name</th>
					<td>
						{!! Form::text('emplist',(!empty($complaintClosingData->emplist)) ? $complaintClosingData->emplist : old('emplist') ,['class' => 'form-control', 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Responsible Employee Name']); !!}
					</td>
				</tr> --}}

                <tr>
                    <th>Responsible Branch/Department Name <span class="text-danger">*</span></th>
                    <td>
                        @inject('allSubGroup','App\Services\UtilService')
                        @php
                            $allSubGroupList = $allSubGroup->getAllSubGroupList();
                            $pBSubGroup = !empty($complaintClosingData->subgroup_id) ? explode(',', $complaintClosingData->subgroup_id) : [];
                        @endphp

                        <select name="subgroup_id[]" id="subgroup_select" class="form-control select2" multiple>
                            @foreach($allSubGroupList as $key => $value)
                                <option value="{{ $key }}" {{ in_array($key, $pBSubGroup) ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>

                        @if($errors->has('subgroup_id'))
                            <div class="error-message">{{ $errors->first('subgroup_id') }}</div>
                        @endif
                    </td>

                    <th>Responsible Employee Name</th>
                    <td>
                        {!! Form::text('emplist', !empty($complaintClosingData->emplist) ? $complaintClosingData->emplist : old('emplist'), ['class' => 'form-control', 'autocomplete'=>'off', 'type'=>'text', 'placeholder'=>'Responsible Employee Name']) !!}
                    </td>
                </tr>

                <tr>
                    <th>FI ID<span class="required">*</span></th>
                    <td>
                        {!! Form::select('fi_id',
                            $fiIds->mapWithKeys(function($item) {
                                $val = $item->branch_name . ' - ' . $item->sbs_code;
                                return [$val => $val];
                            }),
                            !empty($complaintClosingData->fi_id) ? $complaintClosingData->fi_id : old('fi_id'),
                            [
                                'class' => 'form-control',
                                'placeholder' => 'Please Select'
                            ]
                        ) !!}
                        @if ($errors->has('fi_id')) <div class="error-message">{{ $errors->first('fi_id') }}</div> @endif

                            {{--{!!
                              Form::text('fi_id',(!empty($complaintClosingData->fi_id)) ? $complaintClosingData->fi_id : old('fi_id') ,[
                                'class' => 'form-control',
                                'autocomplete'=>'off',
                                'type'=>'text',
                                'placeholder'=>'FI Id'
                              ]);
                            !!}
                            @IF($errors->has('fi_id')) <div class="error-message">{{ $errors->first('fi_id') }}</div> @ENDIF--}}

                        {{-- @php
                            if(!empty($complaintClosingData->fi_id)){
                                $pBfiid = explode(',',$complaintClosingData->fi_id);
                            } else {
                                $pBfiid = [];
                            }
                            $selectedsu = "";
                        @endphp
                        <select name="fi_id[]" class="form-control newselect2" multiple>
                            @foreach($allBranchData as $key => $value)
                                <option value="{{ $key }}"
                                @foreach($pBfiid as $k => $val)
                                    @if ($key == $val)
                                        selected
                                    @endif
                                @endforeach
                                >{{ $value }}</option>
                            @endforeach
                        </select>
                        @IF($errors->has('fi_id')) <div class="error-message">{{ $errors->first('fi_id') }}</div> @ENDIF --}}
                    </td>
                    <th>Root cause of the complaint<span class="required">*</span></th>
                    <td>
                        {!!
                        Form::textarea('rootcause',(!empty($complaintClosingData->rootcause)) ? $complaintClosingData->rootcause : old('rootcause') ,[
                            'rows'=>3,
                            'class' => 'form-control',
                            'label'=>false,
                            'autocomplete'=>'off',
                            'placeholder'=>'Root Cause'
                        ]);
                        !!}
                        @IF($errors->has('rootcause')) <div class="error-message">{{ $errors->first('rootcause') }}</div> @ENDIF

                    </td>
                </tr>
				<tr>
					<th>Action taken<span class="required">*</span></th>
					<td>
						{!!
                          Form::text('actiontaken',(!empty($complaintClosingData->actiontaken)) ? $complaintClosingData->actiontaken : old('actiontaken') ,[
                            'class' => 'form-control',
                            'autocomplete'=>'off',
                            'type'=>'text',
                            'placeholder'=>'Action Taken'
                          ]);
                        !!}
                        @IF($errors->has('actiontaken')) <div class="error-message">{{ $errors->first('actiontaken') }}</div> @ENDIF
                    </td>
                    <th>Amount Involved</th>
                    <td>
                        {!!
                  Form::text('amountinvoled',(!empty($complaintClosingData->amountinvoled)) ? $complaintClosingData->amountinvoled : 0 ,[
                    'class' => 'form-control intNumber',
                    'autocomplete'=>'off',
                    'type'=>'text',
                    'placeholder'=>'Amount Involved'
                  ]);
                !!}
                    </td>
				</tr>
				<tr>
					<th>Complaint Justification<span class="required">*</span></th>
					<td>
						@php
							$compJustification = array('Yes'=>'Yes','No'=>'No','Bangladesh Bank- Yes'=>'Bangladesh Bank- Yes','Bangladesh Bank- No'=>'Bangladesh Bank- No')
						@endphp
						{{ Form::select('justification', [''=>'Please Select']+$compJustification ,(!empty($complaintClosingData->justification)) ? $complaintClosingData->justification : old('justification'), ['class'=>'form-control']) }}
                        @IF($errors->has('justification')) <div class="error-message">{{ $errors->first('justification') }}</div> @ENDIF
                    </td>
                    <th>Mass incident</th>
                    <td>
                        @php
                            $massIncident = array('Fraudulent Activities'=>'Fraudulent Activities','Mass incident'=>'Mass incident','Discrepancy'=>'Discrepancy','Charge waiver'=>'Charge waiver','Dispute'=>'Dispute','Bangladesh bank letter'=>'Bangladesh bank letter','DMD/CEO’s letter'=>'DMD/CEO’s letter')
                        @endphp
                        {{ Form::select('massincident', [null=>'Please Select'] +  $massIncident,(!empty($complaintClosingData->massincident)) ? $complaintClosingData->massincident : old('massincident'), ['class'=>'form-control massincidentlist' ]) }}

                    </td>
				</tr>
				<tr>
					<th><span class="massIncident"> Number of Impacted Customer </span></th>
					<td>
						{!!
                          Form::text('impactedcustomer',(!empty($complaintClosingData->impactedcustomer)) ? $complaintClosingData->impactedcustomer : old('impactedcustomer') ,[
                            'class' => 'form-control massIncident intNumber',
                            'autocomplete'=>'off',
                            'type'=>'text',
                            'placeholder'=>'Impacted Customer'
                          ]);
                        !!}
					</td>
                    <th>Send closure Notification<span class="required">*</span></th>
                    <td>
                        {{ Form::select('closenotification', [null=>'Please Select'] +  UNSERIALIZE(CONFIRMATION),(!empty($complaintClosingData->closenotification)) ? $complaintClosingData->closenotification : old('closenotification'), ['class'=>'form-control' ]) }}
                        @IF($errors->has('closenotification')) <div class="error-message">{{ $errors->first('closenotification') }}</div> @ENDIF
                    </td>
				</tr>
				<tr>
					<th>Closure remarks</th>
					<td>
						{!!
						Form::textarea('closureremarks',(!empty($complaintClosingData->closureremarks)) ? $complaintClosingData->closureremarks : old('closureremarks') ,[
							'rows'=>3,
							'class' => 'form-control',
							'label'=>false,
							'autocomplete'=>'off',
							'placeholder'=>'Closure remarks'
						]);
						!!}
					</td>
                    <th>Customer expectation</th>
                    <td>
                        {!!
                        Form::textarea('customerexpectation',(!empty($complaintClosingData->customerexpectation)) ? $complaintClosingData->customerexpectation : old('customerexpectation') ,[
                            'rows'=>3,
                            'class' => 'form-control',
                            'label'=>false,
                            'autocomplete'=>'off',
                            'placeholder'=>'Customer expectation'
                        ]);
                        !!}
                    </td>
				</tr>
				<tr>
					<th>Nature of Complaint<span class="required">*</span></th>
					<td>
                        <select class="form-control" name="natureofcomp">
                            <option> General Banking </option>
                            <option> Loans And Advantages </option>
                            <option> Mobile Banking </option>
                            <option> Internet Banking </option>
                            <option> Remittaance </option>
                            <option> Import Bill (Local) </option>
                            <option> Import Bill (Forigen) </option>
                            <option> Export Related </option>
                            <option> Bank Guarantee </option>
                            <option> Miscellaneous </option>
                        </select>

                        @IF($errors->has('natureofcomp')) <div class="error-message">{{ $errors->first('natureofcomp') }}</div> @ENDIF
                    </td>
                    {{-- @php
                        $file_size = \Illuminate\Support\Facades\DB::table('settings')->select('attachment_size')->first();
                        $attachment_size = !empty($file_size->attachment_size) ? $file_size->attachment_size : 2;
                    @endphp --}}
                    <th>Attach New File <small class="error-message">(Max file size is {{ $fileSizeLimit }} MB)</small></th>
                    <td>
                        <input type="file" name="file_name[]" class="form-control" multiple>
                        @if($errors->has('file_name.*'))
                        <div class="error-message">
                            {{ $errors->first('file_name.*') }}
                        </div>
                        @endif
                    </td>

                </tr>
                <tr>
                    <td></td>
                    <td>
                        @php
                            if(!empty($complaintClosingData->unreachable) && $complaintClosingData->unreachable == 'Yes'){
                                $chec = 'checked';
                            } else {
                                $chec = '';
                            }
                        @endphp
                        <div class="form-control">
                            <b style="color:red">Send unreachable sms & email notification ?&nbsp;&nbsp;</b>
                            <label class="form-check-label">
                                <input type="checkbox" name="unreachable" value="1" class="closenotify"
                                    {{ $chec }}/>
                            </label>
                        </div>
                    </td>
                    <td>
                        <button type="submit" name="action" value="close" class="btn btn-success btn-block">
                            <i class="fa fa-check"></i>Close
                        </button>
                        <button type="submit" name="action" value="save_hold" class="btn btn-info"> Save
                        </button>
                    </td>
                    <td>

                    </td>
                </tr>
			</table>
		</div>
	</form>
</fieldset>


<script>
    $(document).ready(function() {
        $('#subgroup_select').select2({
            placeholder: "Select Responsible Branch/Department",
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
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
