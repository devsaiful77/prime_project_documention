@extends('layouts.admin')
@section('content')
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <colgroup> <col width="25%"></col> <col width="25%"></col> <col width="25%"></col> <col width="25%"></col> </colgroup>
        <tr>
            <th class="vcenter text-center">User</th>
            <th class="vcenter text-center">Restricted Ip</th>
            <th class="vcenter text-left" style="text-align:left;">
                <a class="btn btn-success ajax_page gradient btn-sm" href="{{url('/restrictedIpsCreate')}}" title="Add Role" activeClassAttr="mngRole"><i class="fa fa-plus"></i> Add Restricted Ip</a>
            </th>
        </tr>
        @IF(!empty($restrictedIpsData))
            @FOREACH($restrictedIpsData as $data)
                <tr>
                    <td class="vcenter text-center">{{$data->user->name}}</td>
                    <td class="vcenter text-center">{{$data->ip}}</td>
                    <td class="vcenter text-left">
                        <a class="btn btn-info btn-sm" href="{{url('/restrictedIpEdit/'.$data->id)}}" activeClassAttr="mngRole"><i class="fa fa-pencil"></i> Edit Ip</a>
                        <?php
                        $deleteUrl = url('/restrictedIpsDelete/'.$data->id);
                        ?>
                        <button class="btn btn-danger" href="" onclick="customConfirm('Delete Ip Address?','Once Delete, You cant retrieve this Ip Address','red','{{$deleteUrl}}')" ><i class="fa fa-trash"></i> Delete Ip Address</button>
                    </td>
                </tr>
            @ENDFOREACH
        @ELSE
            <tr> <th class="vcenter text-center" colspan="4">No Log Users</th> </tr>
        @ENDIF
    </table>
</div>
@endsection
