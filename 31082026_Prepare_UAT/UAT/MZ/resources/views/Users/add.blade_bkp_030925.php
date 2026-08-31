@extends('layouts.admin')

@section('content')

@IF ($id != null)
{!!
    Form::open([
      'method'=>'post',
      'action' => ['UsersController@update',encrypt($id)] ,

      'id'=>'formId',
      'class'=>'form-horizontal form-label-left',
      'enctype' => 'multipart/form-data'
    ]);
!!}
@ELSE
{!!
    Form::open([
      'method'=>'post',
      'action' => ['UsersController@store'] ,
      'id'=>'formId',
      'class'=>'form-horizontal form-label-left',
      'enctype' => 'multipart/form-data'
    ]);
!!}
@ENDIF
  {!! Form::token(); !!}

  {{-- method_field('POST') --}}



  @if ($id == null)
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="userIdInput">User ID <span class="required">*</span>
    </label>
      <div class="col-md-6 col-sm-6 col-xs-9 d-flex">
        <input type="text" class="form-control" id="userIdInput" autocomplete="off" placeholder="User ID">
        <button type="button" class="btn btn-info gradient ml-2" onclick="getUserInfoByUserId()">Search</button>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-9 d-none" id="spinner">
        <span>Please wait <i class="fa fa-spinner fa-spin"></i></span>
      </div>
  </div>
  @endif

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_id">User ID <small>(Use for Login)</small><span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('user_id',(!empty($userInfo["user_id"])) ? $userInfo["user_id"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'XXXXXXXXXXXXXXXX',
            !empty($userInfo["user_id"]) ? 'readonly="readonly"' : ''
          ]);
        !!}
      </div>
      <div class="error">{{ $errors->first('user_id') }}</div>
  </div><!-- User ID -->

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('name',(!empty($userInfo["name"])) ? $userInfo["name"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'Name'
          ]);
        !!}
      </div>
      <div class="error">{{ $errors->first('name') }}</div>
  </div><!-- Name -->

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_id">Employee ID<span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('emp_id',(!empty($userInfo["emp_id"])) ? $userInfo["emp_id"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'XXXXXXXXXXXXXXXX',
            !empty($userInfo["emp_id"]) ? 'readonly="readonly"' : ''
          ]);
        !!}
      </div>
      <div class="error">{{ $errors->first('user_id') }}</div>
  </div><!-- Employee ID -->

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="designation">Designation <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!!
        Form::textarea('designation',(!empty($userInfo["designation"])) ? $userInfo["designation"] : ''  ,[
          'rows'=>3,
          'class' => 'form-control',
          'label'=>false,
          'autocomplete'=>'off',
          'placeholder'=>'Designation'
        ]);
      !!}
    </div>
    <div class="error">{{ $errors->first('designation') }}</div>
  </div><!-- Designation -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_id">Role
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {{ Form::select('role_id', [null=>'Please Select'] +  $allRoleData, (!empty($currentRoleId)) ? $currentRoleId : "", ['class'=>'form-control', 'id'=>'roleDropdown']) }}
    </div>
    <div class="error">{{ $errors->first('role_id') }}</div>
  </div><!-- Role -->

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="size_unit">Status
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('status', [
                '0' => 'InActive',
                '1' => 'Active',
                '-2' => 'Close',
            ], (!empty($userInfo['status'])) ? $userInfo['status'] : "", ['class'=>'form-control', 'id'=>'statusDropdown']) }}
        </div>
        <div class="error">{{ $errors->first('status') }}</div>
    </div><!-- status -->


  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile_no">Mobile No <span class="required">*</span>
    </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!!
          Form::text('mobile_no',(!empty($userInfo["mobile_no"])) ? $userInfo["mobile_no"] : '' ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'01XXXXXXXXX'
          ]);
        !!}
      </div>
      <div class="error">{{ $errors->first('mobile_no') }}</div>
  </div><!-- Mobile NO -->

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <small>(Use for Login)</small> <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!!
        Form::text('email',(!empty($userInfo["email"])) ? $userInfo["email"] : '' ,[
          'class' => 'form-control',
          'label'=>false,
          'autocomplete'=>'off',
          'type'=>'text',
          'placeholder'=>'example@primebank.com.bd',
          !empty($userInfo["email"]) ? 'readonly="readonly"' : ''
        ]);
      !!}
    </div>
    <div class="error">{{ $errors->first('email') }}</div>
  </div><!-- Email -->

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!!
        Form::textarea('remarks',(!empty($userInfo["remarks"])) ? $userInfo["remarks"] : ''  ,[
          'rows'=>3,
          'class' => 'form-control',
          'label'=>false,
          'autocomplete'=>'off',
          'placeholder'=>'Remarks'
        ]);
      !!}
    </div>
    <div class="error">{{ $errors->first('remarks') }}</div>
  </div><!-- remarks -->



  <div class="form-group">
    <div class="col-md-12 pt-3 text-center">
      <strong>Name:</strong> {{$userInfo['name'] ?? 'N/A'}} | <strong> Desgination:</strong> {!! str_replace('#',',',$userInfo['designation'] ?? 'N/A') !!} | <strong>Email:</strong> {{$userInfo['email'] ?? 'N/A'}}
    </div>
  </div>

