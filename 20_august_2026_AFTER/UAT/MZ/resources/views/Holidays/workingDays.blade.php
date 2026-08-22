@extends('layouts.admin')
@section('content')
<div class="curved-inner-pro"> <div class="curved-ctn"> <h2>{{ $title_for_layout }}</h2> </div> </div>
<div class="clearfix">&nbsp;</div>
<form method="GET" action="{{ url('/WorkingDays') }}" class="d-flex align-items-center">
    <div class="form-group me-2">
        <?php
            $workingDayYear = array();
            $sy = 2019;
            $ey = date("Y") + 1;
            $currentYear = date("Y");
            $selectVal = "";
            for( $i=$sy; $i <= $ey; $i++ ){
                $workingDayYear[$i] = $i;
            }
        ?>
        {{ Form::select('year', $workingDayYear, $currentYear, ['class'=>'form-control']) }}
        @IF($errors->has('year')) 
            <div class="error-message">{{ $errors->first('year') }}</div> 
        @ENDIF
    </div>
    
    <button type="submit" name="type" value="search" class="btn btn-primary me-2" style="margin-bottom: 0px;">
        <i class="fa fa-search"></i> <strong>Search</strong>
    </button>
    
    <button type="submit" name="type" value="generate" class="btn btn-success" style="margin-bottom: 0px;">
        <i class="fa fa-check"></i> <strong>Generate Working Days</strong>
    </button>
</form>

<div class="clearfix">&nbsp;</div>
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th class="vcenter text-center">Working Date</th>
                <th class="vcenter text-center">Year</th>
                <th class="vcenter text-center">Status</th>
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $key=>$data)
                <tr>
                    <td class="vcenter text-center"> {{ $data['dates'] }} </td>
                    <td class="vcenter text-center"> {{ $data['year'] }} </td>
                    <td class="vcenter text-center"> {{ $data['status'] }} </td>
                </tr>
            @ENDFOREACH
            @ELSE <tr> <td class="vcenter text-center" colspan="3"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF
            {{--@IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right" colspan="3">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF--}}

        </tbody>
    </table>
</div>
@endsection
