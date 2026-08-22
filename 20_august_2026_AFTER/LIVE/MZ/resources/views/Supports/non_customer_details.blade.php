<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 6/4/2020.
 */
?>
@extends('layouts.admin')
@section('content')
@inject('queueDuration','App\Services\UtilService')
    <div class="curved-inner-pro pt-2 mb-3" style="background-color: #DFF0D8;">
		<div class="curved-inner-pro">
        <div class="curved-ctn">
            <h2 class="p-2">Non Customer Details</h2>
        </div>
        </div>
    </div>
    <fieldset class="scheduler-border" style="background-color:#ffffff">
        <legend class="scheduler-border" style="font-family: Verdana,Geneva,sans-serif;color:#FF4500;background-color:#ffffff">Action</legend>
        <div class="table-responsive">
        <table class="table table-condensed table-bordered no-padding-margin-b">
            <tr>
                <th class="vcenter text-left">Query No.</th>
                <td class="vcenter text-left">{{$dataForView['reference_number']}}</td>
                <th class="vcenter text-left">Customer Name.</th>
                <td class="vcenter text-left">{{$dataForView['customer_name']}}</td>
				<th class="vcenter text-left">Customer Address</th>
                <td class="vcenter text-left">{{$dataForView['customer_address']}}</td>
            </tr>
            <tr>

                <th class="vcenter text-left">Mobile No.</th>
                <td class="vcenter text-left">{{$dataForView['mobile_number']}}</td>
                <th class="vcenter text-left">Customer Email.</th>
                <td class="vcenter text-left">{{$dataForView['customer_email']}}</td>
                <th class="vcenter text-left">Customer DOB</th>
                <td class="vcenter text-left">{{$dataForView['customer_dob']}}</td>

            </tr>

			<tr>
                <th class="vcenter text-left">Time &amp; Ext.</th>
                <td class="vcenter text-left">{{$dataForView['time_and_ext']}}</td>
                <th class="vcenter text-left">Customer Profession</th>
                <td class="vcenter text-left">{{$dataForView['customer_profession']}}</td>
                <th class="vcenter text-left">Employment Address</th>
                <td class="vcenter text-left">{{$dataForView['employment_address']}}</td>
            </tr>
            <tr>
                <th class="vcenter text-left">Salary / Income</th>
                <td class="vcenter text-left">{{$dataForView['salary_income']}}</td>
                <th class="vcenter text-left">Length of Service/Business</th>
                <td class="vcenter text-left">{{$dataForView['service_length']}}</td>
                <th class="vcenter text-left">Request Type</th>
                <td class="vcenter text-left">{{$dataForView['request_name']}}</td>
            </tr>

            <tr>
                <th class="vcenter text-left">Sales Lead</th>
                <td class="vcenter text-left">{{$dataForView['sales_lead_name']}}</td>
                <th class="vcenter text-left">Other Bank Loan</th>
                <td class="vcenter text-left">{{$dataForView['other_bank_loan']}}</td>
                <th class="vcenter text-left">Other Bank Credit Card</th>
                <td class="vcenter text-left">{{$dataForView['other_bank_credit_card']}}</td>
            </tr>

            <tr>
                <th class="vcenter text-left">Details</th>
                <td class="vcenter text-left">{{$dataForView['details']}}</td>
                <th class="vcenter text-left"></th>
                <td class="vcenter text-left"></td>
                <th class="vcenter text-left"></th>
                <td class="vcenter text-left"></td>
            </tr>

            <tr class="success">
                <th class="vcenter text-left">Form Logger.</th>
                <td class="vcenter text-left">{{$dataForView['created_by']}}</td>
                <th class="vcenter text-left">Time.</th>
                <td class="vcenter text-left">{{date('d-m-Y h:i:s A', $dataForView['date'])}}</td>
                <th class="vcenter text-left"></th>
				<td class="vcenter text-left"></td>
            </tr>
            <tr class="success">
                <th class="vcenter text-left">Current Position</th>
                <td class="vcenter text-left">{{ Session::get('subgroupStr')}}</td>
                <th class="vcenter text-left">Last Access By.</th>
                <td class="vcenter text-left">
                    @IF(!empty($dataForView['access_by']))
                        {{$dataForView['access_by']}}
                    @ELSE
                        <?php
                        $lastAccess = "";
                        if (!empty($dataForView['comment'])) {
                            $lastAccess = end($dataForView['comment'])['user_id'];
                        }
                        ?>
                        @IF(!empty($lastAccess))
                            {{$lastAccess}}
                        @ELSE
                            {{$dataForView['created_by']}}
                        @ENDIF
                    @ENDIF
                </td>
                <th class="vcenter text-left"></th>
				<td class="vcenter text-left"></td>
            </tr>

			<?php

		/*
        $sqlFormStatus = \Illuminate\Support\Facades\DB::table('form_status')
            ->join('users','form_status.user_id','=','users.id')
            ->leftjoin('comments','form_status.reference_number','comments.reference_number')
            ->where('form_status.reference_number',$dataForView['reference_number'])
            ->get();

		*/

		$sqlFormStatus = \Illuminate\Support\Facades\DB::table('comments')
			->select('comments.*', 'users.name')
            ->join('users','comments.user_id','=','users.user_id')
            ->where('comments.reference_number',$dataForView['reference_number'])
			->orderBy('comments.time','ASC')
            ->get();

        if (count($sqlFormStatus)> 0) {
        ?>

        <table style="width: 100%; margin: 0 auto; border-spacing: 2px; border-collapse: separate;" border="0" >

            <tr>

                <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Person</td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Log / In Time</td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Task Touch Time</td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Status</td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Close / Out Time</td>
                <td class="topandbottom text-center" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Duration (D:H:M:S)</td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Remarks</td>

            </tr>

            <?php

               $duration_in_minutes = 0;
            try {
                    if(!empty($_GET['qd'])){
                        $duration_in_minutes = decrypt($_GET['qd']);
                    }
            } catch (DecryptException $e) {

            }

            $i = 0;
            $j = 0;
            $models = array();
            $prevgID = '';
            $lastInTime = "";
            //echo count($sqlFormStatus);
           foreach ($sqlFormStatus as $row){

                $groupID = $row->group_id;
                $subGroupID = $row->subgroup_id;
                $userID = $row->user_id;
                $form_status = $row->action;
                $comments = $row->comments;
                $isapproved = $row->isapproved;
                $userName = $row->name;
                $models[$i]['group_id'] = $groupID;
                $models[$i]['duration_in_minutes'] = "";


                if($prevgID == $userID){
                    $models[$i]['user_id'] = '';
                    $models[$i]['user_name'] = '';
                }else{
                    $models[$i]['user_id'] = $userID;
                    $models[$i]['user_name'] = $userName;
                }

                if($i == 0){
                    $models[$i]['isapproved'] = 1;
                    $models[$i]['in_time'] = $row->time;
                    $models[$i]['work_time'] = $row->time;
                    $models[$i]['out_time'] = $row->time;
                    $lastInTime = $row->time;
                    $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row->time));
                }elseif($prevgID != $userID){
                    $models[$i]['in_time'] = $models[$i-1]['out_time'];
                    $models[$i]['work_time'] = $row->time;
                    $models[$i]['out_time'] = 0;
                    $lastInTime = $models[$i-1]['out_time'];
                }elseif($prevgID == $userID && $i > 0 && $isapproved == 0){
                    $models[$i]['in_time'] = 0;
                    $models[$i]['work_time'] = $row->time;
                    $models[$i]['out_time'] = 0;
                }elseif($prevgID == $userID && $i > 0 && $isapproved == 1){
                    $models[$i]['isapproved'] = $isapproved;
                    $models[$i]['in_time'] = 0;
                    $models[$i]['work_time'] = $row->time;
                    $models[$i]['out_time'] = $row->time;
                    $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row->time));
                }

                if(count($sqlFormStatus) == $i+1){
                    $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s'));
                }

                $models[$i]['form_status'] = $form_status;
                $models[$i]['comments'] = $comments;

                $prevgID = $userID;
                $i++;
            }


            foreach($models as $key=>$rowFormVal)
            {

            //pr($rowFormVal);
            $resultComm = '';
            $resultGName = $resultComm;

            ?>

            <tr>
            <?php /*if($rowFormVal['user_name'] != "") {*/?><!--
                <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php /*echo ''; */?></td>
                <?php /*}else{*/?>
                <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">&nbsp;</td>
                --><?php /*}*/?>
                <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['user_name'])){{ $rowFormVal['user_name'] }}@endif</td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if(!empty($rowFormVal['in_time']) > 0) echo date("d.m.Y ## h:i a",$rowFormVal['in_time']); ?></td>
                <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if(!empty($rowFormVal['work_time']) > 0) echo date("d.m.Y ## h:i a",$rowFormVal['work_time']); ?></td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['form_status']; ?></td>
                <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if(!empty($rowFormVal['out_time']) > 0) echo date("d.m.Y ## h:i a",$rowFormVal['out_time']); ?></td>
                <td class="" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px; text-align:center"><?php echo $rowFormVal['duration_in_minutes'] ?></td>
                <td class="topandbottom" colspan="2" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['comments'];  ?>&nbsp;</td>
            </tr>


            <?php } ?>
        </table>
        <?php } ?>


            @IF(!empty($dataForView['comment']))
			{{--<table class="table table-striped w-auto">
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
			</table>--}}

                {{--<tr class="warning"><th colspan="6" class="vcenter text-left">Comment</th></tr>
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
                @ENDFOREACH--}}
            @ENDIF
        <table>
            @IF(!empty($dataForView['non_customers_attachment']))
            <tr><th colspan="6" class="vcenter text-left">Attachment</th></tr>
            @FOREACH($dataForView['non_customers_attachment'] AS $attch)
                <tr>
                    <th class="vcenter float-left">{{ $attch['attachment_date'] }}</th>
                    <td class="vcenter float-left" colspan="5">&nbsp;
                        <?php //echo $attch['file_name'];die; ?>
                        @IF(!empty($attch['file_name']))
                            <?php
                                $basePath = str_replace('engine','',base_path());
                                $imageURL = $basePath.'public/attachments/'.$attch['file_name'];
                            // prd($imageURL);
                            if(file_exists($imageURL)){
                            ?>
                            <a href="{{URL::asset('public/attachments/'.$attch['file_name'])}}" target="_blank">{{$attch['file_name']}}</a>
                            <a href="{{ route('download', ['filename' => $attch['file_name']]) }}"><i class="fa fa-download"></i></a>
                            @IF(Auth::user()->id == $attch['uploaded_by'])
                                <button class="attachmentdel btn btn-link error text-right no-padding-margin" data-filename="{{$attch['file_name']}}" data-id="{{$attch['id']}}"><i class="fa fa-trash"></i> </button>
                            @ENDIF
                            |
                            <?php } else{?>
                            <a href="{{ url('/images') }}" target="_blank">{{$attch['file_name']}}</a>
                            <a href="{{ route('download', ['filename' => $attch['file_name']]) }}"><i class="fa fa-download"></i></a>
                            <?php }?>
                        @ELSE
                            <strong style="color:red;">{{ "Attachment not available" }}</strong>
                        @ENDIF
                    </td>
                </tr>
            @ENDFOREACH
            @ENDIF

            <?php
            $redirect_url = Request::url();
            $redirect_url .= (!empty($_GET)) ? '?'.http_build_query($_GET) : "";

            $editPermission = true;
            if (Auth::user()->can(['supportExecutive'])) {

                if (!empty($dataForView['access_by']) && (Auth::user()->user_id != $dataForView['access_by'])) {

                     $editPermission = false;
                }
                if ($loggerCanAssign == false && $isAdminOrLogger == true) {
                    $editPermission = false;
                }
                if(empty($_GET['qd'])) {
                    $editPermission = false;
                }
            }else if(Auth::user()->hasRole(['supervisor', 'srExecutive']) ){
                $editPermission = false;
            }else //if(Auth::user()->hasRole(['logger', 'executive']))
                {
                    //$editPermission = true;
                    $editPermission = false;
            }
            ?>
            {{--@inject('workflow_list','App\Services\WorkFlowService')
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
            @endphp--}}

            @IF(((Auth::user()->hasRole(['superadmin', 'admin', 'logger'])) || (Auth::user()->can(['supportExecutive']) )) && $editPermission == true && $dataForView['form_status'] != 11 && (!empty($dataForView['access_by'])))


                {!! Form::open(['method'=>'post', 'action' => ['SupportsController@uploadNewAttachment'] , 'enctype' => 'multipart/form-data']); !!}
                {!! Form::token(); !!}
                <tr><th colspan="6" class="">Attach New File<small class="error-message"> (Max file size is 3 MB)  </small></th></tr>
                <tr>
                    <td class="" colspan="6">
                        {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}

                        {{ Form::hidden('redirect_url',$redirect_url) }}

                        <div class="form-inline">
                            <div class="custom-file">
                            {!! Form::file('file_name[]', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file')); !!}
                                <button type="submit" class="btn btn-success ml-1"><i class="fa fa-upload"></i> Upload</button>
                            </div>
                        @if($errors->has('file_name.*'))
                            <div class="error">
                                {!! implode(' ', $errors->all(':message')); !!}
                            </div>
                        @endif
                        </div>
                    </td>

                </tr>
                {!! Form::close(); !!}

            @ENDIF
        </table>
        </table>

    </div>
    </fieldset>
    <div class="clearfix"></div>
    @IF(!empty($_GET['qd']))

        @IF(((Auth::user()->hasRole(['superadmin', 'admin'])) || (Auth::user()->can(['supportExecutive']) )) && $editPermission == true )

            <?php $_GET['st'] = date('Y-m-d H:i:s'); ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 no-padding-margin-l">
                {!! Form::open(['method'=>'post','class'=>'form-inline', 'action' => ['SupportsController@workingOnHandler'] , 'enctype' => 'multipart/form-data']); !!}
                {!! Form::token(); !!}
                {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}
                {{ Form::hidden('request_from','non-customer') }}
                {{ Form::hidden('qd',$_GET['qd']) }}
                {{ Form::hidden('st',$_GET['st']) }}

                <?php $searchedParam = '?'.(!empty($_GET)) ? '?'.http_build_query($_GET) : ""; ?>
                {{ Form::hidden('searchedParam',$searchedParam) }}
                @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11 && !empty($dataForView['access_by']))
                    <div class="row col-lg-4 form-group mr-1 mb-1">
                    {!!
                        Form::textarea('comments',"",[
                          'rows'=>2,
                          'class' => 'form-control comments-input',
                          'autocomplete'=>'off',
                          'placeholder'=>'Comments'
                        ]);
                    !!}
                    @IF($errors->has('comments')) <div class="error-message">{{ $errors->first('comments') }}</div> @ENDIF
                    </div>
                @ENDIF

                @IF(empty($dataForView['access_by']))
                    <?php
                    $_GET['activeUrl'] = "non-customer";
                    $getUrl = url('/Supports/assign/'.encrypt($dataForView['reference_number']));
                    if (!empty($_GET)) {
                        $getUrl .= '?'.(!empty($_GET)) ? '?'.http_build_query($_GET) : "";
                    }
                    ?>

                    @if(in_array($dataForView['unit_id'],userUnits())||in_array($dataForView['unit_id'],userUnits()))
                        <a href="{{$getUrl}}" onclick="overlay('show');" class="btn btn-success">Assign</a>
                    @endif
                @ELSE


                    @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)

                        <button type="submit" class="btn btn-primary form-group" value="forward" onclick="overlay('show');" name="forward">Forward To</button>
                        <div class="col-lg-3">
                            <select class="form-control col-lg-12" name="group_id">
                                <option value="">Please Select</option>
                                @inject('groups','App\Services\WorkFlowService')
                                @inject('groupUser','App\Services\UtilService')
                                @foreach($groups->getAllGroupList() as $group)
                                    <option value="{{ $group->id }}" >{{ $group->name }}
                                    @if($groupUser->groupUser($group->id) && $dataForView['unit_id'] == 1) -- [Checker] @else @endif</option>
                                @endforeach
                            </select>
                            @IF($errors->has('group_id')) <div class="error-message">Please Select Group</div> @ENDIF
                        </div>
                        @if($dataForView['unit_id'] == 1)
                            <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');" value="non_customer_approved" name="submit">Send to Checker</button>
                        @endif
                    @ENDIF


                    {{-- {{ user_unit() }}--}}

                    @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)

                            <button type="submit" class="btn btn-warning form-group mr-1" value="hold" onclick="overlay('show');" name="submit">Hold</button>

                    @ENDIF


                    @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)

                        <button type="submit" class="btn btn-info form-group mr-1" value="close" onclick="overlay('show');" name="submit">Close</button>

                        <!-- <button type="submit" class="btn btn-default" value="print" name="submit">Print</button> -->
                    @ENDIF
                @ENDIF

                {!! Form::close(); !!}
            </div>
        @ENDIF
        <div class="clearfix">&nbsp;</div>
    @ENDIF

