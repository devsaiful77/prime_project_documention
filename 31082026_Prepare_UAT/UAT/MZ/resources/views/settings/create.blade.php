<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/2/2020.
 */
?>
@extends('layouts.admin')

@section('content')

    <form class="form-horizontal form-label-left" method="post" action="{{ url('settings') }}">
        @csrf
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Session Lifetime(Minute) <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="session_lifetime" class="form-control">
            <div class="error">{{ $errors->first('session_lifetime') }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">CI Session time(Minute) <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="ci_session_time" class="form-control">
            <div class="error">{{ $errors->first('ci_session_time') }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Password Change Time
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="number" name="password_change_time" class="form-control">
            </div>
        </div><!-- Name -->
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Allow Ip Restriction
            </label>
            &nbsp;&nbsp;&nbsp;
            <label class="switch">
                <input type="checkbox" name="allow_ip_restriction">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sla Blink Time(Minute)
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="sla_blink" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sla Email Time(Minute)
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="sla_email_time" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Forward Email Time(Minute)
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="forward_time" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Attachment Upload Size Limit (MB) <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="file_size_limit" value="{{ old('file_size_limit') }}" class="form-control" placeholder="Enter file size limit in MB (e.g., 5 for 5MB)">
                <div class="error">{{ $errors->first('file_size_limit') }}</div>
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

