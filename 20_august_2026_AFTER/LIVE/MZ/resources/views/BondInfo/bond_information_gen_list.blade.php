@extends('layouts.admin')
@section('content')
<legend>{{$title_for_layout}}</legend>
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-condensed">
        <thead>
            <tr>
                <th class="vcenter text-center">#</th>
                <th class="vcenter text-center">Category Name</th>
                <th class="vcenter text-center">Sub Category Name</th>
                <th class="vcenter text-center">Title</th>
                <th class="vcenter text-center">Description</th>
                <th class="vcenter text-center">Files</th>
                <!-- <th class="vcenter text-center">Status</th> -->
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $key=>$data)
                <tr>
                    <td class="vcenter text-center"> {{ $key + 1 }} </td>
                    <td class="vcenter text-center"> {{ $data['category_name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['subcategory_name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['title'] }} </td>
                    <td class="vcenter text-center"> {{ $data['description'] }} </td>
                    <td class="vcenter text-center"> 
                        <a target="_blank" href="{{ URL::asset('public/attachments/bond_information/'.$data['file_name']) }}" > {{ $data['file_name'] }}  </a>
                    </td>
                    <!-- <td class="vcenter text-center"> {{ $data['status_name'] }} </td> -->
                </tr>
            @ENDFOREACH
            @ENDIF
        </tbody>
    </table>
</div>
@endsection
