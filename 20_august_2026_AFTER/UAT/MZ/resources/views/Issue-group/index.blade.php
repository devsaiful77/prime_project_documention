@extends('layouts.admin')
@section('content')
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
        <colgroup>
            <col width="10%">
            <col width="30%">
            <col width="20%">
        </colgroup>
        <thead>
            <tr>
                <th class="vcenter text-center">SL</th>
                <th class="vcenter text-center">Issue Name</th>
                <th class="vcenter text-center">Action
                    @if($checker == false)
                        <a href="{{ url('/issue/group/add') }}" class="btn btn-primary gradient ajax_page" title="Add" escape="false"> <i class="fa fa-plus"></i> Add</a>
                    @endif
                </th>
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
                @FOREACH($tblData as $data)
                    <tr>
                        <td class="vcenter text-center"> {{ $loop->iteration }} </td>
                        <td class="vcenter text-center"> {{ $data['issue_name']['name'] ?? '' }}</td>
                        <td class="vcenter actions text-left">
                            <a href="{{ url('/issue/group/view/'.$data['id'])}}" class="btn btn-info gradient ajax_page" title="View" escape="false"> <i class="fa fa-eyes"></i> View</a>
                            <a href="{{ url('/issue/group/edit/'.$data['id'])}}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>
                        </td>
                    </tr>
                @ENDFOREACH
            @ENDIF
        </tbody>
    </table>
</div>
@endsection
