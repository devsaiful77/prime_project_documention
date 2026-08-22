<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/2/2020.
 */
?>
@extends('layouts.admin')

@section('content')

    <form class="form-horizontal form-label-left" method="post" action="{{ url('settings/'.encrypt($row->setting_id)) }}">
        @csrf
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Session Lifetime(Minute)<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="session_lifetime" value="{{old('session_lifetime',$row->session_lifetime) }}" class="form-control">
                <div class="error">{{ $errors->first('session_lifetime') }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ci_session_time">CI Session time(Minute)<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="ci_session_time" value="{{old('ci_session_time',$row->ci_session_time) }}" class="form-control">
                <div class="error">{{ $errors->first('ci_session_time') }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="term_condition">CI Term and Conditions<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control summernote" name="term_condition" cols="30" rows="10">{!! old('term_condition',$row->term_condition) !!}</textarea>
                <div class="error">{{ $errors->first('term_condition') }}</div>
            </div>
        </div>
        {{-- <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">CI T&C URL<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="term_condition_url" value="{{old('term_condition_url',$row->term_condition_url) }}" class="form-control">
                <div class="error">{{ $errors->first('term_condition_url') }}</div>
            </div>
        </div> --}}
        {{--<div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">CI OTP Sent<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="ci_otp_sms_email" class="form-control">
                    <option value="" disabled>Please Select</option>
                    <option value="all" {{$row->ci_otp_sms_email == "all"? 'selected' : ""}}>All</option>
                    <option value="sms" {{$row->ci_otp_sms_email == "sms"? 'selected' : ""}}>SMS</option>
                    <option value="email" {{$row->ci_otp_sms_email == "email"? 'selected' : ""}}>Email</option>
                </select>
                <div class="error">{{ $errors->first('term_condition_url') }}</div>
            </div>
        </div>--}}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Password Change Time<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="password_change_time" value="{{old('password_change_time',$row->password_change_time) }}" class="form-control">
                <div class="error">{{ $errors->first('password_change_time') }}</div>
            </div>
        </div><!-- Name -->

        <div class="form-group">
            <label class="switch">
                <input type="checkbox" name="allow_ip_restriction"  @if($row->allow_ip_restriction == 1) {{'checked'}} @else {{''}} @endif>
                <span class="slider round"></span>
            </label>
            &nbsp;&nbsp;&nbsp;
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Allow Ip Restriction<span class="required">*</span>
            </label>
           
            
            <div class="error">{{ $errors->first('allow_ip_restriction') }}</div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sla Blink Time(Minute)<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="sla_blink" value="{{old('sla_blink',$row->sla_blink) }}" class="form-control">
                <div class="error">{{ $errors->first('sla_blink') }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sla Email Time(Minute)<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="sla_email_time" value="{{old('sla_email_time',$row->sla_email_time) }}" class="form-control">
                <div class="error">{{ $errors->first('sla_email_time') }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Forward Email Time (Minute)<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="forward_time" value="{{old('forward_time',$row->forward_time) }}" class="form-control">
                <div class="error">{{ $errors->first('forward_time') }}</div>
            </div>
        </div>
		<div class="form-group">
            <label class="switch">
                
                <input type="checkbox" name="noncustomersms"  @if($row->noncustomersms == 1) {{'checked'}} @else {{''}} @endif>
                <span class="slider round"></span>
            </label>
            
            &nbsp;&nbsp;&nbsp;
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Non Customer SMS/Email<span class="required">*</span>
            </label>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Attachment Upload Size Limit (MB)<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="file_size_limit" value="{{old('file_size_limit',$row->file_size_limit) }}" class="form-control" placeholder="Enter file size limit in MB (e.g., 5 for 5MB)">
                <div class="error">{{ $errors->first('file_size_limit') }}</div>
            </div>
        </div>

        <div class="ln_solid">&nbsp;</div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input class="btn btn-primary gradient" title="Add" type="submit" value="Update">
            </div>
        </div>

    </form>
@endsection
