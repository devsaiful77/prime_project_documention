<?php
?>
@extends('layouts.admin')

@section('content')

    <form class="form-horizontal form-label-left" method="post" action="{{ url('subgroup-info') }}">
        @csrf
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Department <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="department_id" id="department_id">
                    <option value="">Select Department</option>
                    @inject('department','App\Services\UtilService')
                    {!! $department->getAllDepartments(old('department_id')) !!}
                </select>
            </div>
            <div class="error">{{ $errors->first('department_id') }}</div>

        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Group <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="group_info_id" id="group_id">
                    <option value="">Select Group</option>
                   {{-- @inject('groups','App\Services\UtilService')
                    {!! $groups->getAllGroups(old('group_info_id')) !!}--}}
                </select>
            </div>
            <div class="error">{{ $errors->first('group_info_id') }}</div>
        </div><!-- Name -->
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

        <div class="ln_solid">&nbsp;</div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input class="btn btn-primary gradient" title="Add" type="submit" value="Submit">
            </div>
        </div>

    </form>
@endsection
