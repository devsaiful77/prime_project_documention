@php //prd($dataForView) @endphp
@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
		<table class="table table-bordered table-condensed">
			<thead style="background-color: ;">
		<tr>
			<th style="color: " class="text-center vcenter wordwrap ">Ticket Number</th>
			<th style="color: " class="text-center vcenter wordwrap ">Card / Acc Number</th>
			<th style="color: " class="text-center vcenter wordwrap ">Customer Name</th>
			<th style="color: " class="text-center vcenter wordwrap ">Product Type</th>
			@IF( $searchDataForView["form_type"] == 'wform')
			<th style="color: " class="text-center vcenter wordwrap ">Service Request Type</th>
			@else
			<th style="color: " class="text-center vcenter wordwrap ">Complaint Type</th>
			@endif
			<!-- <th style="color: " class="text-center vcenter wordwrap">Task Group</th> -->
			<th style="color: " class="text-center vcenter wordwrap">User [Task Group]</th>

			<th style="color: " class="text-center vcenter wordwrap ">Log/In Time</th>
			<th style="color: " class="text-center vcenter wordwrap ">Task Touch Time</th>
			<th style="color: " class="text-center vcenter wordwrap ">Status</th>
			<th style="color: " class="text-center vcenter wordwrap ">Close/Out Time</th>
			<th style="color: " class="text-center vcenter wordwrap ">Duration (D:H:M:S)</th>

			<th style="color: " class="text-center vcenter wordwrap ">Remarks</th>
		</tr>
		</thead>
		<tbody>
		@IF(!empty($dataForView['data']))
			<?php
			$prevRefNo ="";

			$duration_in_minutes = 0;
			$totalDuration = 0;
			$i = 0;
			$j = 0;
			$commentsData = array();
			$prevSGID = '';
			$lastInTime = "";

			?>
			@FOREACH($dataForView['data'] AS $data)
				<?php
				if ($prevRefNo != $data['reference_number']) {
					$i = 0;
					$j = 0;
					$commentsData = array();
					$prevSGID = '';
					$prevgID = '';
					$lastInTime = '';
				}
				$stime = date('Y-m-d H:i:s', (int)$data['date']);
				$etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');
				//echo $data['reference_number'].'---'.$stime.'---'.$etime;
                // $duration = $queueDuration->queueDurationCalculator($stime, $etime);
                $data['comment'] = $queueDuration->getAllComments($data['reference_number']);
                // $str_arr = preg_split ("/\:/", $duration);


                if (empty($data['form_type'])) {
                	$data['form_type'] = "N/A";
                }

                foreach ($data['comment'] as $row){

			       	$groupID = $row['group_id'];
			        $subGroupID = $row['subgroup_id'];
			        $userID = $row['user_id'];
			        $form_status = $row['action'];
			        $comments = $row['comments'];
			        $isapproved = $row['isapproved'];
			        $userName = $row['name'];
			        $commentsData[$i]['group_id'] = $groupID;
			        $commentsData[$i]['action'] = $row['action'];
			        $commentsData[$i]['subgroup_name'] = $row['subgroup_name'];
			        $commentsData[$i]['duration_in_minutes'] = "";


			        if($prevgID == $userID){
			            $commentsData[$i]['user_id'] = '';
			            $commentsData[$i]['user_name'] = '';
			        }else{
			            $commentsData[$i]['user_id'] = $userID;
			            $commentsData[$i]['user_name'] = $userName;
			        }

			        if($i == 0){
			            $commentsData[$i]['isapproved'] = 1;
			            $commentsData[$i]['in_time'] = $row['time'];
			            $commentsData[$i]['work_time'] = $row['time'];
			            $commentsData[$i]['out_time'] = $row['time'];
			            $lastInTime = $row['time'];
			            $commentsData[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row['time']));

			        }elseif($row['action'] == "Close"){
			            $commentsData[$i]['in_time'] = 0;
			            $commentsData[$i]['work_time'] = $row['time'];
			            $commentsData[$i]['out_time'] = $row['time'];
			            $commentsData[$i]['duration_in_minutes'] = $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row['time']));

			        }elseif($prevgID != $userID){
			            $commentsData[$i]['in_time'] = (!empty($commentsData[$i-1]['out_time'])) ? $commentsData[$i-1]['out_time'] : $row['time'];
			            $commentsData[$i]['work_time'] = $row['time'];
			            $commentsData[$i]['out_time'] = 0;
			            $lastInTime = (!empty($commentsData[$i-1]['out_time'])) ? $commentsData[$i-1]['out_time'] : $row['time'];
			        }elseif($prevgID == $userID && $i > 0 && $isapproved == 0){
			            $commentsData[$i]['in_time'] = 0;
			            $commentsData[$i]['work_time'] = $row['time'];
			            $commentsData[$i]['out_time'] = 0;
			        }elseif($prevgID == $userID && $i > 0 && $isapproved == 1){
			            $commentsData[$i]['isapproved'] = $isapproved;
			            $commentsData[$i]['in_time'] = 0;
			            $commentsData[$i]['work_time'] = $row['time'];
			            $commentsData[$i]['out_time'] = $row['time'];
			            $commentsData[$i]['duration_in_minutes'] =  $queueDuration->queueDurationCalculator(date('Y-m-d H:i:s', $lastInTime), date('Y-m-d H:i:s', $row['time']));

			        }


			        if(count($data['comment']) == $i+1 && $row['action'] != "Close" && $isapproved != 1){

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
			            $commentsData[$i]['duration_in_minutes'] = $duration_in_minutes;

			        }

			        $commentsData[$i]['form_status'] = $form_status;
			        $commentsData[$i]['comments'] = $comments;

			        $prevgID = $userID;
			        $i++;

			        $prevRefNo = $data['reference_number'];
			    }
			    $totalComment = count($commentsData);

				?>
				@FOREACH($commentsData as $cdKey=>$rowFormVal)
					<?php
					if ($cdKey == 0) {
						$totalDuration = 0;
					}
					$action = $rowFormVal['action'];
					$in_time = date('Y-m-d H:i:s',$rowFormVal['in_time']) ;
					$out_time = date('Y-m-d H:i:s',$rowFormVal['out_time']) ;

					if ($rowFormVal['action'] == 'Hold' || $rowFormVal['action'] == 'Assigned') {
						$out_time = date('Y-m-d H:i:s') ;
					}

					$rowFormVal['queue_duration'] = $queueDuration->queueDurationCalculator($in_time, $out_time);
					$str_arr = preg_split ("/\:/", $rowFormVal['duration_in_minutes']);
					//$str_arr = preg_split ("/\:/", $rowFormVal['queue_duration']);

					$issue_flow_type = $data['flow_type'];
                    $sla_maker = $data['sla_maker'];
                    $sla_checker = $data['sla_checker'];
                    $QueueSLATime = 0;
                    if ($issue_flow_type == "regular") {
                        if ($data['unit_id'] == 2) {
                            $QueueSLATime = $sla_checker;
                        } else {
                            $QueueSLATime = $sla_maker;
                        }
                    }

                    $totalSLA =  count($str_arr) > 1 ?  ((int)$str_arr[0]*24*60)+((int)$str_arr[1]*60)+(int)$str_arr[2] : ((int)$str_arr[0]*24*60);

                    //pr($totalSLA);
                    ?>
					@if(!empty($totalSLA) && ($totalSLA > $QueueSLATime ))
						@if($searchDataForView["form_type"] != 'complaint')
							<tr style="background-color:#E9967A">
						@else
							<tr>
						@endif
					@else
						<tr>
					@endif
						@IF($cdKey == 0)
							<td rowspan="{{$i}}" class="text-center vcenter no-padding-margin-tb">
								@IF( $searchDataForView["form_type"] == 'wform')
									<a href="{{ url('/Supports/WFormReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
								@ELSEIF( $searchDataForView["form_type"] == 'complaint')
									<a href="{{ url('/Supports/ComplaintReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
                                @ELSEIF( $searchDataForView["form_type"] == 'noncustomer')
								    <a href="{{ url('/Supports/NonCustomerReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
								@ENDIF
							</td>
							@if( $searchDataForView["form_type"] != 'noncustomer')
							<td rowspan="{{$i}}" class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['account_number'])) ? $data['account_number']: 'N/A' }}</td>
							@endif
							<td rowspan="{{$i}}" class="text-center vcenter no-padding-margin-tb">{{ $data['customer_name'] }}</td>
							@if( $searchDataForView["form_type"] != 'noncustomer')
							<td rowspan="{{$i}}" class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type_name'])) ? $data['product_type_name']: 'N/A' }}</td>
							@else
							<td rowspan="{{$i}}" class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['prod_name'])) ? $data['prod_name'] : "" }}</td>
							@endif
							@if( $searchDataForView["form_type"] != 'noncustomer')
							<td rowspan="{{$i}}" class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['form_type'] }}</td>
							@endif
						@ENDIF
						<!-- <td class="text-center vcenter no-padding-margin-tb">@if(!empty($rowFormVal['subgroup_name'])){{ $rowFormVal['subgroup_name'] }}@endif</td> -->
						<td class="text-center vcenter no-padding-margin-tb">
							@if(!empty($rowFormVal['user_name']))
								{{ $rowFormVal['user_name'] }}
								@if(!empty($rowFormVal['subgroup_name']))
									[{{ $rowFormVal['subgroup_name'] }}]
								@endif
							@endif
						</td>

						<td class="text-center vcenter no-padding-margin-tb"><?php if(!empty($rowFormVal['in_time']) > 0) echo date("d.m.Y h:i a",$rowFormVal['in_time']); ?></td>
						<td class="text-center vcenter no-padding-margin-tb">
							<?php if(!empty($rowFormVal['work_time']) > 0) echo date("d.m.Y ## h:i a",$rowFormVal['work_time']); ?>
						</td>
						<td class="text-center vcenter no-padding-margin-tb"><?php echo $rowFormVal['form_status']; ?></td>
						<td class="text-center vcenter no-padding-margin-tb"><?php if(!empty($rowFormVal['out_time']) > 0) echo date("d.m.Y h:i a",$rowFormVal['out_time']); ?></td>

						<td class="text-center vcenter no-padding-margin-tb"><?php echo $rowFormVal['duration_in_minutes']; ?></td>
						<td class="text-center vcenter no-padding-margin-tb"><?php echo $rowFormVal['comments'];  ?></td>
					</tr>
					<?php
					/* @IF($cdKey+1 == $totalComment) <tr> <td class="vcenter text-right" colspan="9">Total Duration</td> <td class="vcenter text-center"> <?php $zero    = new DateTime("@0"); $offset  = new DateTime("@$totalDuration"); $diff    = $zero->diff($offset); echo sprintf("%02d:%02d:%02d", $diff->days * 24 + $diff->h, $diff->i, $diff->s); ?> </td> <td>&nbsp;</td> </tr> @ENDIF */
					?>
				@ENDFOREACH
				<?php
				$prevRefNo = $data['reference_number'];
				?>
			@ENDFOREACH

		@ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="11">Data not available</th></tr>
		@ENDIF
		</tbody>
		<tfoot>
		@IF(!empty($dataObj))
			@IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right vcenter no-padding-margin-tb" colspan="11">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF
        @ENDIF
        </tfoot>
	</table>
</div>
