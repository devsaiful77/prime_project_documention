@extends('layouts.admin')

@section('content')

{!!
    Form::open([
      'method'=>'post',
      'action' => ['UsersController@updateAccessControl',encrypt($id)] ,
      'autocomplete'=>'off',
      'id'=>'formId',
      'class'=>'form-horizontal form-label-left',
      'enctype' => 'multipart/form-data'
    ]); 
!!} 
 
  {!! Form::token(); !!}
 
  <div class="form-group">
    <div class="col-md-12">
      <strong>Name:</strong> {{$userInfo['name']}}<br/><strong>
      Desgination:</strong> {!! str_replace('#',',',$userInfo['designation']) !!}<br/>
      <strong>Email:</strong> {{$userInfo['email']}}<br/>
    </div>
  </div>
  <div class="ln_solid">&nbsp;</div>
  <div class="clearfix">&nbsp;</div>
  <div class="form-group">
    <div class="col-md-12">
        @IF(!empty($moduleList))
          <div class="col-md-6 col-lg-4">
            <div class="table-responsive">
              <table class="table table-bordered table mb30">
                <colgroup>
                  <col width="5%"></col>
                  <col width="65%"></col>
                  <col width="30%"></col>
                </colgroup>
                <tr class="info">
                  <td class="vcenter text-center"><strong>Sl.</strong></td>
                  <td class="vcenter text-center"><strong>Module Name</strong></td>
                  <td class="vcenter text-center"><label class="checkbox-inline"><input type="checkbox" value="1" class="checkAll" style="display:none;"><strong>Check All</strong></label></td>
                  
                </tr>
                @FOREACH ($moduleList as $key=> $mduleLst)
                  <?php
                    $checked = '';
                    if (!empty($mduleLst['control'])) {
                      if ( $mduleLst['control']['status'] == 1) {
                        $checked = 'checked';
                      }
                    }
                  ?>
                  <tr class="{{($key %2 == 0 ? 'primary' : 'default')}}">
                    <td class="vcenter text-center">{{$key+1}})</td>
                    <td class="vcenter text-left">{{$mduleLst['name']}}</td>
                    <td class="vcenter text-center">
                      <input type="hidden" name="permitedAction[{{$mduleLst['id']}}][module_name]" value="{{$mduleLst['name']}}">
                      <input type="hidden" name="permitedAction[{{$mduleLst['id']}}][status]" value="0">
                      <input type="checkbox" class="commonClassForCheckbox" name="permitedAction[{{$mduleLst['id']}}][status]" value="1" {{$checked}}>
                    </td>
                  </tr>
                @ENDFOREACH
              </table>
            </div>
          </div>
        @ENDIF
    </div>
  </div><!-- Module -->

  <div class="clearfix">&nbsp;</div>
  <div class="ln_solid">&nbsp;</div>
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::submit('ACL Submit',array(
            'class'=>'btn btn-success gradient',
            'title'=>'ACL Submit',
            'escape'=>false
          ));
        !!} 
        <button type="button" class="btn btn-info gradient" onclick="cancel('/Users')">Back</button> 
      </div>
  </div>   

{!! Form::close(); !!}
<script type="text/javascript">
$(document).off('change','.checkAll');
$(document).on('change','.checkAll',function( event ){
  if($('.checkAll').is(":checked")) {
    $('.commonClassForCheckbox').prop('checked', true);
  } else {
    $('.commonClassForCheckbox').prop('checked', false);
  }
});
</script>
@endsection