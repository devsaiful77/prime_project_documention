@extends('layouts.admin')

@section('content')

@IF ($id != null)
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['UnitItemsController@updateCategory',$id] ,
        
        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]); 
  !!} 
@ELSE
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['UnitItemsController@storeCategory'] ,
        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]); 
  !!} 
@ENDIF
  {!! Form::token(); !!}
  {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Category Name <span class="required">*</span>
    </label>
      
      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('name',(!empty($dataForView["name"])) ? $dataForView["name"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Category Name'
          ]);
        !!} 
      </div>
      <div class="error">{{ $errors->first('name') }}</div>
  </div><!-- Name -->
  <?php //echo '<pre>'; print_r($dataForView); ?>
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_type">Product Type <span class="required">*</span> </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <select class="form-control" name="product_type_id">
      <option value="">Select Product Type</option>
      @inject('product_type','App\Services\ProductTypeService')
      {!! $product_type->getAllProductType(old('product_type_id',(!empty($dataForView["product_type_id"])) ? $dataForView["product_type_id"] : '')) !!}
    </select>
  </div>
  <div class="error">{{ $errors->first('product_type_id') }}</div>
</div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Issue From<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {{ Form::select('issues_from', [null=>'Select Issue From'] +  unserialize(ISSUEFROM), (!empty($dataForView['issues_from'])) ? $dataForView['issues_from'] : "", ['class'=>'form-control']) }}
    </div>
    <div class="error">{{ $errors->first('issues_from') }}</div>
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
        <button type="button" class="btn btn-info gradient" onclick="cancel('/Issues-category{{ $additionalParams}}')">Back</button>
      </div>
  </div>   

{!! Form::close(); !!}
@endsection