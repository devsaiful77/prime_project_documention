@extends('layouts.admin')
@section('content')
    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Users Actions</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
            <span class="text-success" id="message-container"></span>
            <div id="checker-table-container">
                <table class="table table-bordered" id="checker-table">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Column</th>
                            <th>Old Data</th>
                            <th>New Data</th>
                        </tr>
                    </thead>
                    <tbody id="tmp-tbody"></tbody>
                </table>
            </div>
            </div>
            <div class="modal-footer d-flex align-items-center">
                <div class="flex-grow-1 me-2 d-none" id="comments-container">
                    <form id="comments-form" action="" method="get">
                        @csrf
                        <textarea id="comments" name="comments" class="form-control" placeholder="Enter your comments...." cols="3" rows="1" style="height: 38px; position: relative;" required></textarea>
                        <span class="text-danger" id="error-container"></span>
                    </form>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="assign_button" data-id="" >Assign</button>
                <div id="button-container" class="d-none">
                    <a id="approve-btn" class="btn btn-success gradient ajax_page" title="Approve" escape="false"> <i class="fa fa-plus"></i> Approve</a>
                    <a id="sendBack-btn" class="btn btn-warning gradient ajax_page" title="Send Back" escape="false"><i class="fa fa-arrow-left"></i> SendBack</a>
                    <a id="reject-btn" class="btn btn-danger gradient ajax_page" title="Reject" escape="false"><i class="fa fa-times"></i> Reject</a>
                </div>
            </div>
        </div>
        </div>
    </div> --}}

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">User Info Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <span class="text-success" id="message-container"></span> 
                    <div id="checker-table-container">
                        <table class="table table-bordered" id="checker-table">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Column</th>
                                    <th>Old Data</th>
                                    <th>New Data</th>
                                </tr>
                            </thead>
                            <tbody id="tmp-tbody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer d-flex align-items-center">
                    <div class="flex-grow-1 me-2 d-none" id="comments-container">
                        <form id="comments-form" action="" method="get">
                            @csrf
                            <textarea id="comments" name="comments" class="form-control" placeholder="Enter your comments...." required></textarea>
                            <span class="text-danger" id="error-container"></span>
                        </form>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="assign_button" data-id="">Assign</button>
                    <div id="button-container" class="d-none">
                        <a id="approve-btn" class="btn btn-success ajax_page" title="Approve">
                            <i class="fa fa-plus"></i> Approve
                        </a>
                        <a id="sendBack-btn" class="btn btn-warning ajax_page" title="Send Back">
                            <i class="fa fa-arrow-left"></i> Send Back
                        </a>
                        <a id="reject-btn" class="btn btn-danger ajax_page" title="Reject">
                            <i class="fa fa-times"></i> Reject
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="col-md-8 py-3">

    </div>

    <div class="clearfix">&nbsp;</div>
    <form method="GET" action="{{ route('users.action.queue.list') }}">
        <button type="submit" class="btn btn-primary" name="download" value="1">Download</button>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed">
            <colgroup>
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="10%">
                <col width="10%">
                {{-- <col width="10%">
                <col width="20%"> --}}
            </colgroup>
            <thead>
            <tr>
                <th class="vcenter text-center">Name</th>
                <th class="vcenter text-center">Designation</th>
                <th class="vcenter text-center">User ID</th>
                <th class="vcenter text-center">Employee ID</th>
                <th class="vcenter text-center">Email</th>
                <th class="vcenter text-center">Mobile No</th>
                <th class="vcenter text-center">Role</th>
                <th class="vcenter text-center">Comments</th>
                <th class="vcenter text-center">Group(Unit)</th>
                {{-- <th class="vcenter text-center">Created By</th> --}}
                <th class="vcenter text-center">Status</th>
                <th class="vcenter text-center">Btn Action</th>
                <th class="vcenter text-center">Action</th>

            </tr>
            </thead>
            <tbody style="word-break: break-all;">

                @IF(!empty($tmpData))
                    @FOREACH($tmpData as $key => $data)
                        <tr>
                            <td class="vcenter text-center">{{ $data->name }}</td>
                            <td class="vcenter text-center">{!! str_replace('#', ',', $data->designation) !!}</td>
                            <td class="vcenter text-center">{{ $data->user_id }}</td>
                            <td class="vcenter text-center">{{ $data->emp_id }}</td>
                            <td class="vcenter text-center">{{ $data->email }}</td>
                            <td class="vcenter text-center">{{ $data->mobile_no }}</td>
                            <td class="vcenter text-center">{{ $data->role_name ?? "N/A" }}</td>
                            <td class="vcenter text-center">{{ $data->ucomments ?? "N/A" }}</td>
                            <td class="vcenter text-center">{{ $data->unit_name ?? "N/A" }}  {{ $data->group_name ?? "" }}</td>
                            <td class="vcenter text-center">
                                <?php
                                    if ($data->ustatus == '1') {
                                        echo "Active";
                                    } elseif ($data->ustatus == '-2') {
                                        echo "Close";
                                    } else {
                                        echo "Inactive";
                                    }
                                ?>

                            </td>
                            <td class="vcenter text-center">{{ $data->uaction }}</td>
                            <td class="vcenter actions text-center">
                                {{-- <button class="btn btn-primary gradient ajax_page modal_button" type="button" data-id="{{ $data->uid }}" data-toggle="modal" data-target="#exampleModal">View</button> --}}
                                <button class="btn btn-primary modal_button" data-id="{{ $data->uid }}" data-bs-toggle="modal" data-bs-target="#exampleModal">View</button>

                                @if($checker == false)
                                @if($data->u_form_status == 7)
                                    {{-- <a href="{{ url('users/tmp-edit', $data->uid) }}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a> --}}
                                @endif
                                    <a href="{{ url('remove/tmp-data', ['id' => $data->uid, 'table' => 'users_tmp']) }}" class="btn btn-danger gradient ajax_page" title="Delete" escape="false">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                            @endif
                            </td>
                        </tr>
                    @ENDFOREACH
                @ELSE
                    <tr>
                        <td class="vcenter text-center" colspan="10"><strong>Data Not Available</strong></td>
                    </tr>
                @ENDIF


            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.modal_button').click(function(){
                const id = $(this).data('id');
                checkerTableData(id);
                $('#assign_button').attr('data-id', id);
                $('#message-container').text("");
                $('#error-container').text("");
                buttonControl(id);
            });

            $('#assign_button').click(function(){
                const id = $('#assign_button').data('id');
                $.ajax({
                    url: "assign/" + id,
                    type: "GET",
                    success:function(data){
                        $('#message-container').text(data.message);
                        buttonControl(data.id);
                    }
                });
            });

            function checkerTableData(id){
                if(id){
                    $.ajax({
                        url: "fetch/checker-table/" + id,
                        type: "GET",
                        success:function(data){
                            console.log(data);
                            var tableBody = $('#checker-table tbody');
                            tableBody.empty();
                            var serialNumber = 1;
                            $.each(data.new_data, function(key, newValue) {
                                var oldValue = data.old_data ? (data.old_data[key] !== undefined ? data.old_data[key] : 'N/A') : 'N/A';
                                var row = $('<tr class="text-center"></tr>');

                                var formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                                var serialCell = $('<td></td>').text(serialNumber);
                                var keyCell = $('<td></td>').text(formattedKey);
                                var oldDataCell = $('<td></td>').text(oldValue !== null ? oldValue : 'N/A');
                                var newDataCell = $('<td></td>').text(newValue !== null ? newValue : 'N/A');

                                row.append(serialCell);
                                row.append(keyCell);
                                row.append(oldDataCell);
                                row.append(newDataCell);

                                tableBody.append(row);
                                serialNumber++;
                            });
                        }
                    })
                }
            }

            $('#sendBack-btn').click(function(e){
                let value = $('#comments').val();
                if (!validateInput(value)) {
                    return;
                }

                const id = $('#assign_button').data('id');
                let url = "{{ url('Users/send-back', '') }}";
                url += '/' + id;

                $('#comments-form').attr('action', url);
                $('#comments-form').submit();
            });

            function validateInput(value) {
                if (value.length <= 0) {
                    $('#error-container').text('Comment is required.');
                    return false;
                }
                return true;
            }

            $('#reject-btn').click(function(e){
                let value = $('#comments').val();
                if (!validateInput(value)) {
                    return;
                }

                const id = $('#assign_button').data('id');
                let url = "{{ url('Users/reject', '') }}";
                url += '/' + id;

                $('#comments-form').attr('action', url);
                $('#comments-form').submit();
            });

            $('#approve-btn').click(function(){
                const id = $('#assign_button').data('id');
                let url = "{{ url('Users/approve', '') }}";
                // let url = "{{ url('Users/approve/with/role', '') }}";
                url += '/' + id;

                window.location.href = url;
            });

            function buttonControl(id){
                $.ajax({
                    url: "fetch/button-status/" + id,
                    type: "GET",
                    success:function(data){
                        $('#assign_by').text("");
                        if(data.assign_btn == true){
                            $('#assign_button').show();
                            $('#button-container').addClass('d-none');
                            $('#comments-container').addClass('d-none');
                        }else{
                            if(data.all_buttons == true){
                                $('#assign_button').hide();
                                $('#button-container').removeClass('d-none');
                                $('#comments-container').removeClass('d-none');
                            }else{
                                $('#assign_button').hide();
                                $('#button-container').addClass('d-none');
                                $('#comments-container').addClass('d-none');
                                $('#assign_by').text("Assigned By : " + data.modified_by);
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
