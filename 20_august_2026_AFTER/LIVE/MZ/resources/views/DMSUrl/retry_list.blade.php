@php
    use Carbon\Carbon;
@endphp
@extends('layouts.admin')
@section('content')
    <legend class="text-center my-2">{{$title ?? ''}}</legend>
    <div class="clearfix">&nbsp;</div>
    <form class="form-inline justify-content-between" id="dmsretry-search">
        <div class="custom-control-inline mr-2">
            <label class="radio-inline mr-2">
                <input type="radio" name="active_tab" class="i-checks form-type" value="wform" {{
            $searchDataForView['active_tab'] == 'wform' ? 'checked' : '' }} ><strong>&nbsp;Service Request</strong>
            </label>
            <label class="radio-inline mr-2">
                <input type="radio" name="active_tab" class="i-checks form-type" value="complaint" {{
            $searchDataForView['active_tab'] == 'complaint' ? 'checked' : '' }} ><strong>&nbsp;Complaint</strong>
            </label>
        </div>
        <div class="div form-inline">
            <div class="mr-1">
                <input type="text" name="date_from" class="form-control datePicker" placeholder="Log Date From" value="{{ $searchDataForView['date_from'] }}" autocomplete="off">
            </div>
            <div class="mr-1">
                <input type="text" name="date_to" class="form-control datePicker" placeholder="Log Date To" value="{{ $searchDataForView['date_to'] }}" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-success mr-1"><i class="fa fa-search"></i> <strong>Find</strong></button>
            <button type="button" class="btn btn-success bulk_exe"><strong>Bulk Retry</strong> <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
        </div>
    </form>
    <div class="clearfix">&nbsp;</div>

    @IF($searchDataForView['active_tab'] == "wform")
        <div class="table-responsive">
            <table class="commonDataTableAllAsc table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" name="check_all" class="form-type" value="">
                        </th>
                        <th class="vcenter text-center">Ticket&nbsp;No</th>
                        <th class="vcenter text-center">Attachment</th>
                        <th class="vcenter text-center">Log&nbsp;Date</th>
                        <th class="vcenter text-center">Log&nbsp;Time</th>
                        <th class="vcenter text-center">File&nbsp;Attach&nbsp;Date</th>
                        <th class="vcenter text-center">Retry</th>
                    </tr>
                </thead>
                <tbody style="word-break: break-all;">
                @IF(!empty($wFormDataObj))
                @FOREACH($wFormDataObj as $data)
                    <tr>
                        <td class="text-center">
                            @php
                                $check_enc = $data['reference_number'];
                                $check_enc .= '?'.$data['attachID'];
                            @endphp
                            <input type="checkbox" name="check_issue" class="form-type" value="{{ $check_enc }}"/>
                        </td>
                        <td class="vcenter text-center">{{ $data['reference_number'] }}</td>
                        <td class="vcenter text-center">
                            @IF(!empty($data['file_name']))
                                <?php
                                    $basePath = str_replace('engine','',base_path());
                                    $imageURL = $basePath.'public/attachments/'.$data['file_name'];
                                if(file_exists($imageURL)){
                                ?>
                                <a href="{{ URL::asset('public/attachments/'.$data['file_name']) }}" target="_blank">{{ $data['file_name'] }}</a>
                                <?php } else{?>
                                <a href="{{ url('/images') }}" target="_blank">{{ $data['file_name'] }}</a>
                                <?php }?>
                            @ELSE
                                {{ __('Attachment not available') }}
                            @ENDIF
                        </td>
                        <td class="vcenter text-center">{{ Carbon::createFromTimestamp($data['date'])->format('Y-m-d') }}</td>
                        <td class="vcenter text-center">{{ Carbon::createFromTimestamp($data['date'])->format('h:i:s a') }}</td>
                        <td class="vcenter text-center">{{ $data['attachment_date'] }}</td>
                        <td class="vcenter text-center">
                            @php
                                $url = url('/DMSDocRetry/'.encrypt($data['reference_number']).'/'.encrypt($data['attachID']));
                            @endphp
                            <a href="{{ $url }}" class="btn btn-success btn-sm" title="Retry Attachment Uploading"><i class="fa fa-paper-plane"></i></a>
                        </td>
                    </tr>
                @ENDFOREACH
                @ELSE
                    <tr>
                        <td class="vcenter text-center" colspan="7"><strong>Data Not Available</strong></td>
                    </tr>
                @ENDIF
                </tbody>
            </table>
        </div>
    @ELSEIF($searchDataForView['active_tab'] == "complaint")
        <div class="table-responsive">
            <table class="commonDataTableAllAsc table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="text-center">
                        <input type="checkbox" name="check_all" class="form-type" value="">
                    </th>
                    <th class="vcenter text-center">Ticket&nbsp;No</th>
                    <th class="vcenter text-center">Attachment</th>
                    <th class="vcenter text-center">Log&nbsp;Date</th>
                    <th class="vcenter text-center">Log&nbsp;Time</th>
                    <th class="vcenter text-center">File&nbsp;Attach&nbsp;Date</th>
                    <th class="vcenter text-center">Retry</th>
                </tr>
                </thead>
                <tbody style="word-break: break-all;">
                @IF(!empty($complaintDataObj))
                    @FOREACH($complaintDataObj as $data)
                        <tr>
                            <td class="text-center">
                                @php
                                    $check_enc = $data['reference_number'];
                                    $check_enc .= '?'.$data['attachID'];
                                @endphp
                                <input type="checkbox" name="check_issue" class="form-type" value="{{ $check_enc }}"/>
                            </td>
                            <td class="vcenter text-center">{{ $data['reference_number'] }}</td>
                            <td class="vcenter text-center">
                                @IF(!empty($data['file_name']))
                                    <?php
                                        $basePath = str_replace('engine','',base_path());
                                        $imageURL = $basePath.'public/attachments/'.$data['file_name'];
                                    if(file_exists($imageURL)){
                                    ?>
                                    <a href="{{ URL::asset('public/attachments/'.$data['file_name']) }}" target="_blank">{{ $data['file_name'] }}</a>
                                    <?php } else{?>
                                    <a href="{{ url('/images') }}" target="_blank">{{ $data['file_name'] }}</a>
                                    <?php }?>
                                @ELSE
                                    {{ __('Attachment not available') }}
                                @ENDIF
                            </td>
                            <td class="vcenter text-center">{{ Carbon::createFromTimestamp($data['date'])->format('Y-m-d') }}</td>
                            <td class="vcenter text-center">{{ Carbon::createFromTimestamp($data['date'])->format('h:i:s a') }}</td>
                            <td class="vcenter text-center">{{ $data['attachment_date'] }}</td>
                            <td class="vcenter text-center">
                            @php
                                $url = url('/DMSDocRetry/'.encrypt($data['reference_number']).'/'.encrypt($data['attachID']));
                            @endphp
                            <a href="{{ $url }}" class="btn btn-success btn-sm" title="Retry Attachment Uploading"><i class="fa fa-paper-plane"></i></a>
                        </td>
                        </tr>
                    @ENDFOREACH
                @ELSE
                    <tr>
                        <td class="vcenter text-center" colspan="7"><strong>Data Not Available</strong></td>
                    </tr>
                @ENDIF
                </tbody>
            </table>
        </div>
    @ENDIF
