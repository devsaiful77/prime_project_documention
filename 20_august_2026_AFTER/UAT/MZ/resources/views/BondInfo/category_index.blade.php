@extends('layouts.admin')
@section('content')
<legend>{{$title_for_layout}}</legend>
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-striped">
        <thead>
            <tr>
                <th class="vcenter text-center">#</th>
                <th class="vcenter text-center">Category Name</th>
                <th class="vcenter text-center">Sub Category Name</th>
                <th class="vcenter text-center">Description</th>
                <th class="vcenter text-center">Status</th>
                @if($checker == false)
                    <th class="vcenter text-center">
                        <a class="btn btn-success ajax_page gradient btn-sm" href="{{url('bond-info/category/add')}}" title="Add Category"><i class="fa fa-plus"></i> Category</a>
                        <a class="btn btn-primary ajax_page gradient btn-sm" href="{{url('bond-info/sub-category/add')}}" title="Add Sub Category"><i class="fa fa-plus"></i> Sub Category</a>
                    </th>
                @endif
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $key=>$data)
                <tr>
                    <td class="vcenter text-center"> {{ $key + 1 }} </td>
                    <td class="vcenter text-center"> 
                        {{ ($data['parent_id'] == 0) ? $data['name'] : $data['category_name'] }} 

                    </td>
                    <td class="vcenter text-center">
                        {{ ($data['parent_id'] != 0) ? $data['name'] : '-' }} 
                    </td>
                    <td class="vcenter text-center"> {{ $data['description'] }} </td>
                    <td class="vcenter text-center"> {{ $data['status_name'] }} </td>
                    @if($checker == false)
                    <td class="vcenter text-center"> 
                         @IF($data['status'] == '0')
                            <a href="{{ url('/bond-info/category/status/'.$data['id']) }}" class="btn btn-info gradient btn-sm" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>
                        @ELSEIF($data['status'] == '1')
                            <?php
                                $editBtnLabel = '';
                                $editBtnCol = '';
                                if ($data['parent_id'] == 0) {
                                    $editUrl = url('/bond-info/category/edit/'.$data['id']);
                                    $editBtnLabel = 'Edit Category';
                                    $editBtnCol = 'success';
                                } else {
                                    $editUrl = url('/bond-info/sub-category/edit/'.$data['id']);
                                    $editBtnLabel = 'Edit Sub-Category';
                                    $editBtnCol = 'info';
                                }
                                if (!empty($searchDataForView)) {
                                    $editUrl .= '?'.http_build_query($searchDataForView);
                                }
                            ?>
                            
                                <a href="{{$editUrl}}" class="btn btn-{{$editBtnCol}} gradient ajax_page btn-sm" title="{{$editBtnLabel}}" escape="false"> <i class="fa fa-pencil"></i> {{$editBtnLabel}}</a>

                                <a href="{{ url('/bond-info/category/status/'.$data['id']) }}" class="btn btn-danger gradient btn-sm" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>
                            
                        @ENDIF
                    </td>
                    @endif 
                </tr>
            @ENDFOREACH
            @ENDIF
        </tbody>
    </table>
</div>
@endsection
