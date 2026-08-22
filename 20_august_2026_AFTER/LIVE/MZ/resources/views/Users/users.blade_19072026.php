@extends('layouts.admin')
@section('content')
<style>
    .pagination{
        float: right;
        padding-top: 1rem;
    }
</style>
    <div class="col-md-8 py-3">
        {!!
            Form::open([
              'method'=>'GET',
              'action' => ['UsersController@index'] ,
            ]);
        !!}
        {{--<div class="form-group col-xs-2 col-md-2" style="padding-right: 0;">
            <label for="status">Status:</label>
            {!!
              Form::select('status',['1'=>'Active','2'=>'Inactive'],$selectedStatus,[
                'class' => 'form-control',
              ]);
            !!}
        </div>
        <div class="form-group col-xs-3 col-md-3" style="padding-left: 0;padding-right: 0;">
            <label for="unit_id">Unit:</label>
            {!!
              Form::select('unit_id',[''=>'Please Select']+$allUnitData,$selectedUnit,[
                'class' => 'form-control',
              ]);
            !!}
        </div>--}}

        <div class="form-group col-xs-4 col-md-6" style="float:right; padding-right:0;">
            <label for="search_value">User ID / Employee ID / Email  / Mobile No / Name:</label>
            <div class="input-group">
                {!!
                  Form::text('search_value',$selectedValue,[
                    'class' => 'form-control',
                    'label'=>false,
                    'autocomplete'=>'off',
                    'type'=>'text',
                    'placeholder'=>'User ID / Employee ID / Email  / Mobile No / Name'
                  ]);
                !!}
                <div class="input-group-btn">
                    <button class="btn btn-primary" type="submit" title="Search"> <i class="fa fa-search"></i> Search </button>
                </div>
            </div>
        </div>

        @if (Auth::user()->user_id != 'CI')
        <form method="GET" action="{{ route('users.index') }}">
            <button type="submit" class="btn btn-primary" name="download" value="1">Download</button>
        </form>

        @endif

        {!! Form::close(); !!}
    </div>

    <div class="clearfix">&nbsp;</div>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed">
            <colgroup>
                <col width="9%">
                <col width="9%">
                <col width="9%">
                <col width="5%">
                <col width="9%">
                <col width="9%">
                <col width="9%">
                <col width="9%">
                <col width="9%">
                <col width="9%">
                <col width="9%">
            </colgroup>
            <thead>
            <tr>
                <th class="vcenter text-center">Name</th>
                <th class="vcenter text-center">Employee ID</th>
                <th class="vcenter text-center">Designation</th>
                <th class="vcenter text-center">User ID</th>
                <th class="vcenter text-center">Email</th>
                <th class="vcenter text-center">Mobile No</th>
                <th class="vcenter text-center">Remarks</th>
                <th class="vcenter text-center">Last Login</th>
                <th class="vcenter text-center">Role</th>
                <th class="vcenter text-center">Group(Unit)</th>
                <th class="vcenter text-center">Created</th>
                <th class="vcenter text-center">Status</th>



                @unlessrole('audit')
                <th class="vcenter text-left">

                    @can('accessUser')
                    @if($checker == false)

                        <a href="{{ url('/Users/add') }}" class="btn btn-primary btn-sm" title="Add" escape="" > <i class="fa fa-plus"></i> Add User</a>

                    @endif
                    @endcan

                </th>
                @endunlessrole
                {{-- <th class="vcenter text-left"> <a href="{{ url('/Users/approve',5) }}" class="btn btn-primary gradient ajax_page" title="Approve" escape="false"> <i class="fa fa-plus"></i> Approve</a> </th> --}}

            </tr>
            </thead>
            <tbody style="word-break: break-all;">
            @IF(!empty($userData['data']))
                @FOREACH($userData['data'] as $data)
                    <?php
                    $editUrl = "";
                    if (!empty($_GET)) {
                        $editUrl = '?'.http_build_query($_GET);
                    }

                    $roleArray = array("8");
                    $user_role_id = 0;
                    $user_role = "";
                    $user_unit_id = 0;
                    $user_unit_arr = array();
                    $unit_list = array();

                    if (!empty($data['roles'])) {
                        $role_name = (!empty($data['roles'][0]['display_name'])) ? $data['roles'][0]['display_name'] : "N/A";
                        $user_role = (!empty($data['roles'][0]['name'])) ? $data['roles'][0]['name'] : "N/A";
                        $user_role_id = (!empty($data['roles'][0]['id'])) ? $data['roles'][0]['id'] : 0;
                    } else {
                        $role_name = "N/A";
                        $user_role = "N/A";
                    }
                    if (!empty($data['user_unit'])) {
                        $user_unit_id = (!empty($data['user_unit']['unit_id'])) ? $data['user_unit']['unit_id'] : 0;
                        $user_unit_arr = explode(",", $user_unit_id);
                    }
                    ?>

                    @if ($data['user_id'] != 'CI')
                    <tr>
                        <td class="vcenter text-center"> {{ $data['name'] }} </td>
                        <td class="vcenter text-center"> {{ $data['emp_id'] }} </td>
                        <td class="vcenter text-center"> {!! str_replace('#',',',$data['designation']) !!} </td>
                        <td class="vcenter text-center"> {{ $data['user_id'] }} </td>
                        <td class="vcenter text-center"> {{ $data['email'] }} </td>
                        <td class="vcenter text-center"> {{ $data['mobile_no'] }} </td>
                        <td class="vcenter text-center"> {{ $data['remarks'] }} </td>
                        <td class="vcenter text-center"> {{$data['last_login_time']? date('d-m-Y h:i:s A', strtotime($data['last_login_time'])) : ""}} </td>
                        {{-- <td class="vcenter text-center"> {{ \Carbon\Carbon::parse($data['created_at'])->format('d-m-Y h:i:s A') }} </td> --}}
                        <td class="vcenter text-center"> {{ $role_name }} </td>
                        <td class="vcenter text-center">
                            @IF(!empty($user_unit_arr))

                                @FOREACH($user_unit_arr AS $user_unit_data)
                                    <?php
                                    if (!empty($allUnitData[$user_unit_data])) {

                                        $unit_list[] = $allUnitData[$user_unit_data];
                                    }
                                    ?>
                                @ENDFOREACH


                                    @if(!empty(getUserGroup($data['id'])))
                                       {{getUserGroup($data['id'])}}
                                    @elseif(!empty(getUserGroupName($data['id'])))
                                       {{ getUserGroupName($data['id'])}}
                                    @elseif(!empty(getUserDeptName($data['id'])))
                                        {{getUserDeptName($data['id'])}}
                                    @elseif(!empty(getUserDivName($data['id'])))
                                        {{getUserDivName($data['id'])}}
                                    @elseif(!empty(getUserRegName($data['id'])))
                                        {{getUserRegName($data['id'])}}
                                    @endif

                                ({{ implode(",",$unit_list) }})
                            @ELSE
                                N/A
                            @ENDIF

                        </td>
                        <td class="vcenter text-center"> {{ \Carbon\Carbon::parse($data['created_at'])->format('d-m-Y h:i:s A') }} </td>

                        <td class="vcenter text-center"> {{ $data['status_name'] }} </td>

                        @unlessrole('audit')

                        @if($checker == false)
                        <td class="actions">
                            @IF(!in_array($user_role_id, $roleArray) && ($data['id'] != Auth::user()->id))
                                <div class="btn-group">
                                    @if ($data['user_id'] != 'CI')
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm"data-bs-toggle="dropdown" >Actions Menu <span class="caret"></span></button>
                                    @endif

                                    <ul class="dropdown-menu scrollable-menu" role="menu" style="padding-left: 10px">
                                        @can('accessUser')
                                            <li><a href="{{ url('/Users/edit/'.encrypt($data['id'])).$editUrl }}" class="btn btn-success btn-sm margin-top-2" title="Edit" escape="false" > <i class="fa fa-pencil"></i> Edit User</a></li>
                                        @endcan

                                        {{-- @IF($data['status'] == '0')
                                            <li><a href="{{ url('/Users/status/'.encrypt($data['id']).'/1') }}" class="btn btn-info margin-top-2" title="Active" escape="false"> <i class="fa fa-check"></i> Active</a></li>
                                        @ELSEIF($data['status'] == '1') --}}

                                            {{-- @can('accessUser')
                                            <li><a href="{{ url('/Users/edit/'.encrypt($data['id'])).$editUrl }}" class="btn btn-success btn-sm margin-top-2" title="Edit" escape="false" > <i class="fa fa-pencil"></i> Edit User</a></li>
                                            @endcan --}}
                                            <!-- Button trigger modal -->
                                            {{--
                                            <li><a href="#" type="button" class="btn btn-primary margin-top-2" data-toggle="modal" data-target="#myModal-{{$data['id']}}"  title="Edit Access"> <i class="fa fa-circle-o"></i> Role</a></li>
                                            <li><a href="{{ url('/Users/SetPassword/'.encrypt($data['id'])).$editUrl }}" class="btn btn-warning margin-top-2" title="Set Password" escape="false" > <i class="fa fa-gear"></i> Set Password</a></li>
                                            --}}

                                            {{-- <li><a href="{{ url('/Users/status/'.encrypt($data['id']).'/0') }}" class="btn btn-danger margin-top-2" title="Inactive" escape="false"> <i class="fa fa-times"></i> Inactive</a></li> --}}
                                            {{-- <li><a href="{{ url('/Users/status/'.encrypt($data['id']).'/-2') }}" class="btn btn-danger margin-top-2" title="Close" escape="false"> <i class="fa fa-times"></i> Close</a></li> --}}

                                            {{-- <li><a href="{{ url('/Users/SetUnit/'.encrypt($data['id'])).$editUrl }}" class="btn btn-sm btn-info margin-top-2" title="Unit / Dept / Div" escape="false" > <i class="fa fa-wrench"></i> Assign</a></li> --}}
                                            <?php
                                            // $unassign =  url('/Users/un-assign/'.encrypt($data['id']));
                                            ?>
                                            {{-- <li><button type="button" class="btn btn-sm btn-warning margin-top-2" onclick="customConfirm('User Un-assign?','Are You Sure!','red','{{$unassign}}')"> <i class="fa fa-wrench"></i> Un-assign</button></li> --}}


                                            {{--<li><a href="{{ url('/Users/block/'.encrypt($data['id']).'/1') }}" class="btn btn-danger ">Block</a></li>--}}

                                            {{-- @IF($data['id'] == 1 && $userId == 1)
                                                <li><a href="{{ url('/Users/SetPassword/'.encrypt($data['id'])) }}" class="btn btn-warning margin-top-2" title="Set Password" escape="false"> <i class="fa fa-gear"></i> Set Password</a></li>
                                            @ENDIF --}}
                                        {{-- @ENDIF --}}
                                        @ENDIF
                                    </ul>
                                </div>
                        </td>
                        @endif

                        @endunlessrole
                    </tr>
                    @endif

                @ENDFOREACH
                <tr><td class="text-right" colspan="10">{{ $userDataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
            @ELSE <tr> <td class="vcenter text-center" colspan="10"> <strong>Data Not Available</strong></td> </tr>
            @ENDIF

            </tbody>
        </table>
    </div>

    @if(!empty($userData['data']))
        @FOREACH($userData['data'] as $data)
            @if($data['id'] != 1)
                @if($data['status'] == '1')

                    <!-- Edit Access Modal -->
                    <div class="modal fade" id="myModal-{{$data['id']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">{{$data['name']}}: Set Role </h4>
                                </div>
                                <div class="modal-body">
                                    <form action="{{url('/userRoleUpdate/'.encrypt($data['id']))}}" method="post" role="form" id="role-form-{{$data['id']}}">
                                        {{csrf_field()}}
                                        {{method_field('PATCH')}}
                                        <div class="form-group">
                                            <h4>Select Role:</h4>
                                            <div style="overflow: hidden;" class="form-group">

                                                {!! chk_roles('role_id', $data['roles']) !!}

                                                {{--@if(!empty($data['roles'][0]['id'])) @php $user_role_id=$data['roles'][0]['id'];@endphp @endif--}}
                                                {{--{{Form::select('role_id', [null=>'Select Role']+$allRoleData, select_role($data['roles']),['class'=>'form-control']) }}--}}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" onclick="$('#role-form-{{$data['id']}}').submit()">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @ENDFOREACH
    @endif

    {{--<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h2 class="modal-title">Modal 1</h2>
                </div>
            </div>
        </div>
    </div>--}}

@endsection
