<!-- Production Server -->

@extends('layouts.admin')
@section('content')
<div class="curved-inner-pro"> <div class="curved-ctn"> <h2>{{$title_for_layout}}</h2> </div> </div>
<form class="d-flex align-items-center">
    <div class="form-group me-2">
        <input type="text" name="name" class="form-control" placeholder="Search by Name" value="{{ $searchDataForView['name'] }}">
    </div>
    <div class="form-group me-2">
        {{ Form::select('issues_from', [null=>'Issues Form','wform'=>'Service Request','complaint'=>'Complaint'], (!empty($searchDataForView['issues_from'])) ? $searchDataForView['issues_from'] : "", ['class'=>'form-control']) }}
    </div>

    <button type="submit" class="btn btn-success me-2" style="margin-bottom: 0px;"><i class="fa fa-search"></i> <strong>Find</strong></button>

    <?php $newWformUrl = url('/Supports/NewDummyWForm') ?>
    <?php $newComplaintUrl = url('/Supports/NewDummyComplaint') ?>

    <a target="blank" href="{{$newWformUrl}}" class="btn btn-primary newsrcbtn me-2"><i class="fa fa-pagelines"></i> <strong>New Service Request Test</strong></a>
    <a target="blank" href="{{$newComplaintUrl}}" class="btn btn-info newsrcbtn"><i class="fa fa-paper-plane"></i> <strong>New Complaint Test</strong></a>
</form>

<div class="clearfix">&nbsp;</div>
<div class="table-responsive">
<table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
    <colgroup>
        <col width="5%">
        <col width="21%">
        <col width="8%">
        <col width="8%">
        <col width="20%">
        <col width="8%">
        {{-- <col width="8%"> --}}
        <col width="8%">
        {{-- <col width="8%"> --}}
        <col width="22%">
    </colgroup>
    <thead>
        <tr>
            <th class="vcenter text-center">Sl</th>
            <th class="vcenter text-center">Issue Name</th>
            <th class="vcenter text-center">Product Type</th>
            <th class="vcenter text-center">Type</th>
			<th class="vcenter text-center">Issue Category</th>
            <th class="vcenter text-center">Status</th>
            {{-- <th class="vcenter text-center">API Push</th> --}}
            <th class="vcenter text-center">CI Issue</th>
            {{-- <th class="vcenter text-center">CI CIF API Update</th> --}}
            <th class="vcenter text-center"> <a href="{{ url('/Issues/add') }}" class="btn btn-primary gradient ajax_page btn-sm" title="Add" escape="false"> <i class="fa fa-plus"></i> Add</a> </th>
        </tr>
    </thead>
    <tbody style="word-break: break-all;">

        @IF(!empty($tblData))
        @FOREACH($tblData as $key=>$data)

            <tr>
                <td class="vcenter text-center"> {{ $key + 1 }} </td>
                <td class="vcenter text-center"> {{ $data['name'] }} </td>
                <td class="vcenter text-center"> {{ (!empty($data['product_type'])) ? $data['product_type']['name'] : 'N/A' }}</td>

                <td class="vcenter text-center"> @if($data['issues_from']=='wform') service-request @else {{ $data['issues_from'] }} @endif</td>
				<td class="vcenter text-center">{{ (!empty($data['issue_categories'])) ? $data['issue_categories']['name'] : 'N/A' }}</td>
                <td class="vcenter text-center"> {{ $data['status_name'] }} </td>
                {{-- <td class="vcenter text-center"> {{ ($data['is_api']==1)?'Yes':'No' }} </td> --}}
                <td class="vcenter text-center"> {{ ($data['is_ci']==1)?'Yes':'No' }} </td>
                {{-- <td class="vcenter text-center"> {{ ($data['is_ci_cif']==1)?'Yes':'No' }} </td> --}}
                <td class="vcenter actions text-center">
                    @IF($data['status'] == '0')
                        <a href="{{ url('/Issues/tmp-status/'.$data['id'].'/1') }}" class="btn btn-info gradient btn-sm" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a>
                    @ELSEIF($data['status'] == '1')
                        <?php
                            $editUrl = url('/Issues/edit/'.$data['id']);
                            if (!empty($searchDataForView)) {
                                $editUrl .= '?'.http_build_query($searchDataForView);
                            }
                        ?>
                        <a href="{{$editUrl}}" class="btn btn-success btn-sm ajax_page btn-sm mb-1" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>
                            {{--@if($data['issues_from']=='wform')--}}
                        <a href="{{ url('/issues/config/'. $data['id']) }}" class="btn btn-sm btn-primary btn-sm mb-1"><i class="fa fa-edit"></i> Config</a>
                            {{--@endif--}}
                        {{--<a href="{{ url('/issues/common/config/'. $data['id'])}}" class="btn btn-sm btn-outline-info btn-sm mb-1" title="API Common Config" ><i class="fa fa-cogs"></i> API Common Config</a>
                        <a href="{{ url('/issues/inquiry/config/'. $data['id'])}}" class="btn btn-sm btn-outline-primary btn-sm mb-1" title="Inquiry API Config"><i class="fa fa-cog"></i> Inquiry API Config</a>--}}
                        
                        <a href="{{ url('issues/check-list/config/'.$data['id']) }}" class="btn btn-sm btn-info btn-sm mb-1"><i class="fa fa-check-square"></i> Check List Config</a>
                        
                        <a href="{{ url('/issues/fieldset/group/'. $data['id']) }}" class="btn btn-sm btn-outline-warning btn-sm mb-1"><i class="fa-solid fa-layer-group"></i>Fieldset Config</a>

                        @if($data['id'] == getId('BPID') || $data['id'] == getId('AUCTION'))
                            <a href="{{ url('/issue/conditional/field/'. $data['id']) }}" class="btn btn-sm btn-outline-success btn-sm"><i class="fa-solid fa-layer-group"></i> Conditional Field</a>
                        @endif



                        <a href="{{ url('/issues/attachment/'. $data['id']) }}" class="btn btn-sm btn-outline-secondary btn-sm mb-1"><i class="fa-solid fa-layer-group"></i>Attachment</a>


                        <a href="{{ url('/Issues/tmp-status/'.$data['id'].'/0') }}" class="btn btn-danger btn-sm gradient btn-sm mb-1" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a>
                        {{--@IF(empty($data['is_sent_sms']))
                            <a href="{{ url('/Issues/sms_status/'.$data['id'].'/1') }}" class="margin-top-2 btn btn-default gradient" title="Active-SMS" escape="false"> <i class="fa fa-envelope"></i> Active Mail &amp; SMS</a>
                        @ELSEIF($data['is_sent_sms'] == '1')
                            <a href="{{ url('/Issues/sms_status/'.$data['id'].'/0') }}" class="margin-top-2 btn btn-warning gradient" title="Inactive-SMS" escape="false"> <i class="fa fa-ban"></i> Inactive Mail &amp; SMS</a>
                        @ENDIF--}}
                        
                    @ENDIF
                </td>
            </tr>
        @ENDFOREACH
        @ELSE <tr> <td class="vcenter text-center" colspan="8"> <strong>Data Not Available</strong></td> </tr>
        @ENDIF
    </tbody>
</table>
</div>
@endsection
