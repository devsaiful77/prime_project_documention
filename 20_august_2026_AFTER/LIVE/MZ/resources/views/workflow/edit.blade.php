@extends('layouts.admin')
@section('content')
<style>

.ui-menu {
      position: absolute; /* Ensure proper positioning */
      z-index: 1000; /* Ensure it appears above other elements */
      width: auto; /* Automatically adjust to content */
      max-width: 300px; /* Limit the maximum width */
      background-color: #fff; /* Set a clean background */
      border: 1px solid #ccc; /* Add a border for better separation */
      border-radius: 5px; /* Rounded corners */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
      padding: 5px 0; /* Space inside the menu */
  }

  .ui-menu-item {
      padding: 8px 12px; /* Space around the text */
      cursor: pointer; /* Pointer cursor on hover */
      white-space: nowrap; /* Prevent text wrapping */
      overflow: hidden; /* Hide overflow text */
      text-overflow: ellipsis; /* Add ellipsis for long text */
  }

  .ui-menu-item-wrapper {
      font-size: 14px; /* Adjust font size */
      color: #333; /* Set text color */
      transition: background-color 0.3s; /* Smooth hover effect */
  }

  .ui-menu-item-wrapper:hover {
      background-color: #f0f0f0; /* Highlight on hover */
  }
  
.table {
    --bs-table-color-type: initial;
    --bs-table-bg-type: initial;
    --bs-table-color-state: initial;
    --bs-table-bg-state: initial;
    --bs-table-color: var(--bs-body-color);
    --bs-table-bg:  !important;
    --bs-table-border-color: var(--bs-border-color);
    --bs-table-accent-bg: transparent;
    --bs-table-striped-color: var(--bs-body-color);
    --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
    --bs-table-active-color: var(--bs-body-color);
    --bs-table-active-bg: rgba(0, 0, 0, 0.1);
    --bs-table-hover-color: var(--bs-body-color);
    --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
    width: 100%;
    margin-bottom: 1rem;
    vertical-align: top;
    border-color: var(--bs-table-border-color);
}
table th{
color: #fff !important;
}
tr td:first-child {
    /* Add your styles here */
    background-color: #20B2AA;  /* Example background color */
    color: #fff !important;
}
.form-check-input{
  width: 17px;
  height: 17px;;
}
.table label{
  padding: 0rem;
}
.form-check-label {
  padding-left: 0.5rem !important;
    padding-top: 0rem;
    font-size: 1rem;
    color: #000 !important;
}
fieldset{
  padding-top: 2rem;
}
  /* If "Yes" is checked */
  input[type="radio"]:checked[value="1"] {
    background-color: #166834;
    border-color: #166834;
}

/* If "No" is checked */
input[type="radio"]:checked[value="2"] {
    background-color: #dc3545;
    border-color: #dc3545;
}
#addGroupItem{
  color: #fff !important;
}
</style>

@php
    $touch = $touch_groups->pluck('id')->toArray();
    $non_touch = $non_touchGroups->pluck('id')->toArray();
