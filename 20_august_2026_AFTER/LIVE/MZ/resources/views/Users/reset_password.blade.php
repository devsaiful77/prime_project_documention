@extends('layouts.admin')
@section('content')
    
{!!
  Form::open([
    'method'=>'post',
    'action' => ['UsersController@resetPasswordSubmit'] ,
    'class'=>'form-horizontal form-label-left',
    'enctype' => 'multipart/form-data'
  ]); 
!!} 

  {!! Form::token(); !!}
  
  <div class="ln_solid">&nbsp;</div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="password" class="form-control" id="password" name="password" placeholder="XXXXXX" autocomplete="off" value="{{ old('password') }}">
    </div>
    <div class="error">
      @IF($errors->has('password'))
        {{ $errors->first('password') }}
      @ELSE
        Minimum 8 length with alpha, digit &amp; special character
      @ENDIF
    </div>

  </div><!-- Password -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirmation">Confirm Password<span class="required">*</span> </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="password" class="form-control" id="password_confirmation" autocomplete="off" name="password_confirmation" placeholder="XXXXXX"  value="{{ old('password_confirmation') }}">
    </div>
    <div class="error">{{ $errors->first('password_confirmation') }}</div>
	</div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
  	<div class="col-md-6 col-sm-6 col-xs-12">
  		<button class="btn btn-primary" type="submit"><i class="fa fa-"></i>Change</button>
  	</div>
  </div>
{!! Form::close(); !!}
<script type="text/javascript">
    /*function Validate() {
        var password = document.getElementById("password").value;
		if(password.length < 6){
			alert("Minimum Length is 6.");
            return false;
		}
        var confirmPassword = document.getElementById("password_confirmation").value;
        if (password != confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }*/
</script>

@endsection