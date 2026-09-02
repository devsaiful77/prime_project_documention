@extends('layouts.admin')
@section('content')
    <legend class="text-center">{{ $title_for_layout }}</legend>
    <form action="{{ url('/ci_apis/update/'.encrypt($dataForView["id"])) }}" method="post"
          class="form-horizontal form-label-left" enctype="multipart/form-data">@csrf
        <div class="form-group">
            <label for="product_type">API Type <span class="required">*</span></label>
            <select name="product_type" class="form-control" autocomplete="off">
                <option value="">Select</option>
                <option value="account" {{ $dataForView['product_type'] == 'account' ? 'selected' : '' }}>Account</option>
                <option value="loan" {{ $dataForView['product_type'] == 'loan' ? 'selected' : '' }}>Loan</option>
                <option value="card" {{ $dataForView['product_type'] == 'card' ? 'selected' : '' }}>Card</option>
                <option value="account_details" {{ $dataForView['product_type'] == 'account_details' ? 'selected' : '' }}>Account Details</option>
                <option value="validate_myprime_id" {{ $dataForView['product_type'] == 'validate_myprime_id' ? 'selected' : '' }}>Validate Prime Id</option>
            </select>
            <div class="error">{{ $errors->first('product_type') }}</div>
        </div>
        <div class="form-group">
            <label for="endpoint">Endpoint <span class="required">*</span></label>
            <input name="endpoint" value="{{ (!empty($dataForView["endpoint"])) ? $dataForView["endpoint"] : '' }}"
                   class="form-control" autocomplete="off" type="text" placeholder="Endpoint"/>
            <div class="error">{{ $errors->first('endpoint') }}</div>
        </div>
        <div class="form-group mt-4">
            <button type="button" class="btn btn-info gradient" onclick="cancel('/ci_apis/list')">Back</button>
            <button type="submit" class="btn-primary btn">Submit</button>
        </div>
    </form>
@endsection
