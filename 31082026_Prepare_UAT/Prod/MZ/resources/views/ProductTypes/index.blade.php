@extends('layouts.admin')
@section('content')
<div class="table-responsive">
    <table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
        <colgroup>
            <col width="25%">
            <col width="25%">
            <col width="25%">
            @if ($checker == false)
            <col width="25%">
            @endif
        </colgroup>
        <thead>
            <tr>
                <th class="vcenter text-center">Products Name</th>
                <th class="vcenter text-center">Description</th>
                <th class="vcenter text-center">Status</th>
                @if ($checker == false)
				<th class="vcenter text-left">Action</th>
                @endif
                {{-- <th class="vcenter text-left"> <a href="{{ url('/ProductTypes/approve',5) }}" class="btn btn-primary gradient ajax_page" title="Approve" escape="false"> <i class="fa fa-plus"></i> Approve</a> </th> --}}

                {{--<th class="vcenter text-left"> <a href="{{ url('/ProductTypes/add') }}" class="btn btn-primary gradient ajax_page btn-sm" title="Add" escape=""> <i class="fa fa-plus"></i> Add</a> </th>--}}
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @IF(!empty($tblData))
            @FOREACH($tblData as $key=>$data)
                <tr>
                    <td class="vcenter text-center"> {{ $data['name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['description'] }} </td>
                    <td class="vcenter text-center"> {{ $data['status_name'] }} </td>
                    @if ($checker == false)
                    <td class="vcenter actions text-left">
                        @IF($data['status'] == '0')
						{{--<a href="{{ url('/ProductTypes/status/'.$data['id'].'/1') }}" class="btn btn-info gradient btn-sm" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>--}}
                        @ELSEIF($data['status'] == '1')
                            <?php
                                $editUrl = url('/ProductTypes/edit/'.$data['id']);
                                if (!empty($searchDataForView)) {
                                    $editUrl .= '?'.http_build_query($searchDataForView);
                                }
                            ?>
                            
                                <a href="{{$editUrl}}" class="btn btn-success gradient ajax_page btn-sm" title="Edit" escape="false"  activeClassAttr="mngProductTypes"> <i class="fa fa-pencil"></i> Edit</a>
                            
								{{--<a href="{{ url('/ProductTypes/status/'.$data['id'].'/0') }}" class="btn btn-danger gradient btn-sm" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>--}}
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
