@extends('layouts.admin')
@section('content')
<div class="table-responsive">
    <table class ="table table-bordered table-condensed table-striped table-hover">
        
        <thead>
            <tr>
                <th class="vcenter text-center">Unit Name</th>
                <th class="vcenter text-center">Description</th>
                <th class="vcenter text-center">Status</th>
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $key=>$data)
                <tr>
                    <td class="vcenter text-center"> {{ $data['name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['description'] }} </td>
                    <td class="vcenter text-center"> {{ $data['status_name'] }} </td>
                </tr>
            @ENDFOREACH
            @ELSE <tr> <td class="vcenter text-center" colspan="3"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF
        </tbody>
    </table>
</div>
@endsection
