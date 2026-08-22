@extends('layouts.admin')

@section('content')
<div class="col-md-12 text-right" style="padding-top: 5px; padding-bottom: 5px; "> <a href="{{ url('/Permissions/add') }}" class="btn btn-primary ajax_page gradient" title="Add"  activeClassAttr="mngPerm"> <i class="fa fa-plus"></i> Add</a></div>
<div class="table-responsive">    
    <table cellpadding="0" cellspacing="0" class ="table table-bordered table-primary mb30">
        <colgroup>
            <col width="20%">
            <col width="25%">
            <col width="25%">
            <col width="30%">
        </colgroup>
        <thead>
            
            <tr>
                <th class="vcenter text-center">Name</th>
                <th class="vcenter text-center">Controller Name</th>
                <th class="vcenter text-center">Display Name</th>
                <th class="vcenter text-center">Description </th>
            </tr>

        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $data)
                <tr>
                    <td class="vcenter text-center"> {{ $data['name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['controller_name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['display_name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['description'] }} </td>
                </tr>
            @ENDFOREACH
            @ELSE <tr> <td class="vcenter text-center" colspan="4"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF
            {{--<tr><td class="text-right" colspan="4">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>--}}
        </tbody>
    </table>
</div>
@endsection