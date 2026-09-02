@extends('layouts.admin')

@section('content')
@IF ($id != null)
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['SubGroupsController@update',$id] ,
        
        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]); 
  !!} 
@ELSE
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['SubGroupsController@store'] ,
        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]); 
  !!} 
@ENDIF
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
              'autofocus'=>'true',
              'placeholder'=>'Name'
            ]);
          !!} 
        </div>
        <div class="error">{{ $errors->first('name') }}</div>
    </div><!-- Name -->
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
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group">Groups </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        @IF(!empty($allGroupData))
          @FOREACH($allGroupData as $groupIDKey=>$groupName)
          <div class="col-md-4">
            <div class="checkbox">
              <label>
               
                <input type="checkbox" {{(!empty($checkedGroupData[$groupIDKey])) ? 'checked' : ''}} name="group[{{$groupIDKey}}]" value="{{$groupIDKey}}"> <strong>{{$groupName}}</strong>
              </label>
            </div>
          </div>
          @ENDFOREACH
        @ENDIF

      </div>
      <div class="error">{{ $errors->first('description') }}</div>
    </div><!-- Description -->


    <div class="ln_solid">&nbsp;</div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <?php $additionalParams = (!empty($searchDataForView)) ? '?'.http_build_query($searchDataForView) : ""; ?>
          {{ Form::hidden('additionalParams',$additionalParams) }}

          {!!
            Form::submit((!empty($id)) ? 'Update':'Submit',array(
              'class'=>'btn btn-primary gradient',
              'title'=>'Add',
              'escape'=>false
            ));
          !!} 
          <button type="button" class="btn btn-info gradient" onclick="cancel('/Groups{{ $additionalParams}}')">Back</button> 
        </div>
    </div>   

{!! Form::close(); !!}
@endsection