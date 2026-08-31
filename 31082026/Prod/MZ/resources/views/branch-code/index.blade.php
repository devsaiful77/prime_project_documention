@extends('layouts.admin')
@section('content')
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
        <colgroup>
            <col width="10%">
            <col width="15%">
            <col width="10%">
            @if($checker == false)
            <col width="15%">
            @endif
            <col width="15%">
            <col width="15%">
            <col width="15%">
        </colgroup>
        <thead>
            <tr>
                <th class="vcenter text-center">Company Code</th>
                <th class="vcenter text-center">Branch Name</th>
                <th class="vcenter text-center">Branch Code</th>
                <th class="vcenter text-center">Region</th>
                <th class="vcenter text-center">Mnemonic</th>
                <th class="vcenter text-center">Status</th>
                @if($checker == false)
                <th class="vcenter text-left">
                    <a href="{{ url('/branchcode/add') }}" class="btn btn-primary gradient ajax_page" title="Add" escape="false"> <i class="fa fa-plus"></i> Add</a>
                </th>
                @endif
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $data)
                <tr>
                    <td class="vcenter text-center"> {{ $data['company_code'] }} </td>
                    <td class="vcenter text-center"> {{ $data['branch_name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['br_code'] }} </td>
                    <td class="vcenter text-center"> {{ $data['region'] }} </td>
                    <td class="vcenter text-center"> {{ $data['mnemonic'] }} </td>
                    <td class="vcenter text-center"> {{ $data['status_name'] }} </td>
                    @if($checker == false)
                    <td class="vcenter actions text-left">
                        @IF($data['status'] == '0')
                            <a href="{{ url('/branchcode/status/'.$data['id'].'/1') }}" class="btn btn-info gradient" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>
                        @ELSEIF($data['status'] == '1')

                        <?php
                            $editUrl = url('/branchcode/edit/'.$data['id']);
                            if (!empty($searchDataForView)) {
                                $editUrl .= '?'.http_build_query($searchDataForView);
                            }
                        ?>

                                <a href="{{$editUrl}}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>
                                <a href="{{ url('/branchcode/status/'.$data['id'].'/0') }}" class="btn btn-danger gradient" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>

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
