@extends('layouts.admin')
@section('content')
    <div class="col-lg-12">
        <h3 class="text-center">Issue : {{ $issue->name }}</h3>
    </div>
    <div class="table-responsive">
        <table class="commonDataTableAllAsc table table-bordered table-striped table-hover">
            {{--<colgroup>
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="10%">
                <col width="10%">
            </colgroup>--}}
            <thead>
            <tr>
                <th class="vcenter text-center">Sl</th>
                <th class="vcenter text-center">Inquiry API</th>
                <th class="vcenter text-center">Search Index</th>
                <th class="vcenter text-center">Node Index</th>
                <th class="vcenter text-center">Node Value</th>
                <th class="vcenter text-center">Status</th>
                <th class="vcenter text-left">
                    <a href="{{ url('/issues/inquiry/config/add',$id) }}" class="btn btn-primary gradient ajax_page
                    btn-sm" title="Add" escape="false">
                        <i class="fa fa-plus"></i> Add
                    </a>
                </th>
            </tr>
            </thead>
            <tbody style="word-break: break-all;">
                @php
                    $i = 1;
                @endphp
                @forelse($tblData as $data)
                <tr>
                    <td class="vcenter text-center"> {{ $i++ }} </td>
                    <td class="vcenter text-center"> {{ $data->url }} </td>
                    <td class="vcenter text-center"> {{ $data->search_idx }} </td>
                    <td class="vcenter text-center"> {{ $data->node_idx }} </td>
                    <td class="vcenter text-center"> {{ $data->node_value }} </td>
                    <td class="vcenter text-center"> {{ $data->status_name }} </td>
                    <td class="vcenter actions text-left">
                        @if ($data->status == 1)
                            <a href="{{  url('/issues/inquiry/config/edit',$data->id) }}" class="btn btn-primary gradient btn-sm" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>
                            <a href="{{  url('/issues/inquiry/config/child',$data->id) }}" class="btn btn-info gradient btn-sm" title="Set Child Node" escape="false"> <i class="fa fa-sitemap"></i> Set Child Node</a>
                            <a href="{{  url('/issues/inquiry/config/status/'.$data->id.'/0') }}" class="btn btn-warning gradient btn-sm" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>
                        @else
                            <a href="{{  url('/issues/inquiry/config/status/'.$data->id.'/1') }}" class="btn btn-success gradient btn-sm" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr> <td class="vcenter text-center" colspan="6"> <strong>Data Not Available</strong></td> </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
