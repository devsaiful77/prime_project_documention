<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 3/19/2020.
 */
?>
@extends('layouts.admin')

@section('content')

    <form class="form-horizontal form-label-left" method="post" action="{{ url('unit-assign') }}">
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

        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Group <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="group_info_id" id="group_info_id">
                    <option value="">Select Group</option>
                    @inject('groups','App\Services\UtilService')
                    {!! $groups->getAllGroups(old('group_info_id')) !!}
                </select>
            </div>

        </div><!-- Name -->
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Subgroup <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="subgroup_info_id" id="subgroup_info_id">
                    <option value="">Select Subgroup</option>
                    @inject('subgroups','App\Services\UtilService')
                    {!! $subgroups->getAllSubGroups(old('group_info_id')) !!}
                </select>
            </div>

        </div><!-- Name -->
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Units <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                @inject('units','App\Services\UtilService')
                {!! $units->getAllUnits(old('')) !!}
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
