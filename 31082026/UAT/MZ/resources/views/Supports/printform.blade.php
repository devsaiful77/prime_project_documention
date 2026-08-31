@extends('layouts.admin')
@section('content')
    <style media="print"> @page {size: auto; margin-left: 0; margin-right: 0 } </style>
    <style>
        tr {font-size: 12px;}
        .table-condensed>tbody>tr>td,
        .table-condensed>tbody>tr>th,
        .table-condensed>tfoot>tr>td,
        .table-condensed>tfoot>tr>th,
        .table-condensed>thead>tr>td,
        .table-condensed>thead>tr>th {
            padding: 3px;
        }
        .h4, .h5, .h6, h4, h5, h6 {
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .graybg{
            background-color: gainsboro;
        }
        .footersign{
            font-size: 12px;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 4px;
        }
        label{
            padding-top: 0;
        }
        .main-header-wrap{
            display: none!important;
        }
        .navbar{
            display: none!important;
        }
        .fotter-pat{
            display: none!important;
        }
        .d-flex.align-items-center{
	display: none! important;
        }
        /* Flex container for the row */
        .row-for-footer {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-around;
        }
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap; /* Prevents wrapping in print view */
            margin: 0;
        }

        .col-md-6 {
            flex: 1; /* Allows the columns to take up equal width */
            padding: 0 15px;
        }

        .text-right {
            text-align: right;
        }

        /* Ensures alignment and balance in print mode */
        @media print {
            .row {
                flex-wrap: nowrap; /* Forces row to remain on the same line in print */
            }
            .col-md-6 {
                width: 50%;
            }
            .img-fluid {
                max-width: 100%;
                height: auto;
            }
        }

        /* Styling each column to ensure they stay in the same row */
        .col-md-6-for-footer {
            flex: 1 1 30%;
            max-width: 30%;
            text-align: center;
            padding-top: 1rem;
        }
        .col-md-6-for {
            flex: 1 1 30%;
            max-width: 30%;
            text-align: center;
        }

        /* Border styling for the signature box */
        .border-top-color {
            border-top: 1px solid #000;
        }
        .border-all{
            border: 1px solid #000;
            padding: 1rem;
        }
        table td, table th{
            font-size: 0.9rem;
        }
        .form-element-list{
            background: white;
        }
    </style>
    <?php
    // Get session data
