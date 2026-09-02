@extends('layouts.admin')
@section('content')

<style type="text/css">
.wordwrap {
  	word-wrap: break-word !important;
    word-break: break-all !important;
    white-space: normal !important;
}
</style>
<div class="row">
	<div class="panel panel-primary">
		<div class="panel-heading">
	        <div class="panel-title">
	            Report
	        </div>
	    </div>
	    <div class="panel-body" style="padding-bottom: 0">
			{!! Form::open(['method'=>'get', 'class'=>'form-horizontal', 'action' => ['ReportsController@index'] , 'enctype' => 'multipart/form-data']); !!}
				<div class="col-md-3">
			    	<div class="form-group">
			    		<label>Report Type</label>
			    		{{ Form::select('report_type', [null=>'Please Select'] + $reportType, (!empty($searchDataForView["report_type"])) ? $searchDataForView["report_type"] : '', ['class'=>'form-control report-type']) }}
				    </div>
			    </div>
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<label>Account/Card/ID Number</label>
				        <input type="text" name="account_number" class="form-control acc-numb" placeholder="Account/Card/ID Number" value="{{ $searchDataForView['account_number'] }}">
				    </div>
			    </div>
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<label>Ticket Number</label>
				        <input type="text" name="reference_number" class="form-control tckt-numb" placeholder="Ticket Number" value="{{ $searchDataForView['reference_number'] }}">
				    </div>
			    </div>
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<label>Mobile Number</label>
				        <input type="text" name="mobile_number" class="form-control mob-numb" placeholder="Mobile Number" value="{{ $searchDataForView['mobile_number'] }}">
				    </div>
			    </div>

				<div class="col-md-3">
			    	<div class="form-group">
				    	<label>Form Type</label>
				    	{{ Form::select('form_type', [null=>'Select Type'] + $reportCategory, (!empty($searchDataForView["form_type"])) ? $searchDataForView["form_type"] : 'wform', ['class'=>'form-control form_type']) }}
				    </div>
			    </div>
			    <div class="col-md-3">
			    	<div class="form-group">
				    	<label>Type</label>
				    	{{ Form::select('form_category', [null=>'Select Type'], (!empty($searchDataForView["form_category"])) ? $searchDataForView["form_category"] : '', ['class'=>'form-control cat']) }}

				    	{{ Form::select('form_categoryw', [null=>'Select Type']+$allWformUnitData, (!empty($searchDataForView["form_categoryw"])) ? $searchDataForView["form_categoryw"] : '', ['class'=>'form-control catwform','style'=>'display:none;']) }}

				    	{{ Form::select('form_categoryc', [null=>'Select Type']+$allComplaintUnitData, (!empty($searchDataForView["form_categoryc"])) ? $searchDataForView["form_categoryc"] : '', ['class'=>'form-control catcomplaint','style'=>'display:none;']) }}
				    </div>
			    </div>
			    {{--
			    <div class="col-md-3">
			    	<div class="form-group">
				    	<label>Status</label>
				    	{{ Form::select('status', [null=>'Select Status']+$allStatus, (!empty($searchDataForView["status"])) ? $searchDataForView["status"] : '', ['class'=>'form-control']) }}
				    </div>
			    </div>
			    --}}
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<label>Date of Birth</label>
				        <input type="text" name="date_of_birth" class="form-control datePicker" placeholder="Date of Birth" value="{{ $searchDataForView['date_of_birth'] }}">
				    </div>
			    </div>
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<label>Date from</label>
				        <input type="text" name="date_from" class="form-control datePicker" placeholder="Date To" value="{{ $searchDataForView['date_from'] }}">
				    </div>
			    </div>
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<label>Date to</label>
				        <input type="text" name="date_to" class="form-control datePicker" placeholder="Date To" value="{{ $searchDataForView['date_to'] }}">
				    </div>
			    </div>
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<label>Source</label>
				        <input type="text" name="soruce" class="form-control tckt-numb" placeholder="Source" value="{{ $searchDataForView['soruce'] }}">
				    </div>
			    </div>
			    <div class="clearfix">&nbsp;</div>
			
			    <div class="col-md-3">
			    	<div class="form-group">
			    		<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
			    		{{--
			    		@IF(!empty($dataForView['data']))
			    			<button class="btn btn-success" type="submit" name="export" value="export_to_excel"><i class="fa fa-file-excel-o"></i> Export to excel</button>
			    		@ENDIF
			    		--}}
			    	</div>
			    </div>

			{!! Form::close(); !!}
		</div>
	</div>