@endsection

@section('extrajssection')
@IF($errors->has('comments'))
        <script type="text/javascript"> customAlert('Please Type Comment','You need to write comment','red');</script>
@ENDIF
<script type="text/javascript">

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

/*
var preventReload = false;
var browserPrefixes = ['moz', 'ms', 'o', 'webkit'],
isVisible = true; // internal flag, defaults to true
// get the correct attribute name
function getHiddenPropertyName(prefix) {
  return (prefix ? prefix + 'Hidden' : 'hidden');
}
// get the correct event name
function getVisibilityEvent(prefix) {
  return (prefix ? prefix : '') + 'visibilitychange';
}
// get current browser vendor prefix
function getBrowserPrefix() {
  for (var i = 0; i < browserPrefixes.length; i++) {
    if(getHiddenPropertyName(browserPrefixes[i]) in document) {
      // return vendor prefix
      return browserPrefixes[i];
    }
  }
  // no vendor prefix needed
  return null;
}
// bind and handle events
var browserPrefix = getBrowserPrefix(),
    hiddenPropertyName = getHiddenPropertyName(browserPrefix),
    visibilityEventName = getVisibilityEvent(browserPrefix);
function onVisible() {
    //alert('abc');
  // prevent double execution
  if(isVisible) {
    return;
  }
  if (preventReload == false) {
    location.reload();
  }
  // change flag value
  isVisible = true;
}
function onHidden() {
  // prevent double execution
  if(!isVisible) {
    return;
  }
  // change flag value
  isVisible = false;
}
function handleVisibilityChange(forcedFlag) {
  // forcedFlag is a boolean when this event handler is triggered by a
  // focus or blur eventotherwise it's an Event object
  if(typeof forcedFlag === "boolean") {
    if(forcedFlag) {
      return onVisible();
    }
    return onHidden();
  }
  if(document[hiddenPropertyName]) {
    return onHidden();
  }
  return onVisible();
}
$("input:file").click(function (){
    preventReload = true;
});
$(".comments-input").click(function (){
    preventReload = true;
});


document.addEventListener(visibilityEventName, handleVisibilityChange, false);
// extra event listeners for better behaviour
document.addEventListener('focus', function() {
  handleVisibilityChange(true);
}, false);
document.addEventListener('blur', function() {
  handleVisibilityChange(false);
}, false);
window.addEventListener('focus', function() {
    handleVisibilityChange(true);
}, false);
window.addEventListener('blur', function() {
  handleVisibilityChange(false);
}, false);
*/

</script>
@endsection
