@extends('layouts.admin')

@section('content')

  @IF ($id != null)
    {!!
        Form::open([
          'method'=>'post',
          'action' => ['UnitItemsController@update',$id] ,

          'id'=>'formId',
          'class'=>'form-horizontal form-label-left',
          'enctype' => 'multipart/form-data'
        ]);
    !!}
  @ELSE
    {!!
        Form::open([
          'method'=>'post',
          'action' => ['UnitItemsController@store'] ,
          'id'=>'formId',
          'class'=>'form-horizontal form-label-left',
          'enctype' => 'multipart/form-data'
        ]);
    !!}
  @ENDIF
  {!! Form::token(); !!}
  <?php //prd($dataForView); ?>
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
    </div>
  <?php //echo '<pre>'; print_r($dataForView); ?>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_type">Product Type <span class="required">*</span> </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <select class="form-control" name="product_type_id" id="product_type">
        <option value="">Select Product Type</option>
        @inject('product_type','App\Services\ProductTypeService')
        {!! $product_type->getAllProductType(old('product_type_id',(!empty($dataForView["product_type_id"])) ? $dataForView["product_type_id"] : '')) !!}
      </select>
    </div>
    <div class="error">{{ $errors->first('product_type_id') }}</div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="issues_from">Issues From <span class="required">*</span> </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {{ Form::select('issues_from', [null=>'Please Select','wform'=>'Service Request','complaint'=>'Complaint'], (!empty($dataForView['issues_from'])) ? $dataForView['issues_from'] : '', ['class'=>'form-control', 'id' => 'issues_from']) }}
    </div>
    <div class="error">{{ $errors->first('issues_from') }}</div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="issue_categories_id">Issue Category</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <select class="form-control" name="issue_categories_id" id="issue_categories_id">
        <option value="">Select Category</option>
      @inject('issue_category','App\Services\UtilService')
      {!! $issue_category->getAllIssueCategories(old('issue_category',(!empty($dataForView["issue_categories_id"])) ? $dataForView["issue_categories_id"] : '')) !!}
      </select>
    </div>
    <div class="error">{{ $errors->first('issue_categories_id') }}</div>
  </div>
  {{-- <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_api">Is API Push ??</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <label>
            <input type="hidden" name="is_api" value="0">
            <input type="checkbox" value="1" name="is_api" {{ (!empty($dataForView["is_api"]) == 1) ? 'checked' : '' }} >
        </label>
    </div>
  </div> --}}
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_ci">Is CI Issue ??</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <label>
            <input type="hidden" name="is_ci" value="0">
            <input type="checkbox" value="1" name="is_ci" {{ (!empty($dataForView["is_ci"]) == 1) ? 'checked' : '' }} >
        </label>
    </div>
  </div>
  {{-- <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_ci_cif">Is CI CIF API Update ??</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <label>
            <input type="hidden" name="is_ci_cif" value="0">
            <input type="checkbox" value="1" name="is_ci_cif" {{ (!empty($dataForView["is_ci_cif"]) == 1) ? 'checked' : '' }} >
        </label>
    </div>
  </div> --}}
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn-primary btn">Submit</button>
        <a class="btn btn-info gradient" href="{{ url('Issues') }}" >Back</a>
      </div>
  </div>
{!! Form::close(); !!}
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#issues_from').on('change', function(){
          var issues_from = $('#issues_from').val();
          var product_type = $('#product_type').val();
          if (issues_from) {
              $.ajax({
                  url: base_url+'/get-issue-wise-category/'+ issues_from +'/'+ product_type,
                  type: "GET",
                  dataType: "json",
                  success: function (data) {
                      $('#issue_categories_id').html('<option value="">Select Issue Category</option>');
                      $.each(data, function (key, value) {
                          $('#issue_categories_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                      });
                  }
              });
          }
      });
      $('#product_type').on('change', function(){
        var issues_from = $('#issues_from').val();
        var product_type = $('#product_type').val();
        if (issues_from) {
          $.ajax({
              url: base_url+'/get-issue-wise-category/'+ issues_from +'/'+ product_type,
              type: "GET",
              dataType: "json",
              success: function (data) {
                  $('#issue_categories_id').html('<option value="">Select Issue Category</option>');
                  $.each(data, function (key, value) {
                      $('#issue_categories_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                  });
              }
          });
        }
      });
    });
  </script>
@endsection
