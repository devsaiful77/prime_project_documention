<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 3/19/2020.
 */
?>
@extends('layouts.admin')

@section('content')


    <form method="post" action="{{ url('Departments/'.$id) }}" enctype="multipart/form-data" class="form-horizontal form-label-left">
        @csrf
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input name="name" class="form-control" value="{{ old('name',$dataForView->name) }}" required>
            </div>
            <div class="error">
                @error('name')
                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
            </div>
        </div><!-- Name -->

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="division_id">Division <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="division_id">
                    <option value="">Select Division</option>
                    @inject('division','App\Services\UtilService')
                    {!! $division->getAllDivisions(old('division_id')) !!}
                </select>
            </div>
             <div class="error">{{ $errors->first('division_id') }}</div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control" name="description">{{ old('description',$dataForView->description) }}</textarea>
            </div>
            <div class="error">{{ $errors->first('description') }}</div>
        </div><!-- Description -->

        <div class="ln_solid">&nbsp;</div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input type="submit" value="Submit" class="btn btn-primary">
                <a href="{{url('Departments')}}" class="btn btn-info gradient" >Back</a>
            </div>
        </div>
    </form>
@endsection

