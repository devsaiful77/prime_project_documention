@extends('layouts.admin')
@section('content')
<style>
    .jconfirm-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .error-message{
        font-size: 13px;
    }
    #complaint_summary,
    #complaint_summary::placeholder {
        color: #030303;
        opacity: 1;
    }
    #complaint_summary{
        border: 1px solid #030303;
    }
    #complaint_root_cause,
    #complaint_root_cause::placeholder {
        color: #873600;
        opacity: 1;
    }
    #complaint_root_cause{
        border: 1px solid #873600;
    }
    #action_taken,
    #action_taken::placeholder {
        color: #02600f;
        opacity: 1;
    }
    #action_taken{
        border: 1px solid #02600f;
    }
    .js-date{
        border: 1px solid #cccccc;
    }
    .row.here{
        textarea,select{
            margin-top: 0.5rem;
            width: 190px;
            height: 3rem;
            /* margin-left: 1rem; */
            margin-right: 0.5rem;
            appearance: auto;
        }
        button{

            width: 170px;
            height: 2.2rem;
            margin-right: 0.5rem;
            margin-top: 1.1rem;
            font-size: 0.9rem;
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
        .assign {
            width: 150px;
            height: 2.2rem;
            /* margin-right: 0.5rem; */
            /* margin-top: 0.5rem; */
            font-size: 0.9rem;
            padding-top: 0.3rem;
            /* padding-bottom: 0.5rem; */
            margin-left: 0.8rem;


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
<div class="curved-inner-pro pt-2 mb-3" style="background-color: #DFF0D8;">
	<div class="curved-inner-pro">
        <div class="curved-ctn">
            <h2 class="p-2">Complaint Detail</h2>
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
                <th class="vcenter text-left">Account/Card No.</th>
                <td class="vcenter text-left">
                    {{ !empty($dataForView['account_number']) ? $dataForView['account_number'] . (!empty($acc_br_code) ? '\\' . $acc_br_code : '') : '' }}
                </td>
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
                <th class="vcenter text-left">Passport No.</th>
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
                            $subgroup_info_id = Auth::user()->user_unit->subgroup_info_id;
                        @endphp
                        <!-- Button for balance inquiry -->
                        @if($subgroup_info_id == 3)
                            <button type="button" class="btn btn-warning form-group m-0 p-0 ballanceInquery"
                                    value="{{ $accountNumber }}, {{ $customer_id }}"
                                    onclick="overlay('show');">
                                Balance Inquiry
                            </button>
                        @endif
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
                    'comments.user_id', 'comments.reference_number', 'comments.time', 'comments.group_id', 'comments.subgroup_id', 'comments.action', 'comments.comments', 'comments.isapproved', 'comments.time', 'users.name',)
                    ->join('users', 'comments.user_id', '=', 'users.user_id')
                    ->where('comments.reference_number', $dataForView['reference_number'])->orderBy('comments.time', 'ASC')
                    ->get();

                    if (count($sqlFormStatus) > 0)
                    {
                ?>
                <br/>
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
                            $models[$i]['group_id'] = $groupID;
                            $models[$i]['duration_in_minutes'] = "";

                            if ($prevgID == $userID)
                            {
                                $models[$i]['user_id'] = '';
                                $models[$i]['user_name'] = '';
                            }
                            else
                            {
                                $models[$i]['user_id'] = $userID;
                                $models[$i]['user_name'] = $userName;
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
                            <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['user_name'])){{ $rowFormVal['user_name'] }}@endif</td>
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
                        if ($loggerCanAssign == false && $isAdminOrLogger == true)
                        {
                            $editPermission = false;
                        }
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

                                        <tr><th colspan="6" class="">Attach New File<small class="error-message"> (Max file size is 3 MB)  </small></th></tr>
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

                            <tr><th colspan="6" class="">Attach New File<small class="error-message"> (Max file size is 3 MB)  </small></th></tr>
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
    @IF(!empty($_GET['qd']))
        @IF(((Auth::user()->hasRole(['superadmin', 'admin'])) || (Auth::user()->can(['supportExecutive']) )) && $editPermission == true )
            <?php $_GET['st'] = date('Y-m-d H:i:s'); ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 no-padding-margin-l">
                {!! Form::open(['method'=>'post','class'=>'row here', 'action' => ['SupportsController@workingOnHandler'] , 'enctype' => 'multipart/form-data']); !!}
                    {!! Form::token(); !!}
                    {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}
                    {{ Form::hidden('request_from','complaint') }}
                    {{ Form::hidden('qd',$_GET['qd']) }}
                    {{ Form::hidden('st',$_GET['st']) }}

                    <?php $searchedParam = '?'.(!empty($_GET)) ? '?'.http_build_query($_GET) : ""; ?>
                    {{ Form::hidden('searchedParam',$searchedParam) }}
                    @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11 && !empty($dataForView['access_by']))
                        {{-- <div class="row col-lg-4 form-group mr-1 mb-1"> --}}
                        {!!
                            Form::textarea('comments',"",[
                            'rows'=>2,
                            'class' => 'form-control comments-input',
                            'autocomplete'=>'off',
                            'placeholder'=>'Comments'
                            ]);
                        !!}
                        @IF($errors->has('comments'))
                            <div class="error-message">
                                {{ $errors->first('comments') }}
                            </div>
                        @ENDIF
                        {{-- </div> --}}
                        {{--
                        <fieldset class="form-control is_justified">
                        <b>Send Notification?&nbsp;&nbsp;</b>
                        <label class="form-check-label">
                            <input type="radio" class="custom-control-input green" name="Send_Notification" checked value="1">&nbsp;&nbsp;Yes
                        </label> &nbsp;&nbsp;&nbsp;&nbsp;
                        <label class="form-check-label">
                            <input type="radio" class="custom-control-input red" name="Send_Notification" value="2">&nbsp;&nbsp;No
                        </label>
                        </fieldset>

                        {!!
                            Form::textarea('attribute_pin',"",[
                            'rows'=>2,
                            'class' => 'form-control',
                            'autocomplete'=>'off',
                            'placeholder'=>'Attribute Pin'
                            ]);
                        !!}

                        <fieldset class="form-control is_justified">
                        <b>Is Justified?&nbsp;&nbsp;</b>
                        <label class="form-check-label">
                            <input type="radio" class="custom-control-input green" name="is_justified" checked value="1">&nbsp;&nbsp;Yes
                        </label> &nbsp;&nbsp;&nbsp;&nbsp;
                        <label class="form-check-label">
                            <input type="radio" class="custom-control-input red" name="is_justified" value="0">&nbsp;&nbsp;No
                        </label>
                        </fieldset>
                        --}}

                        @IF(!empty($dataForView['is_api']) && ($dataForView['api_status'] == 0) && (Auth::user()->user_unit['subgroup_info_id'] == 16) && ($dataForView['card_status'] == 'C' || $dataForView['card_status'] == 'I' || $dataForView['card_status'] == 'S' || $dataForView['card_status'] == 'SB'))
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
                        @ENDIF
                            {{--
                            <button type="button" data-reference-no="{{ encrypt($dataForView['reference_number'])}}" data-request-from="wform" class="btn btn-info apiUpdateBtn">Update into System &amp; Close?</button>
                            --}}



                        @IF(!empty($dataForView['in_date_time']))
                            @IF($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 0)
                                <button type="button" class="btn btn-success sendBackSmsBtn form-group mr-1" data-comment-id="{{ encrypt
                                ($dataForView['in_date_time']['id'])}}" data-reference-no="{{ encrypt($dataForView['reference_number'])}}" data-mobile-no="{{ encrypt($dataForView['mobile_number'])}}" data-email-addr="{{ encrypt($dataForView['email_address'])}}" data-issue-name="{{ $dataForView['issue_name'] }}">Sendback SMS?</button>
                            @ELSEIF($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 1)
                                <button type="button" class="btn btn-success disabled form-group mr-1">Sendback SMS?</button>
                            @ENDIF
                        @ENDIF

                        @IF(!empty($dataForView['is_api']) && ($dataForView['api_status'] == 0) && (Auth::user()->user_unit['subgroup_info_id'] == 16) && ($dataForView['unit_id'] == 2) && ($dataForView['card_status'] == 'C' || $dataForView['card_status'] == 'I' || $dataForView['card_status'] == 'S' || $dataForView['card_status'] == 'SB'))
                            <button type="button" data-reference-no="{{ encrypt($dataForView['reference_number'])}}" data-request-from="complaint" class="btn btn-info apiUpdateBtn form-group mr-1">Update into System &amp; Close?</button>
                        @ENDIF
                    @ENDIF

                    @IF(empty($dataForView['access_by']))
                        <?php
                            $_GET['activeUrl'] = "complaint";
                            $getUrl = url('/Supports/assign/'.encrypt($dataForView['reference_number']));
                            // @dd($getUrl);
                            if (!empty($_GET)) {

                                $getUrl .= '?'.(!empty($_GET)) ? '?'.http_build_query($_GET) : "";
                            }
                            // @dd($getUrl);
                        ?>

                        @if(in_array($dataForView['unit_id'],userUnits())||in_array($dataForView['unit_id'],userUnits()))
                            <a href="{{$getUrl}}" onclick="overlay('show');" class="btn btn-success  form-group mr-1 assign">Assign</a>
                        @endif
                    @ELSE
                        @inject('is_regular_flow','App\Services\WorkFlowService')

                        @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::REGULAR)
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
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
                                    @IF(!empty($dataForView['auto_unit_id']))
                                        {{ Form::hidden('unit_id',$dataForView['auto_unit_id'] ) }}
                                    @ELSE

                                    @ENDIF

                                    @inject('groupUser','App\Services\UtilService')
                                <div class="col-lg-3 form-group row mr-1">
                                    <select class="form-control col-lg-12" name="group_id" style="height: 2.3rem;margin-top:1.1rem">
                                        <option value="">Please Select</option>
                                        @foreach($allmakers as $allmaker)
                                            <?php if($allmaker->subgroup_id > 0){?>}
                                                <option value="{{ $allmaker->group_id.','.$allmaker->subgroup_id }}" @if($groupUser->groupUser($allmaker->group_id)) disabled @else @endif>{{ $allmaker->name }}(maker)</option>
                                            <?php } ?>
                                        @endforeach
                                    </select>
                                    @IF($errors->has('group_id')) <div class="error-message">Please Select Group</div> @ENDIF
                                </div>
                                    @IF(is_priority() == 1 && is_sendback($dataForView['reference_number'])==0)
                                        @inject('subflow','App\Services\WorkFlowService')
                                        <?php
                                        $subflowLists = $subflow->subFlowList($dataForView['issue_id']);
                                        $requiredSelectSubflow = "";
                                        if($errors->has('subflow_type_group_id')) {
                                            $requiredSelectSubflow = "red-border-2px";
                                        }
                                        ?>
                                        {{--@IF(!empty($subflowLists))
                                        <input type="hidden" name="is_subflow_available" value="{{encrypt(1)}}">
                                        <div class="col-lg-3 form-group row mr-1">
                                        <select class="form-control col-lg-12 {{$requiredSelectSubflow}}" name="subflow_type_group_id">
                                            <option value="">Subflow Option</option>
                                            @foreach($subflowLists as $sbFlowList)
                                                <?php
                                                $subflowSelected='';
                                                if (old('subflow_type_group_id') == $sbFlowList->group_id) {
                                                    $subflowSelected='selected';
                                                }
                                                ?>
                                                <option value="{{$sbFlowList->group_id}}" {{$subflowSelected}}>{{$sbFlowList->options}}</option>
                                            @endforeach
                                        </select>
                                        @IF($errors->has('subflow_type_group_id')) <span class="error-message">{{ $errors->first('subflow_type_group_id') }}</span> @ENDIF
                                        </div>
                                        @ENDIF--}}

                                    @ENDIF
                                @endif
                            @ENDIF

                                @inject('last_step','App\Services\WorkFlowService')
                                @php $last_person= $last_step->workflowLastStep($dataForView['reference_number']);

                                @endphp

                                @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)

                                    @if($last_person==false)
                                        @inject('subflowExt','App\Services\WorkFlowService')
                                        @php $subflowExists = $subflowExt->subFlowList($dataForView['issue_id']); @endphp
                                        @if(!empty($subflowExists))
                                        @if($dataForView['unit_id'] == 1)
                                            <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                                                    value="approved" name="submit" style=";">Approve</button>
                                        @elseif(is_priority() == 1 && $dataForView['unit_id'] == 2 && is_sendback($dataForView['reference_number'])==0)
                                            <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                                                    value="approved" name="submit" style=";">Approve</button>
                                        @elseif(is_priority() == 0)
                                            <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                                                    value="approved" name="submit" style=";">Approve</button>
                                        @endif
                                        @else
                                        <button type="submit" class="btn btn-success form-group mr-1" onclick="overlay('show');"
                                                value="approved" name="submit" style=";">Approve</button>
                                        @endif
                                    @endif
                                @ENDIF

                                {{-- {{ user_unit() }}--}}

                                @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                    @if($hold==1)
                                        <button type="submit" class="btn btn-warning form-group mr-1" onclick="overlay('show');"
                                                value="hold" name="submit" style="width: 110px;">Hold</button>
                                    @endif
                                @ENDIF

                                @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::REGULAR)
                                        <button type="submit" class="btn btn-info close-btn form-group mr-1"
                                                        value="close" onclick="overlay('show');" name="submit" style="margin-right: 40px;">Close</button>
                                @endif


                                @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11 && $dataForView['form_status'] != 12)
                                    @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::FORWARD)
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <textarea class="form-control m-0 w-100" id="complaint_summary" name="com_summary" autocomplete="off" placeholder="Complaint Summary" cols="30" rows="3"></textarea>
                                                @if($errors->has('com_summary'))
                                                    <div class="error-message">{{ $errors->first('com_summary') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                <textarea class="form-control m-0 w-100" id="complaint_root_cause" name="com_root_cause" autocomplete="off" placeholder="Complaint Root Cause" rows="3"></textarea>
                                                @if($errors->has('com_root_cause'))
                                                    <div class="error-message">{{ $errors->first('com_root_cause') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                <textarea class="form-control m-0 w-100" id="action_taken" name="action_taken" autocomplete="off" placeholder="Action Taken" rows="3"></textarea>
                                                @if($errors->has('action_taken'))
                                                    <div class="error-message">{{ $errors->first('action_taken') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-2">

                                                <input type="text" class="form-control datePicker js-date" name="action_date" autocomplete="off" maxlength="10" placeholder="Actual Resolve Date" readonly>
                                                @if($errors->has('action_date'))
                                                    <div class="error-message">{{ $errors->first('action_date') }}</div>
                                                @endif
                                                <script>
                                                    $(document).ready(function () {
                                                        $('.datePicker').datepicker({
                                                            dateFormat: 'dd-mm-yy',
                                                            changeYear: true,
                                                            changeMonth: true,
                                                            yearRange: "1900:2050",
                                                        });
                                                    });
                                                    var input = document.querySelectorAll('.js-date')[0];
                                                    var dateInputMask = function dateInputMask(elm) {
                                                        elm.addEventListener('keypress', function(e) {
                                                            if(e.keyCode < 47 || e.keyCode > 57) {
                                                                e.preventDefault();
                                                            }
                                                            var len = elm.value.length;
                                                            if(len !== 1 || len !== 3) {
                                                                if(e.keyCode == 47) {
                                                                    e.preventDefault();
                                                                }
                                                            }
                                                            if(len === 2) {
                                                                elm.value += '-';
                                                            }
                                                            if(len === 5) {
                                                                elm.value += '-';
                                                            }
                                                        });
                                                    };
                                                    dateInputMask(input);
                                                </script>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-info close-btn form-group mr-1" value="resolve" onclick="overlay('show');" name="submit" style="width: 110px;">Resolve</button>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- <button type="submit" class="btn btn-default" value="print" name="submit">Print</button> -->

                                    <a class="form-control is_justified form-group mr-1">
                                    <b style="color:red">Send Close Notification?&nbsp;&nbsp;</b>
                                {{--<label class="form-check-label">--}}
                                        <input type="checkbox" name="closenotification" value="1" checked/>
                                {{--</label>--}}
                                    </a>

                                @ENDIF
                        @endif
                        @inject('is_regular_flow','App\Services\WorkFlowService')
                        @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::FORWARD)
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                @if($dataForView['unit_id'] == 1 && is_priority()==1)

                                @else
                                <button type="submit" class="btn btn-warning form-group mr-1" value="sendBackRegular" onclick="overlay('show');" name="submit" style="">Sendback To Initiator</button>
                                {{ Form::hidden('group_id_reqular', $dataForView['comment'][0]['group_id']) }}
                                {{ Form::hidden('subgroup_id', $dataForView['comment'][0]['subgroup_id']) }}
                                @endif
                                <?php
                                $requiredSelect = "";
                                if($errors->has('unit_id')) {
                                    $requiredSelect = "red-border-2px";
                                }
                                ?>
                                @if(($dataForView['unit_id'] == 1 || $dataForView['unit_id'] == 2) && is_priority()==1)


                            <button type="button" class="btn btn-success fwdToSrc" value="forwardRegular" name="forwardtosrc" style="">Forward To Front Office</button>


                                @endif
                                <button type="submit" class="btn btn-primary form-group mr-1" value="forward" onclick="overlay('show');" name="forward" style="">Forward To Back Office</button>
                            {{-- <div class="col-lg-3 form-group row mr-1"> --}}
                                <select class="form-control col-lg-12" name="group_id" style="height: 2.3rem;margin-top:1.1rem">
                                    <option value="">Please Select</option>
                                    @inject('groups','App\Services\WorkFlowService')
                                    @inject('groupUser','App\Services\UtilService')
                                    @foreach($groups->getAllGroupList() as $group)
                                        <option value="{{ $group->id }}" >{{ $group->name }}
                                        @if($groupUser->groupUser($group->id) && $dataForView['unit_id'] == 1) -- [Checker] @else @endif</option>
                                    @endforeach
                                </select>
                                @IF($errors->has('group_id')) <div class="error-message">Please Select Group</div> @ENDIF
                            {{-- </div> --}}
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                            <!-- <button type="submit" class="btn btn-danger" value="reject" name="submit">Reject</button> -->
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                <button type="submit" class="btn btn-warning form-group mr-1" value="hold" onclick="overlay('show');"
                                        name="submit" style="width: 110px;">Hold</button>
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11 && $dataForView['form_status'] != 12)
                                    <a class="form-control is_justified form-group mr-1">
                                        <b style="color:red">Send Close Notification?&nbsp;&nbsp;</b>
                                        {{-- label class="form-check-label">--}}
                                        <input type="checkbox" name="closenotification" value="1" checked/>
                                        {{--    </label>--}}
                                    </a>
                                    @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::FORWARD)
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <textarea class="form-control m-0 w-100" id="complaint_summary" name="com_summary" autocomplete="off" placeholder="Complaint Summary" rows="3"></textarea>
                                                @if($errors->has('com_summary'))
                                                    <div class="error-message">{{ $errors->first('com_summary') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                <textarea class="form-control m-0 w-100" id="complaint_root_cause" name="com_root_cause" autocomplete="off" placeholder="Complaint Root Cause" rows="3"></textarea>
                                                @if($errors->has('com_root_cause'))
                                                    <div class="error-message">{{ $errors->first('com_root_cause') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                <textarea class="form-control m-0 w-100" id="action_taken" name="action_taken" autocomplete="off" placeholder="Action Taken" rows="3"></textarea>
                                                @if($errors->has('action_taken'))
                                                    <div class="error-message">{{ $errors->first('action_taken') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-2">

                                                <input type="text" class="form-control datePicker js-date" name="action_date" autocomplete="off" maxlength="10" placeholder="Actual Resolve Date" readonly>
                                                @if($errors->has('action_date'))
                                                    <div class="error-message">{{ $errors->first('action_date') }}</div>
                                                @endif
                                                <script>
                                                    $(document).ready(function () {
                                                        $('.datePicker').datepicker({
                                                            dateFormat: 'dd-mm-yy',
                                                            changeYear: true,
                                                            changeMonth: true,
                                                            yearRange: "1900:2050",
                                                        });
                                                    });
                                                    var input = document.querySelectorAll('.js-date')[0];
                                                    var dateInputMask = function dateInputMask(elm) {
                                                        elm.addEventListener('keypress', function(e) {
                                                            if(e.keyCode < 47 || e.keyCode > 57) {
                                                                e.preventDefault();
                                                            }
                                                            var len = elm.value.length;
                                                            if(len !== 1 || len !== 3) {
                                                                if(e.keyCode == 47) {
                                                                    e.preventDefault();
                                                                }
                                                            }
                                                            if(len === 2) {
                                                                elm.value += '-';
                                                            }
                                                            if(len === 5) {
                                                                elm.value += '-';
                                                            }
                                                        });
                                                    };
                                                    dateInputMask(input);
                                                </script>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-info close-btn form-group m-0" value="resolve" onclick="overlay('show');" name="submit" style="width: 110px;">Resolve</button>
                                            </div>
                                        </div>
                                    @endif
                                <!-- <button type="submit" class="btn btn-default" value="print" name="submit">Print</button> -->



                            @ENDIF
                        @endif
                {{-- @if($is_exist == 1)
                        <a href="#" data-id="" class="btn btn-warning" id="isInquiryApi">Inquiry API</a>
                    @endif--}}
                    @ENDIF

                {!! Form::close(); !!}
            </div>
        @ENDIF
        <div class="clearfix">&nbsp;</div>
    @ENDIF

    <!-- Modal -->
        {{-- <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Issue Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($issue_checklist_status)
                        <button type="submit" id="savechange" class="btn btn-primary">Save changes</button>
                        @endif
                    </div>
                    </form>
                </div>
            </div>
        </div> --}}

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

        <div class="modal fade" id="issueHistoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">History Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        {{-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> --}}
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped commonDataTableAll">
                    <thead>
                        <tr style="background-color: #DFF0D8">
                            <th class="vcenter text-left">Issue Data</th>
                            <th class="vcenter text-left">Check List Data</th>
                            <th class="vcenter text-left">User</th>
                            <th class="vcenter text-left">Edit Time</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $history_data = \Illuminate\Support\Facades\DB::table('complaint_form_type_histories')
                    ->select(
                        // 'complaint_form_type_histories.*',
                        'complaint_form_type_histories.user_id',
                        'complaint_form_type_histories.reference_number',
                        'complaint_form_type_histories.created_at',
                        'complaint_form_type_histories.extra_field',
                        'complaint_form_type_histories.check_list',
                        'users.name',
                    )
                    ->join('users','complaint_form_type_histories.user_id','=','users.id')
                    ->where('complaint_form_type_histories.reference_number', $dataForView['reference_number'])
                    ->orderBy('complaint_form_type_histories.created_at','DESC')
                    ->get();
                    // dd($history_data);

                    if(!empty($history_data)) {
                        foreach ($history_data as $row){
                    ?>
                        <tr>
                            <th class="vcenter text-left">
                                <table class="table table-condensed table-bordered no-padding-margin-b">
                                    <?php
                                        //pr($row);
                                        $extra_fields = "";
                                        $i=1;
                                        $extra_fields = (array)json_decode($row->extra_field);
                                        $count = count($extra_fields);
                                        if(!empty($extra_fields)){
                                            foreach($extra_fields as $key=>$r){
                                                foreach($r as $key1=>$value){
                                                    if($i == 1) {
                                    ?>

                                        <tr>
                                    <?php } ?>
                                    <th class="vcenter text-left">{{ $key1 }}</th>
                                    <td class="vcenter text-left">{{ (isset($value))? $value:"" }}</td>

                                    @if($i == 2)
                                        </tr>
                                        <?php $i=0;?>
                                    @elseif($count == 1)
                                        @if($i == 1)
                                            <th>&nbsp;</th>
                                            <td>&nbsp;</td>
                                            </tr>
                                        @else
                                        </tr>
                                        @endif
                                    @endif

                                    <?php $i++; $count--;?>

                                    <?php }}} ?>
                                </table>
                            </th>

                            <th class="vcenter text-left">
                                <table class="table table-condensed table-bordered no-padding-margin-b">
                                    <?php
                                        //pr($row);
                                        $check_lists = "";
                                        $check_lists = (array)json_decode($row->check_list);
                                        if(!empty($check_lists)){
                                            foreach($check_lists as $key=>$r){
                                                foreach($r as $key1=>$value){

                                    ?>
                                <tr>
                                    <th class="vcenter text-left">{{ $key1 }}</th>
                                    <td class="vcenter text-left">{{ (isset($value))? $value:"" }}</td>
                                </tr>
                                    <?php }}} ?>
                                </table>
                            </th>

                            <th class="vcenter text-left">{{$row->name}}</th>
                            <th class="vcenter text-left">{{$row->created_at}}</th>
                        </tr>
                    <?php
                    }
                }

                    ?>
                </tbody>
                </table>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="inquiryModalLabel">Inquiry Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="inquiryData">
                            <div class="col-lg-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- @endsection --}}
    {{-- <script src="{{ URL::asset('public/js/latest-v/jquery-3.7.1.min.js') }}"></script>  --}}

    {{-- @section('extrajssection') --}}
</div>
<script>

$(document).ready(function () {
    console.log("io");
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

        // $(".fwdToSrc").on("click",function($e){

        //     var ref_no = "{{ encrypt($dataForView['reference_number'])}}";
        //     var qd = "{{ (!empty($_GET['qd']))? $_GET['qd']:'' }}";
        //     var req_from = "complaint";
        //     var selectInput = "<select class='form-control otherSrc'  name='othersrc'><option value=''>Please Select</option></select>";
        //     $.ajax({
        //         type: "get",
        //         url: "{{url('/Supports/GetTouchSubGroups/')}}",
        //         dataType: "html",
        //         beforeSend: function(){
        //             overlay('show');
        //         },
        //         success: function(data){
        //             overlay('hide');

        //             selectInput = "<select class='form-control otherSrc' name='othersrc'><option value=''>Please Select</option>"+data+"</select>";

        //             $.confirm({
        //                 title:'Forward to other source?',
        //                 content:''+
        //                     '<div class="form-group">' +
        //                         '<label>Select Source</label>' +
        //                         selectInput+
        //                         '<div class="error othersrcerr"></div>'+
        //                     '</div>'+
        //                     '<div class="form-group">' +
        //                         '<label>Comments</label>' +
        //                         '<input type="text" name="comments" placeholder="Please Write Comments" class="comments form-control" required />' +
        //                         '<div class="error commentserr"></div>'+
        //                     '</div>',
        //                 type:'green',
        //                 typeAnimated: true,
        //                 buttons: {
        //                     Yes: {
        //                         text: 'YES',
        //                         btnClass: 'btn-red',
        //                         action: function(){
        //                             $(".othersrcerr").text('');
        //                             $(".commentserr").text('');

        //                             var subgroup_id = this.$content.find('.otherSrc').val();
        //                             var comments = this.$content.find('.comments').val();
        //                             if(!subgroup_id || !comments){
        //                                 if(!subgroup_id){
        //                                     $(".othersrcerr").text('Other Source is required');
        //                                 }
        //                                 if(!comments){
        //                                     $(".commentserr").text('Comments is required');
        //                                 }
        //                                 return false;
        //                             }
        //                             var group_id = $('option:selected', '.otherSrc').attr('group-id');


        //                             $.ajax({
        //                                 type: "post",
        //                                 url: "{{url('/Supports/WorkingOnHandler/')}}",
        //                                 data: {
        //                                     _token: _token,
        //                                     reference_number: ref_no,
        //                                     qd: qd,
        //                                     submit:'forwardToSource',
        //                                     request_from: req_from,
        //                                     group_id: group_id,
        //                                     subgroup_id: subgroup_id,
        //                                     comments:comments
        //                                 },
        //                                 dataType: "json",
        //                                 beforeSend: function(){
        //                                     overlay('show');
        //                                 },
        //                                 success: function(data){
        //                                     overlay('hide');
        //                                     if (data == 1) {
        //                                         redirectToUrl('Success','This Ticket have been forwarded successfully..','green','Supports/handler');
        //                                     } else if (data == 2) {
        //                                         customAlert('Warning','This Ticket is not available in your queue!!!. Please refresh this page','red');
        //                                     } else if (data == 3) {
        //                                         customAlert('Warning','Failed to forward to other source!! This Ticket is not Created by CI Customer','red');
        //                                     } else {
        //                                         customAlert('Warning','Failed to forward to other source. Please Contact with Administrator','red');
        //                                     }
        //                                 },
        //                                 error: function(data){
        //                                     overlay('hide');
        //                                     customAlert('Error','Something went wrong. Please Contact with Administrator','red');
        //                                 }
        //                             });
        //                         }
        //                     },
        //                     No: {text: 'NO'}
        //                 }
        //             });
        //         },
        //         error: function(data){
        //             overlay('hide');
        //             customAlert('Error','Something went wrong. Please Contact with Administrator','red');
        //         }
        //     });
        // });


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
                                            redirectToUrl('Success', 'This Ticket has been forwarded successfully.', 'green', 'Supports/handler');
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
$(".modalissuebar").click(function (){
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
