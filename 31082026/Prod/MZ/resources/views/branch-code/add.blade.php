@extends('layouts.admin')

@section('content')

@IF ($id != null)
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['BranchCodeController@update',$id] ,

        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]);
  !!}
@ELSE
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['BranchCodeController@store'] ,
        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]);
  !!}
@ENDIF
  {!! Form::token(); !!}

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company Code <span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('company_code',(!empty($dataForView["company_code"])) ? $dataForView["company_code"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Company Code'
          ]);
        !!}
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
      </div>
      <div class="error">{{ $errors->first('company_code') }}</div>
  </div><!-- Name -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Branch Code <span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('br_code',(!empty($dataForView["br_code"])) ? $dataForView["br_code"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Branch Code'
          ]);
        !!}
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
      </div>
      <div class="error">{{ $errors->first('br_code') }}</div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Branch Name <span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('branch_name',(!empty($dataForView["branch_name"])) ? $dataForView["branch_name"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Branch Name'
          ]);
        !!}
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
      </div>
      <div class="error">{{ $errors->first('branch_name') }}</div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mnemonic <span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('mnemonic',(!empty($dataForView["mnemonic"])) ? $dataForView["mnemonic"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Mnemonic'
          ]);
        !!}
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
      </div>
      <div class="error">{{ $errors->first('mnemonic') }}</div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Region <span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('region',(!empty($dataForView["region"])) ? $dataForView["region"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Region'
          ]);
        !!}
        {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
      </div>
      <div class="error">{{ $errors->first('region') }}</div>
  </div>

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
        <button type="button" class="btn btn-info gradient" onclick="cancel('/branchcode{{ $additionalParams}}')">Back</button>
      </div>
  </div>

{!! Form::close(); !!}
@endsection