@endsection
@section('extrajssection')
    <script type="text/javascript">
        $(".form-type").on('ifChecked', function(event){
            $('form#dmsretry-search').submit();
        });
        $(".bulk_exe").attr('disabled', true);
        $('input[name="check_all"]').click(function() {
            var isChecked = $('input[name="check_all"]').is(":checked");
            if (isChecked) {
                $(".bulk_exe").attr('disabled', false);
            } else {
                $(".bulk_exe").attr('disabled', true);
            }
        });
        $('input[name="check_issue"]').click(function() {
            var isChecked = $('input[name="check_issue"]').is(":checked");
            if (isChecked) {
                $(".bulk_exe").attr('disabled', false);
            } else {
                $(".bulk_exe").attr('disabled', true);
            }
        });
        $('input[name="check_all"]').on('change',function(){
            $('input[name="check_issue"]').prop('checked',$(this).prop('checked'));
        });
        $('input[name="check_issue"]').on('change',function(){
            $('input[name="check_all"]').prop('checked',$('.child:checked').length ? true: false);
        });
        $(".bulk_exe").click(function(){
            var ref_nos = [];
            $.each($("input[name='check_issue']:checked"), function(){
                ref_nos.push($(this).val());
            });
            if (!ref_nos) {
                customAlert('Please select checkbox', 'You need to select any check box', 'red');
            } else {
                $.confirm({
                    title: 'Retry DMS Document Uploading in Bulk ?',
                    content:'',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        Yes: {
                            text: 'Confirm',
                            btnClass: 'btn-green',
                            action: function () {
                                $.ajax({
                                    type: "post",
                                    url: "{{ url('/DMSDocRetryBulk/') }}",
                                    data: {
                                        _token: _token,
                                        ref_nos: ref_nos,
                                    },
                                    dataType: "json",
                                    beforeSend: function () {
                                        overlay('show');
                                    },
                                    success: function (response) {
                                        overlay('hide');
                                        if (response === 1) {
                                            $.confirm({
                                                title : 'Success',
                                                content : 'All documents uploaded in DMS successully',
                                                type : 'green',
                                                typeAnimated: true,
                                                buttons : {
                                                    Yes: {
                                                        text: 'OK',
                                                        btnClass: 'btn-green',
                                                        action: function(){
                                                            location.reload();
                                                        }
                                                    }
                                                }
                                            });
                                        } else {
                                            $.confirm({
                                                title : 'Error',
                                                content : 'Something went wrong. Please Contact with Administrator',
                                                type : 'red',
                                                typeAnimated: true,
                                                buttons : {
                                                    Yes: {
                                                        text: 'OK',
                                                        action: function(){
                                                            location.reload();
                                                        }
                                                    }
                                                }
                                            });
                                        }
                                    },
                                    error: function (data) {
                                        overlay('hide');
                                        customAlert('Error', 'Something went wrong. Please Contact with Administrator', 'red');
                                    }
                                });
                            }
                        },
                        No: {
                            text: 'Cancel'
                        }
                    }
                });
            }
        });
    </script>
@endsection
