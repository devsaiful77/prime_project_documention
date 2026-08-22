
@extends('BBL_CI.layouts.master')
@push('app-title')
    Ticket Status
@endpush
@section('content')
    <div class="bg-wrapper">
        <div class="container-fluid custom-layout">
        <div class="row">
            <div class="d-xsm-block d-sm-block d-md-none d-lg-none d-xl-none d-xxl-none">
                <div class="s_b_t_bottom_hr">
                    <p class="s_b_t_head pb-3 mb-0">Complaint Ticket Status</p>
                </div>
                <table class="table exampleDataTable pr-0 mr-0">
                    <thead class="table_head">
                    <tr>
                        <th scope="col" style="font-size: 12px">Date</th>
                        <th scope="col" style="font-size: 12px">Complaint</th>
                        <th scope="col" style="font-size: 12px">Status</th>
                        <th scope="col" class="text-center" style="font-size: 12px;width: 5%">Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($ticketStatus as $status)
                        <tr>
                            <td class="s_b_t_date p-0 text-center" style="width: 20%;">
                                <span class="s_b_t_day">{{date('d', strtotime($status->time_and_ext))}}</span>
                                <span class="s_b_t_month">{{date('M', strtotime($status->time_and_ext))}}  {{date('Y', strtotime($status->time_and_ext))}}</span>
                                <small class="s_b_t_time">{{date('h: i A', strtotime($status->time_and_ext))}}</small>
                            </td>
                            <td class="align-middle" style="width: 60%">
                                <span>
                                    {{$status->serviceName->name}}
                                </span>
                                <br>
                                <span>
                                    @if($status->form_status == 7)
                                        <a href="{{ route('CI.complaint-send-back-status', ['CIToken' => $ci_token, 'request_type' => 'complaint']) }}"> {{$status->reference_number}}</a>
                                    @else
                                        {{$status->reference_number}}
                                    @endif
                                </span>
                            </td>

                            @php
                                $sendBackTimeApp = '';
                                $ResolveTimeApp = '';

                                $sendBackCalection = \Illuminate\Support\Facades\DB::table('comments')
                                                ->select('comments.action', 'comments.time')
                                                ->where('comments.reference_number', '=', $status['reference_number'])
                                                /*->where("comments.action", 'LIKE', DB::raw("'%Forward to CI%'"))*/
                                                ->where(function ($query) use ($group_name) {
                                                        $query->where("comments.action", 'LIKE', '%' . 'Forward to ' . $group_name->name . '%')
                                                            ->orWhere("comments.action", 'LIKE', '%' . 'Send Back to ' . $group_name->name . '%');
                                                    })
                                                ->orderBy('id', 'DESC')
                                                ->first();

                                $resolveCalection = \Illuminate\Support\Facades\DB::table('comments')
                                                ->select('comments.action', 'comments.time', 'comments.comments')
                                                ->where('comments.reference_number', '=', $status['reference_number'])
                                                ->where("comments.action", 'LIKE', DB::raw("'%Close%'"))
                                                ->orderBy('id', 'DESC')
                                                ->first();

                                $sendBackTimeApp = ($sendBackCalection? ($sendBackCalection->time? $sendBackCalection->time: "") : '');
                                $ResolveTimeApp = ($resolveCalection? ($resolveCalection->time? $resolveCalection->time: "") : '');

                            @endphp

                            <td class="align-middle s_b_t_date text-center p-0 m-0">
                                @if( $status->form_status == 11 )
                                    Resolved <br>
                                    <span class="s_b_t_month">{{date('d M Y', ((int)$ResolveTimeApp)) }}</span>
                                    <small class="s_b_t_time">{{date('h: i: A', (int)$ResolveTimeApp) }}</small>
                                @elseif( $status->form_status == 7 )
                                    Send To Customer <br>
                                    <span class="s_b_t_month">{{date('d M Y', (int)$sendBackTimeApp) }}</span>
                                    <small class="s_b_t_time">{{ date('h: i: A',  (int)$sendBackTimeApp) }}</small>
                                @else
                                    In Progress
                                @endif
                            </td>
                            <td class="align-middle text-center" style="width: 5%">
                                @if( $status->form_status == 11 )
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-bs-placement="right" title="{{ $resolveCalection->comments ?? '' }}" style="font-size: 20px" class="dddd">
                                        <i class="far fa-comment-alt" style="color: #2157b5;"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No Data Found!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $ticketStatus->appends(request()->except('page'))->links() }}
            </div>
            <div class="d-xsm-none d-sm-none d-md-block d-lg-block d-xl-block d-xxl-block web_a_verify">
                <h4 class="s_b_t_head pb-4 mb-0">Complaint Ticket Status</h4>
                <table class="table table-responsive table-striped ticketStatusTable">
                    <thead class="table_head">
                    <tr>
                        <th scope="col">Log Date</th>
                        <th scope="col">Complaint Type</th>
                        <th scope="col">Ticket Number</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Closing Remark</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($ticketStatus as $status)
                        <tr>
                            <td class="align-middle">{{date('d M Y', strtotime($status->time_and_ext))}}
                                <br>
                                <small>{{date('h:i A', strtotime($status->time_and_ext))}}</small>
                            </td>
                            <td class="align-middle">{{$status->serviceName->name}}</td>
                            <td class="align-middle">
                                @if($status->form_status == 7)
                                    <a href="{{ route('CI.complaint-send-back-status', ['CIToken' => $ci_token]) }}" class="text-decoration-underline">{{$status->reference_number}}</a>
                                @else
                                    {{$status->reference_number}}
                                @endif
                            </td>

                            @php
                                $sendBackTime = '';
                                $ResolveTime = '';

                                $sendBackCalection = \Illuminate\Support\Facades\DB::table('comments')
                                                ->select('comments.action', 'comments.time')
                                                ->where('comments.reference_number', '=', $status['reference_number'])
                                                /*->where("comments.action", 'LIKE', DB::raw("'%Forward to CI%'"))*/
                                                ->where(function ($query) use ($group_name) {
                                                        $query->where("comments.action", 'LIKE', '%' . 'Forward to ' . $group_name->name . '%')
                                                            ->orWhere("comments.action", 'LIKE', '%' . 'Send Back to ' . $group_name->name . '%');
                                                    })
                                                ->orderBy('id', 'DESC')
                                                ->first();

                                $resolveCalection = \Illuminate\Support\Facades\DB::table('comments')
                                                ->select('comments.action', 'comments.time', 'comments.comments')
                                                ->where('comments.reference_number', '=', $status['reference_number'])
                                                ->where("comments.action", 'LIKE', DB::raw("'%Close%'"))
                                                ->orderBy('id', 'DESC')
                                                ->first();

                                $sendBackTime = ($sendBackCalection? ($sendBackCalection->time? $sendBackCalection->time: "") : '');
                                $ResolveTime = ($resolveCalection? ($resolveCalection->time? $resolveCalection->time: "") : '');
                            @endphp

                            <td class="align-middle">
                                @if( $status->form_status == 11 )
                                    Resolved <br>
                                    <span class="s_b_t_month">{{date('d M Y', (int)$ResolveTime) }}</span>
                                    <small>{{date('h: i: A', (int)$ResolveTime) }}</small>
                                @elseif( $status->form_status == 7 )
                                    Send To Customer <br>
                                    <span class="s_b_t_month">{{date('d M Y', (int)$sendBackTime) }}</span>
                                    <small>{{ date('h: i: A',  (int)$sendBackTime) }}</small>
                                @else
                                    In Progress
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @if( $status->form_status == 11 )
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-bs-placement="right" title="{{ $resolveCalection->comments ?? '' }}" style="font-size: 20px" class="">
                                        <i class="far fa-comment-alt" style="color: #2157b5;"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Data Found!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $ticketStatus->appends(request()->except('page'))->links() }}
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
