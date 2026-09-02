@extends('layouts.admin')
@section('content')
{!!
    Form::open([
      'method'=>'post',
      'action' => ['PermissionsController@store'] ,
      'id'=>'formId',
      'class'=>'form-horizontal form-label-left',
      'enctype' => 'multipart/form-data'
    ]); 
!!} 
  {!! Form::token(); !!} 

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
            'placeholder'=>'Name'
          ]);
        !!} 
      </div>
      <div class="error">{{ $errors->first('name') }}</div>
  </div><!-- Name -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="display_name">Display Name <span class="required">*</span>
    </label>
      
      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('display_name',(!empty($dataForView["display_name"])) ? $dataForView["display_name"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'Display Name'
          ]);
        !!} 
      </div>
      <div class="error">{{ $errors->first('display_name') }}</div>
  </div><!-- Display Name -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="controller_name">Controller Name <span class="required">*</span>
    </label>
      
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('controller_name', [null=>'Select Controller'] + $controllerWiseArray,(!empty($dataForView["controller_name"])) ? $dataForView["controller_name"] : '',
          [
              'class'=>'select2 form-control',
              'label'=>false,
              'autocomplete'=>'off',
              'style'=>'width:100%'
          ]) 
        }} 
        
      </div>
      <div class="error">{{ $errors->first('controller_name') }}</div>
  </div><!-- Controller Name -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!! 
        Form::textarea('description',(!empty($dataForView["description"])) ? $dataForView["description"] : ''  ,[
          'rows'=>3,
          'class' => 'form-control',
          'label'=>false,
          'autocomplete'=>'off',
          'placeholder'=>'Description'
        ]);
      !!}
    </div>
    <div class="error">{{ $errors->first('description') }}</div>
  </div><!-- Description -->
  
  <div class="ln_solid">&nbsp;</div>
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {!!
          Form::submit('Submit',array(
            'class'=>'btn btn-primary gradient',
            'title'=>'Add',
            'escape'=>false
          ));
        !!} 
        <button type="button" class="btn btn-danger gradient" onclick="cancel('/Permissions')">Back</button> 
      </div>
  </div>   

{!! Form::close(); !!}
@endsection