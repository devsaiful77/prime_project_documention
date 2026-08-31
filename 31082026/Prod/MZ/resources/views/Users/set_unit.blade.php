@extends('layouts.admin')
@section('content')

{!!
    Form::open([
      'method'=>'post',
      'action' => ['UsersController@updateUnit',$id] ,
      'id'=>'formId',
      'class'=>'form-horizontal form-label-left',
      'enctype' => 'multipart/form-data'
    ]);
!!}

{!! Form::token(); !!}

  <div class="form-group">
    <div class="col-md-12 text-center">
      <strong>Name:</strong> {{$userInfo['name']}} | <strong> Desgination:</strong> {!! str_replace('#',',',$userInfo['designation']) !!} | <strong>Email:</strong> {{$userInfo['email']}}
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
  <div class="col-md-6 col-sm-offset-3 col-xs-12">
      <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="subgroup" name="type" value="Subgroup" class="custom-control-input" {{$subGroupChecked}} required/>
          <label class="custom-control-label" for="subgroup">Subgroup</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="group" name="type" value="Group" class="custom-control-input"  {{$groupChecked}}  required/>
          <label class="custom-control-label" for="group">Group</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="department" name="type" value="Department" class="custom-control-input" {{$departmentChecked}} required/>
          <label class="custom-control-label" for="department">Department Head</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="division" name="type" value="Division" class="custom-control-input green" {{$divisionChecked}} required/>
          <label class="custom-control-label" for="division">Division Head</label>
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
@endsection
@section('script')
  <script>
    $(function () {
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
    });
  </script>
@endsection