</div>
<div class="row">
	<div class="panel panel-primary">
		<div class="panel-body" style="padding: 0;">
			<div class="table-responsive">
				<table class="table table-bordered table-condensed">
					<thead style="background-color: #337ab7;">
					<tr>
						<th style="color: white" class="text-center vcenter wordwrap ">Ticket Number</th>
						<th style="color: white" class="text-center vcenter wordwrap ">Card / Acc Number</th>
						<th style="color: white" class="text-center vcenter wordwrap ">Customer Name</th>
						<th style="color: white" class="text-center vcenter wordwrap ">Product Type</th>
						<th style="color: white" class="text-center vcenter wordwrap ">
							@IF(!empty($reportCategory[$searchDataForView["form_type"]]))
								{{$reportCategory[$searchDataForView["form_type"]]}}
							@ELSE
								Service Request
							@ENDIF
						</th>
						
						<th style="color: white" class="text-center vcenter wordwrap ">Logged By</th>
						<th style="color: white" class="text-center vcenter wordwrap ">Log Time</th>
						<th style="color: white" class="text-center vcenter wordwrap ">Start Date</th>
						<th style="color: white" class="text-center vcenter wordwrap ">Close Date</th>
						<th style="color: white" class="text-center vcenter wordwrap ">Status</th>

						<th style="color: white" class="text-center vcenter wordwrap no-padding-margin-tb">Maker</th>
					</tr>
					</thead>
					<tbody>
					@IF(!empty($dataForView['data']))
						@FOREACH($dataForView['data'] AS $data)
							<?php
		                    $form_status = $data['form_status'];
		                    if($form_status == 8 || $form_status == 0 || $form_status == null) {
		                        $status = "New";
		                    } elseif($form_status == 2) {
		                        $status = "Wip";
		                    } else if ($form_status == 11) {
		                        $status = "Close";
		                    } else if ($form_status == 10) {
		                        $status = "Hold";
		                    } else {
		                      $status = "Wip";
		                    }
		                    ?>
							<tr>
								<td class="text-center vcenter no-padding-margin-tb">
									@IF( $searchDataForView["form_type"] == 'wform')
										<a href="{{ url('/Supports/WFormDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
									@ELSEIF( $searchDataForView["form_type"] == 'complaint')
										<a href="{{ url('/Supports/ComplaintDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
									@ENDIF
								</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ $data['account_number'] }}</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ $data['customer_name'] }}</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type'])) ? $data['product_type'] : $data['product_type_ext'] }}</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['form_type'] }}</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ $data['time_and_ext'] }}</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('d-m-Y') }}</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ $status }}</td>
								<td class="text-center vcenter no-padding-margin-tb">{{ $data['created_by'] }}</td>
							</tr>
						@ENDFOREACH
					@ELSE
						<tr><th class="text-center vcenter no-padding-margin-tb" colspan="9">Data not available</th></tr>
					@ENDIF
					</tbody>
					<tfoot>
					@IF(!empty($dataObj))
						@IF($dataObj->total() > $dataObj->perPage())
		                    <tr><td class="text-right vcenter no-padding-margin-tb" colspan="9">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
			            @ENDIF
		            @ENDIF
		            </tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('extrajssection')
<script type="text/javascript">
$(document).ready(function($e){
	var form_type = $(".form_type").val();
	$(".cat").hide();
	$(".catwform").hide();
	$(".catcomplaint").hide();
	$(".cat"+form_type).show();

	$(".form_type").on('change',function($e){
		var form_type = $(this).val();
		$(".cat").hide();
		$(".catwform").hide();
		$(".catcomplaint").hide();
		$(".cat"+form_type).show();
	});

	$(".report-type").on('change',function($e){
		var report_type = $(this).val();
		console.log(report_type);
	});
});

</script>
@endsection

