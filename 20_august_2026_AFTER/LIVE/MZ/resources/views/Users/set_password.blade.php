@extends('layouts.admin')

@section('content')
  {!!
      Form::open([
        'method'=>'post',
        'action' => ['UsersController@updatePassword',($currentPath == "ChangePassword") ? "ChangePassword" : encrypt($id)] ,
        'autocomplete'=>'off',
        'id'=>'formId',
        'class'=>'form-horizontal form-label-left',
        'enctype' => 'multipart/form-data'
      ]);
  !!}

  {!! Form::token(); !!}

  @IF($currentPath == "ChangePassword")
    {{ Form::hidden('from_user_request', 'ChangePassword') }}
  @ENDIF
  <div class="form-group">
    <div class="col-md-12 col-md-offset-2">
      <strong>Name:</strong> {{$userInfo['name']}}<br/>
      <strong> Desgination:</strong> {!! str_replace('#',',',$userInfo['designation']) !!}<br/>
      <strong>Email:</strong> {{$userInfo['email']}}<br/>
    </div>

  </div>
  <div class="ln_solid">&nbsp;</div>
  {{-- <p><strong class="error">*Password minimum 8 length with alpha, digit &amp; special character</strong></p> --}}
  <div class="form-group">

    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="admin_password">{{($currentPath == "ChangePassword") ? "Current Password" : "Admin Password"}} <span class="required">*</span>
    </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::password('admin_password' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'XXXXXXXXXXXXXXXX'
          ]);
        !!}
    </div>
    <strong class="error">{{ $errors->first('admin_password_incorrect') }}{{ $errors->first('admin_password') }}</strong>

  </div><!-- Login Password -->

  <div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="user_password">New Password <span class="required">*</span> </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!!
        Form::text('user_password','' ,[
          'class' => 'form-control',
          'label'=>false,
          'autocomplete'=>'off',
          'type'=>'text',
          'placeholder'=>'XXXXXXXXXXXXXXXX'
        ]);
      !!}
      
    </div>
    <strong class="error">
      @IF($errors->has('user_password'))
        {{ $errors->first('user_password') }}
      @ELSE
        Password minimum 8 length with alpha, digit &amp; special character
      @ENDIF
    </strong>
  </div><!-- User Password -->
  <div class="ln_solid">&nbsp;</div>
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
        <?php $additionalParams = (!empty($_GET)) ? '?'.http_build_query($_GET) : ""; ?>
        {{ Form::hidden('additionalParams',$additionalParams) }}
        {!!
          Form::submit('Set Password',array(
            'class'=>'btn btn-success gradient',
            'title'=>'Add',
            'escape'=>false
          ));
        !!}
        <button type="button" class="btn btn-info gradient" onclick="cancel('/Users')">Back</button>
      </div>
  </div>

  {!! Form::close(); !!}
@endsection
