@extends('BBL_CI.layouts.master')
@push('app-title')
    Send Back Tickets
@endpush
@section('content')
    <div class="bg-wrapper">
        <div class="container-fluid custom-layout">
    <div class="row">
        <div class="d-xsm-block d-sm-block d-md-none d-lg-none d-xl-none d d-xxl-none">
            <div class="s_b_t_bottom_hr">
                <p class="s_b_t_head pb-3 mb-0">Complaint Send Back Tickets</p>
            </div>
            <table class="table exampleDataTable pr-0">
                <thead class="table_head">
                <tr>
                    <th scope="col" style="font-size: 12px">Date</th>
                    <th scope="col" style="font-size: 12px">Complaint Request</th>
                    <th scope="col" style="font-size: 12px">Reason</th>
                </tr>
                </thead>
                @forelse($sendBackTickets as $status)
                        <tr>
                            <td class="s_b_t_date p-0 text-center" style="width: 20%;">
                                <span class="s_b_t_day">{{date('d', $status->ciTicketStatus->date)}}</span>
                                <span class="s_b_t_month">{{date('M', $status->ciTicketStatus->date)}}  {{date('Y', $status->ciTicketStatus->date)}}</span>
                                <small class="s_b_t_time">{{date('h: i A', $status->ciTicketStatus->date)}}</small>
                            </td>
                            <td class="align-middle" style="width: 58%">
                                <span> <a href="{{route('CI.comaplaint-send-back-ticket',['issueId'=>$status->serviceName->id, 'refNum'=>$status->reference_number, 'viewMode'=>'app', 'CIToken' => $ci_token, 'request_type' => 'complaint'])}}" class="text-decoration-underline">
                                    {{$status->serviceName->name}}
                                </a></span> <br>
                                <span>
                                    <a href="{{route('CI.comaplaint-send-back-ticket',['issueId'=>$status->serviceName->id, 'refNum'=>$status->reference_number, 'viewMode'=>'app', 'CIToken' => $ci_token, 'request_type' => 'complaint'])}}">{{$status->reference_number}}</a>
                                </span>
                            </td>
                            <td class="align-middle text-center" style="width: 12%;">
                                @php
                                    $sendBackApp = '';
                                    $sendBackCalection = \Illuminate\Support\Facades\DB::table('comments')
                                                    ->select('comments.action', 'comments.comments')
                                                    ->where('comments.reference_number', '=', $status['reference_number'])
                                                    /*->where("comments.action", 'LIKE', DB::raw("'%Forward to CI%'"))*/
                                                    ->where(function ($query) use ($group_name) {
                                                        $query->where("comments.action", 'LIKE', '%' . 'Forward to ' . $group_name->name . '%')
                                                            ->orWhere("comments.action", 'LIKE', '%' . 'Send Back to ' . $group_name->name . '%');
                                                        })
                                                    ->orderBy('id', 'DESC')
                                                    ->first();

                                    $sendBackApp = ($sendBackCalection? ($sendBackCalection->comments? $sendBackCalection->comments: "") : '');
                                @endphp
                                <a href="#" data-toggle="tooltip" data-bs-placement="right" title="{{$sendBackApp}}" style="font-size: 20px" class="">
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
            <h4 class="s_b_t_head pb-4 mb-0">Complaint Send Back Tickets</h4>
            <table class="table table-responsive table-striped exampleDataTable">
                <thead class="table_head">
                <tr>
                    <th scope="col">Log Date</th>
                    <th scope="col">Complaint Request Type</th>
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
                                    <a href="{{route('CI.comaplaint-send-back-ticket',['issueId'=>$row->serviceName->id, 'refNum'=>$row->reference_number, 'viewMode'=>'web', 'CIToken' => $ci_token, 'request_type' => 'complaint'])}}" class="text-decoration-underline">
                                        {{$row->serviceName->name}}
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="{{route('CI.comaplaint-send-back-ticket',['issueId'=>$row->serviceName->id, 'refNum'=>$row->reference_number, 'viewMode'=>'web', 'CIToken' => $ci_token, 'request_type' => 'complaint'])}}" class="text-decoration-underline">
                                        {{$row->reference_number}}
                                    </a>
                                </td>

                                @php
                                    $sendBack = '';
                                    $sendBackCalectionWeb = \Illuminate\Support\Facades\DB::table('comments')
                                                    ->select('comments.action', 'comments.comments')
                                                    ->where('comments.reference_number', '=', $row['reference_number'])
                                                    /*->where("comments.action", 'LIKE', DB::raw("'%Forward to CI%'"))*/
                                                    ->where(function ($query) use ($group_name) {
                                                        $query->where("comments.action", 'LIKE', '%' . 'Forward to ' . $group_name->name . '%')
                                                            ->orWhere("comments.action", 'LIKE', '%' . 'Send Back to ' . $group_name->name . '%');
                                                    })
                                                    ->orderBy('id', 'DESC')
                                                    ->first();

                                    $sendBack = ($sendBackCalectionWeb? ($sendBackCalectionWeb->comments? $sendBackCalectionWeb->comments: "") : '');
                                @endphp
                                <td class="align-middle text-center">
                                    
				<a href="javascript:void(0)" data-toggle="tooltip" data-bs-placement="right" title="{{$sendBack}}" style="font-size: 20px" class="">
                                        <i class="far fa-comment-alt" style="color: #2157b5;"></i>
                                </a>
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
