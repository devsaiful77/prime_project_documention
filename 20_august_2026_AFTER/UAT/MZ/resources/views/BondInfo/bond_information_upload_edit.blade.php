@extends('layouts.admin')

@section('content')
  <legend>{{$title_for_layout}}</legend>
  <form class="form-horizontal form-label-left" method="post" action="{{ url('bond-info/upload-edit/'.$id) }}" enctype="multipart/form-data">
    @csrf
    {{ Form::hidden('tmpId', $tmpId ?? '') }}
    
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="binfo_category_id">Category <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('binfo_category_id', ['' => 'Please Select'] + $bondInfoList, $dataForView['binfo_category_id'] ?? '', ['class' => 'form-control categoryId']) }}
      </div>
      <div class="error">{{ $errors->first('binfo_category_id') }}</div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="binfo_subcategory_id">Sub Category <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('binfo_subcategory_id', ['' => 'Please Select'], $dataForView['binfo_subcategory_id'] ?? '', ['class' => 'form-control subCategoryId']) }}
      </div>
      <div class="error">{{ $errors->first('binfo_subcategory_id') }}</div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="title" class="form-control" value="{{ $dataForView['title'] ?? '' }}" autocomplete="off">
      </div>
      <div class="error">{{ $errors->first('title') }}</div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea name="description" class="form-control">{{ $dataForView['description'] ?? '' }}</textarea>
      </div>
      <div class="error">{{ $errors->first('description') }}</div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="file_name">File</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="file_name" class="form-control">
      </div>
      <div class="error">{{ $errors->first('file_name') }}</div>
    </div>

    @if (!empty($dataForView['file_name']))
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">File Name</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="hidden" name="existing_file" value="{{ $dataForView['file_name'] }}">
          <a target="_blank" class="form-control" href="{{ URL::asset('public/attachments/bond_information/'.$dataForView['file_name']) }}">{{ $dataForView['file_name'] }}</a>
        </div>
      </div>
    @endif

    <div class="ln_solid">&nbsp;</div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <input type="submit" value="Submit" class="btn btn-primary">
        <a href="{{ url('bond-info/list') }}" class="btn btn-info gradient">Back</a>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
  <script src="{{ URL::asset('public/js/vendor/modernizr-2.8.3.min.js') }}"></script>
  <script src="{{ URL::asset('public/js/latest-v/jquery-3.7.1.min.js') }}"></script> {{-- jquery --}}
  <script src="{{ URL::asset('public/js/vendor/jquery-migrate-1.4.1.min.js') }}"></script>
  <script src="{{ URL::asset('public/js/latest-v/bootstrap-5.3.1.bundle.min.js') }}"></script>

  <script type="text/javascript">
    // Parse the JSON object from the controller into a JavaScript variable
    var subCategoryObj = {!! json_encode($bondInfoSubCatList) !!};
  
    // Get the current category and subcategory from the form data
    var category_id = $('.categoryId').val();
    var subcategory_id = "{{ !empty($dataForView['binfo_subcategory_id']) ? $dataForView['binfo_subcategory_id'] : old('binfo_subcategory_id') }}";
  
    // Call the function to populate subcategories based on the category selected
    subcategoryOption(category_id, subcategory_id);
  
    // Event listener for category change
    $('.categoryId').on('change', function() {
      var category_id = $(this).val();
      subcategoryOption(category_id, subcategory_id);
    });
  
    // Function to populate subcategory options
    function subcategoryOption(category_id, subcat_id) {
      var subcatOption = "<option value=''>Please Select</option>";
      var specificSubCat = subCategoryObj[category_id];
  
      // Check if there are subcategories for the selected category
      if (specificSubCat) {
        $(specificSubCat).each(function(idx, val) {
          var selected = (subcat_id == val.id) ? 'selected' : '';
          subcatOption += "<option value='" + val.id + "' " + selected + ">" + val.name + "</option>";
        });
      }
  
      // Update the subcategory dropdown
      $('.subCategoryId').html(subcatOption);
    }
  </script>
  
@endpush
