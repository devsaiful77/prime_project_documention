@php
    use Carbon\Carbon;
@endphp
@extends('layouts.admin')
@section('content')
    <style type="text/css">
        /*th, td {
            white-space: nowrap;
        }*/

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
    <?php
    $getUrlQuery = "";
    ?>
    <div class="table-responsive" id="handlerid">
        <table class="table table-striped bordered">
            @if(session('success'))
            <div class="mb-2 p-0 font-weight-bold text-center alert alert-success" style="font-size: 14px;">
                <strong>*</strong> {{ session('success') }}
            </div>
            @endif
            @if(session('warning'))
                <div class="mb-2 p-0 font-weight-bold text-center alert alert-warning" style="font-size: 14px;">
                    <strong>*</strong> {{ session('warning') }}
                </div>
            @endif
            <form action="{{ route('feedback.bulk_store') }}" method="post" id="">
                @csrf
                <thead>
                    <button type="submit" class="btn btn-success btn-sm mb-1" id="">Read All</button>
                    <tr style="background-color: #DFF0D8">
                        <th class="text-center"><input type="checkbox" id="parentCheck" class=""> </th>
                        <th class="text-center">Ticket No</th>
                        <th class="text-center">Customer Number</th>
                        <th class="text-center">Mobile Number</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Complaint Type</th>
                        <th class="text-center">Log Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @IF(!empty($feedbacks))
                        @FOREACH($feedbacks as $data)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" value="{{ $data->id }}" name="ids[]" class="checkStatus">
                                </td>
                                <td class="text-center">
                                    <span class="text-success">{{ $data['ticket_number'] }}</span>
                                </td>
                                <td class="text-center">{{ !empty($data['cif_number']) ? $data['cif_number'] : '' }}</td>
                                <td class="text-center">{{ $data['mobile_no'] }}</td>
                                <td class="text-left">{{ $data['email'] }}</td>
                                <td class="text-center">{{ $data['comments'] }}</td>
                                <td class="text-center">{{ $data['log_date'] }}</td>
                                <td class="text-center">
                                    @if($data['status'] == 0)
                                        <a class="btn btn-sm btn-success" href="{{ route('feedback.read', $data->id) }}">Read</a>
                                    @endif
                                </td>
                            </tr>
                            @ENDFOREACH
                            @ENDIF
                        </tbody>
                    </form>
                <tfoot>
                @IF(!empty($complaintDataObj))
                    @IF($complaintDataObj->total() > $complaintDataObj->perPage())
                    <tr><td class="text-right vcenter no-padding-margin-tb" colspan="10">{{ $complaintDataObj->appends($searchDataForView)->links('vendor/pagination/default') }}</td></tr>
                    @ENDIF
                @ENDIF
            </tfoot>
        </table>
        {{ $feedbacks->links() }}
    </div>

    <div class="clearfix">&nbsp;</div>
    <script type="text/javascript">
        $(function (e) {
            $("#parentCheck").click(function () {
                $(".checkStatus").prop('checked',$(this).prop('checked'));
            })
        })
    </script>
@endsection
