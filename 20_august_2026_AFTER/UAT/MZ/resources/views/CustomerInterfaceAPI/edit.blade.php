@extends('layouts.admin')
@section('content')
<legend class="text-center">{{ $title_for_layout }}</legend>
<form action="{{ url('/ci_apis/update/'.encrypt($dataForView["id"])) }}" method="post"
      class="form-horizontal form-label-left" enctype="multipart/form-data">@csrf
    <div class="form-group">
        <label for="product_type">Api Type <span class="required">*</span></label>
        <select name="product_type" class="form-control" autocomplete="off">
            <option value="">Select</option>
	    <option {{ $dataForView['product_type'] == 'account' ? 'selected' : '' }} value="account">Account</option>
	    <option {{ $dataForView['product_type'] == 'loan' ? 'selected' : '' }} value="loan">Loan</option>
	    <option {{ $dataForView['product_type'] == 'card' ? 'selected' : '' }} value="card">Card</option>
	    <option {{ $dataForView['product_type'] == 'account_details' ? 'selected' : '' }} value="account_details">Account Details</option>
	    <option {{ $dataForView['product_type'] == 'validate_myprime_id' ? 'selected' : '' }} value="validate_myprime_id">Validate Prime Id</option>
        </select>
        <div class="error">{{ $errors->first('product_type') }}</div>
    </div>
    <div class="form-group">
        <label for="name">Name <span class="required">*</span></label>
        <input name="name" value="{{ (!empty($dataForView["name"])) ? $dataForView["name"] : '' }}"
               class="form-control" autocomplete="off" type="text" placeholder="Name"/>
        <div class="error">{{ $errors->first('name') }}</div>
    </div>
    <div class="form-group">
        <label for="endpoint">Endpoint <span class="required">*</span></label>
        <input name="endpoint" value="{{ (!empty($dataForView["endpoint"])) ? $dataForView["endpoint"] : '' }}"
               class="form-control" autocomplete="off" type="text" placeholder="Endpoint"/>
        <div class="error">{{ $errors->first('endpoint') }}</div>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-info gradient" onclick="cancel('/ci_apis/list')">Back</button>
        <button type="submit" class="btn-primary btn">Submit</button>
    </div>
</form>
@endsection
