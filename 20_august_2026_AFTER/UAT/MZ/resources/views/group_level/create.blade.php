<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/12/2020.
 */
?>
@extends('layouts.admin')

@section('content')
    <form class="form-horizontal form-label-left" method="post" action="{{ url('group-level') }}">
        @csrf

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="name" class="form-control" id="name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input class="btn btn-primary gradient" title="Add" type="submit" value="Submit">
            </div>
        </div>
    </form>
@endsection

