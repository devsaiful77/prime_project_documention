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
  .bg-info{
    background-color: #20B2AA !important;  /* Example background color */
      color: #fff !important;
  }
  tr td:first-child {
      background-color: #20B2AA;  
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
  .text-primary{
    color: #212529 !important;
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
    <legend>New Workflow</legend>
    <form class="pb-5" method="post" action="{{ url('workflow') }}">
      @csrf
      <div class="form-group row">
        <label class="control-label col-md-2 col-sm-2 col-lg-2" for="issueType">Flow<span class="required">*</span></label>
        <div class="col-md-6 col-sm-6 col-lg-6">
          <div class="form-check form-check-inline">
              <input type="radio" id="regular" name="flow_type" value="{{ \App\Enum\FlowEnum::REGULAR }}" 
                     class="form-check-input" />
              <label class="form-check-label p-0" for="regular">Auto Flow</label>
          </div>
          <div class="form-check form-check-inline">
              <input type="radio" id="forward" name="flow_type" value="{{ \App\Enum\FlowEnum::FORWARD }}" 
                     class="form-check-input" />
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
              <input type="radio" id="wform" name="issue_id" value="wform" class="form-check-input" />
              <label class="form-check-label p-0" for="wform">Service Request</label>
          </div>
          <div class="form-check form-check-inline">
              <input type="radio" id="complaint" name="issue_id" value="complaint" class="form-check-input" />
              <label class="form-check-label p-0" for="complaint">Complaint</label>
          </div>
      </div>
      
      </div>
      <div class="form-group row pt-3">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Issues <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select class="form-control" name="issue_id" id="issueItems" required>
              <option value="">Select Issue</option>
            </select>
        </div>
      </div>

      {{-- <div class="form-group row">
        <label class="control-label col-md-1 col-sm-1 col-xs-1" for="name">Touch Group<span class="required">*</span> </label>
        <div class="col-md-11 col-sm-11 col-xs-11">
          <div class="row">
            @inject('groupList','App\Services\UtilService')
            @foreach($groupList->getAllTouchGroupList() as $key=>$r)
              <div class="col-md-3">
                <input type="hidden" value="{{ $r->id }}" name="priority_groups[]">
                <fieldset>
                  <legend>{{ $r->name }}:</legend>
                  <div class="form-group">
                    <label>Is Customer Touch Group <input type="checkbox" value="{{ $r->id }}" name="touch_group[]"></label>
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
                          <input type="hidden" name="priority_touch_checker_{{ $r->id }}">
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>Touch</b></td>
                          <td id="colorOfRightBorder"></td>
                          <td>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_1_{{ $r->id }}" name="priority_touch_checker_{{ $r->id }}" value="1" class="custom-control-input green">
                                  <label class="custom-control-label green" for="rd_1_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_2_{{ $r->id }}" name="priority_touch_checker_{{ $r->id }}" value="2" class="custom-control-input red">
                                  <label class="custom-control-label red" for="rd_2_{{ $r->id }}">&nbsp;&nbsp;No</label>
                              </div>

                          </td>
                      </tr>
                      <tr>
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>Hold</b></td>
                          <td id="colorOfRightBorder">
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_3_{{ $r->id }}" class="custom-control-input green"
                                         name="priority_hold_maker_{{ $r->id }}" value="1">
                                  <label class="custom-control-label green" for="rd_3_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_4_{{ $r->id }}" class="custom-control-input red"
                                         name="priority_hold_maker_{{ $r->id }}" value="2">
                                  <label class="custom-control-label red" for="rd_4_{{ $r->id }}">&nbsp;&nbsp;No</label>
                              </div>
                          </td>
                          <td>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_5_{{ $r->id }}" class="custom-control-input green" name="priority_hold_checker_{{ $r->id }}" value="1">
                                  <label class="custom-control-label green" for="rd_5_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_6_{{ $r->id }}"  class="custom-control-input red" name="priority_hold_checker_{{ $r->id }}" value="2">
                                  <label class="custom-control-label red" for="rd_6_{{ $r->id }}">&nbsp;&nbsp;No</label>
                              </div>
                          </td>
                      </tr>
                      <tr>
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>SLA</b></td>
                          <td id="colorOfRightBorder">
                              <input type="number" placeholder="" class="workflow-input" name="priority_sla_maker_{{ $r->id }}" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)"></td>
                          <td>
                              <input type="number" placeholder="" class="workflow-input" name="priority_sla_checker_{{ $r->id }}" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                          </td>
                      </tr>
                      <tr>
                          <td id="colorOfTableHeadLightSeaGreenBackground"><b>Attach</b></td>
                          <td id="colorOfRightBorder">
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_7_{{ $r->id }}" class="custom-control-input"
                                         name="priority_attach_maker_{{ $r->id }}" value="1">
                                  <label class="custom-control-label green" for="rd_7_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio"id="rd_8_{{ $r->id }}" class="custom-control-input"
                                         name="priority_attach_maker_{{ $r->id }}" value="2">
                                  <label class="custom-control-label red" for="rd_8_{{ $r->id }}">&nbsp;&nbsp;
                                      No</label>
                              </div>
                              <input type="number" placeholder="" class="workflow-input" name="priority_attach_maker_item_{{ $r->id }}" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                          </td>
                          <td>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_9_{{ $r->id }}" class="custom-control-input"
                                         name="priority_attach_checker_{{ $r->id }}" value="1">
                                  <label class="custom-control-label green" for="rd_9_{{ $r->id }}">&nbsp;&nbsp;Yes</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="rd_10_{{ $r->id }}" class="custom-control-input" name="priority_attach_checker_{{
                                  $r->id }}" value="2">
                                  <label class="custom-control-label red" for="rd_10_{{ $r->id }}">&nbsp;&nbsp;No</label>
                              </div>
                              <input type="number" placeholder="" class="workflow-input" name="priority_attach_checker_item_{{ $r->id }}" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
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
        <label class="col-md-1 col-sm-1 col-12 control-label" for="name">Touch Group<span class="required">*</span></label>
        <div class="col-md-11 col-sm-11 col-12">
            <div class="row">
                @inject('groupList','App\Services\UtilService')
                @foreach($groupList->getAllTouchGroupList() as $key=>$r)
                    <div class="col-md-3 mb-4">
                        <input type="hidden" value="{{ $r->id }}" name="priority_groups[]">
                        <fieldset>
                            <legend class="text-primary">{{ $r->name }}:</legend>
                            <div class="form-group">
                                <label class="form-check-label pb-3">
                                    Is Customer Touch Group
                                    <input type="checkbox" value="{{ $r->id }}" name="touch_group[]" class="form-check-input">
                                </label>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="background-color: darkcyan">
                                        <th class="text-white">#</th>
                                        <th class="text-white">Maker</th>
                                        <th class="text-white">Checker</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <input type="hidden" name="priority_touch_checker_{{ $r->id }}">
                                        <td class="bg-info"><b>Touch</b></td>
                                        <td></td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_1_{{ $r->id }}" name="priority_touch_checker_{{ $r->id }}" value="1" class="form-check-input">
                                                <label class="form-check-label" for="rd_1_{{ $r->id }}">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_2_{{ $r->id }}" name="priority_touch_checker_{{ $r->id }}" value="2" class="form-check-input">
                                                <label class="form-check-label" for="rd_2_{{ $r->id }}">No</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-info"><b>Hold</b></td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_3_{{ $r->id }}" class="form-check-input" name="priority_hold_maker_{{ $r->id }}" value="1">
                                                <label class="form-check-label" for="rd_3_{{ $r->id }}">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_4_{{ $r->id }}" class="form-check-input" name="priority_hold_maker_{{ $r->id }}" value="2">
                                                <label class="form-check-label" for="rd_4_{{ $r->id }}">No</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_5_{{ $r->id }}" class="form-check-input" name="priority_hold_checker_{{ $r->id }}" value="1">
                                                <label class="form-check-label" for="rd_5_{{ $r->id }}">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_6_{{ $r->id }}" class="form-check-input" name="priority_hold_checker_{{ $r->id }}" value="2">
                                                <label class="form-check-label" for="rd_6_{{ $r->id }}">No</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-info"><b>SLA</b></td>
                                        <td>
                                            <input type="number" placeholder="" class="form-control" name="priority_sla_maker_{{ $r->id }}" min="0">
                                        </td>
                                        <td>
                                            <input type="number" placeholder="" class="form-control" name="priority_sla_checker_{{ $r->id }}" min="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-info"><b>Attach</b></td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_7_{{ $r->id }}" class="form-check-input" name="priority_attach_maker_{{ $r->id }}" value="1">
                                                <label class="form-check-label" for="rd_7_{{ $r->id }}">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_8_{{ $r->id }}" class="form-check-input" name="priority_attach_maker_{{ $r->id }}" value="2">
                                                <label class="form-check-label" for="rd_8_{{ $r->id }}">No</label>
                                            </div>
                                            <input type="number" placeholder="" class="form-control" name="priority_attach_maker_item_{{ $r->id }}" min="0">
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_9_{{ $r->id }}" class="form-check-input" name="priority_attach_checker_{{ $r->id }}" value="1">
                                                <label class="form-check-label" for="rd_9_{{ $r->id }}">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="rd_10_{{ $r->id }}" class="form-check-input" name="priority_attach_checker_{{ $r->id }}" value="2">
                                                <label class="form-check-label" for="rd_10_{{ $r->id }}">No</label>
                                            </div>
                                            <input type="number" placeholder="" class="form-control" name="priority_attach_checker_item_{{ $r->id }}" min="0">
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
              <button type="button" class="btn px-4 btn-info list-header" id="addGroupItem"><i class="fa fa-plus"></i> Add Group </button>
            </div>
          </div>
          <div id="groupList">
            @include('partials.group_info')
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
                        <input type="radio" id="log1" class="custom-control-input" name="log" value="1">
                        <label class="custom-control-label" for="log1">&nbsp;Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="log2" class="custom-control-input" name="log" value="0" checked>
                        <label class="custom-control-label" for="log2">&nbsp;No</label>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-3">
                <fieldset>
                    <legend>Execute:</legend>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="execute1" class="custom-control-input" name="execute" value="1">
                        <label class="custom-control-label" for="execute1">&nbsp;Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="execute2" class="custom-control-input" name="execute" value="0" checked>
                        <label class="custom-control-label" for="execute2">&nbsp;No</label>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-3" id="excalationtime">
                <fieldset>
                    <legend>SLA Time (In Min):</legend>
                    <input type="number" class="form-control" name="complain_sla_time" placeholder="Escalation Time" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" onkeypress="validate(event)">
                </fieldset>
            </div>
          </div>
        </div>
      </div>
      <div class="ln_solid">&nbsp;</div>
      <div class="form-group">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <input class="btn btn-primary gradient" title="Submit" type="submit" value="Submit">
          </div>
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
  $(document).ready(function() {
    $("#group_search").autocomplete({
      source: function(request, response) {
        // Fetch data
        $.ajax({
          url: "{{url('groups/get-groups')}}",
          type: 'get',
          dataType: "json",
          data: {
            _token: CSRF_TOKEN,
            search: request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
      select: function(event, ui) {
        // Set selection// display the selected text
        $('#group_search').val(ui.item.label);
        $('#group_name').val(ui.item.label);
        $('#group_id').val(ui.item.value);
        return false;
      }
    });

    $('#addGroupItem').on('click', function() {
      let id = $('#group_id').val();
      $.ajax({
        type: "post",
        url: "{{url('add-to-group')}}",
        data: {
          _token: '{{ @csrf_token() }}',
          id: id,
        },
        dataType: "html",
        success: function(data) {
          $('#groupList').html(data);
        },
        error: function(data) {
          // error handling
        }
      })
    });
  });

  $(document).ready(function() {
    $(document).on("click", ".deleteGroup", function() {
      let group_id = this.id;
      $.ajax({
        type: "get",
        url: "{{ url('/delete-to-group') }}",
        data: {
          _token: '{{ @csrf_token() }}',
          id: group_id,
        },
        dataType: "html",
        success: function(data) {
          console.log(data);
          group_id = '';
          $('#groupList').html(data);
        },
        error: function(data) {
          // error handling
        }
      });
    });
  });

  var issueType = $('input[type=radio][name=issue_id]', '.container-fluid');
  var issueItems = $('#issueItems', '.container-fluid');
  issueType.on('change', function() {
    var issueTypeID = $(this).val();
    getIssueOptions(issueTypeID);
  });

  var getIssueOptions = function(issueTypeID) {
    if (issueTypeID) {
      $.ajax({
        url: "{{url('/type-wise-issue')}}/"+issueTypeID,
        type: "GET",
        dataType: "json",
        success: function(data) {
          issueItems.html('<option value="">Select Issue</option>');
          $.each(data, function(key, value) {
            issueItems.append('<option value="' + value.id + '">' + value.name + '</option>');
          });
        }
      });
    } else {
    }
  };


  $('input[type=radio][name=issue_id]').change(function() {
    if (this.value == 'wform') {
      getIssueOptions(this.value);
      //$("#regular").prop('checked', true);
      //$('#excalationtime').fadeOut();
    } else if (this.value == 'complaint') {
      getIssueOptions(this.value);
      //$("#forward").prop("checked", true);
      //$("#regular").prop('checked', false);
      //$('#excalationtime').fadeIn();
    }
  });

  $('input[type=radio][name=flow_type]').change(function() {
    if (this.value == 'regular') {
      getIssueOptions('wform');
      $("#wform").prop('checked', true);

      //$("#complaint").prop('checked', false);
      $('#excalationtime').fadeOut();
    } else if (this.value == 'forward') {
      $('#excalationtime').fadeIn();
      getIssueOptions('complaint');
      $("#complaint").prop("checked", true);
      //$("#wform").prop('checked', false);
    }
  });



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
    if (!regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) theEvent.preventDefault();
    }
  }
</script>

@endsection

