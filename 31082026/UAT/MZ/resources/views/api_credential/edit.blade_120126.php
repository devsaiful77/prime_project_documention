<?php
/**
 * User:Muajjam Hossain
 * Email:muajjam.imu@gmail.com
 * Created by Muajjam Hossain<muajjam.imu@gmail.com> on 05/09/2024.
 */
?>
@extends('layouts.admin')

@section('content')
<style>
    label{
        padding-top: 0.5rem;
    }
</style>
{{-- <th class="vcenter text-left"> <a href="{{ url('/api-credential/approve',5) }}" class="btn btn-primary gradient ajax_page" title="Approve" escape="false"> <i class="fa fa-plus"></i> Approve</a> </th> --}}

    <form class="form-horizontal form-label-left" method="post" action="{{ url('api-credential/'.$apiCredential->id) }}">
        @csrf
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
        {{-- User Name --}}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_name">User Name <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="user_name" value="{{ old('user_name', $apiCredential->user_name) }}" class="form-control">
                <div class="error">{{ $errors->first('user_name') }}</div>
            </div>
        </div>

         {{-- Password --}}
         <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_password">Password <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="user_password" value="{{ old('user_password', $apiCredential->user_password) }}" class="form-control">
                <div class="error">{{ $errors->first('user_password') }}</div>
            </div>
        </div>

         {{-- Token URL --}}
         <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="token_url">Token URL <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="token_url" value="{{ old('token_url', $apiCredential->token_url) }}" class="form-control">
                <div class="error">{{ $errors->first('token_url') }}</div>
            </div>
        </div>

        {{-- Pull API URL --}}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Pull_API_URL">Pull API URL <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="Pull_API_URL" value="{{ old('Pull_API_URL', $apiCredential->Pull_API_URL) }}" class="form-control">
                <div class="error">{{ $errors->first('Pull_API_URL') }}</div>
            </div>
        </div>

        {{-- Loan API Request --}}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="loan_api_request">Pull API URL (Loan)<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="loan_api_request" value="{{ old('loan_api_request', $apiCredential->loan_api_request) }}" class="form-control">
                <div class="error">{{ $errors->first('loan_api_request') }}</div>
            </div>
        </div>

        {{-- SMS API URL --}}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="SMS_API_URL">SMS API URL <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="SMS_API_URL" value="{{ old('SMS_API_URL', $apiCredential->SMS_API_URL) }}" class="form-control">
                <div class="error">{{ $errors->first('SMS_API_URL') }}</div>
            </div>
        </div>

        <div class="ln_solid">&nbsp;</div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                @if(!$checker)
                    <input class="btn btn-primary gradient" title="Update" type="submit" value="Update">
                @endif
            </div>
        </div>

    </form>
@endsection

