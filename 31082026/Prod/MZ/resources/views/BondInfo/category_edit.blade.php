@extends('layouts.admin')

@section('content')
    <form class="form-horizontal form-label-left" method="post" action="{{ url('bond-info/category/edit/'.$id) }}">
        @csrf
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input name="name" class="form-control" value="{{old('name',$dataForView->name)}}">
            </div>
            <div class="error">{{ $errors->first('name') }}</div>
        </div><!-- Name -->
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="description" class="form-control">{{ old('description',$dataForView->description) }}</textarea>
            </div>
            <div class="error">{{ $errors->first('description') }}</div>
        </div><!-- Description -->
        <div class="ln_solid">&nbsp;</div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input type="submit" value="Submit" class="btn btn-primary">
                <a href="{{ url('bond-info/category') }}" class="btn btn-info gradient" >Back</a>
            </div>
        </div>
    </form>
@endsection
