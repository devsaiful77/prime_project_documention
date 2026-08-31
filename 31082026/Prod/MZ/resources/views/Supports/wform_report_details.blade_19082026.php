@extends('layouts.admin')
@section('content')
@php //prd($dataForView)
$i = 1;$x = 1;$y = 1;$z = 1;
@endphp
<div class="curved-inner-pro" style="background-color: #DFF0D8; padding: 5px;">
	<div class="curved-inner-pro">
        <div class="curved-ctn">
            <h2 style="padding:5px">{{$title_for_layout}}</h2>
        </div>
    </div>
</div>
<br>
<div>
    <fieldset class="scheduler-border" style="background-color:#f1f1f1">
        <legend class="scheduler-border" style="font-family: Verdana,Geneva,sans-serif;color:#FF4500;background-color:#ffffff">Information</legend>
        <div class="table-responsive">
    <table class="table table-condensed table-bordered no-padding-margin-b">
        <tr>
            <th class="vcenter text-left">Ticket No.</th>
            <td class="vcenter text-left">{{ !empty($dataForView['reference_number']) ? $dataForView['reference_number'] : "" }}</td>
            <th class="vcenter text-left">Account No./Customer ID.</th>
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
            <th class="vcenter text-left">Customer Number</th>
            <td class="vcenter text-left">{{ !empty($dataForView['SIF_Number']) ? $dataForView['SIF_Number'] : "" }}</td>
            <th class="vcenter text-left">Product Type.</th>
            <td class="vcenter text-left">{{ !empty($dataForView['product_name']) ?  $dataForView['product_name'] : $dataForView['product_type'] }}</td>
        </tr>
        <tr>
            <th class="vcenter text-left">{{ !empty($dataForView['segment']) ? "Segment Code" : `&nbsp;` }}</th>
            <td class="vcenter text-left">{{ !empty($dataForView['segment']) ? $dataForView['segment'] : "" }}</td>
            @if($dataForView["product_type"] == 1)
                <th class="vcenter text-left">{{ ($dataForView['card_status']=='SB'?"":"Card Status") }}</th>
                <td class="vcenter text-left">{{ ($dataForView['card_status']=='SB'?"":$dataForView['card_status'])}}</td>
            @else
                <th class="vcenter text-left">&nbsp;</th>
                <td class="vcenter text-left">&nbsp;</td>
            @endif
            <th class="vcenter text-left">{{ !empty($dataForView['date_of_birth']) ? "Customer DOB" : `&nbsp;` }}</th>
            <td class="vcenter text-left">{{ !empty($dataForView['date_of_birth']) ? $dataForView['date_of_birth'] : "" }}</td>
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
        @if($dataForView["product_type"] == 1)
        <tr>
            <th class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? "Masked Card Number" : "" }}</th>
            <td class="vcenter text-left">{{ !empty($dataForView['mask_card_no']) ? $dataForView['mask_card_no'] : "" }}</td>
            <th class="vcenter text-left">&nbsp;</th>
            <td class="vcenter text-left">&nbsp;</td>
            <th class="vcenter text-left">&nbsp;</th>
            <td class="vcenter text-left">&nbsp;</td>
        </tr>
        @endif
    </table>
        </div>
        </fieldset>

    <fieldset class="scheduler-border" style="background-color:#ffffff">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
            <legend class="scheduler-border" style="font-family: Verdana,Geneva,sans-serif;color:#FF4500;background-color:#ffffff">Action</legend>
        </div>

        <div class="table-responsive">
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
        @include('Supports/wform_report_details_extended')

        <tr> <th colspan="6">&nbsp;</th> </tr>

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
        @inject('queueDuration','App\Services\UtilService')
        <?php
		$sqlFormStatus = \Illuminate\Support\Facades\DB::table('comments')
			->select('comments.*','users.name')
            ->join('users','comments.user_id','=','users.user_id')
            ->where('comments.reference_number',$dataForView['reference_number'])
			->orderBy('comments.time','ASC')
            ->get();

        if (count($sqlFormStatus)> 0) { ?>
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

                            //sprintf("%02d",intdiv($row->duration_in_minutes, 60)).':'. sprintf("%02d",($row->duration_in_minutes % 60));
                        }elseif($row->action == "Close"){
                            $models[$i]['in_time'] = 0;
                            $models[$i]['work_time'] = $row->time;
                            $models[$i]['out_time'] = $row->time;
                            $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row->time));

                            //sprintf("%02d",intdiv($row->duration_in_minutes, 60)).':'. sprintf("%02d",($row->duration_in_minutes % 60));
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
                            $models[$i]['duration_in_minutes'] =  $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row->time));

                            //sprintf("%02d",intdiv($row->duration_in_minutes, 60)).':'. sprintf("%02d",($row->duration_in_minutes % 60));
                        }


                        if(count($sqlFormStatus) == $i+1 && $row->action != "Close" && $isapproved != 1){

                            //echo '-------'.$lastInTime;
                            $stime1 = "";
                            if(!empty($lastInTime)){
                                $stime1 = $lastInTime;
                            }else{
                                $stime1 = time();
                            }

                            $stime = date('Y-m-d H:i:s', (int)$stime1);
                            $etime = date('Y-m-d H:i:s');

                            $duration_in_minutes = $queueDuration->queueDurationCalculator($stime, $etime);
                            //echo $stime.'--'.$etime.'--'.$duration_in_minutes;
                            $models[$i]['duration_in_minutes'] = $duration_in_minutes;

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

                        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">@if(!empty($rowFormVal['user_name'])){{ $rowFormVal['user_name'] }}@endif</td>
                        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if(!empty($rowFormVal['in_time']) > 0) echo date("d.m.Y ## h:i a",$rowFormVal['in_time']); ?></td>
                        <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if(!empty($rowFormVal['work_time']) > 0) echo date("d.m.Y ## h:i a",$rowFormVal['work_time']); ?></td>
                        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['form_status']; ?></td>
                        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if(!empty($rowFormVal['out_time']) > 0) echo date("d.m.Y ## h:i a",$rowFormVal['out_time']); ?></td>

                        <td class="" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px; text-align:center"><?php echo $rowFormVal['duration_in_minutes'] ?></td>
                        <td class="topandbottom" colspan="2" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['comments'];  ?>&nbsp;</td>
                    </tr>
                    <?php
                } ?>
            </table>
            <?php
        } ?>

        @IF(!empty($dataForView['w_form_attachment']))
            <tr><th colspan="6" class="vcenter text-left">Attachment : </th></tr>
            @FOREACH($dataForView['w_form_attachment'] AS $attch)
                <tr>
                    <th class="vcenter text-left"> {{ $dataForView['depricate_wform_type'] == 1450 ? $attch['name'] : $attch['attachment_date']  }}</th>
                    <td class="vcenter text-left" colspan="5">                        
                        @IF(!empty($attch['file_name']))
                            <?php
                                $basePath = str_replace('engine','',base_path());
                                $imageURL = $basePath.'public/attachments/'.$attch['file_name'];
                            // prd($imageURL);
                            if(file_exists($imageURL)){
                            ?>
                            <a href="{{ URL::asset('public/attachments/'.$attch['file_name']) }}" target="_blank">{{$attch['file_name']}}</a>
                            <a href="{{ route('download', ['filename' => $attch['file_name']]) }}"><i class="fa fa-download"></i></a>
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

        </tbody>
    </table>

    {{-- BPID Print option --}}
    @if ($dataForView['depricate_wform_type'] == 1150)
        <form action="{{ route('support.printBpIdTicketDetails') }}" method="POST" target="_blank" id="bpidPrintForm" style="margin: 10px 0px;">
            @csrf
            <input type="hidden" name="issue_id" value="{{ $dataForView['depricate_wform_type'] }}">
            <input type="hidden" name="other_data" value="{{ json_encode($dataForView['w_form_type']) }}">
            <button class="btn btn-sm btn-primary">Print BPID Form</button>
        </form>
    @endif

    {{-- AUCTION Print option --}}
    @if ($dataForView['depricate_wform_type'] == 1149)
        <form action="{{ route('support.printAuctionTicketDetails') }}" method="POST" target="_blank" id="auctionPrintForm" style="margin: 10px 0px;">
            @csrf
            <input type="hidden" name="issue_id" value="{{ $dataForView['depricate_wform_type'] }}">
            <input type="hidden" name="other_data" value="{{ json_encode($dataForView['w_form_type']) }}">
            <button class="btn btn-sm btn-primary">Print Auction Form</button>
        </form>
    @endif


</div>
    <div class="clearfix">&nbsp;</div>
    <div class="modal fade" id="issueHistoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">History Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                            if ($dataForView['main_id'] == 1103 || $dataForView['main_id'] == 1105){
                                ?>
                                @include('partials.quota_history_modal')
                                <?php
                            } else {
                                if(!empty($history_data)) {
                                        foreach ($history_data as $row){
                                    ?>
                                        <tr>
                                            <th class="vcenter text-left">
                                                
                                                <table class="table table-condensed table-bordered no-padding-margin-b">
                                                    <?php
                                                    $i = 1;
                                                    $extra_fields = (array) json_decode($row->extra_field, true); // 
                                                    $count = count($extra_fields);

                                                    if (!empty($extra_fields)) {
                                                        foreach ($extra_fields as $key => $r) {
                                                            if (is_array($r) || is_object($r)) {
                                                                foreach ($r as $key1 => $value) {

                                                                    
                                                                    if (is_object($value) || is_array($value)) {
                                                                        continue;
                                                                    }

                                                                    if ($i == 1) { ?>
                                                                        <tr>
                                                                    <?php } ?>

                                                                    <th class="vcenter text-left">{{ $key1 }}</th>
                                                                    <td class="vcenter text-left">{{ $value ?? '' }}</td>

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

                                                                <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
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
                                                    <td class="vcenter text-left">{{ (!empty($value))? $value:"" }}</td>
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
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extrajssection')

@endsection