@endphp
<legend>Workflow Edit</legend>
  <form class="pb-5" method="post" action="{{ url('workflow/'.$row->issue_workflow_id) }}">
    @csrf
    <input type="hidden" name="touch_group_old" value="{{ json_encode($touch) }}">
    <input type="hidden" name="non_touch_group_old" value="{{ json_encode($non_touch) }}">
    <input type="hidden" value="{{ $row->issue_workflow_id }}" name="issue_workflow_id">
    <div class="form-group row">
      <label class="control-label col-md-2 col-sm-2 col-lg-2" for="issueType">Flow<span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-lg-6">
        <div class="form-check form-check-inline">
            <input type="radio" id="regular" name="flow_type" onclick="return false;" value="{{ \App\Enum\FlowEnum::REGULAR }}" class="form-check-input" @if($row->flow_type == \App\Enum\FlowEnum::REGULAR) checked @endif />
            <label class="form-check-label p-0" for="regular">Auto Flow</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" id="forward" name="flow_type" onclick="return false;" value="{{ \App\Enum\FlowEnum::FORWARD }}" class="form-check-input" @if($row->flow_type == \App\Enum\FlowEnum::FORWARD) checked @endif />
            <label class="form-check-label p-0" for="forward">Forward Flow</label>
        </div>
        @error('flow_type') 
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    
    </div>
    <div class="form-group row pt-2">
      <label class="control-label col-md-2 col-sm-2 col-lg-2" for="issueType">Issues Type<span class="required">*</span> </label>
      <div class="col-md-6 col-sm-6 col-lg-6">
        <div class="form-check form-check-inline">
            <input type="radio" id="wform" name="type" onclick="return false;" value="wform" class="form-check-input" @if(get_issue_type($row->issue_id) == 'wform') checked @endif />
            <label class="form-check-label p-0" for="wform">Service Request</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" id="complaint" name="type" onclick="return false;" value="complaint" class="form-check-input green" @if(get_issue_type($row->issue_id) == 'complaint') checked @endif />
            <label class="form-check-label p-0" for="complaint">Complaint</label>
        </div>
        @error('type') 
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    
    </div>
    <div class="form-group row pt-3">
      <label class="control-label col-md-2 col-sm-2 col-lg-2" for="name">Issues <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-lg-6">
        <select class="form-control" name="issue_id" id="issueItems" required readonly>
          @inject('issues','App\Services\UtilService')
          {!! $issues->getIssueByID($row->issue_id) !!}
        </select>
      </div>
    </div>
    {{-- <div class="form-group row">
      <label class="control-label col-md-1 col-sm-1 col-xs-1" for="name">Touch Group<span class="required">*</span> </label>
      <div class="col-md-11 col-sm-11 col-xs-11">
        <div class="row">
          @foreach($all_touch_group as $key=>$r)
            <?php
            $group_id = $r->id;
            $workflowTouchGroup = (!empty($touch_groups[$group_id])) ? $touch_groups[$group_id] : new \stdClass();
            $workflowTouchGroup->name = (!empty($workflowTouchGroup->name)) ? $workflowTouchGroup->name : '';

            $workflowTouchGroup->touch_checker = (isset($workflowTouchGroup->touch_checker)) ? $workflowTouchGroup->touch_checker : 2;
            $workflowTouchGroup->hold_maker = (isset($workflowTouchGroup->hold_maker)) ? $workflowTouchGroup->hold_maker : 2;
            $workflowTouchGroup->hold_checker = (isset($workflowTouchGroup->hold_checker)) ? $workflowTouchGroup->hold_checker : 2;
            $workflowTouchGroup->sla_maker = (isset($workflowTouchGroup->sla_maker)) ? $workflowTouchGroup->sla_maker : '0';
            $workflowTouchGroup->sla_checker = (isset($workflowTouchGroup->sla_checker)) ? $workflowTouchGroup->sla_checker : '0';
            $workflowTouchGroup->attach_maker = (isset($workflowTouchGroup->attach_maker)) ? $workflowTouchGroup->attach_maker : 2;
            $workflowTouchGroup->attach_checker = (isset($workflowTouchGroup->attach_checker)) ? $workflowTouchGroup->attach_checker : 2;
            $workflowTouchGroup->attach_maker_item = (!empty($workflowTouchGroup->attach_maker_item)) ? $workflowTouchGroup->attach_maker_item : 0;
            $workflowTouchGroup->attach_checker_item = (!empty($workflowTouchGroup->attach_checker_item)) ? $workflowTouchGroup->attach_checker_item : 0;

            $existsTouchGroup = (!empty($touch_groups[$group_id])) ? 'checked' : '';

            ?>
            <div class="col-md-3">
              <input type="hidden" value="{{ $group_id }}" name="priority_groups[]">
              <fieldset>
                <legend>{{ $r->name }}:</legend>
				        <div class="form-group">
                  <label>Is Customer Touch Group <input type="checkbox" value="{{ $group_id }}" {{$existsTouchGroup}} name="touch_group[]"></label>
                </div>
                <table class="table table-bordered">
                  <thead>
                    <tr style="background-color: darkcyan">
                      <th style="background-color: darkslategray;color: white">#</th>
                      <th style="color: white">Maker</th>
                      <th style="color: white">Checker</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td id="colorOfTableHeadLightSeaGreenBackground"><b>Touch</b></td>
                      <td id="colorOfRightBorder"></td>
                      <td>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="rd_1_{{ $group_id }}" name="priority_touch_checker_{{ $group_id }}" value="1" class="custom-control-input green" @if($workflowTouchGroup->touch_checker==1) checked @endif>
                          <label class="custom-control-label green" for="rd_1_{{ $group_id }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="rd_2_{{ $group_id }}" name="priority_touch_checker_{{ $group_id }}" value="2" class="custom-control-input red"  @if($workflowTouchGroup->touch_checker==0) checked @endif>
                          <label class="custom-control-label red" for="rd_2_{{ $group_id }}">&nbsp;&nbsp;No</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td id="colorOfTableHeadLightSeaGreenBackground"><b>Hold</b></td>
                      <td id="colorOfRightBorder">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_3_{{ $r->id }}" class="custom-control-input"
                                name="priority_hold_maker_{{ $group_id }}" value="1" @if($workflowTouchGroup->hold_maker==1) checked @endif>
                            <label class="custom-control-label green" for="rd_3_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_4_{{ $r->id }}" class="custom-control-input"
                                name="priority_hold_maker_{{ $group_id }}" value="2"  @if($workflowTouchGroup->hold_maker==0) checked @endif>
                            <label class="custom-control-label red" for="rd_4_{{ $r->id }}">&nbsp;&nbsp;No</label>
                        </div>
                      </td>
                      <td>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_5_{{ $r->id }}" class="custom-control-input"
                                name="priority_hold_checker_{{ $group_id }}" value="1" @if($workflowTouchGroup->hold_checker==1) checked @endif>
                            <label class="custom-control-label green" for="rd_5_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_6_{{ $r->id }}" class="custom-control-input"
                                name="priority_hold_checker_{{ $group_id }}" value="2" @if($workflowTouchGroup->hold_checker==0) checked @endif>
                            <label class="custom-control-label red" for="rd_6_{{ $r->id }}">&nbsp;&nbsp;No</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td id="colorOfTableHeadLightSeaGreenBackground"><b>SLA</b></td>
                      <td id="colorOfRightBorder"><input type="number" placeholder="" class="workflow-input" name="priority_sla_maker_{{ $group_id }}" value="@if($workflowTouchGroup->sla_maker){{$workflowTouchGroup->sla_maker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)"></td>
                      <td>
                        <input type="number" placeholder="" class="workflow-input" name="priority_sla_checker_{{ $group_id }}" value="@if($workflowTouchGroup->sla_checker){{$workflowTouchGroup->sla_checker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                      </td>
                    </tr>
                    <tr>
                      <td id="colorOfTableHeadLightSeaGreenBackground"><b>Attach</b></td>
                      <td id="colorOfRightBorder">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_7_{{ $r->id }}" class="custom-control-input"
                                   name="priority_attach_maker_{{ $group_id }}" value="1" @if($workflowTouchGroup->attach_maker==1) checked @endif>
                            <label class="custom-control-label green" for="rd_7_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_8_{{ $r->id }}" class="custom-control-input"
                                   name="priority_attach_maker_{{ $group_id }}" value="2" @if($workflowTouchGroup->attach_maker==0) checked @endif>
                            <label class="custom-control-label red" for="rd_8_{{ $r->id }}">&nbsp;&nbsp;No</label>
                        </div>
                        <input type="number" placeholder="" class="workflow-input" name="priority_attach_maker_item_{{ $group_id }}" value="@if($workflowTouchGroup->attach_maker_item){{$workflowTouchGroup->attach_maker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                      </td>
                      <td>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_9_{{ $r->id }}" class="custom-control-input"
                            name="priority_attach_checker_{{ $group_id }}" value="1" @if($workflowTouchGroup->attach_checker==1) checked @endif>
                            <label class="custom-control-label green" for="rd_9_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="rd_10_{{ $r->id }}" class="custom-control-input"
                                   name="priority_attach_checker_{{ $group_id }}" value="2" @if($workflowTouchGroup->attach_checker==0) checked @endif>
                            <label class="custom-control-label red" for="rd_10_{{ $r->id }}">&nbsp;&nbsp;No</label>
                        </div>
                        <input type="number" placeholder="" class="workflow-input" name="priority_attach_checker_item_{{ $group_id }}" value="@if($workflowTouchGroup->attach_checker_item){{$workflowTouchGroup->attach_checker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </fieldset>
            </div>
          @endforeach
        </div>
      </div>
    </div> --}}

    {{-- <div class="form-group row">
      <label class="control-label col-md-1 col-sm-1 col-xs-1" for="name">Touch Group<span class="required">*</span> </label>
      <div class="col-md-11 col-sm-11 col-xs-11">
          <div class="row">
              @foreach($all_touch_group as $key=>$r)
              <?php
                  $group_id = $r->id;
                  $workflowTouchGroup = (!empty($touch_groups[$group_id])) ? $touch_groups[$group_id] : new \stdClass();
                  $workflowTouchGroup->name = (!empty($workflowTouchGroup->name)) ? $workflowTouchGroup->name : '';
  
                  // Default values
                  $workflowTouchGroup->touch_checker = (isset($workflowTouchGroup->touch_checker)) ? $workflowTouchGroup->touch_checker : 2;
                  $workflowTouchGroup->hold_maker = (isset($workflowTouchGroup->hold_maker)) ? $workflowTouchGroup->hold_maker : 2;
                  $workflowTouchGroup->hold_checker = (isset($workflowTouchGroup->hold_checker)) ? $workflowTouchGroup->hold_checker : 2;
                  $workflowTouchGroup->sla_maker = (isset($workflowTouchGroup->sla_maker)) ? $workflowTouchGroup->sla_maker : '0';
                  $workflowTouchGroup->sla_checker = (isset($workflowTouchGroup->sla_checker)) ? $workflowTouchGroup->sla_checker : '0';
                  $workflowTouchGroup->attach_maker = (isset($workflowTouchGroup->attach_maker)) ? $workflowTouchGroup->attach_maker : 2;
                  $workflowTouchGroup->attach_checker = (isset($workflowTouchGroup->attach_checker)) ? $workflowTouchGroup->attach_checker : 2;
                  $workflowTouchGroup->attach_maker_item = (!empty($workflowTouchGroup->attach_maker_item)) ? $workflowTouchGroup->attach_maker_item : 0;
                  $workflowTouchGroup->attach_checker_item = (!empty($workflowTouchGroup->attach_checker_item)) ? $workflowTouchGroup->attach_checker_item : 0;
  
                  $existsTouchGroup = (!empty($touch_groups[$group_id])) ? 'checked' : '';
              ?>
              <div class="col-md-3">
                  <input type="hidden" value="{{ $group_id }}" name="priority_groups[]">
                  <fieldset>
                      <legend>{{ $r->name }}:</legend>
                      <div class="form-group">
                          <label>Is Customer Touch Group <input type="checkbox" value="{{ $group_id }}" {{$existsTouchGroup}} name="touch_group[]"></label>
                      </div>
                      <table class="table table-bordered table-striped">
                          <thead>
                              <tr class="bg-dark text-white">
                                  <th>#</th>
                                  <th>Maker</th>
                                  <th>Checker</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr class="bg-light">
                                  <td><b>Touch</b></td>
                                  <td></td>
                                  <td>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_1_{{ $group_id }}" name="priority_touch_checker_{{ $group_id }}" value="1" class="form-check-input text-success" @if($workflowTouchGroup->touch_checker==1) checked @endif>
                                          <label class="form-check-label text-success" for="rd_1_{{ $group_id }}">Yes</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_2_{{ $group_id }}" name="priority_touch_checker_{{ $group_id }}" value="2" class="form-check-input text-danger" @if($workflowTouchGroup->touch_checker==0) checked @endif>
                                          <label class="form-check-label text-danger" for="rd_2_{{ $group_id }}">No</label>
                                      </div>
                                  </td>
                              </tr>
                              <tr class="bg-light">
                                  <td><b>Hold</b></td>
                                  <td>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_3_{{ $group_id }}" class="form-check-input text-success" name="priority_hold_maker_{{ $group_id }}" value="1" @if($workflowTouchGroup->hold_maker==1) checked @endif>
                                          <label class="form-check-label text-success" for="rd_3_{{ $group_id }}">Yes</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_4_{{ $group_id }}" class="form-check-input text-danger" name="priority_hold_maker_{{ $group_id }}" value="2" @if($workflowTouchGroup->hold_maker==0) checked @endif>
                                          <label class="form-check-label text-danger" for="rd_4_{{ $group_id }}">No</label>
                                      </div>
                                  </td>
                                  <td>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_5_{{ $group_id }}" class="form-check-input text-success" name="priority_hold_checker_{{ $group_id }}" value="1" @if($workflowTouchGroup->hold_checker==1) checked @endif>
                                          <label class="form-check-label text-success" for="rd_5_{{ $group_id }}">Yes</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_6_{{ $group_id }}" class="form-check-input text-danger" name="priority_hold_checker_{{ $group_id }}" value="2" @if($workflowTouchGroup->hold_checker==0) checked @endif>
                                          <label class="form-check-label text-danger" for="rd_6_{{ $group_id }}">No</label>
                                      </div>
                                  </td>
                              </tr>
                              <tr class="bg-light">
                                  <td><b>SLA</b></td>
                                  <td><input type="number" class="form-control" name="priority_sla_maker_{{ $group_id }}" value="@if($workflowTouchGroup->sla_maker){{$workflowTouchGroup->sla_maker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)"></td>
                                  <td>
                                      <input type="number" class="form-control" name="priority_sla_checker_{{ $group_id }}" value="@if($workflowTouchGroup->sla_checker){{$workflowTouchGroup->sla_checker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                                  </td>
                              </tr>
                              <tr class="bg-light">
                                  <td><b>Attach</b></td>
                                  <td>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_7_{{ $group_id }}" class="form-check-input text-success" name="priority_attach_maker_{{ $group_id }}" value="1" @if($workflowTouchGroup->attach_maker==1) checked @endif>
                                          <label class="form-check-label text-success" for="rd_7_{{ $group_id }}">Yes</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_8_{{ $group_id }}" class="form-check-input text-danger" name="priority_attach_maker_{{ $group_id }}" value="2" @if($workflowTouchGroup->attach_maker==0) checked @endif>
                                          <label class="form-check-label text-danger" for="rd_8_{{ $group_id }}">No</label>
                                      </div>
                                      <input type="number" class="form-control" name="priority_attach_maker_item_{{ $group_id }}" value="@if($workflowTouchGroup->attach_maker_item){{$workflowTouchGroup->attach_maker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                                  </td>
                                  <td>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_9_{{ $group_id }}" class="form-check-input text-success" name="priority_attach_checker_{{ $group_id }}" value="1" @if($workflowTouchGroup->attach_checker==1) checked @endif>
                                          <label class="form-check-label text-success" for="rd_9_{{ $group_id }}">Yes</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                          <input type="radio" id="rd_10_{{ $group_id }}" class="form-check-input text-danger" name="priority_attach_checker_{{ $group_id }}" value="2" @if($workflowTouchGroup->attach_checker==0) checked @endif>
                                          <label class="form-check-label text-danger" for="rd_10_{{ $group_id }}">No</label>
                                      </div>
                                      <input type="number" class="form-control" name="priority_attach_checker_item_{{ $group_id }}" value="@if($workflowTouchGroup->attach_checker_item){{$workflowTouchGroup->attach_checker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </fieldset>
              </div>
              @endforeach
          </div>
      </div>
  </div> --}}

  <div class="form-group row">
    <label class="control-label col-md-1 col-sm-1 col-xs-1" for="name">Touch Group<span class="required">*</span> </label>
    <div class="col-md-11 col-sm-11 col-xs-11">
        <div class="row">
            @foreach($all_touch_group as $key=>$r)
            <?php
                $group_id = $r->id;
                $workflowTouchGroup = (!empty($touch_groups[$group_id])) ? $touch_groups[$group_id] : new \stdClass();
                $workflowTouchGroup->name = (!empty($workflowTouchGroup->name)) ? $workflowTouchGroup->name : '';

                // Default values
                $workflowTouchGroup->touch_checker = (isset($workflowTouchGroup->touch_checker)) ? $workflowTouchGroup->touch_checker : 2;
                $workflowTouchGroup->hold_maker = (isset($workflowTouchGroup->hold_maker)) ? $workflowTouchGroup->hold_maker : 2;
                $workflowTouchGroup->hold_checker = (isset($workflowTouchGroup->hold_checker)) ? $workflowTouchGroup->hold_checker : 2;
                $workflowTouchGroup->sla_maker = (isset($workflowTouchGroup->sla_maker)) ? $workflowTouchGroup->sla_maker : '0';
                $workflowTouchGroup->sla_checker = (isset($workflowTouchGroup->sla_checker)) ? $workflowTouchGroup->sla_checker : '0';
                $workflowTouchGroup->attach_maker = (isset($workflowTouchGroup->attach_maker)) ? $workflowTouchGroup->attach_maker : 2;
                $workflowTouchGroup->attach_checker = (isset($workflowTouchGroup->attach_checker)) ? $workflowTouchGroup->attach_checker : 2;
                $workflowTouchGroup->attach_maker_item = (!empty($workflowTouchGroup->attach_maker_item)) ? $workflowTouchGroup->attach_maker_item : 0;
                $workflowTouchGroup->attach_checker_item = (!empty($workflowTouchGroup->attach_checker_item)) ? $workflowTouchGroup->attach_checker_item : 0;

                $existsTouchGroup = (!empty($touch_groups[$group_id])) ? 'checked' : '';
            ?>
            <div class="col-md-3">
                <input type="hidden" value="{{ $group_id }}" name="priority_groups[]">
                <fieldset>
                    <legend>{{ $r->name }}:</legend>
                    <div class="form-group pb-3">
                        <label>Is Customer Touch Group <input type="checkbox" value="{{ $group_id }}" {{$existsTouchGroup}} name="touch_group[]"></label>
                    </div>
                    <table class="table table-bordered" style="background-color: #f2f2f2;">
                        <thead style="background-color: #20B2AA; color: white;">
                            <tr>
                                <th>#</th>
                                <th>Maker</th>
                                <th>Checker</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Touch</b></td>
                                <td></td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_1_{{ $group_id }}" name="priority_touch_checker_{{ $group_id }}" value="1" class="form-check-input text-success" @if($workflowTouchGroup->touch_checker==1) checked @endif>
                                        <label class="form-check-label text-success" for="rd_1_{{ $group_id }}">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_2_{{ $group_id }}" name="priority_touch_checker_{{ $group_id }}" value="2" class="form-check-input text-danger" @if($workflowTouchGroup->touch_checker==0) checked @endif>
                                        <label class="form-check-label text-danger" for="rd_2_{{ $group_id }}">No</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Hold</b></td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_3_{{ $group_id }}" class="form-check-input text-success" name="priority_hold_maker_{{ $group_id }}" value="1" @if($workflowTouchGroup->hold_maker==1) checked @endif>
                                        <label class="form-check-label text-success" for="rd_3_{{ $group_id }}">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_4_{{ $group_id }}" class="form-check-input text-danger" name="priority_hold_maker_{{ $group_id }}" value="2" @if($workflowTouchGroup->hold_maker==0) checked @endif>
                                        <label class="form-check-label text-danger" for="rd_4_{{ $group_id }}">No</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_5_{{ $group_id }}" class="form-check-input text-success" name="priority_hold_checker_{{ $group_id }}" value="1" @if($workflowTouchGroup->hold_checker==1) checked @endif>
                                        <label class="form-check-label text-success" for="rd_5_{{ $group_id }}">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_6_{{ $group_id }}" class="form-check-input text-danger" name="priority_hold_checker_{{ $group_id }}" value="2" @if($workflowTouchGroup->hold_checker==0) checked @endif>
                                        <label class="form-check-label text-danger" for="rd_6_{{ $group_id }}">No</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><b>SLA</b></td>
                                <td><input type="number" class="form-control" name="priority_sla_maker_{{ $group_id }}" value="@if($workflowTouchGroup->sla_maker){{$workflowTouchGroup->sla_maker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)"></td>
                                <td>
                                    <input type="number" class="form-control" name="priority_sla_checker_{{ $group_id }}" value="@if($workflowTouchGroup->sla_checker){{$workflowTouchGroup->sla_checker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                                </td>
                            </tr>
                            <tr>
                                <td><b>Attach</b></td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_7_{{ $group_id }}" class="form-check-input text-success" name="priority_attach_maker_{{ $group_id }}" value="1" @if($workflowTouchGroup->attach_maker==1) checked @endif>
                                        <label class="form-check-label text-success" for="rd_7_{{ $group_id }}">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_8_{{ $group_id }}" class="form-check-input text-danger" name="priority_attach_maker_{{ $group_id }}" value="2" @if($workflowTouchGroup->attach_maker==0) checked @endif>
                                        <label class="form-check-label text-danger" for="rd_8_{{ $group_id }}">No</label>
                                    </div>
                                    <input type="number" class="form-control" name="priority_attach_maker_item_{{ $group_id }}" value="@if($workflowTouchGroup->attach_maker_item){{$workflowTouchGroup->attach_maker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                                </td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_9_{{ $group_id }}" class="form-check-input text-success" name="priority_attach_checker_{{ $group_id }}" value="1" @if($workflowTouchGroup->attach_checker==1) checked @endif>
                                        <label class="form-check-label text-success" for="rd_9_{{ $group_id }}">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="rd_10_{{ $group_id }}" class="form-check-input text-danger" name="priority_attach_checker_{{ $group_id }}" value="2" @if($workflowTouchGroup->attach_checker==0) checked @endif>
                                        <label class="form-check-label text-danger" for="rd_10_{{ $group_id }}">No</label>
                                    </div>
                                    <input type="number" class="form-control" name="priority_attach_checker_item_{{ $group_id }}" value="@if($workflowTouchGroup->attach_checker_item){{$workflowTouchGroup->attach_checker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            @endforeach
        </div>
    </div>
