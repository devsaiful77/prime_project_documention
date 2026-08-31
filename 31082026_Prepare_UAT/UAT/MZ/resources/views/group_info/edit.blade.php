<?php ?>
@extends('layouts.admin')

@section('content')

    <form class="form-horizontal form-label-left" method="post" action="{{ url('group-info/'.$row->id) }}">
        @csrf
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Department <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="department_id">
                    <option value="">Select Department</option>
                    @inject('department','App\Services\UtilService')
                    {!! $department->getAllDepartments(old('department_id',$row->department_id)) !!}
                </select>
                @error('department_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        {{--<div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group_level_id">Group Level <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="group_level_id" id="group_level_id">
                    <option value="">Select Level</option>
                    @inject('groupLevels','App\Services\UtilService')
                    {!! $groupLevels->getAllGroupLevels(old('group_level_id',$row->group_level_id)) !!}
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
                <input type="text" class="form-control" name="name" value="{{ old('name',$row->name) }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="description" class="form-control">{{ old('description',$row->description) }}</textarea>
            </div>
        </div><!-- Description -->
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="address" class="form-control">{{ old('description',$row->address) }}</textarea>
            </div>
        </div><!-- Description -->
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group_level_id">Group Level <span class="required"></span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <label>
                    <input type="hidden" name="group_level_id" value="0">
                    <input type="checkbox" name="group_level_id" value="1" @if($row->group_level_id==1) checked @else @endif> &nbsp;Is Touch Point
                </label>
            </div>

        </div>
        <div class="ln_solid">&nbsp;</div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input class="btn btn-primary gradient" title="Add" type="submit" value="Submit">
            </div>
        </div>
        <input type="hidden" name="tmpId" value="{{ !empty($tmpId) ? $tmpId : "" }}">
    </form>
@endsection
