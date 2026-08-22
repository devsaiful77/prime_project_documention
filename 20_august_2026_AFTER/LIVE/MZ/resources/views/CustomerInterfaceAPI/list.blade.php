@extends('layouts.admin')
@section('content')
    <legend class="text-center">{{ $title_for_layout }}</legend>
    <div class="table-responsive">
        <table class="commonDataTableAllAsc table table-bordered table-striped">
            <thead>
            <tr>
                <th class="vcenter text-center">Sl</th>
                <th class="vcenter text-center">Name</th>
                <th class="vcenter text-center">Endpoint</th>
                <th class="vcenter text-center">Status</th>
                <th class="vcenter text-left">
                    {{--                        <a href="{{ url('/ci_apis/create') }}" class="btn btn-primary gradient ajax_page btn-sm"--}}
                    {{--                           title="Add" escape="false"><i class="fa fa-plus"></i> Add--}}
                    {{--                        </a>--}}
                </th>
            </tr>
            </thead>
            <tbody style="word-break: break-all;">
            @php  $i = 1; @endphp
            @IF(!empty($tblData))
                @FOREACH($tblData as $data)
                    <tr>
                        <td class="vcenter text-center"> {{ $i ++ }} </td>
                        <td class="vcenter text-center"> {{ $data['product_type'] }} </td>
                        <td class="vcenter text-center"> {{ $data['endpoint'] }}</td>
                        <td class="vcenter text-center"> {{ $data['status_name'] }} </td>
                        <td class="vcenter actions text-left">
                            @if($data['status'] == 0)
                                <a href="{{ url('/ci_apis/status/'.encrypt($data['id']).'/1') }}"
                                   class="btn btn-info gradient btn-sm" title="Active" escape="false">
                                    <i class="fa fa-check"></i> Active
                                </a>
                            @else
                                <a href="{{ url('/ci_apis/edit/'.encrypt($data['id'])) }}"
                                   class="btn btn-success gradient ajax_page btn-sm" title="Edit" escape="false">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a href="{{ url('/ci_apis/status/'.encrypt($data['id']).'/0') }}"
                                   class="btn btn-danger gradient btn-sm" title="Inactive" escape="false">
                                    <i class="fa fa-times"></i> Inactive
                                </a>
                            @endif
                        </td>
                    </tr>
                @ENDFOREACH
            @ELSE
                <tr>
                    <td class="vcenter text-center" colspan="7"> <strong>Data Not Available</strong></td>
                </tr>
            @ENDIF
            </tbody>
        </table>
    </div>
@endsection
