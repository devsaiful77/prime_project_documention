@extends('layouts.admin')
@section('content')
<legend class="text-center">DMS Url Edit</legend>
<form action="{{ url('/DMSAPIServices/DMSUrl/update/'.encrypt($dataForView["id"])) }}" method="post"
      class="form-horizontal form-label-left" enctype="multipart/form-data">@csrf
    <div class="form-group">
        <label for="name">Service Name <span class="required">*</span></label>
        <input name="name" value="{{ (!empty($dataForView["name"])) ? $dataForView["name"] : '' }}"
               class="form-control" autocomplete="off" type="text" placeholder="Service Name"/>
        <div class="error">{{ $errors->first('name') }}</div>
    </div>
    <div class="form-group">
        <label for="url">Url <span class="required">*</span></label>
        <input name="url" value="{{ (!empty($dataForView["url"])) ? $dataForView["url"] : '' }}"
               class="form-control" autocomplete="off" type="text" placeholder="Url"/>
        <div class="error">{{ $errors->first('url') }}</div>
    </div>
    <div class="form-group">
        <label for="request">Request <span class="required">*</span></label>
        <textarea name="request" class="form-control" autocomplete="off" rows="20" placeholder="Request">{{ (!empty
        ($dataForView["request"])) ? $dataForView["request"] : '' }}</textarea>
        <div class="error">{{ $errors->first('request') }}</div>
    </div>
    <div class="form-group">
        <a class="btn btn-info gradient" href="{{ url('/DMSAPIServices/DMSUrl/list') }}">Back</a>
        <button type="submit" class="btn-primary btn">Submit</button>
    </div>
</form>
@endsection
