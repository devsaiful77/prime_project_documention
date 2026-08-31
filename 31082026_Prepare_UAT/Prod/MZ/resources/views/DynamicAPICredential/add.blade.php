@extends('layouts.admin')
@section('content')
<legend class="text-center">{{ $title_for_layout }}</legend>
<form action="{{ url('/DynamicAPICredential/store') }}" method="post" class="form-horizontal form-label-left"
      enctype="multipart/form-data">@csrf
    <div class="form-group">
        <label for="api">API <span class="required">*</span></label>
        <select name="api" class="form-control" autocomplete="off">
            <option value="">Select API</option>
            @foreach($cifUrl as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
            @endforeach
        </select>
        <div class="error">{{ $errors->first('api') }}</div>
    </div>
    <div class="form-group">
        <label for="user_name">Username <span class="required">*</span></label>
        <input name="user_name" class="form-control" autocomplete="off" type="text" placeholder="Username"/>
        <div class="error">{{ $errors->first('user_name') }}</div>
    </div>
    <div class="form-group">
        <label for="password">Password <span class="required">*</span></label>
        <input name="password" class="form-control" autocomplete="off" type="text" placeholder="Password"/>
        <div class="error">{{ $errors->first('password') }}</div>
    </div>
    <div class="form-group">
        <a class="btn btn-info gradient" href="{{ url('/DynamicAPICredential') }}">Back</a>
        <button type="submit" class="btn-primary btn">Submit</button>
    </div>
</form>
@endsection
