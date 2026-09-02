@extends('layouts.admin')
@section('content')
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
        <colgroup>
            <col width="10%">
            <col width="20%">
            <col width="20%">
            <col width="20%">
            <col width="10%">
        </colgroup>
        <thead>
            <tr>
				<th class="vcenter text-center">Sl</th>
                <th class="vcenter text-center">Category Name</th>
                <th class="vcenter text-center">Issue From</th>
                <th class="vcenter text-center">Product Type</th>
                <th class="vcenter text-center">Status</th>
                @if($checker == false)
                    <th class="vcenter text-left">
                        <a href="{{ url('/Issues-category/addcategory') }}" class="btn btn-primary gradient ajax_page btn-sm" title="Add" escape=""> <i class="fa fa-plus"></i> Add</a>
                    </th>
                @endif 

            </tr>
        </thead>
        <tbody style="word-break: break-all;">
			<?php //echo '<pre>'; print_r($tblData);die;?>
            @IF(!empty($tblData))
            @FOREACH($tblData as $key=>$data)

                <tr>
					<td class="vcenter text-center"> {{ $key + 1 }} </td>
                    <td class="vcenter text-center"> {{ $data['name'] }} </td>
                    <td class="vcenter text-center"> {{ ($data['issues_from']=='wform')? "service-request" : $data['issues_from'] }} </td>
                    <td class="vcenter text-center"> {{ (!empty($data['product_type'])) ? $data['product_type']['name'] : 'N/A' }}</td>
                    <td class="vcenter text-center"> {{ ($data['status']=='1')? "Active" : "Inactive" }} </td>
                    @if($checker == false)
                    <td class="vcenter actions text-left">
                        @IF($data['status'] == '0')
                            <a href="{{ url('/Issues-category/statuscategory/'.$data['id'].'/1') }}" class="btn btn-info gradient btn-sm" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>
                        @ELSEIF($data['status'] == '1')
                            <?php
                                $editUrl = url('/Issues-category/editcategory/'.$data['id']);
                                if (!empty($searchDataForView)) {
                                    $editUrl .= '?'.http_build_query($searchDataForView);
                                }
                            ?>
                            
                                <a href="{{$editUrl}}" class="btn btn-success gradient ajax_page btn-sm" title="Edit" escape="false"  activeClassAttr="mngProductTypes"> <i class="fa fa-pencil"></i> Edit</a>
                                <a href="{{ url('/Issues-category/statuscategory/'.$data['id'].'/0') }}" class="btn btn-danger gradient btn-sm" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>
                           
                        @ENDIF
                    </td>
                    @endif 
                </tr>
            @ENDFOREACH
            @ELSE <tr> <td class="vcenter text-center" colspan="4"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF
            {{--@IF($dataObj->total() > $dataObj->perPage())
                <tr><td class="text-right" colspan="4">{{ $dataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ENDIF--}}

        </tbody>
    </table>
</div>
@endsection
