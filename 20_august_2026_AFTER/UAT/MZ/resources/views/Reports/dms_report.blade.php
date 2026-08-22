@php //prd($dataForView) @endphp
@inject('queueDuration','App\Services\UtilService')
<div class="table-responsive">
    <table class="table table-bordered table-condensed">
        <thead style="background-color: ;">
        <tr>
            <th style="color: " class="text-center vcenter wordwrap ">Ticket Number</th>
            @if($searchDataForView["form_type"] != 'noncustomer')
                <th style="color:" class="text-center vcenter wordwrap ">Card / Acc Number</th>
            @endif
            <th style="color: " class="text-center vcenter wordwrap ">Customer Name</th>
            <th style="color: " class="text-center vcenter wordwrap ">Attachment</th>
            @if($searchDataForView["form_type"] != 'noncustomer')
                <th style="color: " class="text-center vcenter wordwrap ">Product Type</th>
                {{--<th style="color: " class="text-center vcenter wordwrap ">Caller ID</th>--}}
                @IF( $searchDataForView["form_type"] == 'wform')
                    <th style="color: " class="text-center vcenter wordwrap ">Service Request Type</th>
                @else
                    <th style="color: " class="text-center vcenter wordwrap ">Complaint Type</th>
                @endif
            @endif
            <th style="color: " class="text-center vcenter wordwrap no-padding-margin-tb">Maker</th>
                <th style="color: " class="text-center vcenter nowrap ">Log Date & Time</th>
           {{-- <th style="color: " class="text-center vcenter wordwrap ">Current Position</th>--}}
           {{-- <th style="color: " class="text-center vcenter wordwrap ">TaT(D:H:M:S)</th>--}}
            @if( $searchDataForView["form_type"] != 'noncustomer')
            @endif
            @if( $searchDataForView["form_type"] == 'complaint')
               {{-- <th style="color: " class="text-center vcenter wordwrap ">Is Justified</th>--}}
            @endif
            <th style="color: " class="text-center vcenter wordwrap ">Status</th>
            @if($searchDataForView["form_type"] != 'noncustomer')
                <th style="color: " class="text-center vcenter wordwrap ">Customer Number</th>
            @endif
            <th style="color: " class="text-center vcenter wordwrap ">Upload Date</th>
            <th style="color: " class="text-center vcenter wordwrap ">DMS Remark</th>
        </tr>
        </thead>
        <tbody>
        <?php  //echo '<pre>'; print_r($searchDataForView); print_r($dataForView); die;?>
        @IF(!empty($dataForView['data']))
            @FOREACH($dataForView['data'] AS $data)
                <?php

                $getCommentInfo = getCommentsActionTimeAndCloseComment($data['reference_number']);
                $data['action_time'] = $getCommentInfo['action_time'];
                $data['close_comments'] = $getCommentInfo['close_comments'];
                $getDMSRemark = getDMSRemarks($data['attachment_id'], $data['reference_number']);
                $data['upload_date'] = $getDMSRemark['upload_date'];
                $data['dms_remark'] = $getDMSRemark['dms_remark'];
                $stime = date('Y-m-d H:i:s', (int)$data['date']);
                $etime = ($data['form_status'] == 11)? date('Y-m-d H:i:s', (int)$data['access_date']) : date('Y-m-d H:i:s');

                $duration = $queueDuration->queueDurationCalculator($stime, $etime);
                // $smsEmailStatus = $queueDuration->getMailSMSStatus($data['reference_number']);

                $str_arr = preg_split ("/\:/", $duration);
                //pr($str_arr);
                //echo $data['reference_number'].'----'.$stime.'--'.$etime.'--'.$duration;

                $form_status = $data['form_status'];
                if($form_status == 8 || $form_status == 0 || $form_status == null) {
                    $status = "Pending";
                } elseif($form_status == 2) {
                    $status = "Wip";
                } else if ($form_status == 11) {
                    $status = "Close";
                } else if ($form_status == 10) {
                    $status = "Hold";
                } else {
                    $status = "Wip";
                }

                $backgroundColor = "";
                if (($str_arr[0]*60)+$str_arr[1] > "1440") {
                    if ($searchDataForView["form_type"] != 'complaint') {
                        $backgroundColor = "background-color:#E9967A";
                    }
                }
                ?>

                <tr style="{{$backgroundColor}}">
                    @IF( $searchDataForView["form_type"] == 'wform')
                        <td class="text-center vcenter no-padding-margin-tb">
                            <a href="{{ url('/Supports/WFormReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
                        </td>
                        <td class="text-center vcenter no-padding-margin-tb">{{ $data['account_number'] }}</td>
                    @ELSEIF( $searchDataForView["form_type"] == 'complaint')
                        <td class="text-center vcenter no-padding-margin-tb">
                            <a href="{{ url('/Supports/ComplaintReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
                        </td>
                        @if( $searchDataForView["form_type"] != 'noncustomer')
                            <td class="text-center vcenter no-padding-margin-tb">{{ $data['account_number'] }}</td>
                        @endif
                        @ELSEIF( $searchDataForView["form_type"] == 'noncustomer')
                        <td class="text-center vcenter no-padding-margin-tb">
                                <a href="{{ url('/Supports/NonCustomerReportDetails/'.encrypt($data['reference_number'])) }}" target="_blank">{{ $data['reference_number'] }}</a>
                        </td>
                        @ENDIF
                        <td class="text-center vcenter no-padding-margin-tb">{{ $data['customer_name'] }}</td>
                        <td class="vcenter text-center">
                            @IF(!empty($data['file_name']))
                                <?php
                                $basePath = str_replace('engine','',base_path());
                                $imageURL = $basePath.'public/attachments/'.$data['file_name'];
                                if(file_exists($imageURL)){
                                    ?>
                                <a href="{{ URL::asset('public/attachments/'.$data['file_name']) }}" target="_blank">{{ $data['file_name'] }}</a>
                                <?php } else{?>
                                <a href="{{ url('/images') }}" target="_blank">{{ $data['file_name'] }}</a>
                                <?php }?>
                            @ELSE
                                {{ __('Attachment not available') }}
                            @ENDIF
                        </td>
                        @if( $searchDataForView["form_type"] != 'noncustomer')
                            <td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['product_type_name'])) ? $data['product_type_name'] : $data['product_type_ext'] }}</td>
                            {{--<td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['caller_id'])) ? $data['caller_id'] : '-' }}</td>--}}
                            <td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['category_name'])) ? $data['category_name'] : $data['form_type'] }}</td>
                        @endif
                        <td class="text-center vcenter no-padding-margin-tb">{{ (!empty($data['created_by_name'])) ? $data['created_by_name'] : $data['created_by'] }} {{$data['source_maker']!=null? "(".$data['source_maker'].")" : ""}}</td>

                            <td class="text-center vcenter no-padding-margin-tb">
                                {{ \Carbon\Carbon::createFromTimestamp($data['date'])->format('d-m-Y H:i:s') }}</td>
                        {{--<td class="text-center vcenter no-padding-margin-tb">
                            @if($data['form_status'] == 11)
                                Form Close at {{ $data['name'] }}&nbsp;
                                {{ !empty($data['access_by']) ? '-'.$data['access_by'] : '' }}
                                [{{($data['unit_id']==1)? "Maker":"Checker"}}]
                            @else
                                {{ !empty($data['access_by']) ? $data['access_by'] .' ('. $data['name'] .')' : $data['name'] }}
                                &nbsp;[{{($data['unit_id']==1)? "Maker":"Checker"}}]
                            @endif
                        </td>--}}
                        {{--<td class="text-center vcenter no-padding-margin-tb">{{ $duration }}</td>--}}
                        @if( $searchDataForView["form_type"] != 'noncustomer')
                            @if( $searchDataForView["form_type"] == 'complaint')
                                {{--<td class="text-center vcenter no-padding-margin-tb">{{$data['is_justified_name']}}</td>--}}
                            @endif
                        @endif
                        <td class="text-center vcenter no-padding-margin-tb">{{ $status }}</td>
                        @if($searchDataForView["form_type"] != 'noncustomer')
                            <td class="text-center vcenter no-padding-margin-tb">{{ $data['SIF_Number'] }}</td>
                        @endif
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ $data['upload_date'] }}
                        </td>
                        <td class="text-center vcenter no-padding-margin-tb">
                            {{ (!empty($data['dms_remark'])) ? $data['dms_remark'] : ''}}
                        </td>
                </tr>
            @ENDFOREACH
        @ELSE
            <tr><th class="text-center vcenter no-padding-margin-tb" colspan="18">Data not available DMS</th></tr>
        @ENDIF
        </tbody>
        <tfoot>
        @IF(!empty($dataObj))
            @IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right vcenter no-padding-margin-tb" colspan="18">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF
        @ENDIF
        </tfoot>
    </table>
</div>