//  $dataForPrint = session('dataForPrint');
//  dd($dataForPrint);
//  ?>
    <?php
    $subgroupStr = '';
    $subgroupList = (!empty(Auth::user()->user_unit)) ? Auth::user()->user_unit->subgroup_info_id : '' ;
    if (!empty($subgroupList)) {
        $subgroupArr = explode(',', $subgroupList);
        $subgroup = DB::table('subgroup_info')->select('id','name')->whereIn('id',$subgroupArr)->pluck('name')->toArray();
        if(!empty($subgroup)){
            $subgroupStr = implode(',', $subgroup);
        }
    }
    ?>
    <div class="my-1" style="border: 1px solid #000;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 pt-1">
                    <p style="font-size: 14px;"class="pt-2 m-0">To</p>
                    <h6 style="font-size: 14px;">The Manager</h6>
                    <h6 style="font-size: 14px;">{{ $subgroupStr }} </h6>
                    <h6 style="font-size: 14px;">Prime Bank PLC.</h6>
                </div>
                <div class="col-md-6 text-right">
                    <img class="img-fluid" src="{{ URL::asset('public/login_asset_v3/images/Logo-prime.png') }}" alt="Prime Bank PLC" style="height: 38px;"/>
                    <p style="padding-right: 1rem;"><b>Date:</b> {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                </div>
            </div>

            <div class="row justify-content-center" style="margin-top: -2rem;">
                <h4 class="text-center" style="font-size: 1.5rem !important;"><b>
                        @php
                            if($stype=='complaint')
                                echo 'Digital Complaint Form';
                            elseif($stype=='service')
                                echo 'Digital Service Request Form';
                            else
                                echo 'Non Customer Form';
                        @endphp
                    </b></h4>
            </div>

            {{-- <div style="padding-top:35px">
              <table width="100%">
                <tr>
                  <td align="center" width="100%">
                      <h4><b>
                      @php
                        if($stype=='complaint')
                            echo 'Digital Complaint Form';
                        elseif($stype=='service')
                            echo 'Digital Service Request Form';
                        else
                            echo 'Non Customer Form';
                      @endphp
                      </b></h4>
                  </td>

                </tr>
              </table>
            </div> --}}

            <div class="border-all">
                <table class="table table-bordered table-condensed table">
                    <colgroup>
                        <col width="35%">
                        <col width="65%">
                    </colgroup>
                    <?php
                    $dataForPrint = session('dataForPrint');
                    // dd($dataForPrint);
                    $fields_to_include = [
                        "Account/ID_No",
                        "Account_Name",
                        "Mobile_Number",
                        "Customer_Name",
                        "Product_Type*",
                        "Customer_Email",
                        "CIF_Number*",
                        "Time_&_Ext*",
                        "Customer_Number",
                    ];

                    $dataForPrint = array_intersect_key($dataForPrint, array_flip($fields_to_include));
                    $dynamicFieldFlag = 0;
                    $customerName = '';
                    ?>
                    @IF(!empty($dataForPrint))
                        <tr><th colspan="2" class="text-center graybg"><h4 style="font-size: 1rem !important;"><b>Customer Information</b></h4></th></tr>
                        @FOREACH($dataForPrint AS $key=>$value)
                            <?php

                            $staticField = ['Priority','Source','Tin_Verified','Static_Verified','Dynamic_Verified','Caller_ID*','Caller_ID','Segment_Code'];

                            if(!in_array($key,$staticField)){

                                $dynamicField = ['Service_Sub_Request_Type*', 'Complaint_Sub_Category*', 'Forward_To*','Check_List*'];
                                if (in_array($key,$dynamicField)) {
                                    $dynamicFieldFlag = 1;
                                }

                                if ($key == 'Customer_Name' || $key == 'Customer_Name*') {
                                    $customerName = $value;
                                }

                                ?>
                            @if($dynamicFieldFlag == 0)
                                @if(($key == 'Notes') && ($value == 'N/A'))
                                @else
                                    <tr> <th>{{str_replace('_',' ',$key)}}</th> <td>{{$value}}</td> </tr>
                                @endif
                            @else
                                @if(!empty($value) && ($value != 'N/A'))
                                    <tr> <th>{{str_replace('_',' ',$key)}}</th> <td>{{$value}}</td> </tr>
                                @endif
                            @endif
                            {{-- @if($key == 'Customer_DOB')
                              <tr><th colspan="2" class="text-left graybg"><h4><b>Action</b></h4></th></tr>
                            @endif --}}
                            <?php } ?>
                        @ENDFOREACH
                    @ENDIF
                </table>
            </div>
            <div class="border-all mt-3">
                <table class="table table-bordered table-condensed table">
                    <colgroup>
                        <col width="35%">
                        <col width="65%">
                    </colgroup>
                    <?php
                    $dataForPrint = session('dataForPrint');
                    $keysToRemove = [
                        'Account/ID_No',
                        'Account_Name',
                        'Mobile_Number',
                        'Customer_Name',
                        'Product_Type*',
                        'Customer_Email',
                        'CIF_Number*',
                        'Time_&_Ext*',
                        'Customer_DOB',
                        'Customer_Number',
                    ];

                    $dataForPrint = array_filter($dataForPrint, function($key) use ($keysToRemove) {
                        return !in_array($key, $keysToRemove);
                    }, ARRAY_FILTER_USE_KEY);

                    // dd($dataForPrint);
                    $dynamicFieldFlag = 0;
                    $customerName = '';
                    // dd($dataForPrint);
                    ?>
                    @IF(!empty($dataForPrint))
                        <tr><th colspan="2" class="text-center graybg"><h4 style="font-size: 1rem !important;"><b>Information Update</b></h4></th></tr>
                        @FOREACH($dataForPrint AS $key=>$value)
                            <?php

                            $staticField = ['Priority','Source','Tin_Verified','Static_Verified','Dynamic_Verified','Caller_ID*','Caller_ID','Segment_Code'];

                            if(!in_array($key,$staticField)){

                                $dynamicField = ['Service_Sub_Request_Type*', 'Complaint_Sub_Category*', 'Forward_To*','Check_List*'];
                                if (in_array($key,$dynamicField)) {
                                    $dynamicFieldFlag = 1;
                                }

                                if ($key == 'Customer_Name' || $key == 'Customer_Name*') {
                                    $customerName = $value;
                                }

                                ?>
                            @if($dynamicFieldFlag == 0)
                                @if(($key == 'Notes') && ($value == 'N/A'))
                                @else
                                    <tr> <th>{{str_replace('_',' ',$key)}}</th> <td>{{$value}}</td> </tr>
                                @endif
                            @else
                                @if(!empty($value) && ($value != 'N/A'))
                                    <tr> <th>{{str_replace('_',' ',$key)}}</th> <td>{{$value}}</td> </tr>
                                @endif
                            @endif
                            @if($key == 'Customer_DOB')
                                <tr><th colspan="2" class="text-left graybg"><h4><b>Action</b></h4></th></tr>
                            @endif
                            <?php } ?>
                        @ENDFOREACH
                    @ENDIF
                </table>


            </div>

            <div class="mt-2 mx-2">
                <p style="font-size: 0.7rem !important; margin-bottom: 0!important;">
                    <b><u>
                            Note: I/We declare that the above information provided by me/us are correct & do authorize the Bank to execute the same.
                        </u></b></p>
            </div>

            <!-- Signature Section -->
            <div class="mt-5">
                <div class="col-md-6-for">
                    <div class="border-top-color pt-2">
                        <p style="font-weight: 500;"class="m-0">Customer Signature</p>
                        <?php
                        // Get session data
                        $dataForPrint = session('dataForPrint');
                        $cust_name = $dataForPrint['Customer_Name'] ?? 'NA';
                        ?>
                        <p class="m-0" style="font-size: 12px">{{$cust_name}}</p>
                    </div>
                </div>
            </div>







            <div class="p-0 mt-4">
                <!-- Bank Use Only Section -->
                <div class="border">
                    <h6 class="text-center" style="font-size: 14px !important; font-width:bold"><b>Bank Use Only</b></h6>
                </div>
                <div class="border p-2">

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="confirmed1">
                        <label class="form-check-label" style="font-size: 14px !important;" for="confirmed1">Physical Presence</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="confirmed2">
                        <label class="form-check-label" style="font-size: 14px !important;" for="confirmed2">Signature & Contact detail has been verified from bank records</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="confirmed3">
                        <label class="form-check-label" style="font-size: 14px !important; padding-right: 5px" for="confirmed3">Customer signed in my presence</label>
                        {{-- </div>

                        <br>

                        <div class="form-check form-check-inline"> --}}
                        <input class="form-check-input" type="checkbox" id="supportingDocs">
                        <label class="form-check-label" style="font-size: 14px !important;" for="supportingDocs">Supporting documents (if any) .................................</label>
                    </div>

                    {{-- <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="remarks">
                        <label class="form-check-label" for="remarks">Remarks (if any)</label>
                    </div> --}}
                </div>
            </div>

            <!-- Signature Section -->
            <div class="row-for-footer text-center pt-3 mt-4">
                <div class="col-md-4-for-footer">
                    <div class="border-top-color pt-2">
                        <p style="font-weight: 500;"class="m-0">RM Signature</p>
                        <p style="font-size: 0.9rem;" class="m-0">Name:  {{Auth::user()->name}}</p>
                        <p style="font-size: 0.9rem;" class="m-0">Date: {{ \Carbon\Carbon::now()->format('d-m-Y') }} , Emp ID: {{Auth::user()->emp_id}}</p>
                        {{-- <p style="font-size: 0.9rem;" class="m-0">Emp ID: {{Auth::user()->emp_id}}</p> --}}
                        <br>

                    </div>
                </div>
                <div class="col-md-8-for-footer">
                    <div class="border-top-color pt-2">
                        <p style="font-weight: 500;" class="m-0">Operation Manager/Head of Branch/Division</p>
                    </div>
                </div>
            </div>

            {{-- <br>
            <br>
            <br>
            <div class="text-left pull-left footersign">
              <u>Customer Signature</u><br>
              {{$customerName}}
            </div>
            <div class="text-right footersign">
              <u>Bank Official Signature</u><br>
              {{Auth::user()->name}}<br>
              {{Auth::user()->designation}}
            </div> --}}
        </div>
    </div>
@endsection
<script type="text/javascript">
    window.print();
</script>