<hr />
<?php
$subGroupChecked  = "";
$groupChecked  = "";
$departmentChecked  = "";
$divisionChecked = "";
$dataForViewObj = "";
if(!empty($dataForAudit)){
    $dataForViewObj = json_encode((object)$dataForAudit);
}

if (!empty($dataForView->division_id) || !empty($dataForView->is_division_head)) {
  $divisionChecked  = "checked";
} elseif (!empty($dataForView->department_id) || !empty($dataForView->is_department_head)) {
  $departmentChecked  = "checked";
} elseif (!empty($dataForView->group_info_id) || !empty($dataForView->is_group_info_head)) {
  $groupChecked  = "checked";
} elseif (!empty($dataForView->subgroup_info_id) || !empty($dataForView->unit_id) || !empty($dataForView->is_unit_head)) {
  $subGroupChecked  = "checked";
}

?>
<div class="form-group ">
  <div class="col-md-6 offset-md-3 col-12">
    <div class="d-flex">
      <div class="custom-control custom-radio me-3">
        <input type="radio" id="subgroup" name="type" value="Subgroup" class="custom-control-input" {{$subGroupChecked}} required/>
        <label class="custom-control-label p-0" for="subgroup">Subgroup</label>
      </div>
      <div class="custom-control custom-radio me-3">
        <input type="radio" id="group" name="type" value="Group" class="custom-control-input" {{$groupChecked}} required/>
        <label class="custom-control-label p-0" for="group">Group</label>
      </div>
      <div class="custom-control custom-radio me-3">
        <input type="radio" id="department" name="type" value="Department" class="custom-control-input" {{$departmentChecked}} required/>
        <label class="custom-control-label p-0" for="department">Department Head</label>
      </div>
      <div class="custom-control custom-radio">
        <input type="radio" id="division" name="type" value="Division" class="custom-control-input green" {{$divisionChecked}} required/>
        <label class="custom-control-label p-0" for="division">Division Head</label>
      </div>
    </div>
  </div>
  
</div>
<hr>

