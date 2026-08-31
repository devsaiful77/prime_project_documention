@extends('layouts.admin')
@section('content')
    {{--
    @if(session('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif
    --}}

<legend>Workflow List</legend>
<div class="table-responsive">
<table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
    <colgroup>
        <col width="5%">
        <col width="8%">
        <col width="22%">
        <col width="10%">
        <col width="12%">
        <col width="12%">
        <col width="12%">
        <col width="15%">
    </colgroup>
    <thead>
        <tr>
          <th class="vcenter text-center">#</th>
          <th class="vcenter text-center">Flow Type</th>
          <th class="vcenter text-center">Issue</th>
          <th class="vcenter text-center">Issue Type</th>
          <th class="vcenter text-center">SMS/Email Log</th>
          <th class="vcenter text-center">SMS/Email Execute</th>
          <th class="vcenter text-center">Comp. SLA (Min.)</th>
          <th class="vcenter text-center">
              @hasanyrole('superadmin|CEX_ADMIN')
                <a href="{{ url('workflow/create') }}" class="btn btn-primary btn-sm">Add New</a>
              @endhasanyrole
          </th>
        </tr>
        </thead>
        <tbody style="word-break: break-all;">

        @if(!empty($rows))
        @foreach($rows as $key=>$row)
            <tr>
                <td class="vcenter text-center">{{ ++$key }}</td>
                <td class="vcenter text-center">{{ ($row->flow_type == 'regular') ? 'auto':$row->flow_type }}</td>
                <td class="vcenter text-center">{{ $row->issue->name}}</td>
                <td class="vcenter text-center">@if($row->issue->issues_from==\App\Enum\IssueTypeEnum::SERVICE_REQUEST)
                        {{ 'Service Request'}}
                    @elseif($row->issue->issues_from==\App\Enum\IssueTypeEnum::COMPLAINT)
                        {{ 'Complaint' }}
                     @else
                        {{ 'N/A' }}
                    @endif
                </td>

                <td class="vcenter text-center">@if($row->log==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                <td class="vcenter text-center">@if($row->execute==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                <td class="vcenter text-center">{{$row->complain_sla_time}}</td>
                <td class="vcenter text-center">
                    <a href="{{ url('workflow/show/'.$row->issue_workflow_id) }}" class="btn btn-success btn-sm">View</a>
                    @hasanyrole('superadmin|admin|CEX_ADMIN')
                      <a href="{{ url('workflow/edit/'.$row->issue_workflow_id) }}" class="btn btn-info btn-sm">Edit</a>
                      <a href="#{{--{{ url('workflow/destroy/'.$row->issue_workflow_id) }}--}}" data-id="{{ url('workflow/destroy/'.$row->issue_workflow_id) }}" class="btn btn-danger btn-del btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-delete">Delete</a>
                    @endhasanyrole
                </td>

            </tr>
        @endforeach
        @else
        <tr> <td class="vcenter text-center" colspan="8"> <strong>Data Not Available</strong></td> </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection

@section('extrajssection')
<script>
$(".btn-del").on("click", function () {

    var action = $(this).attr('data-id');
    $(".confirm").attr('href', action);

});
</script>
@endsection
