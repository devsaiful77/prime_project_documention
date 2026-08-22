@extends('layouts.admin')

@section('content')

<?php
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
  {!!
    Form::open([
      'method'=>'post',
      'action' => 'HomeController@uploadProfilePhoto',
      'class'=>'form-horizontal form-label-left',
      'enctype' => 'multipart/form-data'
    ]);
    echo Form::token();
  !!}
  {{ method_field('POST') }}

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12"> </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      @IF(!empty(Auth::user()->profile_picture))                           
          <img src="{{ URL::asset('public/img/profile_picture/')}}/{{ Auth::user()->profile_picture }}?date={{date('YmdHis')}}" alt="{{ Auth::user()->name }}" class = "thumbnail img-responsive" /> 
      @ELSE
          <img src="{{ URL::asset('public/img/profile_picture/default-user.png') }}" alt="{{ Auth::user()->name }}" class = "thumbnail img-responsive" />
      @ENDIF
    </div>      
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Change
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!!
        Form::file('profile_picture', $attributes = array(
          'class'=>'form-control',
          'id'=> '',
          'label'=>false,
          'type'=>'file'));
       !!} 
    </div>
    <div class="error"><b>{{ $errors->first('profile_picture') }}</b></div>
  </div>
  <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12"> </label>
      <div class="col-md-6 col-sm-6 col-xs-12 text-left">
          <ul>
              <li> File Must be <b>JPG/JPEG</b> or <b>PNG</b>. </li>
              <li> Maximum file size is <b>1 MB</b>.</li>
          </ul>          
      </div>
  </div>
  <div class="ln_solid">&nbsp;</div>
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {!!
          Form::submit('Submit',array(
            'class'=>'btn btn-primary gradient',
            'title'=>'Add',
            'escape'=>false
          ));
        !!} 
      </div>
  </div>   
  <?php echo Form::close(); ?>
<script type="text/javascript">
$(':input').removeAttr('required')
</script>
@endsection
