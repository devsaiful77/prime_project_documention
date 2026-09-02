@extends('layouts.admin')
@section('content')

<style type="text/css">
.wordwrap {
  	word-wrap: break-word !important;
    word-break: break-all !important;
    white-space: normal !important;
}
/*style="background-color: #DFF0D8"*/
</style>

<div class="row">
	<div class="panel panel-primary">
		<div class="panel-heading">
	        <div class="panel-title">
	            {{$title_for_layout}}
	        </div>
	    </div>
		<div class="panel-body" style="padding: 5px 0px 5px 0px;">
			@IF(!empty($searchDataForView['report_type']))
				    @includeIf('Dashboard.'.$searchDataForView['report_type'])
			@ELSE
				<p class="text-center error"><strong>Report Not Available</strong></p>
			@ENDIF

		</div>
	</div>
</div>
@endsection

@section('extrajssection')

@endsection

