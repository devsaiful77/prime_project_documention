@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ url('workflow/checker') }}" class="btn btn-primary btn-sm float-right m-1">Back</a>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Flow Type</th>
                <th scope="col">Issue</th>
                <th>Issue Type</th>
                <th scope="col">SMS/Email Log</th>
                <th scope="col">SMS/Email Execute</th>
                {{--<th scope="col">SMS/Email Send Back</th>
                <th scope="col">SMS/Email Can't Reach to customer</th>--}}
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ ($row->flow_type == 'regular') ? 'auto':$row->flow_type }}</td>
                <td>{{ $row->issue->name}}</td>
                <td>@if($row->issue->issues_from==\App\Enum\IssueTypeEnum::SERVICE_REQUEST)
                        {{ 'Service Request'}}
                    @elseif($row->issue->issues_from==\App\Enum\IssueTypeEnum::COMPLAINT)
                        {{ 'Complaint' }}
                    @else
                        {{ 'N/A' }}
                    @endif
                </td>
                <td>@if($row->log==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                <td>@if($row->execute==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                {{--<td>@if($row->send_back==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                <td>@if($row->cant_reach_to_customer==1) {{'Yes'}} @else {{ 'No' }} @endif</td>--}}
            </tr>
        </tbody>
    </table>

    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" href="#maker" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> Maker</a></li>
        <li class="nav-item"><a class="nav-link" href="#checker" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> Checker</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane in active" id="maker">
            <table class="table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th scope="col">Touch</th>
                        <th scope="col">Hold</th>
                        <th scope="col">SLA</th>
                        <th scope="col">Attachment</th>
                        <th scope="col">Attachment Quantity </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    @foreach($rows as $r)
                        <td>{{ $r->group_info->name}}</td>
                        <td>@if($r->touch_maker==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->hold_maker==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->sla_maker) {{$r->sla_maker}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->attach_maker==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->attach_maker_item) {{$r->attach_maker_item}} @else {{ '0' }} @endif</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="checker">
            <table class="table">
                <thead>
                <tr>
                    <th>Group</th>
                    <th scope="col">Touch</th>
                    <th scope="col">Hold</th>
                    <th scope="col">SLA</th>
                    <th scope="col">Attachment</th>
                    <th scope="col">Attachment Quantity </th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                    @foreach($rows as $r)
                        <td>{{ $r->group_info->name}}</td>
                        <td>@if($r->touch_checker==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->hold_checker==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->sla_checker) {{$r->sla_checker}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->attach_checker==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                        <td>@if($r->attach_checker_item) {{$r->attach_checker_item}} @else {{ '0' }} @endif</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
