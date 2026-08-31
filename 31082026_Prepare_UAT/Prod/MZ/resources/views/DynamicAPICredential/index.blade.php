@extends('layouts.admin')
@section('content')
    <legend class="text-center">{{ $title_for_layout }}</legend>
    <div class="table-responsive">
        <table class="commonDataTableAllAsc table table-bordered table-striped">
            <colgroup>
                <col width="5%">
                <col width="25%">
                <col width="25%">
                <col width="25%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="vcenter text-center">Sl</th>
                    <th class="vcenter text-center">API</th>
                    <th class="vcenter text-center">Username</th>
                    <th class="vcenter text-center">Password</th>
                    <th class="vcenter text-left">
                        <a href="{{ url('/DynamicAPICredential/add') }}" class="btn btn-primary gradient ajax_page btn-sm"
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
                    <td class="vcenter text-center"> {{ $data['username'] }}</td>

                    <td class="vcenter text-center"> {{ decrypt($data['password']) }} </td>
                    <td class="vcenter actions text-left">
                        <a href="{{ url('/DynamicAPICredential/edit/'.encrypt($data['id'])) }}"
                           class="btn btn-success gradient ajax_page btn-sm" title="Edit" escape="false">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                    </td>
                </tr>
            @ENDFOREACH
            @ELSE
                <tr>
                    <td class="vcenter text-center" colspan="5"> <strong>Data Not Available</strong></td>
                </tr>
            @ENDIF
            </tbody>
        </table>
    </div>
@endsection
