@extends('layouts.admin')

@section('content')

@IF ($id != null)
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['DepartmentsController@update',$id] ,

        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]);
  !!}
@ELSE
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['DepartmentsController@store'] ,
        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]);
  !!}
@ENDIF
  {!! Form::token(); !!}

<form method="post" action="{{ url('Departments') }}" enctype="multipart/form-data" class="form-horizontal form-label-left">
  @csrf
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
      {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
      <div class="error">{{ $errors->first('name') }}</div>
  </div><!-- Name -->

  <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="division_id">Division <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" name="division_id">
                <option value="">Select Division</option>
                @inject('division','App\Services\UtilService')
                {!! $division->getAllDivisions(old('division_id',(!empty($dataForView["division_id"])) ? $dataForView["division_id"] : '')) !!}
            </select>
        </div>
      <div class="error">{{ $errors->first('division_id') }}</div>
    </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description
    </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('description',(!empty($dataForView["description"])) ? $dataForView["description"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Description'
          ]);
        !!}
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
