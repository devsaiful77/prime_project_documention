@extends('layouts.admin')
@section('content')
<legend>Subflow List</legend>
<div class="table-responsive">
<table class ="commonDataTableAllAsc table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th class="vcenter text-center">#</th>
            <th class="vcenter text-center">Issue Category Name</th>
            <th class="vcenter text-center">Issue Name</th>
            <th class="vcenter text-center">Subflow List</th>
            <th class="vcenter text-center">
                {{--
                <a href="{{ url('workflow/subflow/create') }}" class="btn btn-primary float-right btn-sm">Add New Subflow</a>
                --}}
            </th>
        </tr>
        </thead>
        <tbody style="word-break: break-all;">
            @inject('subflow','App\Services\WorkFlowService')

            @if(!empty($issueItems))
                @foreach($issueItems as $key=>$issueItem)
                    <?php
                    $subflowLists = $subflow->subFlowList($issueItem->id);
                    ?>
                    <tr>
                        <td class="vcenter text-center">{{ ++$key }}</td>
                        <td class="vcenter text-center">{{ $issueItem->issue_cat_name}}</td>
                        <td class="vcenter text-center">{{ $issueItem->name}}</td>
                        <td class="vcenter text-left">
                            @if(!empty($subflowLists))
                                <ul>
                                @foreach($subflowLists AS $subflowList)
                                    <li><strong>{{$subflowList->options}}</strong>:{{$subflowList->group_name}}</li>
                                @endforeach
                                </ul>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="vcenter text-center">
                            <a href="{{ url('workflow/subflow/set/'.$issueItem->id) }}" class="btn btn-info btn-sm">Set Subflow</a>
                        </td>
                    </tr>
                @endforeach
            @else
            <tr> <td class="vcenter text-center" colspan="4"> <strong>Data Not Available</strong></td> </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection

@section('extrajssection')
<script>
$(".btn-del").on("click", function () {

    var action = $(this).attr('data-id');
    $(".confirm").attr('href', action);

});
</script>
@endsection