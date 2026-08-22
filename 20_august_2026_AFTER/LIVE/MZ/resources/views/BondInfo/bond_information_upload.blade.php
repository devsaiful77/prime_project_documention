@extends('layouts.admin')

@section('content')
  <legend>{{ $title_for_layout }}</legend>
  <form class="form-horizontal form-label-left" method="post" action="{{ url('bond-info/upload') }}" enctype="multipart/form-data">
    @csrf
    {{ Form::hidden('tmpId', (!empty($tmpId)) ? $tmpId : '' ) }}
    
    <!-- Category -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="binfo_category_id">Category <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('binfo_category_id', [''=>'Please Select'] + $bondInfoList, (!empty($dataForView['binfo_category_id'])) ? $dataForView['binfo_category_id'] : '', ['class' => 'form-control categoryId']) }}
      </div>
      <div class="error">{{ $errors->first('binfo_category_id') }}</div>
    </div>

    <!-- Sub Category -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="binfo_subcategory_id">Sub Category <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('binfo_subcategory_id', [''=>'Please Select'], (!empty($dataForView['binfo_subcategory_id'])) ? $dataForView['binfo_subcategory_id'] : '', ['class' => 'form-control subCategoryId']) }}
      </div>
      <div class="error">{{ $errors->first('binfo_subcategory_id') }}</div>
    </div>

    <!-- Title -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" autocomplete="off">
      </div>
      <div class="error">{{ $errors->first('title') }}</div>
    </div>

    <!-- Description -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
      </div>
      <div class="error">{{ $errors->first('description') }}</div>
    </div>

    <!-- File Upload -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="file_name">File <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <img id="imgInWform" alt="W-Form image" width="100" height="100" style="display:none;" />
        <a id="downloadBtnWform" href="#" style="display:none;" download>Download File</a>
        <input type="file" name="file_name" class="form-control" onchange="
          var file = this.files[0];
          var img = document.getElementById('imgInWform');
          var downloadBtn = document.getElementById('downloadBtnWform');
          if (file) {
              if (file.type.startsWith('image/')) {
                  img.style.display = 'block';
                  img.src = window.URL.createObjectURL(file);
                  downloadBtn.style.display = 'none';
                  downloadBtn.href = '';
              } else {
                  img.style.display = 'none';
                  img.src = '';
                  downloadBtn.style.display = 'block';
                  downloadBtn.href = window.URL.createObjectURL(file);
                  downloadBtn.innerHTML = 'Download ' + file.name;
              }
          }
        ">
      </div>
      <div class="error">{{ $errors->first('file_name') }}</div>
    </div>

    <!-- Submit Buttons -->
    <div class="ln_solid">&nbsp;</div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <input type="submit" value="Submit" class="btn btn-primary">
        <a href="{{ url('bond-info/list') }}" class="btn btn-info gradient">Back</a>
      </div>
    </div>
  </form>
@endsection

<script src="{{ URL::asset('public/js/vendor/modernizr-2.8.3.min.js') }}"></script>
<script src="{{ URL::asset('public/js/latest-v/jquery-3.7.1.min.js') }}"></script> {{-- jquery --}}
{{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

<script src="{{ URL::asset('public/js/vendor/jquery-migrate-1.4.1.min.js') }}"></script>
<script src="{{ URL::asset('public/js/latest-v/bootstrap-5.3.1.bundle.min.js') }}"></script> {{-- bootstrap --}}

<script type="text/javascript">
  // Subcategory object for dynamic dropdown
  var subCategoryObj = @json($bondInfoSubCatList);
  console.log("Subcategory Object:", subCategoryObj); // Check the structure in console

  $(document).ready(function() {
      var category_id = $('.categoryId').val();
      var subcategory_id = "{{ old('binfo_subcategory_id', $dataForView['binfo_subcategory_id'] ?? '') }}";

      // Populate subcategory on load if a category is selected
      subcategoryOption(category_id, subcategory_id);

      // Populate subcategory dropdown when category changes
      $('.categoryId').on('change', function() {
          var category_id = $(this).val();
          subcategoryOption(category_id, '');
      });
  });

  function subcategoryOption(category_id, selected_subcat_id) {
      var subcatOption = "<option value=''>Please Select</option>";
      var specificSubCat = subCategoryObj[category_id];

      if (specificSubCat) {
          specificSubCat.forEach(function(subcat) {
              var selected = (selected_subcat_id == subcat.id) ? 'selected' : '';
              subcatOption += "<option value='" + subcat.id + "' " + selected + ">" + subcat.name + "</option>";
          });
      }

      $('.subCategoryId').html(subcatOption);
  }
</script>

