@php
    use App\SubgroupInfo;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use App\GroupInfo;
@endphp
<style>
.jconfirm-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
.row.here{
    textarea,select{
        margin-top: 0.5rem;
        width: 190px;
        height: 2.5rem;
        /* margin-left: 1rem; */
        margin-right: 0.5rem;
        appearance: auto;
    }
    button{

        width: 135px;
        height: 2.5rem;
        margin-right: 0.5rem;
        margin-top: 0.5rem;
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
    .assign{
        width: 135px;
        /* height: 2.5rem; */
        margin-right: 0.5rem;
        margin-top: 0.5rem;
        font-size: 1rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    /* input{
        width: 150px;
    }
    button{
        width: 150px;
    } */
}

</style>
<?php $i = 1;$x = 1;$y = 1;$z = 1;
$w_form_type_history = "";
//pr($dataForView);

$status = "";
    $form_status = $dataForView['form_status'];
    if($form_status == 8 || $form_status == 0 || $form_status == null) {
        $status = "Assigned";
    } else if ($form_status == 11) {
        $status = "Close";
    } else if ($form_status == 12) {
        $status = "Resolved";
    } else if ($form_status == -1) {
        $status = "Reject";
    } else if ($form_status == -2) {
        $status = "Cancel";
    } else if ($form_status == 10) {
        $status = "Hold";
    }else{
        $status = "WIP";
    }
?>
@extends('layouts.admin')
@section('content')
    @php
        use App\Setting;
        $settings = Setting::first();
        if (!empty($settings) && !empty($settings->file_size_limit)) {
            $fileSizeLimit = (int) $settings->file_size_limit / 1024;
        } else {
            $fileSizeLimit = 10240 / 1024;
        }
    @endphp

    @inject('queueDuration','App\Services\UtilService')
    <div class="curved-inner-pro pt-2 mb-3" style="background-color: #e8eff7;">
        <div class="curved-inner-pro">
            <div class="curved-ctn">
                <h2 class="p-2">Service Request Detail <small style="color: black; font-weight: bold;">(Ticket No. : {{$dataForView['reference_number']}})&nbsp;(Ticket Status : {{$status}})</small></h2>
            </div>
        </div>
    </div>
    <?php //echo '<pre>';print_r($dataForView);
    //print_r(Auth::user()->user_unit['subgroup_info_id']);  ?>
    <div>

        <fieldset class="scheduler-border" style="background-color:#ffff">
            <div class="scheduler-border">
                <a class="colla" data-bs-toggle="collapse" href="#collapseOne" role="button" aria-expanded="false" aria-controls="collapseOne" style="cursor: pointer; font-weight: bold; color: #ffffff;">
                    Information <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
            <div class="table-responsive collapse in" id="collapseOne">
                <table class="table table-condensed table-bordered no-padding-margin-b">
                    <tr>
                        <th class="vcenter text-left">Ticket No.</th>
                        <td class="vcenter text-left">{{ !empty($dataForView['reference_number']) ? $dataForView['reference_number'] : "" }}</td>
                        <th class="vcenter text-left">Account/Card No..</th>
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
                    @if($dataForView["product_type"] == 1)
                        <tr>
                            <th class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? "Masked Card Number" : "" }}</th>
                            <td class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? $dataForView['mask_card_no'] : "" }}</td>
                            <th class="vcenter text-left"></th>
                            <td class="vcenter text-left"></td>
                            <th class="vcenter text-left"></th>
                            <td class="vcenter text-left"></td>
                            {{--  <th class="vcenter text-left"></th>
                              <td class="vcenter text-left"></td>--}}
                        </tr>
                    @endif
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
                            <th class="vcenter text-left">{{ ($dataForView['card_status']=='SB'?"":"Card Status") }}</th>
                            <td class="vcenter text-left">{{ ($dataForView['card_status']=='SB'?"":$dataForView['card_status']) }}</td>
                        @else
                            <th class="vcenter text-left">&nbsp;</th>
                            <td class="vcenter text-left">&nbsp;</td>
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

                                    $isTouchPoint = false;
                                    if ($subgroup_info_id) {
                                        $subgroup = SubgroupInfo::find($subgroup_info_id);
                                        if ($subgroup) {
                                            $group = GroupInfo::find($subgroup->group_info_id);
                                            if ($group && $group->group_level_id == 1) {
                                                $isTouchPoint = true;
                                            }
                                        }
                                    }
                                @endphp
                                <!-- Button for balance inquiry -->
                                @if($dataForView['product_name'] == "Accounts" && !$isTouchPoint)
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
                <a class="colla" data-bs-toggle="collapse" data-bs-target="#collapseTow" aria-expanded="false" aria-controls="collapseTow" style="cursor: pointer; font-weight: bold; color: #ffffff;">
                    Action <i class="fa fa-minus" aria-hidden="true"></i>
                </a>

                {{-- <a class="colla" data-toggle="collapse" data-target="#collapseTow" aria-expanded="false" aria-controls="collapseTow" style="cursor: pointer; font-weight: bold; color:#ffffff;">
                    Action <i class="fa fa-minus" aria-hidden="true"></i>
                </a> --}}
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
                        <th class="vcenter text-left">Static Verified</th>
                        <td class="vcenter text-left">{{$dataForView['static_verified']}}</td>
                        <th class="vcenter text-left">Dynamic Verified</th>
                        <td class="vcenter text-left">{{$dataForView['dynamic_verified']}}</td>
                        <th class="vcenter text-left">Notes.</th>
                        <td class="vcenter text-left">{{$dataForView['notes']}}</td>
                    </tr>
                    {{--<tr>
                        <th class="vcenter text-left">Address.</th>
                        <td class="vcenter text-left">{{$dataForView['address']}}</td>
                        <th class="vcenter text-left">Other.</th>
                        <td class="vcenter text-left">{{$dataForView['other']}}</td>
                        <th class="vcenter text-left">Other 2.</th>
                        <td class="vcenter text-left">{{$dataForView['other2']}}</td>
                    </tr>--}}
                    <tr>

                        <th class="vcenter text-left">Caller ID.</th>
                        <td class="vcenter text-left">{{$dataForView['caller_id']}}</td>
                        <th class="vcenter text-left"><b>Service Request Type</b></th>
                        <td class="vcenter text-left">{{ (!empty($dataForView['category_name'])) ? $dataForView['category_name'] : $dataForView['depricate_wform_type'] }}</td>
                        <!-- <th class="vcenter text-left">TARA/Non TARA</th>
                        <td class="vcenter text-left">
                            @php
                                $taratype = UNSERIALIZE(TARATYPE);
                                $is_tara = (!empty($dataForView['is_tara'])) ? $dataForView['is_tara'] : 0;
                            @endphp
                            {{ (!empty($taratype[$is_tara])) ? $taratype[$is_tara] : 'N/A' }}
                        </td> -->
                    </tr>
                    @include('Supports/wform_details_extended')

                    <tr>
                        <th colspan="6">&nbsp;</th>
                    </tr>

                    <tbody style="border:0">
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
                                Form Close at {{ $dataForView['name'] }} [{{ $dataForView['unit_id'] <> 2 ? "Maker" : "Checker" }}]
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
                        <th class="vcenter text-left">&nbsp;</th>
                        <td class="vcenter text-left">&nbsp;</td>
                    </tr>


                    @IF(!empty($dataForView['w_form_attachment']))
                        <table style="margin-top: 15px; margin-bottom: 15px">
                            <tr>
                                <th colspan="6" class="vcenter text-left">Attachment</th>
                            </tr>
                            
                            @FOREACH($dataForView['w_form_attachment'] AS $attch)
                                <tr>

                                    @if ($dataForView['depricate_wform_type'] == 1450)

                                        <td class="vcenter float-left" style="margin-right: 10px">
                                            {{ $attch['name'] ?? '' }} :
                                        </td>
                                    @else
                                        <td class="vcenter float-left">{{ $attch['attachment_date'] }}</td>
                                    @endif

                                    <td class="vcenter float-left" colspan="5">&nbsp;
                                        <?php //echo $attch['file_name'];die; ?>
                                        @IF(!empty($attch['file_name']))
                                            <?php
                                            $basePath = str_replace('engine', '', base_path());
                                            $imageURL = $basePath . 'public/attachments/' . $attch['file_name'];
                                            $ext = strtolower(pathinfo($attch['file_name'], PATHINFO_EXTENSION));

                                            // prd($imageURL);
                                            if (file_exists($imageURL)){
                                                ?>
                                                @if ($ext == 'msg')
                                                    <a href="{{ route('download', ['filename' => $attch['file_name']]) }}">{{ $attch['file_name'] }}</a>
                                                @else
                                                    <a href="{{URL::asset('public/attachments/'.$attch['file_name']) }}"
                                                        target="_blank">{{$attch['file_name']}}</a>
                                                @endif

                                                <a href="{{ route('download', ['filename' => $attch['file_name']]) }}"><i
                                                    class="fa fa-download"></i></a>


                                            @IF(Auth::user()->id == $attch['uploaded_by'])
                                                <button
                                                    class="attachmentdel btn btn-link error text-right no-padding-margin"
                                                    data-filename="{{$attch['file_name']}}" data-id="{{$attch['id']}}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @ENDIF
                                            |
                                            <?php } else{ ?>
                                            <a href="{{ url('/images') }}" target="_blank">{{$attch['file_name']}}</a>
                                            <a href="{{ route('download', ['filename' => $attch['file_name']]) }}"><i
                                                    class="fa fa-download"></i></a>
                                            <?php } ?>
                                        @ELSE
                                            <strong style="color:red;">{{ "Attachment not available" }}</strong>
                                        @ENDIF
                                    </td>
                                </tr>
                            @ENDFOREACH
                            @ENDIF

                            @php
                                $redirect_url = Request::url();
                                $redirect_url .= (!empty($_GET)) ? '?' . http_build_query($_GET) : "";

                                $editPermission = true;
                                if (Auth::user()->can(['supportExecutive'])) {
                                    if (!empty($dataForView['access_by']) && (Auth::user()->user_id != $dataForView['access_by'])) {
                                        $editPermission = false;
                                    }
                                    if ($loggerCanAssign == false && $isAdminOrLogger == true) {
                                        $editPermission = false;
                                    }
                                    if (empty($_GET['qd'])) {
                                        $editPermission = false;
                                    }
                                } else if (Auth::user()->hasRole(['supervisor', 'srExecutive'])) {
                                    $editPermission = false;
                                } else //if(Auth::user()->hasRole(['logger', 'executive']))
                                {
                                    //$editPermission = true;
                                    $editPermission = false;
                                }
                            @endphp
                            @inject('flow_type','App\Services\WorkFlowService')
                            @php
                                $flow_type_name = $flow_type->getFlowTypeCheck($dataForView['reference_number']);
                            @endphp
                            @if($flow_type_name == \App\Enum\FlowEnum::REGULAR)
                                @inject('workflow_list','App\Services\WorkFlowService')

                                @php
                                    $work = $workflow_list->workflowStage($dataForView['reference_number']);
                                    $sla_user = false;
                                    if (\Illuminate\Support\Facades\Auth::user()->hasRole([\App\Enum\RoleEnum::LOGGER,\App\Enum\RoleEnum::EXECUTIVE])) {
                                        $sla_user = true;
                                    }
                                    if (!empty($work)) {
                                        $touch = $work['touch'];
                                        $sla = $work['sla'];
                                        $hold = $work['hold'];
                                        $attach = $work['attach'];
                                        $attach_item = $work['attach_item'];
                                    }
                                @endphp
                            @else
                                @php
                                    $sla_user = false;
                                    $touch = '';
                                    $sla = '';
                                    $hold = '';
                                    $attach = '';
                                    $attach_item = '';
                                @endphp
                            @endif

                            {{-- ignore new attachment for bpID id no BPID--}}
                            @if ($dataForView['depricate_wform_type'] != 1450)
                                @IF (((Auth::user()->hasRole(['superadmin', 'admin', 'logger'])) || (Auth::user()->can(['supportExecutive']) )) && $editPermission == true && $dataForView['form_status'] != 11 && (!empty($dataForView['access_by'])))
                                    @if($flow_type_name==\App\Enum\FlowEnum::REGULAR)
                                        @if($attach==1)
                                        @inject('attachmentCount','App\Services\UtilService')
                                        @php $attachmentItemCount = $attachmentCount->attachmentCount($dataForView['reference_number']); @endphp
                                        @if($attachmentItemCount < $attach_item)
                                            {!! Form::open(['method'=>'post', 'action' => ['SupportsController@uploadNewAttachment'] , 'enctype' => 'multipart/form-data']); !!}
                                            {!! Form::token(); !!}
                                            <tr>
                                                <th colspan="6" class="">Attach New File<small class="error-message">
                                                        (Max file size is {{ $fileSizeLimit }} MB) </small></th>
                                            </tr>
                                            <tr>
                                                <td class="" colspan="6">
                                                    {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}

                                                    {{ Form::hidden('redirect_url',$redirect_url) }}
                                                    <div class="form-inline">
                                                        <div class="custom-file">
                                                            @for($i=0;$i < $attach_item-$attachmentItemCount; $i++)
                                                                {!! Form::file('file_name[]', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file')); !!}
                                                            @endfor
                                                            <button type="submit" class="btn btn-success ml-1"><i
                                                                    class="fa fa-upload"></i> Upload
                                                            </button>
                                                        </div>
                                                        @if($errors->has('file_name.*'))
                                                            <div
                                                                class="error">{!! implode(' ', $errors->all(':message')); !!}</div>
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
                                    <tr>
                                        <th colspan="6" class="">Attach New File<small class="error-message"> (Max file
                                                size is {{ $fileSizeLimit }} MB) </small></th>
                                    </tr>
                                    <tr>
                                        <td class="" colspan="6">
                                            {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}

                                            {{ Form::hidden('redirect_url',$redirect_url) }}
                                            <div class="form-inline">
                                                <div class="custom-file">
                                                    {!! Form::file('file_name[]', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file','multiple')); !!}
                                                    <button type="submit" class="btn btn-success ml-1"><i
                                                            class="fa fa-upload"></i> Upload
                                                    </button>
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
                                    @endif
                                @ENDIF
                            @endif
                        </table>
                    </tbody>
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
                <?php

                /*
                $sqlFormStatus = \Illuminate\Support\Facades\DB::table('form_status')
                    ->join('users','form_status.user_id','=','users.id')
                    ->leftjoin('comments','form_status.reference_number','comments.reference_number')
                    ->where('form_status.reference_number',$dataForView['reference_number'])
                    ->get();

                */

                $sqlFormStatus = \Illuminate\Support\Facades\DB::table('comments')
                    ->select(
                    // 'comments.*',
                        'comments.user_id',
                        'comments.reference_number',
                        'comments.time',
                        'comments.group_id',
                        'comments.subgroup_id',
                        'comments.action',
                        'comments.comments',
                        'comments.isapproved',
                        'comments.time',
                        'users.name',
                    )
                    ->join('users', 'comments.user_id', '=', 'users.user_id')
                    ->where('comments.reference_number', $dataForView['reference_number'])
                    ->orderBy('comments.time', 'ASC')
                    ->get();

                if (count($sqlFormStatus) > 0) {
                    ?>
                <br/>
                <table style="width: 100%; margin: 0 auto; border-spacing: 2px; border-collapse: separate;"
                       border="0">
                    <tr>

                        <td class="topandbottom"
                            style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">
                            Person
                        </td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">
                            Log / In Time
                        </td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">
                            Task Touch Time
                        </td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">
                            Status
                        </td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">
                            Close / Out Time
                        </td>
                        <td class="topandbottom text-center"
                            style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">
                            Duration (D:H:M:S)
                        </td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">
                            Remarks
                        </td>

                    </tr>
                        <?php

                        $duration_in_minutes = 0;
                        try {
                            if (!empty($_GET['qd'])) {
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
                        foreach ($sqlFormStatus as $row) {

                            $groupID = $row->group_id;
                            $subGroupID = $row->subgroup_id;
                            $userID = $row->user_id;
                            $form_status = $row->action;
                            $comments = $row->comments;
                            $isapproved = $row->isapproved;
                            $userName = $row->name;
                            $models[$i]['group_id'] = $groupID;
                            $models[$i]['duration_in_minutes'] = "";


                            if ($prevgID == $userID) {
                                $models[$i]['user_id'] = '';
                                $models[$i]['user_name'] = '';
                            } else {
                                $models[$i]['user_id'] = $userID;
                                $models[$i]['user_name'] = $userName;
                            }

                            if ($i == 0) {
                                $models[$i]['isapproved'] = 1;
                                $models[$i]['in_time'] = $row->time;
                                $models[$i]['work_time'] = $row->time;
                                $models[$i]['out_time'] = $row->time;
                                $lastInTime = $row->time;
                                $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row->time));
                            } elseif ($prevgID != $userID) {
                                $models[$i]['in_time'] = $models[$i - 1]['out_time'];
                                $models[$i]['work_time'] = $row->time;
                                $models[$i]['out_time'] = 0;
                                $lastInTime = $models[$i - 1]['out_time'];
                            } elseif ($prevgID == $userID && $i > 0 && $isapproved == 0) {
                                $models[$i]['in_time'] = 0;
                                $models[$i]['work_time'] = $row->time;
                                $models[$i]['out_time'] = 0;
                            } elseif ($prevgID == $userID && $i > 0 && $isapproved == 1) {
                                $models[$i]['isapproved'] = $isapproved;
                                $models[$i]['in_time'] = 0;
                                $models[$i]['work_time'] = $row->time;
                                $models[$i]['out_time'] = $row->time;
                                $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row->time));
                            }

                            if (count($sqlFormStatus) == $i + 1) {
                                $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s'));
                                //echo date('Y-m-d H:i:s', $lastInTime).'---'.date('Y-m-d H:i:s');
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

                        /*
                        $queue_duration = "";

                        if(!empty($rowFormVal) && $rowFormVal['isapproved'] == 1){
                            $current_date = date("d-m-Y h:i:s A");
                            $last_date = strtotime($current_date);
                            $lastAccessDayHour = 0;
                            $lastAccessDayMinutes = 0;
                            $lastAccessDaySeconds = 0;

                            $todaysDayHour = 0;
                            $todaysDayMinutes = 0;
                            $todaysDaySeconds = 0;
                            $in_time = date($rowFormVal['in_time']);

                            $out_time =$rowFormVal['out_time'];
                            $total_time = $in_time - $out_time;

                            $officeDayTime = \Illuminate\Support\Facades\DB::selectOne("SELECT count(dates) dates FROM working_days where dates >= FROM_UNIXTIME(".$rowFormVal['in_time'].",'%Y-%m-%d') and dates <= FROM_UNIXTIME(".$rowFormVal['out_time'].",'%Y-%m-%d')");
                            $working_hours = \Illuminate\Support\Facades\DB::selectOne('SELECT * FROM working_hours');
                            //pr($working_hours);
                            $firstAccessDate = (!empty($rowFormVal['in_time'])) ? Carbon::createFromTimestamp($rowFormVal['in_time'])->format('Y-m-d')  : Carbon::createFromTimestamp($rowFormVal['in_time'])->format('Y-m-d');
                            $firstAccessTime = (!empty($rowFormVal['in_time'])) ? Carbon::createFromTimestamp($rowFormVal['in_time'])->format('H:i:s')  : Carbon::createFromTimestamp($rowFormVal['in_time'])->format('H:i:s');
                            $firstAccessDateTime = (!empty($rowFormVal['in_time'])) ? Carbon::createFromTimestamp($rowFormVal['in_time'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($rowFormVal['in_time'])->format('Y-m-d H:i:s');
                            //echo $firstAccessDate.'--'.$firstAccessTime.'--'.$firstAccessDateTime.'<br/>';

                            $lastAccessDate = (!empty($rowFormVal['out_time'])) ? Carbon::createFromTimestamp($rowFormVal['out_time'])->format('Y-m-d')  : Carbon::createFromTimestamp($last_date)->format('Y-m-d');
                            $lastAccessTime = (!empty($rowFormVal['out_time'])) ? Carbon::createFromTimestamp($rowFormVal['out_time'])->format('H:i:s')  : Carbon::createFromTimestamp($last_date)->format('H:i:s');
                            $lastAccessDateTime = (!empty($rowFormVal['out_time'])) ? Carbon::createFromTimestamp($rowFormVal['out_time'])->format('Y-m-d H:i:s')  : Carbon::createFromTimestamp($last_date)->format('Y-m-d H:i:s');
                            //echo $lastAccessDate.'--'.$lastAccessTime.'--'.$lastAccessDateTime.'<br/>';
                            $totalWorkingHoursOnThisReq = (!empty($officeDayTime->dates)) ? $officeDayTime->dates * 8 : 0;
                            //echo $totalWorkingHoursOnThisReq;
                            $firstAccessDateForCalc = str_replace('-', '', $firstAccessDate);
                            $firstAccessDateTimeNumb = str_replace("-", "", str_replace(" ", "", str_replace(":", "", $firstAccessDateTime)));

                            $lastAccessDateForCalc = str_replace('-', '', $lastAccessDate);
                            $lastAccessDateTimeNumb = str_replace("-", "", str_replace(" ", "", str_replace(":", "", $lastAccessDateTime)));

                            $firstAccessDateForCalcMin = $firstAccessDateForCalc.$working_hours->office_from;
                            $firstAccessDateForCalcMax = $firstAccessDateForCalc.$working_hours->office_to;

                            $lastAccessDateForCalcMin = $lastAccessDateForCalc.$working_hours->office_from;
                            $lastAccessDateForCalcMax = $lastAccessDateForCalc.$working_hours->office_to;

                            echo $firstAccessDateTimeNumb.'--'.$lastAccessDateTimeNumb;

                            date_default_timezone_set('Asia/Dhaka');
                            $todaysDateCalc = date("Ymd");
                            $todaysDateTimeCalc = date("YmdHis");
                            $todaysDateCalcMin = $todaysDateCalc.$working_hours->office_from;

                            $todaysDateCalcMax = $todaysDateCalc.$working_hours->office_to;

                            $todaysDateTime = date('Y-m-d H:i:s');
                            $todaysDate = date('Y-m-d');

                            if (($lastAccessDateTimeNumb >= $lastAccessDateForCalcMin && $lastAccessDateTimeNumb <= $lastAccessDateForCalcMax)) {
                                $lastAccessDateTimeObj = new DateTime($lastAccessDateTime);
                                if ($lastAccessDate != $todaysDate && $out_time==0) {
                                    $lastAccessDateLastObj = new DateTime($lastAccessDate.'10:00:00');
                                } else {

                                    if ($firstAccessDate==$lastAccessDate) {
                                        $lastAccessDateLastObj = new DateTime($lastAccessDateTime);

                                    } else {
                                        $lastAccessDateLastObj = new DateTime($todaysDate.'10:00:00');
                                    }
                                }

                                $interval = $lastAccessDateTimeObj->diff($lastAccessDateLastObj);

                                $lastAccessDayHour = $interval->format('%h');
                                $lastAccessDayMinutes = $interval->format('%i');
                                $lastAccessDaySeconds = $interval->format('%s');
                            }


                            $totalHoursOnThisQueue = $lastAccessDayHour + $totalWorkingHoursOnThisReq + $todaysDayHour;
                            $totalMinutesOnThisQueue = $lastAccessDayMinutes + $todaysDayMinutes;
                            $totalSecondsOnThisQueue = $lastAccessDaySeconds + $todaysDaySeconds;

                            $queueDurationInMinutes = ($totalHoursOnThisQueue * 60) + $totalMinutesOnThisQueue;

                            $totalHoursOnThisQueue = sprintf("%02d", $totalHoursOnThisQueue);
                            $totalMinutesOnThisQueue = sprintf("%02d", $totalMinutesOnThisQueue);
                            $totalSecondsOnThisQueue = sprintf("%02d", $totalSecondsOnThisQueue);

                            $queue_duration = $totalHoursOnThisQueue.':'.$totalMinutesOnThisQueue;

                        }
                        */
                        ?>

                    <tr>
                            <?php /*if($rowFormVal['user_name'] != "") {*/
                            ?><!--
                            <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php /*echo ''; */
                                                                                                                                                                                   ?></td>
                            <?php /*}else{*/
                                                                                                                                                                                                                     ?>
                            <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">&nbsp;</td>
                            --><?php /*}*/
                                                                                                                                                                                      ?>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['user_name']))
                                {{ $rowFormVal['user_name'] }}
                            @endif</td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if (!empty($rowFormVal['in_time']) > 0) echo date("d.m.Y ## h:i a", $rowFormVal['in_time']); ?></td>
                        <td class="topandbottom"
                            style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if (!empty($rowFormVal['work_time']) > 0) echo date("d.m.Y ## h:i a", $rowFormVal['work_time']); ?></td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['form_status']; ?></td>
                        <td class="topandbottom"
                            style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if (!empty($rowFormVal['out_time']) > 0) echo date("d.m.Y ## h:i a", $rowFormVal['out_time']); ?></td>
                        <td class=""
                            style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px; text-align:center"><?php echo $rowFormVal['duration_in_minutes'] ?></td>
                        <td class="topandbottom" colspan="2"
                            style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['comments']; ?>
                            &nbsp;
                        </td>
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

                @ENDIF
            </div>
        </fieldset>


        <div class="clearfix"></div>
        @IF(!empty($_GET['qd']))
            @IF(((Auth::user()->hasRole(['superadmin', 'admin'])) || (Auth::user()->can(['supportExecutive']) )) && $editPermission == true )
                <?php $_GET['st'] = date('Y-m-d H:i:s'); ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 no-padding-margin-l">
                    {!! Form::open(['method'=>'post','class'=>'row here ', 'action' => ['SupportsController@workingOnHandler'] , 'enctype' => 'multipart/form-data']); !!}
                    {!! Form::token(); !!}
                    {{ Form::hidden('reference_number',encrypt($dataForView['reference_number'])) }}
                    {{ Form::hidden('request_from','wform') }}
                    {{ Form::hidden('qd',$_GET['qd']) }}
                    {{ Form::hidden('st',$_GET['st']) }}


                    <?php $searchedParam = '?' . (!empty($_GET)) ? '?' . http_build_query($_GET) : ""; ?>
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
                                <div class="error-message">{{ $errors->first('comments') }}</div>
                            @ENDIF
                        {{-- </div> --}}
                        @IF(!empty($dataForView['is_api']) && ($dataForView['api_status'] == 0) && (Auth::user()->user_unit['subgroup_info_id'] == 16) && ($dataForView['card_status'] == 'C' || $dataForView['card_status'] == 'I' || $dataForView['card_status'] == 'S' || $dataForView['card_status'] == 'SB'))
                            @if($dataForView['unit_id'] == 1)
                                <div class="input-group form-group mr-1">
                                    {{ Form::select('memo', [null=>'Memo List'] +  UNSERIALIZE(MEMOLIST),(!empty($dataForView['memo'])) ? $dataForView['memo'] : "", ['class'=>'form-control memo-list' ]) }}
                                    <input type="text" name="memo_other" class="form-control memo-other"
                                           placeholder="Other Memo" value="{{old('memo_other')}}"
                                           style="display: none;">
                                </div>
                            @elseif($dataForView['unit_id'] == 2)
                                <div class="input-group form-group mr-1">
                                    <div class="form-control" disabled> {{$dataForView['memo']}}</div>
                                </div>
                            @endif
                        @ENDIF
                        @php
                            $isApiPush = isApiPush($dataForView['issue_id'], $dataForView['subgroup_id'], 2);
                        @endphp
                        @if(($isApiPush == 1) && ($dataForView['product_type'] == 1) && ($dataForView['api_status'] == 0)
                        && ($dataForView['card_status'] == 'C' || $dataForView['card_status'] == 'I' ||
                        $dataForView['card_status'] == 'S' || $dataForView['card_status'] == 'SB'))
                            @if($dataForView['unit_id'] == 1)
                                <div class="input-group form-group mr-1">
                                    {{ Form::select('memo', [null=>'Memo List'] + UNSERIALIZE(MEMOLIST),(!empty($dataForView['memo'])) ? $dataForView['memo'] : "", ['class'=>'form-control memo-list' ]) }}
                                    <input type="text" name="memo_other" class="form-control memo-other"
                                           placeholder="Other Memo" value="{{old('memo_other')}}"
                                           style="display: none;">
                                </div>
                            @elseif($dataForView['unit_id'] == 2 && !empty($dataForView['memo']))
                                <div class="input-group form-group mr-1">
                                    <div class="form-control" disabled> {{ $dataForView['memo'] }}</div>
                                </div>
                            @endif
                        @endif
                        @IF(!empty($dataForView['in_date_time']))
                            @IF($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 0)
                                <button type="button" class="btn btn-success sendBackSmsBtn form-group mr-1"
                                        data-comment-id="{{ encrypt($dataForView['in_date_time']['id'])}}"
                                        data-reference-no="{{ encrypt($dataForView['reference_number'])}}"
                                        data-mobile-no="{{ encrypt($dataForView['mobile_number'])}}"
                                        data-email-addr="{{ encrypt($dataForView['email_address'])}}"
                                        data-issue-name="{{ $dataForView['issue_name'] }}">Sendback SMS?
                                </button>
                            @ELSEIF($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 1)
                                <button type="button" class="btn btn-success disabled form-group mr-1">Sendback SMS?
                                </button>
                            @ENDIF
                        @ENDIF

                    @ENDIF

                    @inject('last_step','App\Services\WorkFlowService')
                    @php $last_person= $last_step->workflowLastStep($dataForView['reference_number']); @endphp

                    @IF(empty($dataForView['access_by']))
                        <?php
                        $getUrl = url('/Supports/assign/' . encrypt($dataForView['reference_number']));
                        if (!empty($_GET)) {
                            $getUrl .= '?' . (!empty($_GET)) ? '?' . http_build_query($_GET) : "";
                        }
                        ?>
                        @if(in_array($dataForView['unit_id'],userUnits())||in_array($dataForView['unit_id'],userUnits()))

                            <a href="{{$getUrl}}" class="btn btn-success form-group mr-1 assign" onclick="overlay('show');">Assign</a>
                        @endif
                    @ELSE
                        @inject('is_regular_flow','App\Services\WorkFlowService')

                        @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::REGULAR)

                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)

                                {{--($dataForView['unit_id'] == 2 || $dataForView['unit_id'] == 1  )) is_priority()==1--}}
                                @if(is_priority()==1 && $dataForView['unit_id'] == 1)
                                @else
                                    <button type="submit" class="btn btn-primary form-group mr-1" value="sendBack"
                                            onclick="overlay('show');" name="submit">Send Back To
                                    </button>

                                        <?php
                                        $requiredSelect = "";
                                        if ($errors->has('unit_id')) {
                                            $requiredSelect = "red-border-2px";
                                        }
                                        //echo '<pre>';
                                        //print_r($allmakers);
                                        ?>
                                    @IF(!empty($dataForView['auto_unit_id']))
                                        {{ Form::hidden('unit_id',$dataForView['auto_unit_id'] ) }}
                                    @ELSE
                                        @inject('groupUser','App\Services\UtilService')

                                        {{--{{ Form::select('unit_id', [null=>'Please Select'] +  $allUnitData,(!empty($dataForView['unit_id'])) ? $dataForView['unit_id'] : "", ['class'=>'form-control '.$requiredSelect ]) }}--}}
                                        <div class="col-lg-3 form-group row mr-1">
                                            <select class="form-control col-lg-12" name="group_id">
                                                <option value="">Please Select</option>
                                                @foreach($allmakers as $allmaker)
                                                        <?php if ($allmaker->subgroup_id > 0){ ?>}
                                                    <option value="{{ $allmaker->group_id.','.$allmaker->subgroup_id }}"
                                                            @if($groupUser->groupUser($allmaker->group_id)) disabled
                                                    @else
                                                        @endif>{{ $allmaker->name }} [maker]
                                                    </option>
                                                    <?php } ?>
                                                @endforeach
                                            </select>
                                            @IF($errors->has('group_id'))
                                                <div class="error-message">Please Select Group</div>
                                            @ENDIF
                                        </div>
                                    @ENDIF
                                    @IF(is_priority() == 1 )
                                        @inject('subflow','App\Services\WorkFlowService')
                                        <?php
                                        $subflowLists = $subflow->subFlowList($dataForView['issue_id']);
                                        $requiredSelectSubflow = "";
                                        if ($errors->has('subflow_type_group_id')) {
                                            $requiredSelectSubflow = "red-border-2px";
                                        }
                                        ?>
                                    @ENDIF
                                @endif
                            @ENDIF

                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)

                                @if($last_person==false)
                                    @inject('subflowExt','App\Services\WorkFlowService')
                                    @php $subflowExists = $subflowExt->subFlowList($dataForView['issue_id']); @endphp
                                    @if(!empty($subflowExists))
                                        @if($dataForView['unit_id'] == 1)
                                            <button type="submit" class="btn btn-success form-group mr-1"
                                                    onclick="overlay('show');"
                                                    value="approved" name="submit" style="margin-right: 40px;">Approve
                                            </button>
                                        @elseif(is_priority() == 1 && $dataForView['unit_id'] == 2 )
                                            <button type="submit" class="btn btn-success form-group mr-1"
                                                    onclick="overlay('show');"
                                                    value="approved" name="submit" style="margin-right: 40px;">Approve
                                            </button>
                                        @elseif(is_priority() == 0)
                                            <button type="submit" class="btn btn-success form-group mr-1"
                                                    onclick="overlay('show');"
                                                    value="approved" name="submit" style="margin-right: 40px;">Approve
                                            </button>
                                        @endif
                                    @else
                                        <button type="submit" class="btn btn-success form-group mr-1"
                                                onclick="overlay('show');"
                                                value="approved" name="submit" style="margin-right: 40px;">Approve
                                        </button>
                                    @endif
                                @endif

                            @ENDIF


                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                <!-- <button type="submit" class="btn btn-danger" value="reject" name="submit">Reject</button> -->
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                @if($hold==1)
                                    <button type="submit" class="btn btn-warning form-group mr-1"
                                            onclick="overlay('show');"
                                            value="hold" name="submit" style="margin-right: 40px;">Hold
                                    </button>
                                @endif
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                @php
                                    $isApiPush = isApiPush($dataForView['issue_id'], $dataForView['subgroup_id'], $dataForView['unit_id']);
                                @endphp

                                <div class="row mt-3">
                                    @php
                                        $subgroupId = Auth::user()->user_unit->subgroup_info_id ?? null;
                                        $unitIds = explode(',', Auth::user()->user_unit->unit_id ?? '');
                                    @endphp

                                    @if($dataForView['issue_id'] == 1450)
                                        <div class="col-lg-3">
                                        <div class="input-group">
                                            {!!
                                                Form::select('bpid_type', [
                                                    'IN137' => 'IN137',
                                                    'GI137' => 'GI137',
                                                    'LI137' => 'LI137',
                                                    'CB137' => 'CB137',
                                                    'IC137' => 'IC137',
                                                    'MF137' => 'MF137',
                                                    'FI137' => 'FI137',
                                                    'PF137' => 'PF137',
                                                    'OT137' => 'OT137',
                                                ], $bpid_type ?? old('bpid_type'), [
                                                    'class' => 'form-control',
                                                    'style' => 'max-width: 90px; flex: 0 0 auto;',
                                                    'disabled' => !($subgroupId == 562 && in_array('1', $unitIds)),
                                                ])
                                            !!}

                                            {!!
                                                Form::textarea('bpid', $bpid ?? '', [
                                                    'rows' => 2,
                                                    'class' => 'form-control bpid-input',
                                                    'autocomplete' => 'off',
                                                    'placeholder' => 'BP ID',
                                                    'style' => 'resize: vertical;',
                                                    'disabled' => !($subgroupId == 562 && in_array('1', $unitIds)),
                                                ])
                                            !!}
                                        </div>
                                        @error('bpid')
                                            <div class="error-message text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                        <div class="col-lg-1">
                                            <button class="btn btn-info btn-sm" type="button" id="bpidPrintBtn">BPID Print</button>
                                        </div>
                                    @endif

                                    <div class="col-lg-2 text-center">
                                        <button type="submit" class="btn btn-danger close-btn form-group mr-1 ml-2"
                                                onclick="overlay('show');" value="close" name="submit" style="margin-right: 40px;">Close
                                        </button>
                                    </div>

                                    <div class="col-lg-3">
                                        <a class="form-control is_justified form-group mr-1">
                                            <b style="color:red">Send Close Notification?&nbsp;&nbsp;</b>
                                            {{--<label class="form-check-label">--}}
                                            <input type="checkbox" name="closenotification" value="1" checked/>
                                            {{--</label>--}}
                                        </a>
                                    </div>
                                </div>

                            @ENDIF

                        @endif
                        @inject('is_regular_flow','App\Services\WorkFlowService')
                        @if($is_regular_flow->getFlowType($dataForView['main_id'])==\App\Enum\FlowEnum::FORWARD)
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)

                                @if($dataForView['unit_id'] == 1 && is_priority()==1)

                                @else
                                    <button type="submit" class="btn btn-warning form-group mr-1"
                                            value="sendBackRegular" onclick="overlay('show');" name="submit" style="margin-right: 40px;">Back To
                                        Source
                                    </button>
                                    {{ Form::hidden('group_id_reqular', $dataForView['comment'][0]['group_id']) }}
                                    {{ Form::hidden('subgroup_id', $dataForView['comment'][0]['subgroup_id']) }}
                                @endif
                                    <?php
                                    $requiredSelect = "";
                                    if ($errors->has('unit_id')) {
                                        $requiredSelect = "red-border-2px";
                                    }
                                    ?>

                                @if(($dataForView['unit_id'] == 1 || $dataForView['unit_id'] == 2) && is_priority()==1)
                                <button type="button" class="btn btn-success fwdToSrc form-group me-1"
                                        value="forwardRegular"
                                        name="forwardtosrc" style="margin-right: 40px;">
                                    Forward To Source
                                </button>
                                @endif

                                <button type="submit" class="btn btn-primary form-group mr-1" value="forward"
                                        onclick="overlay('show');" name="forward" style="margin-right: 40px;">Forward To
                                </button>
                                {{-- <div class="col-lg-3 form-group row mr-1"> --}}
                                    <select class="form-control col-lg-12" name="group_id">
                                        <option value="">Please Select</option>
                                        @inject('groups','App\Services\WorkFlowService')
                                        @inject('groupUser','App\Services\UtilService')
                                        @foreach($groups->getAllGroupList() as $group)
                                            <option value="{{ $group->id }}"
                                                    @if($groupUser->groupUser($group->id)) disabled @else @endif>{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    @IF($errors->has('group_id'))
                                        <div class="error-message">Please Select Group</div>
                                    @ENDIF
                                {{-- </div> --}}
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                <!-- <button type="submit" class="btn btn-danger" value="reject" name="submit">Reject</button> -->
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                <button type="submit" class="btn btn-warning form-group mr-1" value="hold"
                                        onclick="overlay('show');" name="submit" style="margin-right: 40px;">Hold
                                </button>
                            @ENDIF
                            @IF($dataForView['form_status'] != -1 && $dataForView['form_status'] != 11)
                                <button type="submit" class="btn btn-info close-btn form-group mr-1" value="close"
                                        onclick="overlay('show');" name="submit" style="margin-right: 40px;">Close
                                </button>
                                <!-- <button type="submit" class="btn btn-default" value="print" name="submit">Print</button> -->
                                <a class="form-control is_justified form-group mr-1">
                                    <b style="color:red">Send Close Notification?&nbsp;&nbsp;</b>
                                    {{--<label class="form-check-label">--}}
                                    <input type="checkbox" name="closenotification" value="1" checked/>
                                    {{--</label>--}}
                                </a>

                            @ENDIF
                            {{--@if(Auth::user()->user_unit->subgroup_info_idsubgroup_info_id == 710)
                                @if($countIssueLastMonth > 0)
                                    <a class="form-control is_justified form-group mr-1">
                                        <p>This item changed in the last 30 days<span class="badge bg-success p-2 ml-2"
                                                                                      id="issue_change_in_30_days"
                                                                                      style="font-size:16px; color:black;"> {{$countIssueLastMonth.' time'}}</span>
                                        </p>
                                    </a>
                                @endif
                            @endif--}}
                        @endif
                    @ENDIF
                    {!! Form::close(); !!}

                    {{-- Hidden Form for POST in New Tab --}}
                    <form id="bpidPrintForm" action="{{ route('support.printBpIdTicketDetails') }}" method="POST" target="_blank" style="display:none;">
                        @csrf
                        <input type="hidden" name="issue_id" value="{{ $dataForView['issue_id'] }}">
                        <input type="hidden" name="other_data" value="{{ json_encode($dataForView['w_form_type']) }}">
                    </form>

                </div>
            @ENDIF
            <div class="clearfix">&nbsp;</div>
        @ENDIF

        <div class="modal fade" id="InquiryModal" tabindex="-1" role="dialog" data-backdrop="static"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="IRISApiModalLabel">IRIS Inquiry Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4" id="InquiryData">
                        <!-- Modal content goes here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Issue Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('update-issue-wform/'.$dataForView['reference_number']) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="issue_id" value="{{ $dataForView['main_id'] }}" />
                        <div class="modal-body">
                            <!-- Display errors if they exist -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <!-- Issue extra section -->
                            <div id="issue_extra" class="p-3 bg-info text-white">
                                <!-- Dynamic content loaded here -->
                            </div>
                            <!-- Issue checklist -->
                            <div id="issue_check_list">
                                @include('partials.edit_issue_check_list')
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            @if($issue_checklist_status)
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="modal fade" id="issueHistoryModal" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
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
                            $history_data = \Illuminate\Support\Facades\DB::table('w_form_type_histories')
                                ->select(
                                // 'w_form_type_histories.*',
                                    'w_form_type_histories.user_id',
                                    'w_form_type_histories.reference_number',
                                    'w_form_type_histories.created_at',
                                    'w_form_type_histories.extra_field',
                                    'w_form_type_histories.check_list',
                                    'users.name',
                                )
                                ->join('users', 'w_form_type_histories.user_id', '=', 'users.id')
                                ->where('w_form_type_histories.reference_number', $dataForView['reference_number'])
                                ->orderBy('w_form_type_histories.created_at', 'DESC')
                                ->get();
                            if ($dataForView['issue_id'] == 1103 || $dataForView['issue_id'] == 1105) {
                                ?>
                            @include('partials.quota_history_modal')
                                <?php
                            } else {

                            if (!empty($history_data)) {
                            foreach ($history_data as $row){
                                ?>
                            <tr>
                                <th class="vcenter text-left">
                                    <table class="table table-condensed table-bordered no-padding-margin-b">
                                            <?php
                                            //pr($row);
                                            $extra_fields = "";
                                            $i = 1;
                                            $extra_fields = (array)json_decode($row->extra_field);
                                            $count = count($extra_fields);
                                        if (!empty($extra_fields)){

                                        foreach ($extra_fields as $key => $r){
                    
                                            if(is_array($r) || is_object($r)) {
                                            
                                                                foreach ($r as $key1 => $value){
                                            
                                            if(is_array($value) || is_object($value)) {
                                                continue;
                                            }

                                        if ($i == 1) {
                                            ?>

                                        <tr>
                                            <?php } ?>
                                            <th class="vcenter text-left">{{ $key1 }}</th>
                                            <td class="vcenter text-left"> {{ (isset($value))? $value:"" }}</td>

                                            @if($i == 2)
                                        </tr>
                                            <?php $i = 0; ?>
                                        @elseif($count == 1)
                                            @if($i == 1)
                                                <th>&nbsp;</th>
                                                <td>&nbsp;</td>
                            </tr>
                            @else
                                </tr>
                            @endif
                            @endif

                                <?php $i++; $count--; ?>

                            <?php } }
                            }
                            } ?>
                        </table>
                        </th>

                        <th class="vcenter text-left">
                            <table class="table table-condensed table-bordered no-padding-margin-b">
                                    <?php
                                    //pr($row);
                                    $check_lists = "";
                                    $check_lists = (array)json_decode($row->check_list);
                                if (!empty($check_lists)){
                                foreach ($check_lists as $key => $r){
                                foreach ($r as $key1 => $value){

                                    ?>
                                <tr>
                                    <th class="vcenter text-left">{{ $key1 }}</th>
                                    <td class="vcenter text-left">{{ (!empty($value))? $value:"" }}</td>
                                </tr>
                                <?php }
                                }
                                } ?>
                            </table>
                        </th>

                        <th class="vcenter text-left">{{$row->name}}</th>
                        <th class="vcenter text-left">{{$row->created_at}}</th>
                        </tr>
                            <?php
                        }
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

        {{--IRIS API Response--}}
        <div class="modal fade" id="IRISApiModal" tabindex="-1" aria-labelledby="IRISApiModalLabel" aria-hidden="true"
             data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="IRISApiModalLabel">IRIS API Response</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body h5 text-center">
                        <div id="pResMgs" class="mb-2"></div>
                        <div id="cResMgs" class="mb-2"></div>
                        <div id="nResMgs" class="mb-2"></div>
                        <div id="mqResMgs"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <script src="{{ URL::asset('public/js/latest-v/jquery-3.7.1.min.js') }}"></script> --}}

        {{-- @endsection --}}

        {{-- @section('extrajssection') --}}

            @if(!empty($dataForView['in_date_time']))
                @IF($dataForView['in_date_time']['issendback'] == 1 && $dataForView['in_date_time']['sendbacksms'] == 0)
                    <script type="text/javascript">

                        $(".sendBackSmsBtn").on("click", function ($e) {
                            var comment_id = $(this).attr('data-comment-id');
                            var ref_no = $(this).attr('data-reference-no');
                            var mobile_no = $(this).attr('data-mobile-no');
                            var email_address = $(this).attr('data-email-addr');
                            var issue_name = $(this).attr('data-issue-name');

                            $.confirm({
                                title: 'Confirm',
                                content: 'Do you want to send SMS and Email for sendback?',
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    Yes: {
                                        text: 'YES',
                                        btnClass: 'btn-red',
                                        action: function () {
                                            $.ajax({
                                                type: "post",
                                                url: "{{url('/Supports/SendSendBackSMS')}}",
                                                data: {
                                                    _token: _token,
                                                    comment_id: comment_id,
                                                    ref_no: ref_no,
                                                    mobile_no: mobile_no,
                                                    issue_name: issue_name,
                                                    email_address: email_address
                                                },
                                                dataType: "json",
                                                beforeSend: function () {
                                                    overlay('show');
                                                },
                                                success: function (data) {
                                                    overlay('hide');
                                                    if (data.success) {
                                                        customAlert('Success', 'SMS / EMAIL have been sent successfully', 'green');
                                                        $(".sendBackSmsBtn").prop('disabled', true);

                                                        $(".sendBackSmsBtn").removeAttr('data-comment-id');
                                                        $(".sendBackSmsBtn").removeAttr('data-reference-no');
                                                        $(".sendBackSmsBtn").removeAttr('data-mobile-no');
                                                        $(".sendBackSmsBtn").removeAttr('data-email-addr');
                                                        $(".sendBackSmsBtn").removeAttr('data-issue-name');

                                                    } else {
                                                        customAlert('Warning', 'Failed to sent SMS. Please Contact with Administrator', 'red');
                                                    }
                                                },
                                                error: function (data) {
                                                    overlay('hide');
                                                    customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
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
            @endif

            @if($errors->has('comments'))
                <script
                    type="text/javascript"> customAlert('Please Type Comment', 'You need to write comment', 'red'); </script>
            @elseif($errors->has('file_name.*'))
            @elseif($errors->has('unit_id'))
            @elseif($errors->has('group_id'))
            @elseif($errors->has('subflow_type_group_id'))
            @elseif($errors->any())
                <script type="text/javascript">
                    $('#issueModal').modal({
                        backdrop: 'static',
                        show: true
                    });
                    var issue_id = "{{ (!empty($dataForView['issue_id'])) ? $dataForView["issue_id"] : 0 }}";
                    var reference_number = "{{ (!empty($dataForView['reference_number'])) ? $dataForView["reference_number"] : 0 }}";
                    var form_type = "wform";
                    $.post('{{ url('edit-issue-extra-form') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        reference_number: reference_number,
                        form_type: form_type
                    }, function (data) {
                        $('#issue_extra').html(data);
                    });
                    $.post('{{ url('edit-issue-check-list') }}', {
                        _token: '{{ csrf_token() }}',
                        issue_id: issue_id,
                        reference_number: reference_number,
                        form_type: form_type
                    }, function (data) {
                        $('#issue_check_list').html(data);
                    });
                </script>
            @endif

            <script>
                $(document).ready(function () {
                    var ref_no = $('.cifUpdateBtn').attr('data-reference-no');
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/CIFModification/Api-Update-check/') }}",
                        data: {
                            _token: _token,
                            ref_no: ref_no,
                        },
                        success: function (data) {
                            if (data.status === 1) {
                                if (data.ids) {
                                    $(".cifUpdateBtn").prop('disabled', false);
                                    $(".cifUpdateBtn").attr('data-failed-api-attr', data.ids);
                                    $(".cifUpdateBtn").html('Retry API Update');
                                }
                            } else if (data.status === 2) {
                                $(".cifUpdateBtn").prop('disabled', true);
                            } else {
                                $(".cifUpdateBtn").prop('disabled', false);
                                $(".cifUpdateBtn").attr('data-failed-api-attr', '');
                                $(".cifUpdateBtn").html('API Update &amp; Close');
                            }
                        },
                        error: function (data) {
                        }
                    });
                });

                $(document).ready(function () {
                    var ref_no = $('.IRISUpdateBtn').attr('data-reference-no');
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/iris/Api-Update-check/') }}",
                        data: {
                            _token: _token,
                            ref_no: ref_no,
                        },
                        success: function (data) {
                            if (data.status === 1) {
                                $(".IRISUpdateBtn").prop('disabled', false);
                                $(".IRISUpdateBtn").html('Retry IRIS API Update');
                            }else if(data.status === 2){
                                $(".IRISUpdateBtn").prop('disabled', true);
                                $(".IRISUpdateBtn").html('Retry IRIS API Update');
                            }else {
                                $(".IRISUpdateBtn").prop('disabled', false);
                            }
                        },

                        error: function (data) {
                        }
                    });
                });


                $(".apiUpdateBtn").on("click", function ($e) {
                    var ref_no = $(this).attr('data-reference-no');
                    var req_from = $(this).attr('data-request-from');
                    var req_comments = $('.comments-input').val();
                    if (!req_comments) {
                        customAlert('Please Type Comment', 'You need to write comment', 'red');
                    } else {
                        $.confirm({
                            title: 'Update into Card Pro?',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                Yes: {
                                    text: 'YES',
                                    btnClass: 'btn-red',
                                    action: function () {
                                        $.ajax({
                                            type: "post",
                                            url: "{{url('/Supports/ApiUpdate/')}}",
                                            data: {
                                                _token: _token,
                                                ref_no: ref_no,
                                                req_from: req_from
                                            },
                                            dataType: "json",
                                            beforeSend: function () {
                                                overlay('show');
                                            },
                                            success: function (data) {

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
                                            error: function (data) {
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

                $(".cifUpdateBtn").on("click", function ($e) {
                    var ref_no = $(this).attr('data-reference-no');
                    var req_from = $(this).attr('data-request-from');
                    var failed_api = $(this).attr('data-failed-api-attr');
                    var cif_no = "{{ (!empty($dataForView["SIF_Number"])) ? encrypt($dataForView["SIF_Number"]) : '' }}";
                    var account_number = "{{ (!empty($dataForView["account_number"])) ? encrypt($dataForView["account_number"]) : '' }}";
                    var req_comments = $('.comments-input').val();
                    if (!req_comments) {
                        customAlert('Please Type Comment', 'You need to write comment', 'red');
                    } else if (!ref_no) {
                        customAlert('Reference number not found', 'Please refresh this page', 'red');
                    } else if (!cif_no) {
                        customAlert('Customer number not found', 'Please close the ticket and try with a valid CIF ID.', 'red');
                    } else if (!account_number) {
                        customAlert('Account number not found', 'Please close the ticket and try with a valid Account No.', 'red');
                    } else {
                        $.confirm({
                            title: 'Update into System?',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                Yes: {
                                    text: 'YES',
                                    btnClass: 'btn-red',
                                    action: function () {
                                        $.ajax({
                                            type: "post",
                                            url: "{{url('/CIFModification/ApiUpdate/')}}",
                                            data: {
                                                _token: _token,
                                                ref_no: ref_no,
                                                cif_no: cif_no,
                                                account_number: account_number,
                                                req_from: req_from,
                                                failed_api: failed_api
                                            },
                                            dataType: "json",
                                            beforeSend: function () {
                                                overlay('show');
                                            },
                                            success: function (data) {
                                                if (data.status === 1) {
                                                    $('.close-btn').trigger('click');
                                                    $(".cifUpdateBtn").prop('disabled', true);
                                                    $(".cifUpdateBtn").removeAttr('data-reference-no');
                                                } else if (data.status === 2) {
                                                    overlay('hide');
                                                    $(".cifUpdateBtn").prop('disabled', true);
                                                    customAlert('Error', data.msg, 'red');
                                                } else {
                                                    overlay('hide');
                                                    if (data.failed_api) {
                                                        $(".cifUpdateBtn").attr('data-failed-api-attr', data.failed_api);
                                                        $(".cifUpdateBtn").html('Retry API Update');
                                                        customAlert('Error', data.msg, 'red');
                                                    } else {
                                                        customAlert('Error', 'Failed to update api. Please Contact with Administrator', 'red');
                                                    }
                                                }
                                            },
                                            error: function (data) {
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

                // For IRIS Api [by Zihad]

                $(".IRISUpdateBtn").on("click", function ($e) {
                    var ref_no = $(this).attr('data-reference-no');
                    var req_from = $(this).attr('data-request-from');
                    var cif_no = "{{ (!empty($dataForView["SIF_Number"])) ? encrypt($dataForView["SIF_Number"]) : '' }}";
                    var account_number = "{{ (!empty($dataForView["account_number"])) ? encrypt($dataForView["account_number"]) : '' }}";
                    var req_comments = $('.comments-input').val();
                    if (!req_comments) {
                        customAlert('Please Type Comment', 'You need to write comment', 'red');
                    } else if (!ref_no) {
                        customAlert('Reference number not found', 'Please refresh this page', 'red');
                        /*   } else if (!cif_no) {
                               customAlert('Customer number not found', 'Please close the ticket and try with a valid CIF ID.', 'red');*/
                    } else if (!account_number) {
                        customAlert('Account number not found', 'Please close the ticket and try with a valid Account No.', 'red');
                    } else {
                        $.confirm({
                            title: 'Update into System?',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                Yes: {
                                    text: 'YES',
                                    btnClass: 'btn-red',
                                    action: function () {
                                        $.ajax({
                                            type: "post",
                                            url: "{{url('/iris/apiUpdate/')}}",
                                            data: {
                                                _token: _token,
                                                ref_no: ref_no,
                                                cif_no: cif_no,
                                                account_number: account_number,
                                                req_from: req_from,
                                            },
                                            dataType: "json",
                                            beforeSend: function () {
                                                overlay('show');
                                            },
                                            success: function (data) {
                                                overlay('hide');
                                                if (data.successStatus === 1) {
                                                    $('.close-btn').trigger('click');
                                                    $(".IRISUpdateBtn").prop('disabled', true);
                                                    $(".IRISUpdateBtn").removeAttr('data-reference-no');
                                                } else {
                                                    console.log(data.failedRetryCount);
                                                    $('#IRISApiModal').modal('show');
                                                    if(data.failedRetryCount < 2){
                                                        $(".IRISUpdateBtn").html('Retry IRIS API Update');
                                                    }else{
                                                        $(".IRISUpdateBtn").prop('disabled', true);
                                                        $(".IRISUpdateBtn").removeAttr('data-reference-no');
                                                    }
                                                    $.each(data.resultArray, function (pKey, pValue) {
                                                        if (pKey == 'P') {
                                                            $('#pResMgs').html('<b>Passport: </b> ' + pValue.errorMessage);
                                                        }
                                                        if (pKey == 'CY') {
                                                            $('#cResMgs').html('<b>Travel Quota Current Year : </b> ' + pValue.errorMessage);
                                                        }
                                                        if (pKey == 'NY') {
                                                            $('#nResMgs').html('<b>Travel Quota Next Year : </b> ' + pValue.errorMessage);
                                                        }
                                                        if (pKey == 'MQ') {
                                                            $('#mqResMgs').html('<b>Medical Quota : </b> ' + pValue.errorMessage);
                                                        }
                                                    });
                                                }
                                            },
                                            error: function (data) {
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

                // $(".fwdToSrc").on("click", function ($e) {
                //     var ref_no = "{{ encrypt($dataForView['reference_number'])}}";
                //     var qd = "{{ (!empty($_GET['qd']))? $_GET['qd']:'' }}";
                //     var req_from = "complaint";
                //     var selectInput = "<select class='form-control otherSrc'  name='othersrc'><option value=''>Please Select</option></select>";
                //     $.ajax({
                //         type: "get",
                //         url: "{{url('/Supports/GetTouchSubGroups/')}}",
                //         dataType: "html",
                //         beforeSend: function () {
                //             overlay('show');
                //         },
                //         success: function (data) {
                //             overlay('hide');

                //             selectInput = "<select class='form-control otherSrc' name='othersrc'><option value=''>Please Select</option>" + data + "</select>";

                //             $.confirm({
                //                 title: 'Forward to other source?',
                //                 content: '' +
                //                     '<div class="form-group">' +
                //                     '<label>Select Source</label>' +
                //                     selectInput +
                //                     '<div class="error othersrcerr"></div>' +
                //                     '</div>' +
                //                     '<div class="form-group">' +
                //                     '<label>Comments</label>' +
                //                     '<input type="text" name="comments" placeholder="Please Write Comments" class="comments form-control" required />' +
                //                     '<div class="error commentserr"></div>' +
                //                     '</div>',
                //                 type: 'green',
                //                 typeAnimated: true,
                //                 buttons: {
                //                     Yes: {
                //                         text: 'YES',
                //                         btnClass: 'btn-red',
                //                         action: function () {
                //                             $(".othersrcerr").text('');
                //                             $(".commentserr").text('');

                //                             var subgroup_id = this.$content.find('.otherSrc').val();
                //                             var comments = this.$content.find('.comments').val();
                //                             if (!subgroup_id || !comments) {
                //                                 if (!subgroup_id) {
                //                                     $(".othersrcerr").text('Other Source is required');
                //                                 }
                //                                 if (!comments) {
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
                //                                     submit: 'forwardToSource',
                //                                     request_from: req_from,
                //                                     group_id: group_id,
                //                                     subgroup_id: subgroup_id,
                //                                     comments: comments
                //                                 },
                //                                 dataType: "json",
                //                                 beforeSend: function () {
                //                                     overlay('show');
                //                                 },
                //                                 success: function (data) {
                //                                     overlay('hide');
                //                                     if (data == 1) {
                //                                         redirectToUrl('Success', 'This Ticket have been forwarded successfully..', 'green', 'Supports/handler');
                //                                     } else if (data == 2) {
                //                                         customAlert('Warning', 'This Ticket is not available in your queue!!!. Please refresh this page', 'red');
                //                                     } else if (data == 3) {
                //                                         customAlert('Warning', 'Failed to forward to other source!! This Ticket is not Created by CI Customer', 'red');
                //                                     } else {
                //                                         customAlert('Warning', 'Failed to forward to other source. Please Contact with Administrator', 'red');
                //                                     }
                //                                 },
                //                                 error: function (data) {
                //                                     overlay('hide');
                //                                     customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
                //                                 }
                //                             });
                //                         }
                //                     },
                //                     No: {text: 'NO'}
                //                 }
                //             });
                //         },
                //         error: function (data) {
                //             overlay('hide');
                //             customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
                //         }
                //     });
                // });

                $(document).ready(function () {
                    // Event listener for Forward To Source button
                    $(document).on("click", ".fwdToSrc", function (e) {
                        console.log("jcn");
                        e.preventDefault();

                        // Variables
                        var ref_no = "{{ encrypt($dataForView['reference_number']) }}";
                        var qd = "{{ (!empty($_GET['qd'])) ? $_GET['qd'] : '' }}";
                        var req_from = "complaint";
                        var selectInput = "<select class='form-control otherSrc' name='othersrc'><option value=''>Please Select</option></select>";

                        // Fetch source options via AJAX
                        $.ajax({
                            type: "get",
                            url: "{{ url('/Supports/GetTouchSubGroups/') }}",
                            dataType: "html",
                            beforeSend: function () {
                                overlay('show'); // Optional: Show loading overlay
                            },
                            success: function (data) {
                                overlay('hide'); // Hide overlay
                                selectInput = "<select class='form-control otherSrc' name='othersrc'><option value=''>Please Select</option>" + data + "</select>";

                                // Confirmation modal using `$.confirm`
                                $.confirm({
                                    title: 'Forward to other source?',
                                    content: '' +
                                        '<div class="form-group">' +
                                        '<label>Select Source</label>' +
                                        selectInput +
                                        '<div class="error othersrcerr text-danger"></div>' +
                                        '</div>' +
                                        '<div class="form-group mt-3">' +
                                        '<label>Comments</label>' +
                                        '<input type="text" name="comments" placeholder="Please Write Comments" class="comments form-control" required />' +
                                        '<div class="error commentserr text-danger"></div>' +
                                        '</div>',
                                    type: 'green',
                                    buttons: {
                                        Yes: {
                                            text: 'YES',
                                            btnClass: 'btn-danger',
                                            action: function () {
                                                $(".othersrcerr").text('');
                                                $(".commentserr").text('');

                                                var subgroup_id = this.$content.find('.otherSrc').val();
                                                var comments = this.$content.find('.comments').val();

                                                // Validation
                                                if (!subgroup_id || !comments) {
                                                    if (!subgroup_id) {
                                                        $(".othersrcerr").text('Other Source is required');
                                                    }
                                                    if (!comments) {
                                                        $(".commentserr").text('Comments are required');
                                                    }
                                                    return false; // Prevent further actions
                                                }

                                                var group_id = $('option:selected', '.otherSrc').attr('group-id');

                                                // Forward request via AJAX
                                                $.ajax({
                                                    type: "post",
                                                    url: "{{ url('/Supports/WorkingOnHandler/') }}",
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
                                                        overlay('show'); // Optional: Show loading overlay
                                                    },
                                                    success: function (data) {
                                                        overlay('hide'); // Hide overlay

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
                                                    error: function (xhr) {
                                                        overlay('hide');
                                                        console.error("Forwarding Error:", xhr);
                                                        customAlert('Error', 'Something went wrong. Please contact the Administrator.', 'red');
                                                    }
                                                });
                                            }
                                        },
                                        No: {
                                            text: 'NO',
                                            btnClass: 'btn-secondary'
                                        }
                                    }
                                });
                            },
                            error: function (xhr) {
                                overlay('hide');
                                console.error("Error fetching subgroups:", xhr);
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


                $(".attachmentdel").on("click", function ($e) {
                    var attchid = $(this).attr('data-id');
                    var filename = $(this).attr('data-filename');
                    $.confirm({
                        title: 'Confirm',
                        content: 'Do you want to delete: ' + filename,
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            Yes: {
                                text: 'YES',
                                btnClass: 'btn-red',
                                action: function () {
                                    $.ajax({
                                        type: "post",
                                        url: "{{url('/Supports/DeleteAttachment')}}",
                                        data: {_token: _token, attchid: attchid},
                                        dataType: "json",
                                        beforeSend: function () {
                                            overlay('show');
                                        },
                                        success: function (data) {
                                            overlay('hide');
                                            if (data.success) {
                                                customAlert('Success', 'Attachment have been removed successfully', 'green');
                                                location.reload();
                                            } else {
                                                customAlert('Warning', 'Failed to remove attachment.', 'red');
                                            }
                                        },
                                        error: function (data) {
                                            overlay('hide');
                                            customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
                                        }
                                    });
                                }
                            },
                            No: {text: 'NO'}
                        }
                    });
                });

                var selectedMemoList = $(".memo-list :selected").val();
                if (selectedMemoList == "Other") {
                    $(".memo-other").show();
                }

                $(".memo-list").on("change", function (event) {
                    var selectedMemoList = $(this).val();
                    if (selectedMemoList == "Other") {
                        $(".memo-other").show();
                    } else {
                        $(".memo-other").hide();
                    }
                });

                $('#isInquiryApi').on('click', function () {
                    var issue_id = "{{ (!empty($dataForView['issue_id'])) ? $dataForView["issue_id"] : '' }}";
                    var acc_no = "{{ (!empty($dataForView["account_number"])) ? $dataForView["account_number"] : '' }}";
                    var ref_no = "{{ (!empty($dataForView["reference_number"])) ? $dataForView["reference_number"] : '' }}";
                    var cif_no = "{{ (!empty($dataForView["SIF_Number"])) ? $dataForView["SIF_Number"] : '' }}";
                    if (!issue_id) {
                        customAlert('Service Request not found', 'Please refresh this page', 'red');
                    } else if (!ref_no) {
                        customAlert('Reference number not found', 'Please refresh this page', 'red');
                    } else if (!cif_no) {
                        customAlert('Error', 'Customer number not found', 'red');
                    } else if (!acc_no) {
                        customAlert('Error', 'Account number not found', 'red');
                    } else {
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
                            //console.log(response);
                            overlay('hide');
                            if (response.status === 1) {
                                if ($.isEmptyObject(response.data)) {
                                    customAlert('Error', 'Inquiry data not found', 'red');
                                } else {
                                    $('#inquiryModal').modal('show');
                                    var helpers = '';
                                    if (response.data.lienenqury) {
                                        var lienresponse = response.data.lienenqury;
                                        delete response.data.lienenqury;
                                        if (lienresponse.length > 0) {
                                            for (var i = 0; i < lienresponse.length; i++) {
                                                $.each(lienresponse[i], function (key, value) {
                                                    helpers += '<div class="col-lg-6">' + key + '</div><div class="col-lg-6">' + value + '</div>';
                                                });
                                                helpers += '<div class="col-lg-12">&nbsp;</div>';
                                            }
                                        }
                                    }
                                    $.each(response.data, function (key, value) {
                                        helpers += '<div class="col-lg-6">' + key + '</div><div class="col-lg-6">' + value + '</div>';
                                    });
                                    document.getElementById('inquiryData').innerHTML = helpers;
                                }
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

            </script>

            <script>
                $(document).ready(function () {

                    $(".quota-navbar a").removeClass("quota-nav-selected");

                    $(".quota-navbar a").click(function () {
                        $(".quota-navbar a").removeClass("quota-nav-selected");
                        $(this).addClass("quota-nav-selected");
                        $(this).insertAfter(".please_select");
                        return false;
                    });

                    $("#both_year").on("click", function () {
                        $("#loading").addClass("loader-none");
                        $("#currentYear").removeClass("hidden");
                        $("#nextYear").removeClass("hidden");
                    });

                    $("#current_year").on("click", function () {
                        $("#currentYear").removeClass("hidden");
                        $("#nextYear").addClass("hidden");
                    });

                    $("#next_year").on("click", function () {
                        $("#nextYear").removeClass("hidden");
                        $("#currentYear").addClass("hidden");
                    });

                    $('#issueModal').on('show.bs.modal', function (event) {
                        var value = $(event.relatedTarget);
                        var issue_id = value.data('id');
                        var reference_number = value.data('reference');
                        var form_type = "wform";
                        // alert(issue_id)
                        $.post('{{ url('edit-issue-extra-form') }}', {
                            _token: '{{ csrf_token() }}',
                            issue_id: issue_id,
                            reference_number: reference_number,
                            form_type: form_type
                        }, function (data) {
                            // alert(data);
                            $('#issue_extra').html(data);

                        });
                        $.post('{{ url('edit-issue-check-list') }}', {
                            _token: '{{ csrf_token() }}',
                            issue_id: issue_id,
                            reference_number: reference_number,
                            form_type: form_type
                        }, function (data) {
                            //alert(data);
                            $('#issue_check_list').html(data);

                        });
                    });

                });

                {{--$('#InquiryIRISData').on('click', function (e) {--}}
                {{--    e.preventDefault();--}}
                {{--    let passport = "{{ $Pdata ?? '' }}";--}}
                {{--    let customerInfo = "{{ $CustomerInfo ?? '' }}";--}}
                {{--    let CQuota = "{{ $CYdata ?? ''}}";--}}
                {{--    let NQuota = "{{ $NYdata ?? ''}}";--}}
                {{--    let url = "{{ route('iris.inquiryData') }}";--}}

                {{--    $.ajax({--}}
                {{--        url: url,--}}
                {{--        type: 'POST',--}}
                {{--        data: {--}}
                {{--            passport: passport,--}}
                {{--            customerInfo: customerInfo,--}}
                {{--            CQuota: CQuota,--}}
                {{--            NQuota: NQuota,--}}
                {{--            _token: '{{ csrf_token() }}'--}}
                {{--        },--}}
                {{--        beforeSend: function () {--}}
                {{--            $('.loading').removeClass('hidden');--}}
                {{--        },--}}
                {{--        success: function (response) {--}}
                {{--            setTimeout(function () {--}}
                {{--                $('.loading').addClass('hidden');--}}
                {{--            }, 800);--}}

                {{--            $('#InquiryModal').modal('show');--}}
                {{--            $('#InquiryData').html(response.data);--}}

                {{--        },--}}
                {{--        error: function (xhr, status, error) {--}}
                {{--            setTimeout(function () {--}}
                {{--                $('.loading').addClass('hidden');--}}
                {{--            }, 800);--}}
                {{--            $('#InquiryModal').modal('show');--}}
                {{--            $('#InquiryData').html('Something went wrong!');--}}
                {{--        }--}}
                {{--    });--}}
                {{--});--}}




                //data toggle collapse change
                $(".colla").on("click",function(){
                    $(this).find("i").toggleClass("fa fa-plus");
                    $(this).find("i").toggleClass("fa fa-minus");
                });

                // $(document).on('input', '.bpid-input', function () {
                //     this.value = this.value.replace(/[^0-9]/g, '');
                // });

                $(document).on('click', '#bpidPrintBtn', function () {
                    // Submit in new tab
                    $('#bpidPrintForm').submit();
                });

            </script>
@endsection
