@extends('layouts.admin')
@section('content')
<div class="table-responsive">
    <table class="table table-bordered table-condensed">
        <colgroup> 
            <col width="25%"></col> 
            <col width="25%"></col> 
            <col width="25%"></col> 
            <col width="25%"></col> 
        </colgroup>
        <tr>
            <th class="vcenter text-center">Name</th>
            <th class="vcenter text-center">Display Name</th>
            <th class="vcenter text-center">Description</th>
            <th class="vcenter text-left" style="text-align:left;">
                <a class="btn btn-success ajax_page gradient btn-sm" href="{{url('/rolesCreate')}}" title="Add Role" activeClassAttr="mngRole"><i class="fa fa-plus"></i> Add Role</a>
            </th>
        </tr>
        @IF(!empty($rolesData))
            @FOREACH($rolesData as $role)
                <tr>
                    <td class="vcenter text-center">{{$role['name']}}</td>
                    <td class="vcenter text-center">{{$role['display_name']}}</td>
                    <td class="vcenter text-center">{{$role['description']}}</td>
                    <td class="vcenter text-left">
                        <a class="btn btn-info btn-sm" href="{{url('/roleEdit/'.$role['id'])}}" activeClassAttr="mngRole"><i class="fa fa-pencil"></i> Edit Role</a>
                        <?php
                        //$deleteUrl = url('/roleDelete/'.$role['id']);
                        ?>
                        {{--<button class="btn btn-danger btn-sm" href="" onclick="customConfirm('Delete Role?','Once Delete, You cant retrieve this role','red','{{$deleteUrl}}')" ><i class="fa fa-trash"></i> Delete Role</button>--}}
                    </td>
                </tr>
            @ENDFOREACH
        @ELSE
            <tr> <th class="vcenter text-center" colspan="4">No roles</th> </tr>
        @ENDIF
    </table>
</div>

<script>
$(".deleteBtn").on("click", function(e){
    e.preventDefault();
    var formThis = $(this);

    return $.confirm({
            title: 'Delete!',
            content: 'Do you want to Delete this Role!',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Delete',
                    btnClass: 'btn-red',
                    action: function(){
                        formThis.parent('form').submit();
                    }
                },
                Cancel: function () {
                }
            }
        });
});
</script>

@endsection
