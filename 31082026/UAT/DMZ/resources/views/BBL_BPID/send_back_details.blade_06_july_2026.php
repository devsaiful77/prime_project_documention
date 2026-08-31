@extends('BBL_CI.layouts.master')
@push('app-title')
    Send Back Tickets
@endpush
@section('content')
@php
    $isMobile = request()->header('User-Agent') && preg_match('/Mobile|Android|iP(hone|od|ad)/', request()->header('User-Agent'));
@endphp
<div class="bg-wrapper sent-back-wrap">
    <div class="container-fluid custom-layout">
    <div class="row">
        <div class="d-xsm-block d-sm-block d-md-none d-lg-none d-xl-none d d-xxl-none">
            <div class="s_b_t_bottom_hr">
                <p class="s_b_t_head pb-3 mb-0">{{ $title }} Send Back Tickets</p>
            </div>
            <table class="table exampleDataTable pr-0">
                <thead class="table_head">
                <tr>
                    <th scope="col" style="font-size: 12px">Log Date</th>
                    <th scope="col" style="font-size: 12px">Service Request</th>
                    <th scope="col" style="font-size: 12px">Reason</th>
                </tr>
                </thead>
                @forelse($sendBackTickets as $status)
                        <tr>
                            <td class="s_b_t_date p-0 text-center" style="width: 20%;">
                                <span class="s_b_t_day">{{date('d', strtotime($status->time_and_ext))}}</span>
                                <span class="s_b_t_month">{{date('M', strtotime($status->time_and_ext))}}  {{date('Y', strtotime($status->time_and_ext))}}</span>
                                <small class="s_b_t_time">{{date('h: i A', strtotime($status->time_and_ext))}}</small>
                            </td>
                            <td class="align-middle" style="width: 58%">
                                <span> <a href="{{route('CI.send-back.ticket',['issueId'=>$status->serviceName->id, 'refNum'=>$status->reference_number, 'viewMode'=>'web', 'CIToken' => $ci_token, 'request_type' => 'service'])}}" class="text-decoration-underline">
                                        {{$status->serviceName->name}}
                                    </a></span> <br>
                                <span>
                                    <a href="{{route('CI.send-back.ticket',['issueId'=>$status->serviceName->id, 'refNum'=>$status->reference_number, 'viewMode'=>'app', 'CIToken' => $ci_token, 'request_type' => 'service'])}}">{{$status->reference_number}}</a>
                                </span>
                            </td>
                            <td class="align-middle text-center" style="width: 12%;">
                                @php
                                    $sendBackApp = '';
                                    if ($isMobile){
                                         $Send_Back_query = 'Send Back to ' .$group_name->name .' Maker';
                                        $sendBackCalection = \Illuminate\Support\Facades\DB::table('comments')
                                                        ->select('comments.action', 'comments.comments')
                                                        ->where('comments.reference_number', '=', $status['reference_number'])
                                                        ->where("comments.action", '=', $Send_Back_query)
                                                        ->orderBy('id', 'DESC')
                                                        ->first();

                                        $sendBackApp = ($sendBackCalection? ($sendBackCalection->comments? $sendBackCalection->comments: "") : '');
                                    }
                                @endphp
                                <a href="javascript:void(0)" data-toggle="tooltip" data-bs-placement="right" title="{{$sendBackApp}}" style="font-size: 20px" class="">
                                    <i class="far fa-comment-alt" style="color: #2157b5;"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No Data Found!</td>
                        </tr>
                   @endforelse

            </table>
            {{ $sendBackTickets->appends(request()->except('page'))->links() }}
        </div>
        <div class="d-xsm-none d-sm-none d-md-block d-lg-block d-xl-block d-xxl-block web_a_verify">
            <h4 class="s_b_t_head pb-4 mb-0">{{ $title }} Send Back Tickets</h4>
            <table class="table table-responsive table-striped exampleDataTable">
                <thead class="table_head">
                <tr>
                    <th scope="col">Log Date</th>
                    <th scope="col">Service Request Type</th>
                    <th scope="col">Ticket Number</th>
                    <th scope="col" class="text-center">Send Back Reason</th>
                </tr>
                </thead>
                <tbody>
                    @forelse( $sendBackTickets as $row )
                            <tr>
                                <td class="align-middle">{{date('d M Y', $row->ciTicketStatus->date)}}
                                    <br>
                                    <small>{{date('h: i A', $row->ciTicketStatus->date)}}</small>
                                </td>
                                <td class="align-middle">
                                    <a href="{{route('BPID.send-back.ticket',['issueId'=>$row->serviceName->id, 'refNum'=>$row->reference_number, 'viewMode'=>'web', 'CIToken' => $ci_token, 'request_type' => $request_type])}}" class="text-decoration-underline">
                                        {{$row->serviceName->name}}
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="{{route('BPID.send-back.ticket',['issueId'=>$row->serviceName->id, 'refNum'=>$row->reference_number, 'viewMode'=>'web', 'CIToken' => $ci_token, 'request_type' => $request_type])}}" class="text-decoration-underline">
                                        {{$row->reference_number}}
                                    </a>
                                </td>

                                @php
                                    $sendBack = '';
                                    if (!$isMobile){
                                         $Send_Back_query = 'Send Back to ' .$group_name->name .' Maker';
                                        $sendBackCalection = \Illuminate\Support\Facades\DB::table('comments')
                                                        ->select('comments.action', 'comments.comments')
                                                        ->where('comments.reference_number', '=', $status['reference_number'])
                                                        ->where("comments.action", '=', $Send_Back_query)
                                                        ->orderBy('id', 'DESC')
                                                        ->first();

                                        $sendBack = ($sendBackCalection? ($sendBackCalection->comments? $sendBackCalection->comments: "") : '');
                                    }
                                @endphp
                                <td class="align-middle text-center">
                                    <button type="button" 
                                            data-toggle="tooltip" 
                                            data-bs-placement="right" 
                                            title="{{$sendBack}}" 
                                            style="background: none; border: none; padding: 0; cursor: pointer; font-size: 20px;">
                                        <i class="far fa-comment-alt" style="color: #2157b5;"></i>
                                    </button>

                                </td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No Data Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $sendBackTickets->appends(request()->except('page'))->links() }}

        </div>
    </div>
</div>
</div>


@push('js')
    <script nonce="{{ app('csp_nonce') }}">
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
@endsection