</div>


  
    <div class="form-group row">
      <label class="control-label col-md-1 col-sm-1 col-lg-1" for="search">Select Next Group<span class="required"></span> </label>
      <div class="col-md-11 col-sm-11 col-lg-11">
        <div class="row">
          <div class="col-sm-6">
            <div class="customer">
              <input type="text" class="form-control" id="group_search" placeholder="Search Group Name"name="search">
              <input type="hidden" class="form-control" id="group_id" name="group_id">
            </div>
          </div>
          <div class="col-sm-6">
            <button type="button" class="btn px-4 btn-info list-header" id="addGroupItem"><i class="fa fa-plus"></i> Add Next Level Group </button>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div id="groupList">
            <div class="row py-3">
              @inject('workflows','App\Services\WorkFlowService')
              @php $counter = 0; @endphp
              <?php //echo "<pre>";print_r($non_touchGroups); die; ?>
              @foreach($non_touchGroups as $key=>$rr)
                <?php
                $prevGroupId = (!empty($non_touchGroups[$key-1])) ? $non_touchGroups[$key-1]->id : 0;
                $nextGroupId = (!empty($non_touchGroups[$key+1])) ? $non_touchGroups[$key+1]->id : 0;
                $currGroupId = $rr->id;

                $existsGroup = $workflows->checkExistsNextLevelGroup($currGroupId, $row->issue_id);

                ?>
                <div class="col-md-3 exists-group-{{ $rr->id }}">
                  <input type="hidden" value="{{ $rr->id }}" name="groups[]">
                  <fieldset>
                    <legend title="{{$rr->name}}"> {{ substr($rr->name,0,35)  }}:</legend>
                    @IF($existsGroup == 0)
                    <a class="text-danger deleteExistsGroup delete-group-btn text-danger" id="{{ $rr->id }}"><i class="fa fa-times" aria-hidden="true"></i> </a>
                    @ENDIF
                    <button type="button" class="btn btn-primary text-danger pull-right text-danger swapping-right" nextgid="{{ $nextGroupId }}" prevgid="{{ $prevGroupId }}" currentgid="{{ $currGroupId }}"><i class="fa fa-chevron-right" aria-hidden="true"></i> </button>
                    <button type="button" class="btn btn-primary text-danger pull-right text-danger swapping-left" nextgid="{{ $nextGroupId }}" prevgid="{{ $prevGroupId }}" currentgid="{{ $currGroupId }}"><i class="fa fa-chevron-left" aria-hidden="true"></i> </button>

                    <table class="table table-bordered">
                      <thead>
                        <tr style="background-color: darkcyan">
                          <th style="background-color: darkslategray;color: white">#</th>
                          <th style="color: white">Maker</th>
                          <th style="color: white">Checker</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>Touch</b></td>
                          <td id="colorOfRightBorder"></td>
                          <td>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_1_{{ $rr->id }}" name="touch_checker_{{ $rr->id }}" value="1" class="custom-control-input green" @if($rr->touch_checker==1) checked @endif>
                                <label class="custom-control-label green" for="rd_1_{{ $rr->id }}">&nbsp;&nbsp;Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_2_{{ $rr->id }}" name="touch_checker_{{ $rr->id }}" value="2" class="custom-control-input red"  @if($rr->touch_checker==1) @else checked @endif>
                                <label class="custom-control-label red" for="rd_2_{{ $rr->id }}">&nbsp;&nbsp;No</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>Hold</b></td>
                          <td id="colorOfRightBorder">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_3_{{ $rr->id }}" class="custom-control-input"
                                name="hold_maker_{{ $rr->id }}" value="1" @if($rr->hold_maker==1) checked @endif>
                                <label class="custom-control-label green" for="rd_3_{{ $rr->id }}">&nbsp;&nbsp;Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_4_{{ $rr->id }}" class="custom-control-input"
                                name="hold_maker_{{ $rr->id }}" value="2"  @if($rr->hold_maker==1) @else checked @endif>
                                <label class="custom-control-label red" for="rd_4_{{ $rr->id }}">&nbsp;&nbsp;No</label>
                            </div>
                          </td>
                          <td>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_5_{{ $rr->id }}" class="custom-control-input"
                                       name="hold_checker_{{ $rr->id }}" value="1" @if($rr->hold_checker==1) checked @endif>
                                <label class="custom-control-label green" for="rd_5_{{ $rr->id }}">&nbsp;&nbsp;Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_6_{{ $rr->id }}" class="custom-control-input red"
                                       name="hold_checker_{{ $rr->id }}" value="2"  @if($rr->hold_checker==1) @else checked @endif>
                                <label class="custom-control-label red" for="rd_6_{{ $rr->id }}">&nbsp;&nbsp;No</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>SLA</b></td>
                          <td id="colorOfRightBorder"><input type="number" placeholder="" class="workflow-input" name="sla_maker_{{ $rr->id }}" value="@if($rr->sla_maker){{$rr->sla_maker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)"></td>
                          <td>
                              <input type="number" placeholder="" class="workflow-input" name="sla_checker_{{ $rr->id }}" value="@if($rr->sla_checker){{$rr->sla_checker}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                          </td>
                        </tr>
                        <tr>
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>Attach</b></td>
                          <td id="colorOfRightBorder">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_7_{{ $rr->id }}" class="custom-control-input"
                                       name="attach_maker_{{ $rr->id }}" value="1" @if($rr->attach_maker==1) checked @endif>
                                <label class="custom-control-label green" for="rd_7_{{ $rr->id }}">&nbsp;&nbsp;Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_8_{{ $rr->id }}" class="custom-control-input"
                                       name="attach_maker_{{ $rr->id }}" value="2" @if($rr->attach_maker==1) @else checked @endif>
                                <label class="custom-control-label green" for="rd_8_{{ $rr->id }}">&nbsp;&nbsp;No</label>
                            </div>
                            <input type="number" placeholder="" class="workflow-input" name="attach_maker_item_{{ $rr->id }}" value="@if($rr->attach_maker_item){{$rr->attach_maker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                          </td>
                          <td>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_9_{{ $rr->id }}" class="custom-control-input"
                                       name="attach_checker_{{ $rr->id }}" value="1" @if($rr->attach_checker==1) checked @endif>
                                <label class="custom-control-label green" for="rd_9_{{ $rr->id }}">&nbsp;&nbsp;Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="rd_10_{{ $rr->id }}" class="custom-control-input"
                                       name="attach_checker_{{ $rr->id }}" value="2" @if($rr->attach_checker==1) @else checked @endif>
                                <label class="custom-control-label red" for="rd_10_{{ $rr->id }}">&nbsp;&nbsp;No</label>
                            </div>
                            <input type="number" placeholder="" class="workflow-input" name="attach_checker_item_{{ $rr->id }}" value="@if($rr->attach_checker_item){{$rr->attach_checker_item}}@else{{'0'}}@endif" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    @IF($existsGroup > 0)
                      <strong class="error"> Remove not possible <small>Already {{$existsGroup}} active request on this group</small></strong>
                    @ENDIF
                  </fieldset>
                </div>
                @php $counter = $key; @endphp
              @endforeach
              @include('partials.group_info_edit')
            </div>
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label class="control-label col-md-1 col-sm-1 col-xs-1" for="name">SMS/Email<span class="required">*</span> </label>
      <div class="col-md-11 col-sm-11 col-xs-11">
        <div class="row">
          <div class="col-md-3">
            <fieldset>
              <legend>Log:</legend>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="log1" class="custom-control-input" name="log" value="1" @if($row->log==1) checked @endif>
                    <label class="custom-control-label" for="log1">&nbsp;Yes</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="log2" class="custom-control-input" name="log" value="0" @if($row->log==1)@else checked @endif>
                    <label class="custom-control-label" for="log2">&nbsp;No</label>
                </div>
            </fieldset>
          </div>
          <div class="col-md-3">
            <fieldset>
              <legend>Execute:</legend>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="execute1" class="custom-control-input" name="execute" value="1" @if($row->execute==1) checked @endif>
                    <label class="custom-control-label" for="execute1">&nbsp;Yes</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="execute2" class="custom-control-input" name="execute" value="0" @if($row->execute==1)@else checked @endif>
                    <label class="custom-control-label" for="execute2">&nbsp;No</label>
                </div>
            </fieldset>
          </div>

          @if($row->flow_type==\App\Enum\FlowEnum::FORWARD)
          <div class="col-md-3" id="excalationtime">
            <fieldset>
              <legend>SLA Time (In Min):</legend>
              {!! Form::number('complain_sla_time',$row->complain_sla_time,['class' => 'form-control', 'label'=>false, 'autocomplete'=>'off', 'type'=>'number', 'min'=>'0', 'oncopy'=>'return false', 'onpaste'=>'return false', 'oncut'=>'return false', 'onkeypress'=>'validate(event)', 'autofocus'=>'true', 'placeholder'=>'Escalation Time']);!!}
            </fieldset>
          </div>
          @endif
        </div>
      </div>
    </div>
    <div class="ln_solid">&nbsp;</div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1"> <input class="btn btn-primary gradient" title="Update" type="submit" value="Update"> </div>
    </div>
  </form>
{{-- @endsection
@section('script') --}}
<style>
  fieldset{border: 1px solid #ddd!important; padding: 10px; }
  legend{margin-bottom: 0px; }
  .workflow-input{width: 60px; }
  .table.table-bordered>tbody>tr>td, .table.table-bordered>tbody>tr>th, .table.table-bordered>tfoot>tr>td, .table.table-bordered>tfoot>tr>th, .table.table-bordered>thead>tr>td, .table.table-bordered>thead>tr>th {border: 1px solid #F5F5F5; font-size: 14px; color: #333; padding: 10px; }
  .table{margin-bottom: 0px; }
  /* .delete-group-btn{position: absolute; top: 0; right: 6px; background: #ddd; padding: 5px; border-radius: 40px; width: 30px; text-align: center; } */
</style>
<script>
  // CSRF Token
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


  $(document).ready(function () {
    classResuffling();

    $("#group_search").autocomplete({
      source: function (request, response) {
          // Fetch data
          $.ajax({
              url: "{{url('/groups/get-groups')}}",
              type: 'get',
              dataType: "json",
              data: {
                  _token: CSRF_TOKEN,
                  search: request.term
              },
              success: function (data) {
                  response(data);
              }
          });
      },
      select: function (event, ui) {
          // Set selection// display the selected text
          $('#group_search').val(ui.item.label);
          $('#group_name').val(ui.item.label);
          $('#group_id').val(ui.item.value);
          return false;
      }
    });
    $('#addGroupItem').on('click',function () {
        let id=$('#group_id').val();

        $.ajax({
            type: "post",
            url: "{{url('/add-to-group-edit')}}",
            data:{
                _token: '{{ @csrf_token() }}',
                id:id,
                counter:'{{$counter}}',

            },
            dataType: "html",
            success: function(data){
                $("#groupList").load(location.href + " #groupList");
                // $('#groupList').append(data);
                $('#group_search').val('');
                classResuffling();
            },
            error: function(data){
                // error handling
            }
        })
    });

    $(document).on("click",".swapping-right",function(){

      var nextgid = $(this).attr('nextgid');
      var currentgid = $(this).attr('currentgid');
      if (nextgid != 0) {
        var tmpcurrdiv = $('.exists-group-'+currentgid).html();
        var currdivelement = "<div class='col-md-3 exists-group-"+currentgid+"'>"+tmpcurrdiv+"</div>";
        $('.exists-group-'+currentgid).remove();
        $('.exists-group-'+nextgid).after(currdivelement);
      }
      classResuffling();

    });
    $(document).on("click",".swapping-left",function(){
      var prevgid = $(this).attr('prevgid');
      var currentgid = $(this).attr('currentgid');
      if (prevgid != 0) {
        var tmpcurrdiv = $('.exists-group-'+currentgid).html();
        var currdivelement = "<div class='col-md-3 exists-group-"+currentgid+"'>"+tmpcurrdiv+"</div>";
        $('.exists-group-'+currentgid).remove();
        $('.exists-group-'+prevgid).before(currdivelement);
      }
      classResuffling();

    });

    function classResuffling() {
      var tmpprevgid = 0;
      $('.swapping-left').each(function(event){
        var currentgid = $(this).attr('currentgid');
        $(this).attr('prevgid',tmpprevgid);
        tmpprevgid = currentgid;
      });

      var tmpnextgid = 0;
      $($('.swapping-left').get().reverse()).each(function(event) {
        var currentgid = $(this).attr('currentgid');
        $(this).attr('nextgid',tmpnextgid);
        tmpnextgid = currentgid;
      });

      var tmpprevgid = 0;
      $('.swapping-right').each(function(event){
        var currentgid = $(this).attr('currentgid');
        $(this).attr('prevgid',tmpprevgid);
        tmpprevgid = currentgid;
      });

      var tmpnextgid = 0;
      $($('.swapping-right').get().reverse()).each(function(event) {
        var currentgid = $(this).attr('currentgid');
        $(this).attr('nextgid',tmpnextgid);
        tmpnextgid = currentgid;
      });

    }

    $(document).on("click",".deleteGroup",function(){
      let group_id = this.id;
      $.ajax({
          type: "get",
          url: "{{ url('/delete-to-group-edit') }}",
          data: {
              _token: '{{ @csrf_token() }}',
              id: group_id,
              counter:'{{$counter}}',
          },
          dataType: "html",
          success: function (data) {
            // console.log(data);
            group_id='';
            $("#groupList").load(location.href + " #groupList");
            classResuffling();

            // $('#groupList').append(data);
          },
          error: function (data) {
              // error handling
          }

      });
    });
    $(document).on("click",".deleteExistsGroup",function(){
      let group_id = this.id;
      $('.exists-group-'+group_id).remove();
      classResuffling();

    });

  });
  var issueType = $('input[type=radio][name=type]', '.container-fluid');
  var issueItems = $('#issueItems', '.container-fluid');
  issueType.on('change', function () {
    var issueTypeID = $(this).val();
    getIssueOptions(issueTypeID);
  });
  var getIssueOptions = function(issueTypeID) {
    if (issueTypeID) {
      $.ajax({
        url: '{{url('/type-wise-issue')}}/' + issueTypeID,
        type: "GET",
        dataType: "json",
        success: function (data) {
            issueItems.html('<option value="">Select Issue</option>');
            $.each(data, function (key, value) {
                issueItems.append('<option value="' + value.id + '">' + value.name + '</option>');
            });
        }
      });
    } else {

    }
  };

  function validate(evt) {
    var theEvent = evt || window.event;
    // Handle paste
    if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
    } else {
    // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
    }
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }
</script>
@endsection
