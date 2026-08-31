<?php
?>
@extends('layouts.admin')

@section('content')

<form class="form-horizontal form-label-left" method="post" action="{{ url('group-info') }}">
@csrf

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Department <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" name="department_id">
                <option value="">Select Department</option>
                @inject('department','App\Services\UtilService')
                {!! $department->getAllDepartments(old('department_id')) !!}
            </select>

        </div>
        <div class="error">{{ $errors->first('department_id') }}</div>
    </div>
    {{--<div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group_level_id">Group Level <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" name="group_level_id" id="group_level_id">
                <option value="">Select Level</option>
                @inject('groupLevels','App\Services\UtilService')
                {!! $groupLevels->getAllGroupLevels(old('group_level_id')) !!}
            </select>
        </div>
        <div class="error">
            @error('group_level_id')
                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
            @enderror
        </div>
    </div>--}}
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!!
              Form::text('name',(!empty($dataForView["name"])) ? $dataForView["name"] : '' ,[
                'class' => 'form-control',
                'label'=>false,
                'autocomplete'=>'off',
                'type'=>'text',
                'autofocus'=>'true',
                'placeholder'=>'Name'
              ]);
            !!}
        </div>
        <div class="error">{{ $errors->first('name') }}</div>

    </div><!-- Name -->


    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!!
            Form::textarea('description',(!empty($dataForView["description"])) ? $dataForView["description"] : ''  ,[
              'rows'=>3,
              'class' => 'form-control',
              'label'=>false,
              'autocomplete'=>'off',
              'placeholder'=>'Description'
            ]);
          !!}
        </div>
    </div><!-- Description -->
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!!
            Form::textarea('address',(!empty($dataForView["address"])) ? $dataForView["address"] : ''  ,[
              'rows'=>3,
              'class' => 'form-control',
              'label'=>false,
              'autocomplete'=>'off',
              'placeholder'=>'Address'
            ]);
          !!}
        </div>
    </div><!-- Address -->
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group_level_id">Group Level <span class="required"></span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <label>
                <input type="hidden" name="group_level_id" value="0">
                <input type="checkbox" name="group_level_id" value="1"> &nbsp;Is Touch Point
            </label>
        </div>

    </div>
    <div class="ln_solid">&nbsp;</div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <input class="btn btn-primary gradient" title="Add" type="submit" value="Submit">
        </div>
    </div>

</form>
@endsection
