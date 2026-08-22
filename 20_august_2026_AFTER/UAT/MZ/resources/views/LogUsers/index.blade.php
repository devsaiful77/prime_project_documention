@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <legend>User Session Report</legend>
    </div>
    <div class="card-body">
        <div class="form-class pb-3">
        {!! Form::open(['method'=>'get', 'class'=>'form-horizontal row', 'action' => ['LogUsersController@index'] , 'enctype' =>
         'multipart/form-data']); !!}
        <div class="col-md-3">
            <div class="form-group">
                <label>User</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($userID == $user->id) selected @endif>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Date From<span class="required">*</span></label>
                <input type="text" name="date_from" class="form-control datePickerLoguser" placeholder="Date From" value="{{
                 $searchDataForView['date_from'] }}" required autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Date to</label>
                <input type="text" name="date_to" class="form-control datePickerLoguser" placeholder="Date To" value="{{
                $searchDataForView['date_to'] }}" autocomplete="off">

            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group pt-4 mt-1">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
            </div>
        </div>
    </div>
        {!! Form::close(); !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <colgroup> <col width="25%"></col> <col width="25%"></col> <col width="25%"></col> <col width="25%"></col> </colgroup>
                <tr>
                    <th class="vcenter text-center">User Name</th>
                    <th class="vcenter text-center">User Ip</th>
                    <th class="vcenter text-center">Loged in at</th>
                    <th class="vcenter text-center">Loged out at</th>
                </tr>
                @IF(!empty($logsData))
                    @FOREACH($logsData as $logsData)
                        <tr>
                            <td class="vcenter text-center">{{$logsData->log_user->name}}</td>
                            <td class="vcenter text-center">{{$logsData->ip}}</td>
                            <td class="vcenter text-center">{{$logsData->log_in_at}}</td>
                            <td class="vcenter text-center">{{$logsData->log_out_at}}</td>
                        </tr>
                    @ENDFOREACH
                @ELSE
                    <tr> <th class="vcenter text-center" colspan="4">No Log Users</th> </tr>
                @ENDIF
            </table>
        </div>
    </div>
</div>
@endsection
