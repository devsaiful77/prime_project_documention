<?php
?>
@extends('layouts.admin')
@section('content')
    <div class="table-responsive">
        <table class ="commonDataTableAllAsc table table-bordered table-striped table-hoverd">
            <colgroup>
                <col width="25%">
                <col width="25%">
                <col width="25%">
                <col width="15%">
                @if ($checker == false)
                <col width="10%">
                @endif
            </colgroup>
            <thead>
            <tr>
                <th class="vcenter text-center">Group Name</th>
                <th class="vcenter text-center">Department Name</th>
                <th class="vcenter text-center">Description</th>
                <th class="vcenter text-center">Group Level</th>
                @if ($checker == false)
                <th class="vcenter text-left"> <a href="{{ url('/group-info/create') }}" class="btn btn-primary gradient ajax_page btn-sm" title="Add" escape="false"> <i class="fa fa-plus"></i> Add</a> </th>
                @endif
            </tr>
            </thead>
            <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
                @FOREACH($tblData as $data)
                    <tr>
                        <td class="vcenter text-center"> {{ $data['name'] }} </td>
                        <td class="vcenter text-center"> {{ $data->dept->name }}</td>

                        <td class="vcenter text-center"> {{ $data['description'] }} </td>
                        <td>@if($data['group_level_id']==1) {{ 'Touch Point' }}@else{{ 'N/A' }}@endif</td>
                        {{--<td class="vcenter text-center"> {{ $data['status_name'] }} </td>--}}
                        @if ($checker == false)
                        <td class="vcenter actions text-left">
                            @IF($data['is_active'] == '0')
                                <a href="{{ url('group-info/status/'.$data['id'].'/1') }}" class="btn btn-info gradient btn-sm" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>
                            @ELSEIF($data['is_active'] == '1')

                                <?php
                                $editUrl = url('/group-info/edit/'.encrypt($data['id']));
                                if (!empty($searchDataForView)) {
                                    $editUrl .= '?'.http_build_query($searchDataForView);
                                }
                                ?>
                                
                                <a href="{{$editUrl}}" class="btn btn-success gradient ajax_page btn-sm" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>
                                <a href="{{ url('group-info/status/'.$data['id'].'/0') }}" class="btn btn-danger gradient btn-sm" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>
                                
                            @ENDIF
                        </td>
                        @endif
                    </tr>
                @ENDFOREACH
            @ELSE <tr> <td class="vcenter text-center" colspan="4"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF
            </tbody>
        </table>
    </div>
@endsection
