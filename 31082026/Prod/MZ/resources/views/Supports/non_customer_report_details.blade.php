@extends('layouts.admin')
@section('content')
    <div class="curved-inner-pro" style="background-color: #DFF0D8; padding: 5px;">
		<div class="curved-inner-pro">
        <div class="curved-ctn">
            <h2 style="padding:10px">{{$title_for_layout}}</h2>
        </div>
    </div>
    </div>
    <br>
    <div>
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
                <td class="vcenter text-left">
                @if($dataForView['form_status'] == 11)
                    Form Close at {{ $dataForView['name'] }}&nbsp;[{{($dataForView['unit_id']==1)? "Maker":"Checker"}}]
                @else
                    {{ $dataForView['name'] }}&nbsp;[{{($dataForView['unit_id']==1)? "Maker":"Checker"}}]
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
                <th class="vcenter text-left"></th>
				<td class="vcenter text-left"></td>
            </tr>

			@inject('queueDuration','App\Services\UtilService')
			<?php
    		$sqlFormStatus = \Illuminate\Support\Facades\DB::table('comments')
    			->select('comments.*', 'users.name')
                ->join('users','comments.user_id','=','users.user_id')
                ->where('comments.reference_number',$dataForView['reference_number'])
    			->orderBy('comments.time','ASC')
                ->get();

            if (count($sqlFormStatus)> 0) { ?>
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
                            $models[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row->time));

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

            @IF(!empty($dataForView['non_customers_attachment']))
            <tr><th colspan="6" class="vcenter text-left">Attachment</th></tr>
                @FOREACH($dataForView['non_customers_attachment'] AS $attch)
                    <tr>
                        <th class="vcenter text-left">{{ $attch['attachment_date'] }}</th>
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

        </table>
    </div>
    <div class="clearfix">&nbsp;</div>
@endsection