<div id="showSubgroup">
    <input type="hidden" value="{{$dataForViewObj}}" name="oldDataForAudit">
  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="subgroup">For Subgroup<span class="required">*</span> </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <select class="form-control" name="subgroup_info_id" id="subgroup">
        <option value="">Select Subgroup</option>
        @inject('subgroups','App\Services\UtilService')
        @php
          $subGroupInfoId = (!empty($dataForView->subgroup_info_id)) ? $dataForView->subgroup_info_id : old('subgroup_info_id');
        @endphp
        {!! $subgroups->getAllSubGroups($subGroupInfoId) !!}
      </select>
      {{--{{ Form::select('subgroup_info_id[]', [null=>'Please Select'] +$allSubgroupData, (!empty($dataForView['subgroup_info_id'])) ? explode(',',$dataForView['subgroup_info_id'])  : "", ['class'=>'form-control select2']) }}--}}
    </div>
    <div class="error">{{ $errors->first('subgroup_info_id') }}</div>
  </div><!-- Unit -->
  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Units<span class="required">*</span> </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      @inject('units','App\Services\UtilService')
      @php
        $unitId = array();
        if(!empty(old('unit_id'))) {
          $unitId = old('unit_id');
        } elseif(!empty($dataForView->unit_id)) {
          $unitId = explode(',',$dataForView->unit_id);
        }
      @endphp
      {!! $units->getAllUnitOrPermission($unitId); !!}
    </div>
    <div class="error">{{ $errors->first('unit_id') }}</div>
  </div><!-- Unit -->
    <div class="form-group row">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Allow Escalation Email</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" value="0" name="is_email_allow">
         <label class="checkbox-inline">
        <input type="checkbox" name="is_email_allow" value="1"  @if(!empty($dataForView->is_email_allow) &&$dataForView->is_email_allow==1) checked @endif> Yes</label>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div id="showGroup" style='display: none'>
    <div class="form-group row">
      <label class="control-label col-md-2 col-sm-2 col-xs-12" for="group">For Group User</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="group_info_id" id="group">
          <option value="">Select Group</option>
          @inject('groups','App\Services\UtilService')
          @php
            $groupInfoId = (!empty($dataForView->group_info_id)) ? $dataForView->group_info_id : old('group_info_id');
          @endphp

          {!! $groups->getAllGroups($groupInfoId) !!}
        </select>
        {{--{{ Form::select('group_info_id', [null=>'Please Select'] +  $allGroupData, (!empty($dataForView['group_info_id'])) ? $dataForView['group_info_id'] : "", ['class'=>'form-control']) }}--}}

      </div>
      <div class="error">{{ $errors->first('group_info_id') }}</div>

    </div><!-- Division -->
      <div class="form-group row">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Group Head</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label class="radio-inline" >
          {{--  {{ Form::checkbox('unit_id', '1',($dataForView['is_group_head'] == '1'), array()) }}--}}
            <input type="checkbox" name="group_head" value="1" class="" onclick="return false;" checked>
            Yes
          </label>
          <!-- <label class="radio-inline" >
            <input type="radio" name="group_head" value="0" class="custom-control-input red">
           {{-- {{ Form::radio('unit_id', '0',($dataForView['is_group_head'] == '0'), array()) }}--}}
            No
          </label> -->
        </div>
        <div class="error">{{ $errors->first('is_group_head') }}</div>
      </div>
</div>
<div id="showDepartment" style='display: none'>
  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="department">For Department User </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
     {{-- {{ Form::select('department_id', [null=>'Please Select'] +  $allDepartmentData, (!empty($dataForView['department_id'])) ? $dataForView['department_id'] : "", ['class'=>'form-control']) }}--}}
      <select class="form-control" name="department_id" id="department">
        <option value="">Select Department</option>
        @inject('department','App\Services\UtilService')
        @php
          $departmentId = (!empty($dataForView->department_id)) ? $dataForView->department_id : old('department_id');
        @endphp
        {!! $department->getAllDepartments($departmentId) !!}
      </select>
    </div>
    <div class="error">{{ $errors->first('department_id') }}</div>
  </div><!-- Department -->

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="division_id">Department Head</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <label class="radio-inline" >
         <input type="checkbox" name="department_head" value="1" class="" onclick="return false;" checked> Yes
      </label>
      <!-- <label class="radio-inline" >
        <input type="radio" name="department_head" value="0" class="custom-control-input red">
        {{--{{ Form::radio('is_department_head', '0',($dataForView['is_department_head'] == '0'), array()) }}--}}
        No
      </label> -->
    </div>
    <div class="error">{{ $errors->first('is_department_head') }}</div>
  </div>
</div>


<div id="showDivision" style='display: none'>
  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="division">For Division User </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
     {{-- {{ Form::select('division_id', [null=>'Please Select'] +  $allDivisionData, (!empty($dataForView['division_id'])) ? $dataForView['division_id'] : "", ['class'=>'form-control']) }}--}}
      <select class="form-control" name="division_id" id="division">
        <option value="">Select Division</option>
        @inject('division','App\Services\UtilService')
        @php
          $divisionId = (!empty($dataForView->division_id)) ? $dataForView->division_id : old('division_id');
        @endphp
        {!! $division->getAllDivisions($divisionId) !!}
      </select>
    </div>
    <div class="error">{{ $errors->first('division_id') }}</div>
  </div><!-- Department -->

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="division_id">Division Head</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <label class="radio-inline" >
         <input type="checkbox" name="division_head" value="1" class="" onclick="return false;" checked> Yes
      </label>
      <!-- <label class="radio-inline" >
        <input type="radio" name="department_head" value="0" class="custom-control-input red">
        {{--{{ Form::radio('is_department_head', '0',($dataForView['is_department_head'] == '0'), array()) }}--}}
        No
      </label> -->
    </div>
    <div class="error">{{ $errors->first('is_department_head') }}</div>
  </div>
