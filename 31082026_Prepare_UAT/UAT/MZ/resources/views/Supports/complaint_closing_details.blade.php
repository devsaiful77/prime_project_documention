@extends('layouts.admin')
@section('content')
<style>
    .jconfirm-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .col-lg-2 {
        flex: 0 0 auto;
        width: 13%;
        padding: 0.25rem;
    }
    .col-lg-3 {
        flex: 0 0 auto;
        width: 20%;
        padding: 0.25rem;
    }
    .row.here{
        textarea,select{
            /* margin-top: 0.5rem; */
            width: 100%;
            height: 3rem;
            /* margin-left: 1rem; */
            margin-right: 0.5rem;
            appearance: auto;
        }
        button{

            width: 100%;
            height: 2.2rem;
            /* margin-right: 0.5rem; */
            /* margin-top: 0.5rem; */
            font-size: 0.9rem;
        }
        .select2-container--default .select2-selection--single{
            height: 2.2rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
        .form-control.is_justified{
            width: 180px;
            margin-right: 0rem;
            margin-top: 0.5rem;
        }
        .btn {
            --bs-btn-padding-x: 0rem !important;
            --bs-btn-padding-y: 0rem !important;
        }
        .assign{
            width: 100%;
            height: 2.2rem;
            /* margin-right: 0.5rem; */
            /* margin-top: 0.5rem; */
            font-size: 1rem;
            padding-top: 0.25rem;
            /* padding-bottom: 0.5rem; */
        }
        /* input{
            width: 150px;
        }
        button{
            width: 150px;
        } */
    }

</style>
@inject('queueDuration','App\Services\UtilService')
@php
    use App\Setting;
    $settings = Setting::first();
    if(!empty($settings) && !empty($settings->file_size_limit)){
	$fileSizeLimit = (int) $settings->file_size_limit / 1024;
    }else{
	$fileSizeLimit = 10240 / 1024;
    }
@endphp
<div class="curved-inner-pro pt-2 mb-3" style="background-color: #DFF0D8;">
	<div class="curved-inner-pro">
        <div class="curved-ctn">
            <h2 class="p-2">Complaint Closing Details</h2>
        </div>
    </div>
</div>
<div>
  <fieldset class="scheduler-border" style="background-color:#ffff">
      <div class="scheduler-border">
          <a class="colla" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="cursor: pointer; font-weight: bold; color:#ffffff;">
              Information <i class="fa-plus fa" aria-hidden="false"></i>
          </a>
      </div>
      <div class="table-responsive collapse in" id="collapseOne">
      <table class="table table-condensed table-bordered no-padding-margin-b">
          <tr>
              <th class="vcenter text-left">Ticket No.</th>
              <td class="vcenter text-left">{{ !empty($dataForView['reference_number']) ? $dataForView['reference_number'] : "" }}</td>
              <th class="vcenter text-left">Account No.</th>
              <td class="vcenter text-left">{{ !empty($dataForView['account_number']) ? $dataForView['account_number'] : "" }}</td>
              <th class="vcenter text-left">Account Name</th>
              <td class="vcenter text-left">{{ !empty($dataForView['acc_name']) ? $dataForView['acc_name'] : "" }}</td>
          </tr>
          <tr>
              <th class="vcenter text-left">Mobile No.</th>
              <td class="vcenter text-left">{{ !empty($dataForView['mobile_number']) ? $dataForView['mobile_number'] : "" }}</td>
              <th class="vcenter text-left">Customer Email.</th>
              <td class="vcenter text-left">{{ !empty($dataForView['email_address']) ? $dataForView['email_address'] : "" }}</td>
              <th class="vcenter text-left">{{ !empty($dataForView['customer_name']) ? "Customer Name" : "" }}</th>
              <td class="vcenter text-left">{{ !empty($dataForView['customer_name']) ? $dataForView['customer_name'] : "" }}</td>
          </tr>
          <tr>
              <th class="vcenter text-left">Ticket Creation Time</th>
              <td class="vcenter text-left">{{ !empty($dataForView['time_and_ext']) ? $dataForView['time_and_ext'] : "" }}</td>
              <th class="vcenter text-left">Customer ID</th>
              <td class="vcenter text-left">{{ !empty($dataForView['SIF_Number']) ? $dataForView['SIF_Number'] : "" }}</td>
              <th class="vcenter text-left">Product Type.</th>
              <td class="vcenter text-left">{{ !empty($dataForView['product_name']) ?  $dataForView['product_name'] : $dataForView['product_type'] }}</td>
          </tr>
          @if($dataForView["product_type"] == 3)
              <tr>
                  <th class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? "Inputted Masked Card Number" : "" }}</th>
                  <td class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? $dataForView['mask_card_no'] : "" }}</td>
                  <th class="vcenter text-left">{{ !empty($dataForView['product_desc']) ? "Product Name - Description" : "" }}</th>
                  <td class="vcenter text-left">{{ !empty($dataForView['product_desc']) ? $dataForView['product_desc'] : "" }}</td>
                  <th class="vcenter text-left">{{ !empty($dataForView['account_status']) ? "Account Status" : "" }}</th>
                  <td class="vcenter text-left">{{ !empty($dataForView['account_status']) ? $dataForView['account_status'] : "" }}</td>
              </tr>
              <tr>
                  <th class="vcenter text-left">{{ !empty($dataForView['acc_opening_branch']) ? "Account Opening Branch" : `&nbsp` }}</th>
                  <td class="vcenter text-left">{{ !empty($dataForView['acc_opening_branch']) ? $dataForView['acc_opening_branch'] : `&nbsp` }}</td>
                  <th class="vcenter text-left">&nbsp;</th>
                  <td class="vcenter text-left">&nbsp;</td>
                  <th class="vcenter text-left">&nbsp;</th>
                  <td class="vcenter text-left">&nbsp;</td>
              </tr>
          @endif
          <tr>
              <th class="vcenter text-left">{{ !empty($dataForView['segment']) ? "Segment Code" : "" }}</th>
              <td class="vcenter text-left">{{ !empty($dataForView['segment']) ? $dataForView['segment'] : "" }}</td>
              @if($dataForView["product_type"] == 1)
              <th class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? "Masked Card Number" : "" }}</th>
              <td class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? $dataForView['mask_card_no'] : "" }}</td>
              @else
              <th></th>
              <td></td>
              @endif
              <th class="vcenter text-left">{{ !empty($dataForView['date_of_birth']) ? "Customer DOB" : "" }}</th>
              <td class="vcenter text-left">{{ !empty($dataForView['date_of_birth']) ? $dataForView['date_of_birth'] : "" }}</td>
          </tr>

          <tr>

              <th class="vcenter text-left">NID No.</th>
              <td class="vcenter text-left">{{ !empty($dataForView['customer_nid']) ? $dataForView['customer_nid'] : "" }}</td>
              <th class="vcenter text-left">Passpor No.</th>
              <td class="vcenter text-left">{{ !empty($dataForView['passpor_number']) ? $dataForView['passpor_number'] : "" }}</td>
              <th class="vcenter text-left">Branch Code</th>
              <td class="vcenter text-left">{{ !empty($dataForView['branch_code']) ? $dataForView['branch_code'] : "" }}</td>
          </tr>

          <tr>
              <th class="vcenter text-left">Customer Address</th>
              <td class="vcenter text-left">{{ !empty($dataForView['communication']) ? $dataForView['communication'] : "" }}</td>
              <th class="vcenter text-left">Branch Name</th>
              <td class="vcenter text-left">{{ !empty($dataForView['acc_opening_branch']) ? $dataForView['acc_opening_branch'] : "" }}</td>
              <td class="vcenter text-left">
                  @if(!empty($dataForView['access_by']))
                      @php
                          $customer_id = !empty($dataForView['SIF_Number']) ? e($dataForView['SIF_Number']) : '';
                          $accountNumber = !empty($dataForView['account_number']) ? e($dataForView['account_number']) : '';
                      @endphp
                      <!-- Button for balance inquiry -->
                      <button type="button" class="btn btn-warning form-group m-0 p-0 ballanceInquery"
                              value="{{ $accountNumber }}, {{ $customer_id }}"
                              onclick="overlay('show');">
                          Balance Inquiry
                      </button>
                  @endif
              </td>

          </tr>
      </table>
      </div>
  </fieldset>

  <fieldset class="scheduler-border" style="background-color:#ffff">
      <div class="scheduler-border">
          <a class="colla" data-bs-toggle="collapse" data-bs-target="#collapseTow" aria-expanded="false" aria-controls="collapseTow" style="cursor: pointer; font-weight: bold; color:#ffffff;">
              Action <i class="fa fa-minus" aria-hidden="true"></i>
          </a>
      </div>
      <div class="table-responsive collapse show" id="collapseTow">
          <table class="table table-condensed table-bordered no-padding-margin-b">
              <tr>
                  <th class="vcenter text-left">Priority.</th>
                  <td class="vcenter text-left">{{$dataForView['priority']}}</td>
                  <th class="vcenter text-left">Source.</th>
                  <td class="vcenter text-left">{{$dataForView['source']}}</td>
                  <th class="vcenter text-left">Tin Verified.</th>
                  <td class="vcenter text-left">{{$dataForView['tin_verified']}}</td>

              </tr>
              <tr>
                  <th class="vcenter text-left">Repeat Complaint.</th>
                  <td class="vcenter text-left">{{$dataForView['repeat_complaint']}}</td>
                  {{--<th class="vcenter text-left">Amount.</th>
                  <td class="vcenter text-left">{{$dataForView['amount']}}</td>--}}
                  <th class="vcenter text-left">Caller ID.</th>
                  <td class="vcenter text-left">{{$dataForView['caller_id']}}</td>
                  <th></th>
                  <td></td>
              </tr>
              <tr>

                  <th class="vcenter text-left">Complaint Type.</th>
                  <td class="vcenter text-left">{{$dataForView['issue_name']}}</td>
                  <th class="vcenter text-left">Complaint Detail.</th>
                  <td class="vcenter text-left" colspan="3">{{$dataForView['complaint_details']}}</td>
              </tr>

              @include('Supports/complaint_details_extended')

              <tr>
                  <th colspan="6">&nbsp;</th>
              </tr>

              <tr class="success">
                  <th class="vcenter text-left">Form Logger.</th>
                  <td class="vcenter text-left">{{$dataForView['created_by']}}</td>
                  <th class="vcenter text-left">Time.</th>
                  <td class="vcenter text-left">{{date('d-m-Y h:i:s A', $dataForView['date'])}}</td>
                  <th class="vcenter text-left">&nbsp;</th>
                  <td class="vcenter text-left">&nbsp;</td>
              </tr>
              <tr class="success">
                  <th class="vcenter text-left">Current Position</th>
                  <td class="vcenter text-left">
                      @if($dataForView['form_status'] == 11)
                          Form Close at {{ $dataForView['name'] }} [{{ $dataForView['unit_id'] <> 2 ? "Maker" : "Checker" }}
                      @else
                          {{ $dataForView['name'] }} [{{ $dataForView['unit_id'] <> 2 ? "Maker" : "Checker" }}]
                      @endif
                  </td>
                  <th class="vcenter text-left">Last Access By.</th>
                  <td class="vcenter text-left">
                      @IF(!empty($dataForView['access_by']))
                          {{$dataForView['access_by']}}
                      @ELSE
                          <?php
      $lastAccess = "";
      if (!empty($dataForView['comment']))
      {
      $lastAccess = end($dataForView['comment']) ['user_id'];
      }
      ?>
                          @IF(!empty($lastAccess))
                              {{$lastAccess}}
                          @ELSE
                              {{$dataForView['created_by']}}
                          @ENDIF
                      @ENDIF
                  </td>
                  <th class="vcenter text-left">&nbsp;</th>
                  <td class="vcenter text-left">&nbsp;</td>
              </tr>

          </table>
      </div>
  </fieldset>

    @if(!empty($dataForView['com_summary']))
        <style>
            #com_summary{
                color: #030303;
            }
            #com_root_cause{
                color: #873600;
            }
            #action_taken{
                color: #02600f;
            }
        </style>
        <fieldset class="scheduler-border" style="background-color:#ffff">
            <div class="scheduler-border">
                <a class="colla" data-bs-toggle="collapse" data-bs-target="#collapseSummary" aria-expanded="false" aria-controls="collapseThree" style="cursor: pointer; font-weight: bold; color:#ffffff;">
                    Complaint Summary <i class="fa-plus fa" aria-hidden="false"></i>
                </a>
            </div>
            <div class="table-responsive collapse show" id="collapseSummary">
                <table class="table table-bordered table-condensed">
                    <colgroup>
                        <col width="15%">
                        <col width="35%">
                        <col width="15%">
                        <col width="35%">
                    </colgroup>
                    <tr>
                        <th class="vcenter">Complaint Summary</th>
                        <td id="com_summary" class="vcenter "> {{ !empty($dataForView['com_summary']) ? $dataForView['com_summary'] : "" }}</td>
                        <th class="vcenter">Complaint Root Cause</th>
                        <td id="com_root_cause" class="vcenter ">{{ !empty($dataForView['com_root_cause']) ? $dataForView['com_root_cause'] : "" }}</td>
                    </tr>
                    <tr>
                        <th class="vcenter">Action Taken</th>
                        <td id="action_taken" class="vcenter ">{{ !empty($dataForView['action_taken']) ? $dataForView['action_taken'] : "" }}</td>
                        <th class="vcenter">Actual Resolve Date</th>
                        <td class="vcenter">{{ !empty($dataForView['action_date']) ? $dataForView['action_date'] : "" }}</td>
                    </tr>
                </table>
            </div>
        </fieldset>
    @endif

  <fieldset class="scheduler-border" style="background-color:#ffff">
      <div class="scheduler-border">
          <a class="colla" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="cursor: pointer; font-weight: bold; color:#ffffff;">
              Ticket History <i class="fa-plus fa" aria-hidden="false"></i>
          </a>
      </div>
      <div class="table-responsive collapse" id="collapseThree">
          <table class="table table-condensed table-bordered no-padding-margin-b">
              <?php
                  $sqlFormStatus = \Illuminate\Support\Facades\DB::table('comments')->select(
                  // 'comments.*',
                  'comments.user_id', 'comments.reference_number', 'comments.time', 'comments.group_id', 'comments.subgroup_id', 'comments.action', 'comments.comments', 'comments.isapproved', 'comments.time', 'users.name','group_info.name AS group_name')
                  ->join('users', 'comments.user_id', '=', 'users.user_id')
                  ->join('group_info', 'comments.group_id', '=', 'group_info.id')
                  ->where('comments.reference_number', $dataForView['reference_number'])->orderBy('comments.time', 'ASC')
                  ->get();

                  if (count($sqlFormStatus) > 0)
                  {
              ?>
              <br/>
              <table style="width: 100%; margin: 0 auto; border-spacing: 2px; border-collapse: separate;" border="0" >
                  <tr>
                      <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Person</td>
                      <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Group</td>
                      <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Log / In Time</td>
                      <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Task Touch Time</td>
                      <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Status</td>
                      <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Close / Out Time</td>
                      <td class="topandbottom text-center" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Duration (D:H:M:S)</td>
                      <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Remarks</td>
                  </tr>
                  <?php
                      $duration_in_minutes = 0;
                      try
                      {
                          if (!empty($_GET['qd']))
                          {
                              $duration_in_minutes = decrypt($_GET['qd']);
                          }
                      }
                      catch(DecryptException $e)
                      {

                      }

                      $i = 0;
                      $j = 0;
                      $models = array();
                      $prevgID = '';
                      $lastInTime = "";
                      //echo count($sqlFormStatus);
                      foreach ($sqlFormStatus as $row)
                      {
                          $groupID = $row->group_id;
                          $subGroupID = $row->subgroup_id;
                          $userID = $row->user_id;
                          $form_status = $row->action;
                          $comments = $row->comments;
                          $isapproved = $row->isapproved;
                          $userName = $row->name;
                          $groupName = $row->group_name;
                          $models[$i]['group_id'] = $groupID;
                          $models[$i]['duration_in_minutes'] = "";

                          if ($prevgID == $userID)
                          {
                              $models[$i]['user_id'] = '';
                              $models[$i]['user_name'] = '';
                              $models[$i]['group_name'] = '';
                          }
                          else
                          {
                              $models[$i]['user_id'] = $userID;
                              $models[$i]['user_name'] = $userName;
                              $models[$i]['group_name'] = $groupName;
                          }

                          if ($i == 0)
                          {
                              $models[$i]['isapproved'] = 1;
                              $models[$i]['in_time'] = $row->time;
                              $models[$i]['work_time'] = $row->time;
                              $models[$i]['out_time'] = $row->time;
                              $lastInTime = $row->time;
                              $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime) , date('Y-m-d H:i:s', $row->time));
                          }
                          elseif ($prevgID != $userID)
                          {
                              $models[$i]['in_time'] = $models[$i - 1]['out_time'];
                              $models[$i]['work_time'] = $row->time;
                              $models[$i]['out_time'] = 0;
                              $lastInTime = $models[$i - 1]['out_time'];
                          }
                          elseif ($prevgID == $userID && $i > 0 && $isapproved == 0)
                          {
                              $models[$i]['in_time'] = 0;
                              $models[$i]['work_time'] = $row->time;
                              $models[$i]['out_time'] = 0;
                          }
                          elseif ($prevgID == $userID && $i > 0 && $isapproved == 1)
                          {
                              $models[$i]['isapproved'] = $isapproved;
                              $models[$i]['in_time'] = 0;
                              $models[$i]['work_time'] = $row->time;
                              $models[$i]['out_time'] = $row->time;
                              $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime) , date('Y-m-d H:i:s', $row->time));
                          }

                          if (count($sqlFormStatus) == $i + 1)
                          {
                              $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime) , date('Y-m-d H:i:s'));
                          }

                          $models[$i]['form_status'] = $form_status;
                          $models[$i]['comments'] = $comments;

                          $prevgID = $userID;
                          $i++;
                      }

                      foreach ($models as $key => $rowFormVal)
                      {
// dd($rowFormVal);
                          //pr($rowFormVal);
                          $resultComm = '';
                          $resultGName = $resultComm;

                      ?>

                      <tr>
                          <?php /*if($rowFormVal['user_name'] != "") {*/ ?><!--
                          <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php /*echo ''; */ ?></td>
                          <?php /*}else{*/ ?>
                          <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">&nbsp;</td>
                          --><?php /*}*/ ?>
                          {{-- @dd($rowFormVal) --}}
                          <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['user_name'])){{ $rowFormVal['user_name'] }}@endif</td>
                          <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['group_name'])){{ $rowFormVal['group_name'] }}@endif</td>
                          <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['in_time']))<?php if (!empty($rowFormVal['in_time'] > 0)) echo date("d.m.Y ## h:i a", $rowFormVal['in_time']); ?>@endif</td>
                          <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['work_time']))<?php if (!empty($rowFormVal['work_time'] > 0)) echo date("d.m.Y ## h:i a", $rowFormVal['work_time']); ?>@endif</td>
                          <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['form_status']))<?php echo $rowFormVal['form_status']; ?>@endif</td>
                          <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['out_time']))<?php if (!empty($rowFormVal['out_time'] > 0)) echo date("d.m.Y ## h:i a", $rowFormVal['out_time']); ?>@endif</td>
                          <td class="" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px; text-align:center"><?php echo $rowFormVal['duration_in_minutes'] ?></td>
                          <td class="topandbottom" colspan="2" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['comments']))<?php echo $rowFormVal['comments']; ?>@endif &nbsp;</td>
                      </tr>


                      <?php
                  } ?>
              </table>
              <?php
              } ?>
              {{--@IF(!empty($dataForView['comment']))
                  <table class="table table-striped w-auto">
                      <thead>
                      <tr style="background-color: thistle">
                          <th>Person</th>
                          <th>Start / In Time</th>
                          <th>Working Time</th>
                          <th>Status</th>
                          <th>Out Time</th>
                          <th>Remarks</th>
                      </tr>
                      </thead>
                      <tbody>
                      @FOREACH($dataForView['comment'] AS $cmnt)
                          <tr class="table-info">
                              <th scope="row">{{ $cmnt['user_id'] }}</th>
                              <td>{{ date('d-M-y h:i:s A',$cmnt['time'])  }}</td>
                              <td>N/A</td>
                              <td>{{ $cmnt['action'] }}</td>
                              <td>{{ date('d-M-y h:i:s A',strtotime($cmnt['updated_at']))  }}</td>
                              <td>{{ $cmnt['comments'] }}</td>
                          </tr>
                      @endforeach
                      </tbody>
                  </table>

                  <tr class="warning"><th colspan="6" class="vcenter text-left">Comment</th></tr>
                  <tr class="warning">
                      <th class="vcenter text-left">User ID</th>
                      <th class="vcenter text-left">Action</th>
                      <th class="vcenter text-left">Unit</th>
                      <th class="vcenter text-left" colspan="2">Comments</th>
                      <th class="vcenter text-left">Date &amp; Time</th>
                  </tr>
                  @FOREACH($dataForView['comment'] AS $cmnt)
                      <tr class="warning">
                          <th class="vcenter text-left">{{ $cmnt['user_id'] }}</th>
                          <th class="vcenter text-left">{{ $cmnt['action'] }}</th>
                          <th class="vcenter text-left">{{ $cmnt['unit_name'] }}</th>
                          <td class="vcenter text-left" colspan="2"> {{ $cmnt['comments'] }} </td>
                          <th class="vcenter text-left">{{ date('d-M-y h:i:s A',$cmnt['time'])  }}</th>
                      </tr>
                  @ENDFOREACH
              @ENDIF--}}
              <table>
                  @IF(!empty($dataForView['complaint_attachment']))
                      <tr><th colspan="6" class="vcenter text-left">Attachment</th></tr>
                      @FOREACH($dataForView['complaint_attachment'] AS $attch)
                          <tr>
                              <td class="vcenter float-left">{{ $attch['attachment_date'] }}</td>
                              <td class="vcenter float-left" colspan="5">&nbsp;
                                  <?php //echo $attch['file_name'];die;
                                  ?>
                                  @IF(!empty($attch['file_name']))
                                      <?php
                                          $basePath = str_replace('engine', '', base_path());
                                          $imageURL = $basePath . 'public/attachments/' . $attch['file_name'];
                                          // prd($imageURL);
                                          if (file_exists($imageURL))
                                          {
                                      ?>
                                      <a href="{{URL::asset('public/attachments/'.$attch['file_name'])}}" target="_blank">{{$attch['file_name']}}</a>
                                      <a href="{{ route('download', ['filename' => $attch['file_name']]) }}"><i class="fa fa-download"></i></a>
                                      @IF(Auth::user()->id == $attch['uploaded_by'])
                                          <button class="attachmentdel btn btn-link error text-right no-padding-margin" data-filename="{{$attch['file_name']}}" data-id="{{$attch['id']}}"><i class="fa fa-trash"></i> </button>
                                      @ENDIF
                                      |
                                      <?php
                                          }
                                          else
                                      { ?>
                                      <a href="{{ url('/images') }}" target="_blank">{{$attch['file_name']}}</a>
                                      <a href="{{ route('download', ['filename' => $attch['file_name']]) }}"><i class="fa fa-download"></i></a>
                                      <?php
                                          }
                                      ?>
                                  @ELSE
                                      <strong style="color:red;">{{ "Attachment not available" }}</strong>
                                  @ENDIF
                              </td>
                          </tr>
                      @ENDFOREACH
                  @ENDIF

                  @inject('flow_type','App\Services\WorkFlowService')

                  <?php
                      $flow_type_name = $flow_type->getFlowTypeCheck($dataForView['reference_number']);

                      $redirect_url = Request::url();
                      $redirect_url .= (!empty($_GET)) ? '?' . http_build_query($_GET) : "";

                      $editPermission = true;
                      if (Auth::user()->can(['supportExecutive']))
                      {

                      if (!empty($dataForView['access_by']) && (Auth::user()->user_id != $dataForView['access_by']))
                      {

                          $editPermission = false;
                      }
                      // if ($loggerCanAssign == false && $isAdminOrLogger == true)
                      // {
                      //     $editPermission = false;
                      // }
                      if (empty($_GET['qd']))
                      {
                          $editPermission = false;
                      }
                      }
                      else if (Auth::user()->hasRole(['supervisor', 'srExecutive']))
                      {
                      $editPermission = false;
                      }
                      else //if(Auth::user()->hasRole(['logger', 'executive']))

                      {
                      //$editPermission = true;
                      $editPermission = false;
                      }
                  ?>

                  @if($flow_type_name==\App\Enum\FlowEnum::REGULAR)
                      @inject('workflow_list','App\Services\WorkFlowService')
                      @php $work = $workflow_list->workflowStage($dataForView['reference_number']);

                                  $sla_user=false;
                                  if(\Illuminate\Support\Facades\Auth::user()->hasRole([\App\Enum\RoleEnum::LOGGER,\App\Enum\RoleEnum::EXECUTIVE])){
                                      $sla_user=true;
                                  }
                                  $touch = $work['touch'];
                                  $sla = $work['sla'];
                                  $hold = $work['hold'];
                                  $attach = $work['attach'];
                                  $attach_item = $work['attach_item'];
                      @endphp
                  @else
                      @php
                          $sla_user=false;
                          $touch='';
                          $sla ='';
                              $hold ='';
                              $attach='';
                          $attach_item ='';
                      @endphp
                  @endif
                  @IF(((Auth::user()->hasRole(['superadmin', 'admin', 'logger'])) || (Auth::user()->can(['supportExecutive']) )) && $editPermission == true && $dataForView['form_status'] != 11 && (!empty($dataForView['access_by'])))
                      @if($flow_type_name==\App\Enum\FlowEnum::REGULAR)
                          @if($attach==1)
                              @inject('attachmentCount','App\Services\UtilService')
                              @php $attachmentItemCount = $attachmentCount->attachmentCount($dataForView['reference_number']); @endphp
                              @if($attachmentItemCount < $attach_item)
                                  {!! Form::open(['method'=>'post', 'action' => ['SupportsController@uploadNewAttachment'] , 'enctype' => 'multipart/form-data']); !!}
                                      {!! Form::token(); !!}

                                      <tr><th colspan="6" class="">Attach New File<small class="error-message"> (Max file size is {{ $fileSizeLimit }} MB)  </small></th></tr>
                                      <tr>
                                          <td class="" colspan="6">
                                              {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}

                                              {{ Form::hidden('redirect_url',$redirect_url) }}
                                              <div class="form-inline">
                                                  <div class="custom-file">
                                                      @for($i=0;$i < $attach_item-$attachmentItemCount; $i++)

                                                      {!! Form::file('file_name[]', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file')); !!}
                                                      @endfor
                                                      <button type="submit" class="btn btn-success ml-1"><i class="fa fa-upload"></i> Upload</button>
                                                  </div>
                                              @if($errors->has('file_name.*'))
                                                  <div class="error">
                                                  {!! implode('', $errors->all(':message')); !!}
                                                  </div>
                                              @endif
                                              </div>
                                          </td>
                                      </tr>
                              {!! Form::close(); !!}
                          @endif
                      @endif
                  @else
                      {!! Form::open(['method'=>'post', 'action' => ['SupportsController@uploadNewAttachment'] , 'enctype' => 'multipart/form-data']); !!}
                          {!! Form::token(); !!}

                          <tr><th colspan="6" class="">Attach New File<small class="error-message"> (Max file size is {{ $fileSizeLimit }} MB)  </small></th></tr>
                          <tr>
                              <td class="" colspan="6">
                                  {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}

                                  {{ Form::hidden('redirect_url',$redirect_url) }}
                                  <div class="form-inline">
                                      <div class="custom-file">
                                      {!! Form::file('file_name[]', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file','multiple')); !!}
                                          <button type="submit" class="btn btn-success ml-1"><i class="fa fa-upload"></i> Upload</button>
                                      </div>
                                  @if($errors->has('file_name.*'))
                                      <div class="error">
                                      {!! implode('', $errors->all(':message')); !!}
                                      </div>
                                  @endif
                                  </div>
                              </td>
                          </tr>
                      {!! Form::close(); !!}
                  @endif
                  @ENDIF
              </table>
          </table>
      </div>
  </fieldset>

    <div class="clearfix"></div>
    @if((Auth::user()->hasRole('complaint_closing') ) && ($dataForView['form_status'] == 12) && (!empty($_GET['qd'])) )
        {{-- @if( ($dataForView['form_status'] == 12) && (!empty($_GET['qd'])) ) --}}
        {{-- @if(!empty($_GET['qd'])) --}}
        @if(((Auth::user()->hasRole(['superadmin', 'admin'])) || (Auth::user()->can(['supportExecutive']) )) && $editPermission == true )
            <?php $_GET['st'] = date('Y-m-d H:i:s'); ?>
            <div class="row"style="padding-left: 1.3rem;">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 no-padding-margin-l">
                {!! Form::open(['method'=>'post','class'=>'row here', 'action' => ['SupportsController@workingOnHandler'] , 'enctype' => 'multipart/form-data']); !!}
                    {!! Form::token(); !!}
                    {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}
                    {{ Form::hidden('request_from','complaint') }}
                    {{ Form::hidden('qd',$_GET['qd']) }}
                    {{ Form::hidden('st',$_GET['st']) }}
                    <?php $searchedParam = '?'.(!empty($_GET)) ? '?'.http_build_query($_GET) : ""; ?>
                    {{ Form::hidden('searchedParam',$searchedParam) }}
                    <div class="col-lg-3">
                        @if($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11 && !empty($dataForView['access_by']))
                        {!!
                            Form::textarea('comments',"",[
                            'rows'=>2,
                            'class' => 'form-control comments-input',
                            'autocomplete'=>'off',
                            'placeholder'=>'Comments'
                            ]);
                        !!}
                        @if($errors->has('comments')) <div class="error-message">{{ $errors->first('comments') }}</div> @ENDIF
                            @if(!empty($dataForView['is_api']) && ($dataForView['api_status'] == 0) && (Auth::user()->user_unit['subgroup_info_id'] == 16) && ($dataForView['card_status'] == 'C' || $dataForView['card_status'] == 'I' || $dataForView['card_status'] == 'S' || $dataForView['card_status'] == 'SB'))
                                @if($dataForView['unit_id'] == 1)
                                    <div class="input-group form-group mr-1">
                                        {{ Form::select('memo', [null=>'Memo List'] +  UNSERIALIZE(MEMOLIST),(!empty($dataForView['memo'])) ? $dataForView['memo'] : "", ['class'=>'form-control memo-list' ]) }}
                                        <input type="text" name="memo_other" class="form-control memo-other" placeholder="Other Memo" value="{{old('memo_other')}}" style="display: none;">
                                    </div>
                                @elseif($dataForView['unit_id'] == 2)
                                    <div class="input-group form-group mr-1">
                                        <div class="form-control" disabled> {{$dataForView['memo']}}</div>
                                    </div>
                                @endif
                            @endif
                            @IF(!empty($dataForView['in_date_time']))
                            @if($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 0)
                                <button type="button" class="btn btn-success sendBackSmsBtn form-group mr-1" data-comment-id="{{ encrypt
                                ($dataForView['in_date_time']['id'])}}" data-reference-no="{{ encrypt($dataForView['reference_number'])}}" data-mobile-no="{{ encrypt($dataForView['mobile_number'])}}" data-email-addr="{{ encrypt($dataForView['email_address'])}}" data-issue-name="{{ $dataForView['issue_name'] }}">Sendback SMS?</button>
                            @elseif($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 1)
                                <button type="button" class="btn btn-success disabled form-group mr-1">Sendback SMS?</button>
                            @endif
                            @endif
                            @if(!empty($dataForView['is_api']) && ($dataForView['api_status'] == 0) && (Auth::user()->user_unit['subgroup_info_id'] == 16) && ($dataForView['unit_id'] == 2) && ($dataForView['card_status'] == 'C' || $dataForView['card_status'] == 'I' || $dataForView['card_status'] == 'S' || $dataForView['card_status'] == 'SB'))
                            <button type="button" data-reference-no="{{ encrypt($dataForView['reference_number'])}}" data-request-from="complaint" class="btn btn-info apiUpdateBtn form-group mr-1">Update into System &amp; Close?</button>
                            @endif
                        @endif
                    </div>

                    @if(empty($dataForView['access_by']))
                        <?php
                        $_GET['activeUrl'] = "ComplaintClosingDetails";
                        $getUrl = url('/Supports/complaint_closing_assign/'.encrypt($dataForView['reference_number']));
                        if (!empty($_GET)) {

                            $getUrl .= '?'.(!empty($_GET)) ? '?'.http_build_query($_GET) : "";
                        }
                        ?>
                        @if(in_array($dataForView['unit_id'],userUnits())||in_array($dataForView['unit_id'],userUnits()))
                        <div class="row">
                            <div class="col-lg-2">
                                <a style="" href="{{$getUrl}}" onclick="overlay('show');" class="btn btn-success  form-group mr-1 assign">Assign</a>

                            </div>
                        </div>
                        @endif
                    @else
                        @inject('is_regular_flow','App\Services\WorkFlowService')
                        @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::REGULAR)
                        @if($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                            @if($dataForView['unit_id'] == 1 && is_priority()==1)
                            @else
                            <button type="submit" class="btn btn-primary form-group mr-1" value="sendBack" onclick="overlay('show');" name="submit">Send Back To</button>
                            <?php
                                $requiredSelect = "";
                                if($errors->has('unit_id')) {
                                    $requiredSelect = "red-border-2px";
                                }
                                // echo '<pre>';
                                // print_r($allmakers);
                            ?>
                            @if(!empty($dataForView['auto_unit_id']))
                                {{ Form::hidden('unit_id',$dataForView['auto_unit_id'] ) }}
                            @endif
                            @endif
                            @inject('groupUser','App\Services\UtilService')
                            <div class="col-lg-3 pt-3 form-group row mr-1">
                            <select class="form-control col-lg-12" name="group_id">
                                <option value="">Please Select</option>
                                @foreach($allmakers as $allmaker)
                                    <?php if($allmaker->subgroup_id > 0){?>}
                                        <option value="{{ $allmaker->group_id.','.$allmaker->subgroup_id }}" @if($groupUser->groupUser($allmaker->group_id)) disabled @else @endif>{{ $allmaker->name }}(maker)</option>
                                    <?php } ?>
                                @endforeach
                            </select>
                            @if($errors->has('group_id')) <div class="error-message">Please Select Group</div> @endif
                            </div>
                            @if(is_priority() == 1 && is_sendback($dataForView['reference_number'])==0)
                            @inject('subflow','App\Services\WorkFlowService')
                            <?php
                                $subflowLists = $subflow->subFlowList($dataForView['issue_id']);
                                $requiredSelectSubflow = "";
                                if($errors->has('subflow_type_group_id')) {
                                    $requiredSelectSubflow = "red-border-2px";
                                }
                            ?>
                            <?php
                                $subflowSelected='';
                                if (old('subflow_type_group_id') == $sbFlowList->group_id) {
                                    $subflowSelected='selected';
                                }
                            ?>
                            @endif
                        @endif
                        @endif
                        @inject('last_step','App\Services\WorkFlowService')
                        @php $last_person= $last_step->workflowLastStep($dataForView['reference_number']);

                        @endphp
                        @if($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                            @if($last_person==false)
                                @inject('subflowExt','App\Services\WorkFlowService')
                                @php $subflowExists = $subflowExt->subFlowList($dataForView['main_id']); @endphp
                                @if(!empty($subflowExists))
                                    @if($dataForView['unit_id'] == 1)
                                        <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                                            value="approved" name="submit" style="margin-right: 40px;">Approve</button>
                                    @elseif(is_priority() == 1 && $dataForView['unit_id'] == 2 && is_sendback($dataForView['reference_number'])==0)
                                        <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                                            value="approved" name="submit" style="margin-right: 40px;">Approve</button>
                                    @elseif(is_priority() == 0)
                                        <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                                            value="approved" name="submit" style="margin-right: 40px;">Approve</button>
                                    @endif
                                @else
                                    {{-- <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                            value="approved" name="submit" style="margin-right: 40px;">Approve</button> --}}
                            @endif
                        @endif


                        @if($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11 && $dataForView['form_status'] != 12)
                        <button type="submit" class="btn btn-info close-btn form-group mr-1" value="resolve" onclick="overlay
                            ('show');" name="submit" style="margin-right: 40px;">Resolve</button>
                        <a class="form-control is_justified form-group mr-1">
                            <b style="color:red">Send Close Notification?&nbsp;&nbsp;</b>
                            <input type="checkbox" name="closenotification" value="1" checked/>
                        </a>

                        @endif
                    @endif

                    @inject('is_regular_flow','App\Services\WorkFlowService')
                    @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::FORWARD)
                        @if($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                        @if($dataForView['unit_id'] == 1 && is_priority()==1)
                        @else
                            <div class="col-lg-2 pt-3">
                                <button type="submit" class="btn btn-warning form-group mr-1" value="sendBackRegular" onclick="overlay('show');" name="submit" style="">Back To Source</button>
                                {{ Form::hidden('group_id_reqular', $dataForView['comment'][0]['group_id']) }}
                                {{ Form::hidden('subgroup_id', $dataForView['comment'][0]['subgroup_id']) }}
                            </div>
                        @endif
                        <?php
                            $requiredSelect = "";
                            if($errors->has('unit_id')) {
                                $requiredSelect = "red-border-2px";
                            }
                        ?>
                        {{-- @if(($dataForView['unit_id'] == 1 || $dataForView['unit_id'] == 2) && is_priority()==1)
                            <button type="button" class="btn btn-success fwdToSrc" value="forwardRegular" name="forwardtosrc" style="margin-right: 40px;">Forward To Source</button>
                        @endif --}}
                        {{-- <button type="submit" class="btn btn-primary form-group mr-1" value="forward" onclick="overlay('show');" name="forward" style="margin-right: 40px;">Forward To</button>
                        <select class="form-control col-lg-12" name="group_id">
                            <option value="">Please Select</option>
                            @inject('groups','App\Services\WorkFlowService')
                            @inject('groupUser','App\Services\UtilService')
                            @foreach($groups->getAllGroupList() as $group)
                            <option value="{{ $group->id }}" >{{ $group->name }}
                                @if($groupUser->groupUser($group->id) && $dataForView['unit_id'] == 1) -- [Checker]
                                @else
                                @endif
                            </option>
                            @endforeach
                        </select> --}}
                        {{-- @if($errors->has('group_id')) <div class="error-message">Please Select Group</div>
                        @endif --}}
                    @endif
                    @if($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                        <!-- <button type="submit" class="btn btn-danger" value="reject" name="submit">Reject</button> -->
                    @endif
































                        {{--
                        <div style="padding-top: 10px;">
                            @php
                                $array = [292, 290, $dataForView['comment'][0]['subgroup_id']];
                                $getAllTouchSubGroupInfo = App\GroupInfo::select('subgroup_info.id','group_info.id AS group_id','subgroup_info.name')
                                ->join('subgroup_info','subgroup_info.group_info_id','group_info.id')
                                ->where('group_info.group_level_id','1')
                                ->where('subgroup_info.is_active','1')
                                ->orderBy('subgroup_info.name', 'ASC')
                                ->get();

                            @endphp
                            <button type="submit" class="btn btn-primary forwardSelectPart" value="forward" onclick="overlay('show');"
                                name="forward">Forward To</button>

                            <select class="form-control select2-icon sourceTouchGroupOrBackOffice forwardSelectPart col-lg-4" name="group_id"
                                style="width: 300px;">
                                <option value="" selected disabled>Please Select</option>
                                <option value="Touch Point Group" data-icon="fa-arrow-right">Touch Point Group</option>
                                @inject('groups','App\Services\WorkFlowService')
                                @inject('groupUser','App\Services\UtilService')
                                @foreach($groups->getAllGroupListCompClose() as $group)
                                <option value="{{ $group->group_id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>

                            <div class="forwardToSrcSelectPart" style="display:none;">
                                <select class="form-control select2-icon col-lg-4 sourceOrTouchGroup" id="sourceOrTouchGroup"
                                    name="group_id_new">
                                    <option selected disabled>Please Select Source</option>
                                    <option value="Back" data-icon="fa-arrow-left">Back</option>
                                    @foreach($getAllTouchSubGroupInfo as $touchGroup)
                                    @if(!in_array($touchGroup->id, $array))
                                    <option value="{{$touchGroup->id}}">{{$touchGroup->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('group_id')) <div class="error-message">Please Select Group</div> @endif

                        </div> --}}




                        {{-- <div class="col-lg-4"> --}}
                            @php
                                $array = [292, 290, $dataForView['comment'][0]['subgroup_id']];
                                $getAllTouchSubGroupInfo = App\GroupInfo::select('subgroup_info.id','group_info.id AS group_id','subgroup_info.name')
                                    ->join('subgroup_info','subgroup_info.group_info_id','group_info.id')
                                    ->where('group_info.group_level_id','1')
                                    ->where('subgroup_info.is_active','1')
                                    ->orderBy('subgroup_info.name', 'ASC')
                                    ->get();
                            @endphp

                            {{-- <div class="row align-items-center"> --}}
                                <div class="col-lg-2 pt-3">
                                    <button type="submit" class="btn btn-primary forwardSelectPart" value="forward" onclick="overlay('show');"
                                        name="forward">Forward To</button>
                                </div>
                                <!-- First Select Box -->
                                <div class="col-lg-3 pt-3">
                                    {{-- <label for="group_id">Search & Select Group</label> --}}
                                    <select class="form-control select2 sourceTouchGroupOrBackOffice forwardSelectPart" name="group_id" id="group_id">
                                        <option value="Please Select Group" selected disabled>Please Select Group</option>
                                        <option value="Touch Point Group" data-icon="fa-arrow-right">Touch Point Group</option>
                                        @inject('groups','App\Services\WorkFlowService')
                                        @foreach($groups->getAllGroupListCompClose() as $group)
                                            <option value="{{ $group->group_id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Second Select Box (Initially Hidden) -->
                                <div class="col-lg-3 pt-3 forwardToSrcSelectPart" style="display:none;">
                                    {{-- <label for="sourceOrTouchGroup">Select Source</label> --}}
                                    <select class="form-control select2 sourceOrTouchGroup" id="sourceOrTouchGroup" name="group_id_new">
                                        <option value="Please Select Source" selected disabled>Please Select Source</option>
                                        <option value="Back" data-icon="fa-arrow-left">Back</option>
                                        @foreach($getAllTouchSubGroupInfo as $touchGroup)
                                            @if(!in_array($touchGroup->id, $array))
                                                <option value="{{$touchGroup->id}}">{{$touchGroup->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            {{-- </div> --}}

                            @if($errors->has('group_id'))
                                <div class="error-message">Please Select Group</div>
                            @endif
                        {{-- </div> --}}












                        @if($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11 && $dataForView['form_status'] != 12)
                        <button type="submit" class="btn btn-info close-btn form-group mr-1" value="resolve" onclick="overlay('show');" name="submit" style="margin-right: 40px;">Resolve</button>
                        <a class="form-control is_justified form-group mr-1">
                            <b style="color:red">Send Close Notification?&nbsp;&nbsp;</b>
                            <input type="checkbox" name="closenotification" value="1" checked/>
                        </a>
                        @endif
                    @endif
                    @endif
                {!! Form::close(); !!}
                </div>
                @if(!empty($dataForView['access_by']) && $dataForView['access_by'] == Auth::user()->user_id)

                    <div class="clearfix">&nbsp;</div>
                    @include('Supports/complaint_closing_form')
                @endif
            </div>
        @endif

    @endif

    <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Issue Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('update-issue-complain/'.$dataForView['reference_number']) }}" method="post">
                    @csrf
                    <input type="hidden" name="issue_id" value="{{ $dataForView['main_id'] }}">
                    <div class="modal-body">
                        <div id="issue_extra">
                            @include('partials.edit_extra_form_field')
                        </div>
                        <div id="issue_check_list">
                            @include('partials.edit_issue_check_list')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @if($issue_checklist_status)
                            <button type="submit" id="savechange" class="btn btn-primary">Save changes</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>












  {{-- <script>
    $(document).ready(function () {
        console.log("io");
    });


    $('.sourceTouchGroupOrBackOffice').on('change', function(){
        $("#sourceOrTouchGroup").val("");

        if($(this).val() != 'Touch Point Group' && $(this).val() != 'Back'){
            // alert( $(this).find(":selected").val() );
            executorSlaCheck($(this).find(":selected").val());
        }
        if ($(this).val() == 'Touch Point Group') {
            $(this).val("");;
            $('.forwardToSrcSelectPart').css({'display':'inline-block'}).children('span').width(300);
        }else{
            $('.forwardToSrcSelectPart').css({'display':'none'});
        }
    });

    $('.sourceOrTouchGroup').on('change', function(){
        $("#sourceTouchGroupOrBackOffice").val("");

        if($(this).val() != 'Touch Point Group' && $(this).val() != 'Back'){
            executorSlaCheck($(this).find(":selected").val());

        }
        if ($(this).val() == 'Back') {
            $(this).val("");
            $('.forwardToSrcSelectPart').css({'display':'none'});
        }else{
            $('.forwardToSrcSelectPart').css({'display':'inline-block'}).children('span').width(300);
        }
    });






  </script> --}}

  <script>
    $(document).ready(function () {
        $(".select2").select2({
            placeholder: "Search & Select",
            allowClear: true,
            width: '100%'
        });

        $('.sourceTouchGroupOrBackOffice').on('change', function(){
            $("#sourceOrTouchGroup").val("").trigger('change');

            if ($(this).val() === 'Touch Point Group') {
                $('.forwardToSrcSelectPart').show();
                $("#sourceOrTouchGroup").val("").trigger("change"); // Default select reset
            } else {
                $('.forwardToSrcSelectPart').hide();
            }
        });

        $('.sourceOrTouchGroup').on('change', function(){
            if ($(this).val() === 'Back') {
                $(this).val("").trigger("change");
                $('.forwardToSrcSelectPart').hide();
            }
        });
    });
</script>












  @IF($errors->has('comments'))
      <script type="text/javascript"> customAlert('Please Type Comment','You need to write comment','red'); </script>
  @ENDIF
  @IF(!empty($dataForView['in_date_time']))
    @IF($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 0)
        <script type="text/javascript">


        $(".sendBackSmsBtn").on("click",function($e){
            var comment_id = $(this).attr('data-comment-id');
            var ref_no = $(this).attr('data-reference-no');
            var mobile_no = $(this).attr('data-mobile-no');
            var email_address = $(this).attr('data-email-addr');
            var issue_name = $(this).attr('data-issue-name');

            $.confirm({
                title:'Confirm',
                content:'Do you want to send SMS and Email for sendback?',
                type:'green',
                typeAnimated: true,
                buttons: {
                    Yes: {
                        text: 'YES',
                        btnClass: 'btn-red',
                        action: function(){
                            $.ajax({
                                type: "post",
                                url: "{{url('/Supports/SendSendBackSMS')}}",
                                data: {
                                    _token: _token,
                                    comment_id:comment_id,
                                    ref_no:ref_no,
                                    mobile_no:mobile_no,
                                    issue_name:issue_name,
                                    email_address:email_address
                                },
                                dataType: "json",
                                beforeSend: function(){
                                    overlay('show');
                                },
                                success: function(data){
                                    overlay('hide');
                                    if (data.success) {
                                        customAlert('Success','SMS / EMAIL have been sent successfully','green');
                                        $(".sendBackSmsBtn").prop('disabled',true);

                                        $(".sendBackSmsBtn").removeAttr('data-comment-id');
                                        $(".sendBackSmsBtn").removeAttr('data-reference-no');
                                        $(".sendBackSmsBtn").removeAttr('data-mobile-no');
                                        $(".sendBackSmsBtn").removeAttr('data-email-addr');
                                        $(".sendBackSmsBtn").removeAttr('data-issue-name');

                                    } else {
                                        customAlert('Warning','Failed to sent SMS. Please Contact with Administrator','red');
                                    }
                                },
                                error: function(data){
                                    overlay('hide');
                                    customAlert('Error','Something went wrong. Please Contact with Administrator','red');
                                }
                            });

                        }
                    },
                    No: {text: 'NO'}
                }
            });
        });
        </script>
    @ENDIF
  @ENDIF
  <script>
    $('#issueModal').on('show.bs.modal', function (event) {
        var value = $(event.relatedTarget);
        var issue_id = value.data('id');
        var reference_number = value.data('reference');
        var form_type = "complain";
        $.post('{{ url('edit-issue-extra-form') }}', {_token:'{{ csrf_token() }}', issue_id:issue_id,reference_number:reference_number,form_type:form_type}, function(data){
            //alert(data);
            $('#issue_extra').html(data);

        });
        $.post('{{ url('edit-issue-check-list') }}', {_token:'{{ csrf_token() }}', issue_id:issue_id,reference_number:reference_number,form_type:form_type}, function(data){
            //alert(data);
            $('#issue_check_list').html(data);

        });
    });

    $(".apiUpdateBtn").on("click", function($e) {
        var ref_no = $(this).attr('data-reference-no');
        var req_from = $(this).attr('data-request-from');
        var req_comments = $('.comments-input').val();
        if(!req_comments){
            customAlert('Please Type Comment','You need to write comment','red');
        }else{
            $.confirm({
                title: 'Update into Card Pro?',
                type: 'green',
                typeAnimated: true,
                buttons: {
                    Yes: {
                        text: 'YES',
                        btnClass: 'btn-red',
                        action: function() {
                            $.ajax({
                                type: "post",
                                url: "{{url('/Supports/ApiUpdate/')}}",
                                data: {
                                    _token: _token,
                                    ref_no: ref_no,
                                    req_from: req_from
                                },
                                dataType: "json",
                                beforeSend: function() {
                                    overlay('show');
                                },
                                success: function(data) {

                                    if (data.success == 1) {

                                        // comments-input
                                        // close-btn

                                        //customAlert('Success', 'System data have been updated.', 'green');

                                        // $('.comments-input').val('Close');
                                        $('.close-btn').trigger('click');

                                        $(".apiUpdateBtn").prop('disabled', true);
                                        $(".apiUpdateBtn").removeAttr('data-reference-no');
                                    } else {
                                        overlay('hide');
                                        if (data.msg) {
                                            customAlert('Warning', data.msg, 'red');
                                        } else {
                                            customAlert('Warning', 'Failed to update system data. Please Contact with Administrator', 'red');
                                        }
                                    }
                                },
                                error: function(data) {
                                    overlay('hide');
                                    customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
                                }
                            });
                        }
                    },
                    No: {
                        text: 'NO'
                    }
                }
            });
        }
    });

    $(document).ready(function () {
    $(document).on("click", ".fwdToSrc", function (e) {
        e.preventDefault();
        console.log("Button clicked");

        // Variables
        var ref_no = "{{ encrypt($dataForView['reference_number']) }}";
        var qd = "{{ (!empty($_GET['qd'])) ? $_GET['qd'] : '' }}";
        var req_from = "complaint";
        var selectInput = "<select class='form-control otherSrc' name='othersrc'><option value=''>Please Select</option></select>";

        // AJAX request to fetch options for the select
        $.ajax({
            type: "get",
            url: "{{url('/Supports/GetTouchSubGroups/')}}",
            dataType: "html",
            beforeSend: function () {
                console.log("Fetching subgroup options...");
                overlay('show');
            },
            success: function (data) {
                console.log("Subgroup options fetched");
                overlay('hide');

                // Populate select input with options
                selectInput = "<select class='form-control otherSrc' name='othersrc'><option value=''>Please Select</option>" + data + "</select>";

                // Confirmation modal
                $.confirm({
                    title: 'Forward to other source?',
                    content: '' +
                        '<div class="form-group">' +
                        '<label>Select Source</label>' +
                        selectInput +
                        '<div class="error othersrcerr"></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Comments</label>' +
                        '<input type="text" name="comments" placeholder="Please Write Comments" class="comments form-control" required />' +
                        '<div class="error commentserr"></div>' +
                        '</div>',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        Yes: {
                            text: 'YES',
                            btnClass: 'btn-red',
                            action: function () {
                                console.log("Confirm clicked");

                                // Clear errors
                                $(".othersrcerr").text('');
                                $(".commentserr").text('');

                                // Validate inputs
                                var subgroup_id = this.$content.find('.otherSrc').val();
                                var comments = this.$content.find('.comments').val();

                                if (!subgroup_id || !comments) {
                                    if (!subgroup_id) {
                                        $(".othersrcerr").text('Other Source is required');
                                    }
                                    if (!comments) {
                                        $(".commentserr").text('Comments are required');
                                    }
                                    return false;
                                }

                                var group_id = $('option:selected', '.otherSrc').attr('group-id');

                                // AJAX to forward the request
                                $.ajax({
                                    type: "post",
                                    url: "{{url('/Supports/WorkingOnHandler/')}}",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        reference_number: ref_no,
                                        qd: qd,
                                        submit: 'forwardToSource',
                                        request_from: req_from,
                                        group_id: group_id,
                                        subgroup_id: subgroup_id,
                                        comments: comments
                                    },
                                    dataType: "json",
                                    beforeSend: function () {
                                        console.log("Forwarding request...");
                                        overlay('show');
                                    },
                                    success: function (data) {
                                        overlay('hide');
                                        console.log("Forwarding success:", data);

                                        if (data == 1) {
                                            redirectToUrl('Success', 'This Ticket has been forwarded successfully.', 'green', 'Supports/complaintClosing');
                                        } else if (data == 2) {
                                            customAlert('Warning', 'This Ticket is not available in your queue! Please refresh the page.', 'red');
                                        } else if (data == 3) {
                                            customAlert('Warning', 'Failed to forward! This Ticket is not created by CI Customer.', 'red');
                                        } else {
                                            customAlert('Warning', 'Failed to forward. Please contact the Administrator.', 'red');
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        overlay('hide');
                                        console.error("Error forwarding request:", error);
                                        customAlert('Error', 'Something went wrong. Please contact the Administrator.', 'red');
                                    }
                                });
                            }
                        },
                        No: { text: 'NO' }
                    }
                });
            },
            error: function (xhr, status, error) {
                overlay('hide');
                console.error("Error fetching subgroup options:", error);
                customAlert('Error', 'Something went wrong. Please contact the Administrator.', 'red');
            }
        });
    });

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on("click", ".ballanceInquery", function (e) {
            e.preventDefault();
            var value = $(this).val();
            var parts = value.split(', ');
            var accountNumber = parts[0];
            var customerId = parts[1];

            let url = "/ballance/inquery";

            $.ajax({
                url: url,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    accountNumber: accountNumber,
                    customerId: customerId
                }),
                dataType: "json",
                beforeSend: function () {
                    overlay('show'); // Optional loading overlay
                },
                success: function (response) {
                    overlay('hide'); // Hide overlay
                    console.log("Success:", response);

                    if (response.data && response.data) {
                        alert("Current Ballance is: " + response.data);
                    } else {
                        alert("Error: Unable to retrieve balance");
                    }
                },
                error: function (xhr, status, error) {
                    overlay('hide'); // Hide overlay
                    console.error("Error:", xhr.responseText);
                    alert("Failed to fetch balance. Please try again.");
                }
            });
        });

    });

    $(".attachmentdel").on("click",function($e){
        var attchid = $(this).attr('data-id');
        var filename = $(this).attr('data-filename');
        $.confirm({
            title:'Confirm',
            content:'Do you want to delete: '+filename,
            type:'green',
            typeAnimated: true,
            buttons: {
                Yes: {
                    text: 'YES',
                    btnClass: 'btn-red',
                    action: function(){
                        $.ajax({
                            type: "post",
                            url: "{{url('/Supports/DeleteAttachment')}}",
                            data: {_token: _token, attchid:attchid },
                            dataType: "json",
                            beforeSend: function(){overlay('show'); },
                            success: function(data){
                                overlay('hide');
                                if (data.success) {
                                    customAlert('Success','Attachment have been removed successfully','green');
                                    location.reload();
                                } else {
                                    customAlert('Warning','Failed to remove attachment.','red');
                                }
                            },
                            error: function(data){overlay('hide'); customAlert('Error','Something went wrong. Please Contact with Administrator','red');
                            }
                        });
                    }
                },
                No: {text: 'NO'}
            }
        });
    });

    $('#isInquiryApi').on('click', function (){
        var issue_id = "{{ (!empty($dataForView['issue_id'])) ? $dataForView["issue_id"] : 0 }}";
        var acc_no = "{{ (!empty($dataForView["account_number"])) ? $dataForView["account_number"] : 0 }}";
        var ref_no = "{{ (!empty($dataForView["reference_number"])) ? $dataForView["reference_number"] : 0 }}";
        var cif_no = "{{ (!empty($dataForView["SIF_Number"])) ? $dataForView["SIF_Number"] : 0 }}";
        if(issue_id.length > 0) {
            inquiryAPI(issue_id, acc_no, ref_no, cif_no);
        }
    });

    function inquiryAPI(issue_id, acc_no, ref_no, cif_no) {
        $.ajax({
            url: base_url + '/CIFModification/inquiry/' + issue_id + '/' + acc_no + '/' + ref_no + '/' + cif_no,
            type: "GET",
            beforeSend: function () {
                overlay('show');
            },
            success: function (response) {
                console.log(response);
                overlay('hide');
                if(response.status === 1) {
                    $('#inquiryModal').modal('show');
                    var helpers = '';
                    $.each(response.data, function(key, value) {
                        helpers += '<div class="col-lg-6">'+key+'</div><div class="col-lg-6">'+value+'</div>';
                    });
                    document.getElementById('inquiryData').innerHTML = helpers;
                } else {
                    customAlert('Error', response.data, 'red');
                }
            },
            error: function (error) {
                console.log(error);
                overlay('hide');
                customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
            }
        });
    };

    $(".colla").on("click",function(){
        $(this).find("i").toggleClass("fa fa-plus");
        $(this).find("i").toggleClass("fa fa-minus");
    });

  </script>
@endsection


