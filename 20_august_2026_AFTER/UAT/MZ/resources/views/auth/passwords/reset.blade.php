@extends('layouts.login_layout')

@section('content')
<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">
    
    <div class="input-group mb15">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
        <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? '' }}" autofocus placeholder="Email">
    </div><!-- input-group -->
    @if ($errors->has('email')) <div class="error help-block"> <strong>{{ $errors->first('email') }}</strong></div> @endif

    <div class="input-group mb15">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
        <input id="password" type="password" class="form-control" name="password" value="{{ $password ?? '' }}" autofocus placeholder="Password">
    </div><!-- input-group -->
    @if ($errors->has('password')) <div class="error help-block"> <strong>{{ $errors->first('password') }}</strong></div> @endif

    <div class="input-group mb15">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="{{ $password_confirmation $password_confirmation ?? '' }}" autofocus placeholder="Confirm Password">
    </div><!-- input-group -->
    @if ($errors->has('password_confirmation')) <div class="error help-block"> <strong>{{ $errors->first('password_confirmation') }}</strong></div> @endif
    <div class="clearfix">
        <div class="pull-left">
            <button type="submit" class="btn btn-primary"> Reset Password </button>
        </div>
    </div>    
</form>
<script> $(".login-page-title").text("Reset Password"); </script>
@endsection
