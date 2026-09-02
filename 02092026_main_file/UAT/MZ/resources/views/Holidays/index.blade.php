@extends('layouts.admin')
@section('content')
@if ($checker == false)
<form method="POST" action="{{ url('/Holidays/add') }}" class="row gx-2 gy-1 align-items-center">
    {!! Form::token(); !!}
    
    <div class="col-auto">
        <input 
            type="text" 
            name="dates" 
            class="form-control srvDatePicker" 
            placeholder="Date" 
            value="{{ old('dates') }}" 
            autocomplete="off">
        @if($errors->has('dates')) 
            <div class="error-message">{{ $errors->first('dates') }}</div> 
        @endif
    </div>
    
    <div class="col-auto">
        {{ Form::select('type', [''=>'--Holiday Type--','public'=>'Public','optional'=>'Optional'], "", ['class'=>'form-control']) }}
        @if($errors->has('type')) 
            <div class="error-message">{{ $errors->first('type') }}</div> 
        @endif
    </div>
    
    <div class="col-auto">
        {!! Form::textarea('remarks', '', [
            'rows' => 1,
            'class' => 'form-control',
            'autocomplete' => 'off',
            'placeholder' => 'Remarks'
        ]); !!}
        @if($errors->has('remarks')) 
            <div class="error-message">{{ $errors->first('remarks') }}</div> 
        @endif
    </div>
    
    <div class="col-auto">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> <strong>Submit</strong>
        </button>
    </div>
</form>

@endif
<div class="clearfix">&nbsp;</div>
{{-- @if ($checker == false)
{!! Form::open(['method'=>'post', 'class'=>'form-inline', 'action' => ['HolidaysController@uploadHolidays'] , 'enctype' => 'multipart/form-data']); !!}
{!! Form::token(); !!}
{{ Form::hidden('request_type','upload')}}
<div class="form-group">
    {!! Form::file('upload_holidays', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file')); !!}
    <button class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
    <a class="btn btn-link" href="{{ URL::asset('public/sample_file/sample_holiday.xlsx') }}">Sample Excel File</a>
</div>
@IF($errors->has('upload_holidays')) <div class="error-message">{{ $errors->first('upload_holidays') }}</div> @ENDIF

{!! Form::close(); !!}
@endif --}}

@if ($checker == false)
{!! Form::open(['method'=>'post', 'class'=>'row gx-3 align-items-center', 'action' => ['HolidaysController@uploadHolidays'], 'enctype' => 'multipart/form-data']) !!}
{!! Form::token() !!}
{{ Form::hidden('request_type','upload') }}

<div class="col-auto">
    {!! Form::file('upload_holidays', [
        'class' => 'form-control',
        'label' => false,
        'type' => 'file'
    ]) !!}
</div>

<div class="col-auto">
    <button class="btn btn-primary">
        <i class="fa fa-upload"></i> Upload
    </button>
</div>

<div class="col-auto">
    <a class="btn btn-link" href="{{ URL::asset('public/sample_file/sample_holiday.xlsx') }}">
        Sample Excel File
    </a>
</div>

@if ($errors->has('upload_holidays'))
    <div class="col-12 error-message">{{ $errors->first('upload_holidays') }}</div>
@endif

{!! Form::close() !!}
@endif

<div class="clearfix">&nbsp;</div>
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
        <colgroup>
            <col width="20%">
            <col width="20%">
            <col width="20%">
            <col width="20%">
            @if ($checker == false)
            <col width="20%">
            @endif
        </colgroup>
        <thead>
            <tr>
                <th class="vcenter text-center">Sl</th>
                <th class="vcenter text-center">Holiday Date</th>
                <th class="vcenter text-center">Type of Holiday</th>
                <th class="vcenter text-center">Remarks</th>
                @if ($checker == false)
                <th class="vcenter text-center">Action</th>
                @endif
                {{-- @if ($checker == false)
                    <th class="vcenter text-left"> <a href="{{ url('/Holidays/add') }}" class="btn btn-primary gradient ajax_page" title="Add" escape="false"> <i class="fa fa-plus"></i> Add</a> </th>
                @endif --}}
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $key=>$data)
                <tr>
                    <td class="vcenter text-center"> {{ $key + 1 }} </td>
                    <td class="vcenter text-center"> {{ $data['dates'] }} </td>
                    <td class="vcenter text-center"> {{ $data['type'] }} </td>
                    <td class="vcenter text-center"> {{ $data['remarks'] }} </td>
                    @if ($checker == false)
                    <td class="vcenter actions text-center">

                        <?php
                            $editUrl = url('/Holidays/edit/'.$data['id']);
                            if (!empty($searchDataForView)) {
                                $editUrl .= '?'.http_build_query($searchDataForView);
                            }
                        ?>
                        
                        <a href="{{$editUrl}}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>
                        <a href="{{ url('/Holidays/delete/'.$data['id']) }}" class="btn btn-danger gradient" title="Delete" escape="false"> <i class="fa fa-trash"></i> Delete</a>
                        
                    </td>
                    @endif
                </tr>
            @ENDFOREACH
            @ELSE <tr> <td class="vcenter text-center" colspan="5"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF
            {{--@IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right" colspan="5">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF--}}

        </tbody>
    </table>
</div>
@endsection
