<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 3/19/2020.
 */
?>
@extends('layouts.admin')
@section('content')
    <div class="table-responsive">
        <table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
            <colgroup>
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
            </colgroup>
            <thead>
            <tr>
                <th class="vcenter text-center">Unit</th>
                <th class="vcenter text-center">Subgroup Name</th>
                <th class="vcenter text-center">Group Name</th>
                <th class="vcenter text-center">Department</th>
                <th class="vcenter text-center">Status</th>
                <th class="vcenter text-left"> <a href="{{ url('/unit-assign/create') }}" class="btn btn-primary gradient ajax_page" title="Add" escape="false"> <i class="fa fa-plus"></i> Add</a> </th>
            </tr>
            </thead>
            <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
                @FOREACH($tblData as $data)

                    <tr>
                        <td class="vcenter text-center"> {{ $data->unit->name }} </td>
                        <td class="vcenter text-center"> {{ $data->subgroupInfo->name }} </td>
                        <td class="vcenter text-center"> {{ $data->groupInfo->name}} </td>
                        <td class="vcenter text-center"> {{ $data->dept->name }} </td>

                        <td class="vcenter text-center"> @if($data['is_active']==1){{ 'Active' }}@else {{ 'Inactive' }} @endif </td>
                        <td class="vcenter actions text-left">
                            @IF($data['is_active'] == '0')
                                <a href="{{ url('/unit-assign/activate/'.encrypt($data['id']).'/1') }}" class="btn btn-info gradient" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>
                            @ELSEIF($data['is_active'] == '1')

                                <?php
                                $editUrl = url('/unit-assign/edit/'.encrypt($data['id']));
                                if (!empty($searchDataForView)) {
                                    $editUrl .= '?'.http_build_query($searchDataForView);
                                }
                                ?>
                                {{--<a href="{{$editUrl}}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>--}}
                                <a href="{{ url('/unit-assign/activate/'.encrypt($data['id']).'/0') }}" class="btn btn-danger gradient" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>
                            @ENDIF
                        </td>
                    </tr>
                @ENDFOREACH
            @ELSE <tr> <td class="vcenter text-center" colspan="4"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF
            </tbody>
        </table>
    </div>
@endsection
