@extends('layouts.admin')
@section('content')
<div class="col-lg-12">
    <h3 class="text-center">Issue : {{ $issue->name }}</h3>
</div>
<form method="post" action="{{ url('issues/inquiry/config/store') }}" enctype="multipart/form-data" class="form-horizontal form-label-left">
  @csrf
    {{--<div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent_id">Parent Node</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" name="parent_id">
                <option value="">Select Parent Node</option>
                @forelse($parent as $pa)
                    <option value="{{ $pa->id }}">{{ !empty($pa->search_idx) ? $pa->search_idx : $pa->node_idx }} - {{ $pa->node_value }}</option>
                @empty
                    <option value="">Select Parent Node</option>
                @endforelse
            </select>
        </div>
        <div class="error">
            @error('parent_id')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>--}}
    <input type="hidden" value="{{ $issue->id }}" name="issue_id">
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="division_id">Select Inquiry API <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" name="cif_modification_url_id">
                @forelse($inquiry as $inq)
                    <option value="{{ $inq->id }}">{{ $inq->name }}</option>
                @empty
                    <option value="">Select Inquiry API</option>
                @endforelse
            </select>
        </div>
        <div class="error">
            @error('cif_modification_url_id')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="search_idx">Search Index</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('search_idx',(!empty($dataForView["search_idx"])) ? $dataForView["search_idx"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Search Index'
          ]);
        !!}
      </div>
      <div class="error">
        @error('search_idx')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>
  </div>
    <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="node_idx">Node Index <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('node_idx',(!empty($dataForView["node_idx"])) ? $dataForView["node_idx"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Node Index'
          ]);
        !!}
      </div>
      <div class="error">
        @error('node_idx')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>
  </div>
    <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="node_value">Node Value <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('node_value',(!empty($dataForView["node_value"])) ? $dataForView["node_value"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'autofocus'=>'true',
            'placeholder'=>'Node Value'
          ]);
        !!}
      </div>
      <div class="error">
        @error('node_value')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>
  </div>

  <div class="ln_solid">&nbsp;</div>
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="{{ url('issues/inquiry/config',$issue->id) }}" class="btn btn-info gradient">Back</a>
      </div>
  </div>
</form>
@endsection
