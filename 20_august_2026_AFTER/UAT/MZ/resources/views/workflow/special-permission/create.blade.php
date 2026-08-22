<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/17/2020.
 */
?>
@extends('layouts.admin')

@section('content')
    <div class="row">
        <form class="" method="post" action="{{ url('workflow-spacial') }}">
            @csrf
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="issueType">Issues Type<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                   {{-- <select class="form-control" name="issue_id" id="issueType" required>
                        <option value="">Select Issue Type</option>
                        <option value="wform">Service Request</option>
                        <option value="complaint">Complaint</option>
                    </select>--}}
                    <label for="wform">
                        <input type="radio" id="wform" name="issue_id" value="wform" class="custom-control-input green" />
                        Service Request
                    </label>

                    <label for="complaint">
                        <input type="radio" id="complaint" name="issue_id" value="complaint" class="custom-control-input green"/>
                        Complaint
                    </label>
                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Issues <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control" name="issue_id" id="issueItems">
                        <option value="">Select Issue</option>
                       {{-- @inject('issues','App\Services\UtilService')
                        {!! $issues->getAllIssues(old('issue_id')) !!}--}}
                    </select>

                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Group <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control group_info_id" name="group_info_id" id="group_id">
                        <option value="">Select Group</option>
                        @inject('groups','App\Services\UtilService')
                        {!! $groups->getAllGroups(old('group_info_id')) !!}
                    </select>
                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Subgroup
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control" name="subgroup_info_id" id="subgroup_id">
                        <option value="">Select Subgroup</option>
                        @inject('subgroups','App\Services\UtilService')
                        {!! $subgroups->getAllSubGroups(old('subgroup_info_id')) !!}
                    </select>
                </div>

            </div><!-- Name -->
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="name">Special Permission<span class="required">*</span>
                </label>

                <div class="col-md-9 col-sm-9 col-xs-9">
                    <div class="row">

                            <div class="mx-auto col-md-4">
                                <input type="hidden" value="" name="priority_groups[]">

                                <div id="specialPermission">

                                    @include('partials.special_workflow')
                                </div>
                            </div>

                    </div>
                </div>

            </div>


            <div class="ln_solid">&nbsp;</div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <input class="btn btn-primary gradient" title="Add" type="submit" value="Submit">
                </div>
            </div>

        </form>
    </div>
@endsection
@section('script')
    <script>
        var issueType = $('input[type=radio][name=issue_id]', '.container-fluid');
        var issueItems = $('#issueItems', '.container-fluid');

        issueType.on('change', function () {
            var issueTypeID = $(this).val();
            getIssueOptions(issueTypeID);
        });
        $('#group_id').on('change',function () {
            var gInfoID = $(this).val();
            var issue_id = $('#issueItems').val();
            getGroupWorkflow(gInfoID,issue_id);
        });
        var getIssueOptions = function(issueTypeID) {
            if (issueTypeID) {
                $.ajax({
                    url: '{{url('/type-wise-issue-for-special')}}/' + issueTypeID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        issueItems.html('<option value="">Select Issue</option>');
                        $.each(data, function (key, value) {
                            issueItems.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {

            }
        };
        var getGroupWorkflow = function(gInfoID,issue_id) {
            console.log('d');
            if (gInfoID) {
                $.ajax({
                    type: "get",
                    url: "{{ url('group-wise-workflow/') }}" + '/'+gInfoID+'/'+issue_id,

                    dataType: "html",
                    success: function (data) {
                        $('#specialPermission').html(data);
                    },
                    error: function (data) {
                        // error handling
                    }
                })
            }

        };


    </script>
    <style>
        fieldset{
            border: 1px solid #ddd!important;
            padding: 10px;
        }
        legend{
            margin-bottom: 0px;
        }
        .workflow-input{
            width: 60px;
        }
        .table.table-bordered>tbody>tr>td, .table.table-bordered>tbody>tr>th, .table.table-bordered>tfoot>tr>td, .table.table-bordered>tfoot>tr>th, .table.table-bordered>thead>tr>td, .table.table-bordered>thead>tr>th {
            border: 1px solid #F5F5F5;
            font-size: 14px;
            color: #333;
            padding: 10px;
        }
        .table{
            margin-bottom: 0px;
        }
        .delete-group-btn{
            position: absolute;
            top: 0;
            right: 6px;
            background: #ddd;
            padding: 5px;
            border-radius: 40px;
            width: 30px;
            text-align: center;
        }
    </style>

@endsection
