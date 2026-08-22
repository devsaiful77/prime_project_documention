@extends('layouts.admin')
@section('content')


<!-- Modal Structure -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Info Category Actions</h5>
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

<!-- Table Structure -->
<div class="table-responsive">
    <table class="commonDataTableAllAsc table table-bordered">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Category Name</th>
                <th class="text-center">Sub Category Name</th>
                <th class="text-center">Description</th>
                <th class="text-center">Status</th>
                <th class="text-center">Created By</th>
                <th class="text-center">Comments</th>
                <th class="text-center">Btn Action</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($tmpData))
                @foreach($tmpData as $key => $data)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ ($data['parent_id'] == 0) ? $data['name'] : $data['category_name'] }}</td>
                        <td class="text-center">{{ ($data['parent_id'] != 0) ? $data['name'] : '-' }}</td>
                        <td class="text-center">{{ $data['description'] }}</td>
                        <td class="text-center">{{ $data['action'] }}</td>
                        <td class="text-center">
                            @php
                               $created_by = \App\User::where('id', $data['created_by'])->first();
                               echo $created_by->user_id;
                            @endphp
                        </td>
                        <td class="text-center">{{ $data['comments'] }}</td>
                        <td class="text-center">{{ $data['action'] ?? 'Add' }}</td>
                        <td class="text-center">
                            <button class="btn btn-primary modal_button" data-id="{{ $data['id'] }}" data-bs-toggle="modal" data-bs-target="#exampleModal">View</button>
                            @if($isChecker == false)
                            @if($data['form_status'] == 7)
                                <a href="{{ url('bond-info/cat-tmp-edit', $data['id']) }}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a>
                            @endif
                                <a href="{{ url('delete/tmp-data', ['id' => $data['id'], 'table' => 'binfo_catsubcats_tmp']) }}" class="btn btn-danger gradient ajax_page" title="Delete" escape="false">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                        @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<!-- JavaScript to Handle Modal and Button Actions -->
<script type="text/javascript">

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
        const id = $(this).data('id');
        $.ajax({
            url: "assign/" + id,
            type: "GET",
            success: function(data) {
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
                let url = "{{ url('bond-info/category/send-back', '') }}";
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
                let url = "{{ url('bond-info/category/reject', '') }}";
                url += '/' + id;

                $('#comments-form').attr('action', url);
                $('#comments-form').submit();
            });

            $('#approve-btn').click(function(){
                const id = $('#assign_button').data('id');
                let url = "{{ url('bond-info/category/approve', '') }}";
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
</script>
@endpush