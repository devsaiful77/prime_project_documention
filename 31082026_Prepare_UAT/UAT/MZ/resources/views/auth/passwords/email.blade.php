@extends('layouts.login_layout')

<!-- Main Content -->
@section('content')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<form action="{{ url('/password/email') }}" method="post">
    {{ csrf_field() }}
    <div class="input-group mb15">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">

    </div><!-- input-group -->

    @if ($errors->has('email')) <div class="error help-block"> <strong>{{ $errors->first('email') }}</strong></div> @endif

    <div class="clearfix">       
        <div class="pull-left">
            <button type="submit" class="btn btn-primary gradient"> Send Password Reset Link </button>
        </div>       
    </div>                         
</form>
<script> $(".login-page-title").text("Reset Password"); </script>
@endsection
