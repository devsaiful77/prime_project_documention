@extends('layouts.admin')
@section('content')
<legend class="text-center">{{ $title_for_layout }}</legend>
<form action="{{ url('/ci_apis/store') }}" method="post" class="form-horizontal form-label-left"
      enctype="multipart/form-data">@csrf
    <div class="form-group">
        <label for="product_type">Product Type <span class="required">*</span></label>
        <select name="product_type" class="form-control" autocomplete="off">
            <option value="">Select</option>
            <option value="account">Account</option>
            <option value="loan">Loan</option>
            <option value="card">Card</option>
            <option value="account_details">Account Details</option>
            <option value="validate_myprime_id">Validate Prime Id</option>
        </select>
        <div class="error">{{ $errors->first('product_type') }}</div>
    </div>
    <div class="form-group">
        <label for="name">Name <span class="required">*</span></label>
        <input name="name" value="{{ old('name') }}" class="form-control" autocomplete="off" type="text"
               placeholder="Name"/>
        <div class="error">{{ $errors->first('name') }}</div>
    </div>
    <div class="form-group">
        <label for="endpoint">Endpoint <span class="required">*</span></label>
        <input name="endpoint" value="{{ old('endpoint') }}" class="form-control" autocomplete="off" type="text"
               placeholder="Endpoint"/>
        <div class="error">{{ $errors->first('endpoint') }}</div>
    </div>
    <div class="form-group">
        <a class="btn btn-info gradient" href="{{ url('/ci_apis/list') }}">Back</a>
        <button type="submit" class="btn-primary btn">Submit</button>
    </div>
</form>

@endsection
