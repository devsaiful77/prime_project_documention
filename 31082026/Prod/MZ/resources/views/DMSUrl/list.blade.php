@extends('layouts.admin')
@section('content')
    <legend class="text-center">DMS Url List</legend>
    <div class="table-responsive">
        <table class="commonDataTableAllAsc table table-bordered table-striped">
            <colgroup>
                <col width="3%">
                <col width="17%">
                <col width="20%">
                <col width="40%">
                <col width="8%">
                <col width="12%">
            </colgroup>
            <thead>
                <tr>
                    <th class="vcenter text-center">Sl</th>
                    <th class="vcenter text-center">Name</th>
                    <th class="vcenter text-center">Url</th>
                    <th class="vcenter text-center">Request</th>
                    <th class="vcenter text-center">Status</th>
                    <th class="vcenter text-left">
                        <a href="{{ url('/DMSAPIServices/DMSUrl/add') }}" class="btn btn-primary gradient ajax_page btn-sm"
                           title="Add" escape="false"><i class="fa fa-plus"></i> Add
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody style="word-break: break-all;">
            @php  $i = 1;  @endphp
            @IF(!empty($tblData))
            @FOREACH($tblData as $data)
                <tr>
                    <td class="vcenter text-center"> {{ $i ++ }} </td>
                    <td class="vcenter text-center"> {{ $data['name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['url'] }}</td>
                    <td class="vcenter text-center"> <div style="width: 600px; overflow : hidden; text-overflow:
                    ellipsis; white-space: nowrap;"> {{ $data['request'] }} </div></td>
                    <td class="vcenter text-center"> {{ $data['status_name'] }} </td>
                    <td class="vcenter actions text-left">
                        @if($data['status'] == 0)
                            <a href="{{ url('/DMSAPIServices/DMSUrl/status/'.encrypt($data['id']).'/1') }}"
                               class="btn btn-info gradient btn-sm" title="Active" escape="false">
                                <i class="fa fa-check"></i> Active
                            </a>
                        @else
                            <a href="{{ url('/DMSAPIServices/DMSUrl/edit/'.encrypt($data['id'])) }}"
                               class="btn btn-success gradient ajax_page btn-sm" title="Edit" escape="false">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <a href="{{ url('/DMSAPIServices/DMSUrl/status/'.encrypt($data['id']).'/0') }}"
                               class="btn btn-danger gradient btn-sm" title="Inactive" escape="false">
                                <i class="fa fa-times"></i> Inactive
                            </a>
                        @endif
                    </td>
                </tr>
            @ENDFOREACH
            @ELSE
                <tr>
                    <td class="vcenter text-center" colspan="6"> <strong>Data Not Available</strong></td>
                </tr>
            @ENDIF
            </tbody>
        </table>
    </div>
@endsection
