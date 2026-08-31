@extends('layouts.admin')
@section('content')
<div class="clearfix">&nbsp;</div>
{!! Form::open(['method'=>'post', 'class'=>'form-inline', 'action' => ['SegmentCodeController@segmentExcelUpload'] , 'enctype' => 'multipart/form-data']); !!}
    {!! Form::token(); !!}
    {{ Form::hidden('request_type','upload')}}
    <div class="form-group">
        {!! Form::file('file', $attributes = array('class'=>'form-control', 'label'=>false, 'type'=>'file')); !!}
        <button class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
        <a class="btn btn-link" href="{{ URL::asset('public/sample_file/sample_segment.xlsx') }}">Sample Excel File</a>
    </div>
    @IF($errors->has('file')) <div class="error-message text-danger">{{ $errors->first('file') }}</div> @ENDIF

{!! Form::close(); !!}
<div class="clearfix">&nbsp;</div>

    <legend class="text-center">{{ $title_for_layout }}</legend>
    <a href="#" id="addModal" class="btn btn-primary gradient my-2 btn-sm float-right"
                           title="Add" escape="false"><i class="fa fa-plus"></i> Add </a>
    <div class="table-responsive">
        <table class="commonDataTableAllAsc table table-bordered table-striped">
            <colgroup>
                <col width="5%">
                <col width="30%">
                <col width="30%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th class="vcenter text-center">Sl</th>
                    <th class="vcenter text-center">Name</th>
                    <th class="vcenter text-center">Code</th>
                    <th class="vcenter text-center">Status</th>
                </tr>
            </thead>
            <tbody style="word-break: break-all;">
            <?php $i = 1; ?>
            @IF(!empty($tblData))
            @FOREACH($tblData as $data)
                <tr>
                    <td class="vcenter text-center"> {{ $i ++ }} </td>
                    <td class="vcenter text-center"> {{ $data['name'] }} </td>
                    <td class="vcenter text-center"> {{ $data['code'] }}</td>
                    <td class="vcenter actions text-left">
                        @if($data['status'] == 0)
                            <a href="{{ url('/segment/status/'.encrypt($data['id']).'/1') }}"
                               class="btn btn-info gradient btn-sm" title="Active" escape="false">
                                <i class="fa fa-check"></i> Active
                            </a>
                        @else
                            <a href="{{ url('/segment/edit/'.encrypt($data['id'])) }}" id=""
                               class="btn btn-success gradient ajax_page btn-sm editModal" title="Edit" escape="false">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <a href="{{ url('/segment/status/'.encrypt($data['id']).'/0') }}"
                               class="btn btn-primary gradient btn-sm" title="Inactive" escape="false">
                                <i class="fa fa-times"></i> Inactive
                            </a>
                        @endif
                        <a href="{{ url('/segment/delete/'.encrypt($data['id'])) }}"
                               class="btn btn-danger gradient btn-sm" title="Delete" escape="false">
                                <i class="fa fa-times"></i> Delete
                            </a>
                    </td>
                </tr>
            @ENDFOREACH
            @ELSE
                <tr>
                    <td class="vcenter text-center" colspan="4"> <strong>Data Not Available</strong></td>
                </tr>
            @ENDIF
            </tbody>
        </table>
        <!-- add Modal -->
        <div class="modal fade" id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6>Segment Code Add</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="{{ route('segment.store') }}" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name <span class="required">*</span></label>
                                <input name="name" value="{{ old('name') }}" class="form-control" autocomplete="off" type="text"
                                    placeholder="Name"/>
                                <div class="error">{{ $errors->first('name') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="code">Code <span class="required">*</span></label>
                                <input type="text" name="code" value="{{ old('code') }}" class="form-control" autocomplete="off" type="text"
                                    placeholder="Code"/>
                                <div class="error">{{ $errors->first('code') }}</div>
                            </div>
                            <div class="form-group float-right">
                                <button type="submit" class="btn-primary btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- edit add Modal -->
        <div class="modal fade" id="openModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6>Segment Code Edit</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4" id="editModalBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#addModal').on('click', function(e) {
                $('#openModal').modal('show')
            });
            $('.editModal').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    processData: false,
                    dataType: false,
                    cache: false,
                    success: function (data){
                        $('#openModalEdit').modal('show');
                        $('#editModalBody').html(data);
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            toastr.error(xhr.responseJSON.error);
                        } else {
                            toastr.error('An error occurred.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
