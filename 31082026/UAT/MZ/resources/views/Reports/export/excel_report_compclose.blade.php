@php
 use Carbon\Carbon;
@endphp
@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th class="text-center vcenter wordwrap ">Lodge date</th>
                <th class="text-center vcenter wordwrap ">Complain source</th>
                <th class="text-center vcenter wordwrap ">Customer Email address</th>
                <th class="text-center vcenter wordwrap ">Complaint Hnadler</th>
                <th class="text-center vcenter wordwrap ">Customer's Name</th>
                <th class="text-center vcenter wordwrap ">Contact number</th>
                <th class="text-center vcenter wordwrap ">Acc/Card No:</th>
                <th class="text-center vcenter wordwrap ">Complaint Status (resolved/ unresolved)</th>
                <th class="text-center vcenter wordwrap ">Reference/ ticket number</th>
                <th class="text-center vcenter wordwrap ">Nature of Complaints</th>
                <th class="text-center vcenter wordwrap ">Correspondence source</th>
                <th class="text-center vcenter wordwrap ">FI Branch ID</th>
                <th class="text-center vcenter wordwrap ">Resolve Date</th>
                <th class="text-center vcenter wordwrap ">AMOUNT INVOLVED</th>
                <th class="text-center vcenter wordwrap ">Root cause</th>
                <th class="text-center vcenter wordwrap ">Subject of email/ Complaint Subject</th>
                <th class="text-center vcenter wordwrap ">Action taken</th>
                <th class="text-center vcenter wordwrap ">Complaint summary</th>
                <th class="text-center vcenter wordwrap ">Days to resolve (Working Days)</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($dataForView) --}}
            @IF(!empty($dataForView))
                @FOREACH($dataForView AS $data)
                    <?php
                        $getCommentInfo = getCommentsActionTimeAndCloseComment($data['reference_number']);
                        $data['action_time'] = $getCommentInfo['action_time'];
                        $data['close_comments'] = $getCommentInfo['close_comments'];

                    $form_status = $data['form_status'];
                    if($form_status == 8 || $form_status == 0 || $form_status == null) {
                        $status = "Pending";
                    } elseif($form_status == 2) {
                        $status = "Wip";
                    } else if ($form_status == 11) {
                        $status = "Close";
                    } else if ($form_status == 10) {
                        $status = "Hold";
                    } else if ($form_status == 12) {
                        $status = "Resolved";
                    }
                    else {
                        $status = "Wip";
                    }

                    $stime = date('Y-m-d H:i:s', (int)$data['date']);
                    $etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');

                    $duration = $queueDuration->queueDurationCalculator($stime, $etime);

                    $str_arr = preg_split ("/\:/", $duration);
                    //pr($str_arr);
                    //echo $data['reference_number'].'----'.$stime.'--'.$etime.'--'.$duration;

                    ?>
                    @if(($str_arr[0]*60)+$str_arr[1] > "1440")
                        @if($searchDataForView["form_type"] != 'complaint')
                            <tr style="background-color:#E9967A">
                        @else
                            <tr>
                        @endif
                    @else
                        <tr>
                    @endif


                {{-- 1. Lodge data --}}
                    <td class="text-center vcenter no-padding-margin-tb">
                        @if($searchDataForView['date_type'] == 'action_date')
                            {{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('d-m-Y') }}
                            {{ \Carbon\Carbon::createFromTimestamp($data['action_time'])->format('H:i:s') }}
                        @else
                            {{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('d-m-Y') }}
                            {{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('H:i:s') }}
                        @endif
                    </td>

                {{-- 2. Complain Source --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ ($data['source']) }} </td>
                                            
                {{-- 3. Customer Email --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ $data['email_address'] }}</td>

                {{-- 4. Complaint Handler --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{$data['source_maker'] != null ? $data['source_maker'] : ""}}</td>

                {{-- 5. Customer Name --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ htmlentities($data['customer_name']) }}</td>

                {{-- 6. Customer Number --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ $data['mobile_number'] }}</td>
                
                {{-- 7. Acc/Card Number --}}
                    <td class="text-center vcenter no-padding-margin-tb"> '{{ $data['account_number'] }}</td>

                {{-- 8. Complain Status --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ $status }}</td>

                {{-- 9. Reference/Ticket Number --}}
                    <td class="text-center vcenter no-padding-margin-tb">
                        <a href="{{ url('/Supports/ComplaintReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
                    </td>
                {{-- 10. Reference/Ticket Number --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{$data['natureofcomp']}}</td>
                
                {{-- 11. Crosspondence source --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['created_by_name'])) ? $data['created_by_name'] : $data['created_by'] }} </td>

                {{-- 12. Fi Id --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ $data['fi_id'] }}</td>

                {{-- 13. Resolve Date --}}
                    <td class="text-center vcenter no-padding-margin-tb">
                        @IF($data['form_status'] == 11)
                            {{ $data['last_access_dt'] }}
                        @ENDIF
                    </td>

                {{-- 14. Amount --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{$data['amountinvoled']}}</td>

                {{-- 15. Root Cause --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{$data['rootcause']}}</td>

                {{-- 16. Complaint Subject --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['form_type'] }}</td>

                {{-- 17. Action Taken --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ $data['actiontaken'] }}</td>
                
                {{-- 18. Complaint Summary --}}
                    <td class="text-center vcenter no-padding-margin-tb">{{ $data['complaint_details'] }}</td>

                {{-- 19. Days To Resolve --}}
                {{-- @inject('queueDuration', 'App\Services\UtilService') --}}
                @inject('timeCalculate', 'App\Services\UtilService')

                @php
                    $comments = App\Comment::where('reference_number', $data['reference_number'])
                        ->orderBy('id', 'DESC')
                        ->first();

                    // $sla_used = $timeCalculate->queueDurationCalculatorNew(
                        $sla_used = $timeCalculate->queueDurationCalculatorNew(date('Y-m-d H:i:s', $comments->time), date('Y-m-d H:i:s'));

                    //     date('Y-m-d H:i:s', strtotime("2025-03-10 09:00:00")), 
                    //     date('Y-m-d H:i:s', strtotime("2025-03-19 09:25:34"))
                    // );

                    list($hours, $minutes, $seconds) = explode(":", $sla_used);

                    $hours = (int)$hours;
                    $minutes = (int)$minutes;
                    $seconds = (int)$seconds;

                    $days = floor($hours / 8); // প্রতি ৮ ঘণ্টা = ১ দিন
                    $remaining_hours = $hours % 8;

                    if ($minutes >= 60) {
                        $extra_hours = floor($minutes / 60);
                        $remaining_minutes = $minutes % 60;
                        $remaining_hours += $extra_hours;
                    } else {
                        $remaining_minutes = $minutes;
                    }
                    // days with minutes
                    // $output = "{$days} days {$remaining_minutes} minutes";
                    // Only day
                    // dd($days);
                @endphp

                    <td class="text-center vcenter no-padding-margin-tb"> {{ $days }}</td>

                    {{-- <td class="text-center vcenter no-padding-margin-tb">{{$total_time}}</td> --}}
                    {{-- <td class="text-center vcenter no-padding-margin-tb">Days To Resolve</td> --}}

                </tr>
                @ENDFOREACH
            @ELSE
			<tr><th class="text-center vcenter no-padding-margin-tb" colspan="21">Data not available</th></tr>
		@ENDIF
        </tbody>
    </table>
</div>