</div>


  <div class="ln_solid">&nbsp;</div>
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <?php $additionalParams = (!empty($_GET)) ? '?'.http_build_query($_GET) : ""; ?>
        {{ Form::hidden('additionalParams',$additionalParams) }}

        {!!
          Form::submit((!empty($id)) ? 'Update':'Submit',array(
            'class'=>'btn btn-primary gradient',
            'title'=>'Add',
            'escape'=>false
          ));
        !!}
        <button type="button" class="btn btn-info gradient" onclick="cancel('/Users{{ $additionalParams }}')">Back</button>
      </div>
  </div>

{!! Form::close(); !!}


<script>
    function getUserInfoByUserId() {
        let userId = $('#userIdInput').val(); // Input field where user enters the user ID

        if (!userId) {
            alert("Please enter a valid User ID");
            return;
        }

        $('#spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("getUserInfo") }}',
            method: 'GET',
            data: {
                user_id: userId,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                console.log('Fetching user info...');
            },
            success: function(response) {
              $('#spinner').addClass('d-none');
                if (response.success) {
                    let userInfo = response.data;

                    // Populate form fields
                    $('input[name="name"]').val(userInfo.name);
                    $('textarea[name="designation"]').val(userInfo.designation);
                    $('input[name="mobile_no"]').val(userInfo.phone);
                    $('input[name="emp_id"]').val(userInfo.emp_id);
                    $('input[name="user_id"]').val(userInfo.user_id).prop('readonly', true);
                    $('input[name="email"]').val(userInfo.email).prop('readonly', true);
                } else {
                    alert('User not found!');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error fetching user info:', error);
                alert('There was an error fetching user information. Please try again.');
                $('#spinner').addClass('d-none');
            },
            complete: function() {
                console.log('Request complete.');
            }
        });
    }



  function myPasswordFunction() {
      var x = document.getElementById("myInput");
      if (x.type === "password") {
          x.type = "text";
      } else {
          x.type = "password";
      }
  }
</script>

<script>
    $(function () {
        // $("#roleDropdown").change(function() {
        //     // Pure JS
        //     var selectedVal = this.value;
        //     var selectedText = this.options[this.selectedIndex].text;

        //     // jQuery
        //     // var selectedVal = $(this).find(':selected').val();
        //     // var selectedText = $(this).find(':selected').text();
        // });


      if ($("#subgroup").is(":checked")) {
        $("#showSubgroup").show();
        $("#showGroup").hide();
        $("#showDepartment").hide();
        $("#showDivision").hide();
      }
      if ($("#group").is(":checked")) {
        $("#showGroup").show();
        $("#showSubgroup").hide();
        $("#showDepartment").hide();
        $("#showDivision").hide();
      }
      if ($("#department").is(":checked")) {
        $("#showDepartment").show();
        $("#showSubgroup").hide();
        $("#showGroup").hide();
        $("#showDivision").hide();
      }
      if ($("#division").is(":checked")) {
        $("#showDivision").show();
        $("#showDepartment").hide();
        $("#showSubgroup").hide();
        $("#showGroup").hide();

      }

      $("#subgroup").click(function () {
        if ($(this).is(":checked")) {
          $("#showSubgroup").show();
          $("#showGroup").hide();
          $("#showDepartment").hide();
          $("#showDivision").hide();
        }
      });
      $("#group").click(function () {
        if ($(this).is(":checked")) {
          $("#showGroup").show();
          $("#showSubgroup").hide();
          $("#showDepartment").hide();
          $("#showDivision").hide();
        }
      });
      $("#department").click(function () {
        if ($(this).is(":checked")) {
          $("#showDepartment").show();
          $("#showSubgroup").hide();
          $("#showGroup").hide();
          $("#showDivision").hide();
        }
      });
      $("#division").click(function () {
        if ($(this).is(":checked")) {
          $("#showDivision").show();
          $("#showDepartment").hide();
          $("#showSubgroup").hide();
          $("#showGroup").hide();
        }
      });

        var selectedVal = $("#roleDropdown").val();
        if (selectedVal > 0) {
            $("#subgroup").prop('required', false);
            $("#group").prop('required', false);
            $("#department").prop('required', false);
            $("#division").prop('required', false);
        }

        $("#roleDropdown").change(function() {
            selectedVal = this.value;
            if (selectedVal > 0) {
                $("#subgroup").prop('required', false);
                $("#group").prop('required', false);
                $("#department").prop('required', false);
                $("#division").prop('required', false);
            }
        });

    });
  </script>
@endsection
