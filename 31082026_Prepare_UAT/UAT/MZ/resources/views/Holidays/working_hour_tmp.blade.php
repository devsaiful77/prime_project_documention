@extends('layouts.admin')
@section('content')

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Working Hour Actions</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
            <span class="text-danger" id="assign_by"></span>
            <div class="flex-grow-1 me-2 d-none" id="comments-container">
                <form id="comments-form" action="" method="post">
                    @csrf
                    <textarea id="comments" name="comments" class="form-control" placeholder="Enter your comments...." cols="3" rows="1" style="height: 38px; position: relative;" required></textarea>
                    <span class="text-danger" id="error-container"></span>
                </form>
            </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="assign_button" data-id="" >Assign</button>
            <div id="button-container" class="d-none">
                <a id="approve-btn" class="btn btn-success gradient ajax_page" title="Approve" escape="false"> <i class="fa fa-plus"></i> Approve</a>
                {{-- <a id="sendBack-btn" class="btn btn-warning gradient ajax_page" title="Send Back" escape="false"><i class="fa fa-arrow-left"></i> SendBack</a> --}}
                <a id="reject-btn" class="btn btn-danger gradient ajax_page" title="Reject" escape="false"><i class="fa fa-times"></i> Reject</a>
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

<div class="table-responsive">
    @if ($dataForView['start_hour'] != null)
    <table class ="table table-bordered table-striped table-hover">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            @if (!$isChecker)
            <col>
            @endif
            <col>
            <col width="15%">
        </colgroup>
        <thead class="bg-primary">
            <tr>
                <th class="vcenter text-center">Field</th class="vcenter text-center">
                <th class="vcenter text-center">Hour</th class="vcenter text-center">
                <th class="vcenter text-center">Minitue</th class="vcenter text-center">
                <th class="vcenter text-center">Second</th class="vcenter text-center">
                <th class="vcenter text-center">Btn Action</th class="vcenter text-center">
                 @if (!$isChecker)
                <th class="vcenter text-center">Comment</th class="vcenter text-center">
                 @endif
                <th class="vcenter text-center">Created By</th class="vcenter text-center">
                <th class="vcenter text-center">Action</th>
            </tr>
        </thead>
        <tbody style="word-break: break-all;">
            <tr>
                <td class="vcenter text-center"><strong>Start :</strong></td>
                <td class="vcenter text-center">{{ $dataForView['start_hour'] }}</td>
                <td class="vcenter text-center">{{ $dataForView['start_minute'] }}</td>
                <td class="vcenter text-center">{{ $dataForView['start_second'] }}</td>
                <td class="vcenter text-center" rowspan="2">{{ $dataForView['action'] }}</td>
                @if (!$isChecker)
                <td class="vcenter text-center" rowspan="2">{{ isset($dataForView['comments'])? $dataForView['comments'] : "N/A" }}</td>
                @endif
                <td class="vcenter text-center" rowspan="2">
                    @php
                        $created_by =  \App\User::where('id', $dataForView['created_by'] )->first();
                        echo $created_by->user_id;
                    @endphp
                </td>
                <td class="vcenter text-center" rowspan="2">
                    <button class="btn btn-primary gradient ajax_page modal_button" type="button" data-id="{{ $dataForView['id'] }}"  data-bs-toggle="modal" data-bs-target="#exampleModal">View</button>
                        @if($isChecker == false)
                            @if($dataForView['form_status'] == 7)
                                {{-- <a href="{{ url('Divisions/tmp-edit', $dataForView['id']) }}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a> --}}
                            @endif
                                <a href="{{ url('delete/tmp-data', ['id' => $dataForView['id'], 'table' => 'working_hour_tmps']) }}" class="btn btn-danger gradient ajax_page" title="Delete" escape="false">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                        @endif

                </td>
              </tr>
              <tr>
                <td class="vcenter text-center"><strong>End :</strong></td>
                <td class="vcenter text-center">{{ $dataForView['end_hour'] }}</td>
                <td class="vcenter text-center">{{ $dataForView['end_minute'] }}</td>
                <td class="vcenter text-center">{{ $dataForView['end_second'] }}</td>
              </tr>
        </tbody>
    </table>
    @else
        <h4 class="text-center mt-5">No new working hour is requested.</h4>
    @endif
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
                buttonControl(id);
                $('#error-container').text("");
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
                    });
                }
            }


            $('#sendBack-btn').click(function(e){
                let value = $('#comments').val();
                if (!validateInput(value)) {
                    return;
                }

                const id = $('#assign_button').data('id');
                let url = "{{ url('WorkingHours/send-back', '') }}";
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
                let url = "{{ url('WorkingHours/reject', '') }}";
                url += '/' + id;

                $('#comments-form').attr('action', url);
                $('#comments-form').submit();
            });

            $('#approve-btn').click(function(){
                const id = $('#assign_button').data('id');
                let url = "{{ url('approve/working-hour', '') }}";
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
                                if(data.modified_by){
                                    $('#assign_by').text("Assigned By : " + data.modified_by);
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
