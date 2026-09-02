@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <legend>Dashboard</legend>
    </div>
    <div class="card-body">
        {!! Form::open(['method'=>'get', 'class'=>'form-horizontal row', 'action' => ['ReportsController@index'] ,
        'enctype' => 'multipart/form-data']); !!}
            <div class="col-md-3">
                <div class="form-group">
                    <label>Date From</label>
                    {{-- <input type="text" name="date_from" class="datePicker common-class date-from form-control
                    datePickerThreeFromCurr" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" readonly  autocomplete="off">
                     --}}
                    <input type="text" name="date_from" class="common-class date-from form-control
                    datePickerThreeFromCurr" placeholder="Date From" value="{{ $searchDataForView['date_from'] }}" readonly  autocomplete="off">
                    

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Date to</label>
                    {{-- <input type="text" name="date_to" class="datePicker common-class date-to form-control datePickerThreeToCurr"
                           placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" readonly  autocomplete="off"> --}}

                           <input type="text" name="date_to" class="common-class date-to form-control datePickerThreeToCurr"
                           placeholder="Date To" value="{{ $searchDataForView['date_to'] }}" readonly  autocomplete="off">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Total Item</label>
                    <input type="text" name="qty" class="common-class qty form-control" placeholder="Total Item" value="{{ $searchDataForView['qty'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Top Low Based</label>
                    {{ Form::select('tl_based', $tlBasedOption, (!empty($searchDataForView["tl_based"])) ? $searchDataForView["tl_based"] : 'wform', ['class'=>'common-class form-control tl-based']) }}
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
        {!! Form::close(); !!}
    </div>
    <div class="gen-dashboard db-common-cls">
        <div class=" ">
            <div id="workingStatus" style="display: block; float: left; width: 50%"></div>
            <div id="requestComplain" style="display: block; float: right; width: 50%"></div>
        </div>
        <div class=" " style="padding-bottom: 5%">
            <div id="workingStatusBar" style="display: block; float: left; width: 50%"></div>
            <div id="requestComplainBar" style="display: block; float: right; width: 50%"></div>
        </div>
    </div>
    <div class="srt-dashboard db-common-cls">
        <div id="serviceRequestTypeBar" style="display: block; float: left; width: 100%"></div>
    </div>
    <div class="ct-dashboard db-common-cls">
        <div id="complaintTypeBar" style="display: block; float: left; width: 100%"></div>
    </div>
    <div class="srpl-dashboard db-common-cls">
        <div id="serviceRequestPendingListBar" style="display: block; float: left; width: 100%"></div>
    </div>
    <div class="cpl-dashboard db-common-cls">
        <div id="complaintPendingListBar" style="display: block; float: left; width: 100%"></div>
    </div>
    <div class="srsb-dashboard db-common-cls">
        <div id="serviceRequestSLABreachBar" style="display: block; float: left; width: 100%"></div>
    </div>
    <div class="csb-dashboard db-common-cls">
        <div id="complaintSLABreachBar" style="display: block; float: left; width: 100%"></div>
    </div>
</div>

{{-- @endsection

@section('extrajssection') --}}
<script type="text/javascript">
    $(".qty").val('');
    $(".qty").prop('disabled',true);
    //$(".date-from").val('');
    //$(".date-to").val('');
    //$(".date-from").prop('disabled',true);
    //$(".date-to").prop('disabled',true);


    $(".common-class").on('change keyup',function(event){
        genDashBoard();

    });

    // $('.datePickerThreeFromCurr').datepicker({
    //     dateFormat: 'dd-mm-yy',
    //     changeYear: true,
    //     changeMonth: true,
    //     onSelect: function(dateText) {
    //         var dateArr = dateText.split("-");
    //         var fromDateObj = new Date(dateArr[2],(dateArr[1]-1),dateArr[0]);
    //         $('.datePickerThreeToCurr').datepicker('option', 'minDate', fromDateObj );
    //         $('.datePickerThreeToCurr').datepicker('option', 'maxDate', fromDateObj.addDays(30) );
    //         if($('.datePickerThreeToCurr').val() == ""){
    //             // $('.datePickerThreeToCurr').val($('.datePickerThreeFromCurr').val());
    //         }
    //         genDashBoard();
    //     }
    // });

    // $('.datePickerThreeToCurr').datepicker({
    //     dateFormat: 'dd-mm-yy',
    //     changeYear: true,
    //     changeMonth: true,
    //     onSelect: function(dateText) {
    //         var dateArr = dateText.split("-");
    //         var toDateObj = new Date(dateArr[2],(dateArr[1]-1),dateArr[0]);
    //         // $('.datePickerThreeFromCurr').datepicker('option', 'minDate', toDateObj.addDays(-30) );
    //         $('.datePickerThreeFromCurr').datepicker('option', 'maxDate', toDateObj );
    //         if($('.datePickerThreeFromCurr').val() == ""){
    //             // $('.datePickerThreeFromCurr').val($('.datePickerThreeToCurr').val());
    //         }
    //         genDashBoard();
    //     }
    // });


// By Asif
    // Initialize the From Date Picker with Flatpickr
flatpickr('.datePickerThreeFromCurr', {
    dateFormat: 'd-m-Y',  // Flatpickr date format (day-month-year)
    changeYear: true,
    changeMonth: true,
    onChange: function(selectedDates, dateStr, instance) {
        // Get the selected 'From' date
        var fromDateObj = selectedDates[0];
        
        // Set the minDate and maxDate for the 'To' date picker
        var toDatePicker = document.querySelector('.datePickerThreeToCurr')._flatpickr;
        toDatePicker.set('minDate', fromDateObj); // Set minDate to selected 'From' date
        toDatePicker.set('maxDate', new Date(fromDateObj.getTime() + 30 * 24 * 60 * 60 * 1000)); // 30 days after the 'From' date

        // Optionally set 'To' date if empty
        if (document.querySelector('.datePickerThreeToCurr').value === "") {
            toDatePicker.setDate(dateStr);
        }

        // Call the function to update the dashboard
        genDashBoard();
    }
});

// Initialize the To Date Picker with Flatpickr
flatpickr('.datePickerThreeToCurr', {
    dateFormat: 'd-m-Y',  // Flatpickr date format (day-month-year)
    changeYear: true,
    changeMonth: true,
    onChange: function(selectedDates, dateStr, instance) {
        // Get the selected 'To' date
        var toDateObj = selectedDates[0];

        // Set the maxDate for the 'From' date picker
        var fromDatePicker = document.querySelector('.datePickerThreeFromCurr')._flatpickr;
        fromDatePicker.set('maxDate', toDateObj); // Set maxDate to selected 'To' date

        // Optionally set 'From' date if empty
        if (document.querySelector('.datePickerThreeFromCurr').value === "") {
            fromDatePicker.setDate(dateStr);
        }

        // Call the function to update the dashboard
        genDashBoard();
    }
});


    function genDashBoard(){
        var tlbased = $(".tl-based :selected").val();
        var datefrom = $(".date-from").val();
        var dateto = $(".date-to").val();

        if(datefrom == ''){
            customAlert('Information','Please select start date and end date.','red');
        }

        //var dateto = $(".date-to").val();
        var qty = $(".qty").val();
        if(qty > 15){
            customAlert('Information','Please write a value within 15','red');
            $(".qty").val('15');
        }
        if (tlbased == 'gen') {
            $(".qty").val('');
            $(".qty").prop('disabled',true);
            //$(".date-from").val('');
            //$(".date-to").val('');
            //$(".date-from").prop('disabled',true);
            //$(".date-to").prop('disabled',true);
        } else {
            //$(".date-from").prop('disabled',false);
            //$(".date-to").prop('disabled',false);
            $(".qty").prop('disabled',false);
        }

        $(".db-common-cls").hide();
        $("."+tlbased+"-dashboard").show();

        var dateReg = /^\d{2}([./-])\d{2}\1\d{4}$/;

        if (datefrom && dateto) {
            if (datefrom.match(dateReg) != null) {
                getDashboardData(tlbased,datefrom,dateto,qty);
            }
            if (dateto.match(dateReg) != null) {
                getDashboardData(tlbased,datefrom,dateto,qty);
            }
        } else {
            //getDashboardData(tlbased,datefrom,dateto,qty);
        }
    }

    var tlbased = $(".tl-based :selected").val();
    var datefrom = $(".date-from").val();
    var dateto = $(".date-to").val();

    $("."+tlbased+"-dashboard").show();

    if (datefrom && dateto) {
        getDashboardData(tlbased);
    }else{
        customAlert('Information','Please select start date and end date.','red');
    }


    function getDashboardData(tlbased,datefrom,dateto,qty) {
        overlay('show');
        var func_param_obj = {'tlbased':tlbased,'datefrom':datefrom,'dateto':dateto};
        if (tlbased == "gen") {
            $.ajax({
                type: "post",
                url: "{{url('Dashboards/GetWStatusCompRate')}}",
                data: {_token: _token, date_from:datefrom, date_to:dateto, qty:qty},
                dataType: "json",
                success: function(data){
                    overlay('hide');
                    if (data.ws) {
                        pieHighChart('workingStatus','Working Status',data.ws,func_param_obj);
                    }
                    if (data.wsn) {
                        func_param_obj['preventClick'] = "true";
                        columnHighChart('workingStatusBar','Working Status',['Work In Progress', 'Hold Form', 'Pending Form', 'Close Form'],data.wsn,func_param_obj);
                    }
                    if (data.totsr) {
                        pieHighChart('requestComplain','Request & Complaint Rate',data.totsr,func_param_obj);
                    }
                    if (data.totsrnc) {
                        barHighChart("requestComplainBar","Number of Request & Complain","Year Wise",["Request","Complaint","Non Customer"],"Yearly Breakdown", data.totsrnc,func_param_obj);
                    }
                },
                error: function(data){overlay('hide');}
            });
        } else if (tlbased == "srt") {
            $.ajax({
                type: "post",
                url: "{{url('Dashboards/ServiceRequestType')}}",
                data: {_token: _token, date_from:datefrom, date_to:dateto, qty:qty},
                dataType: "json",
                success: function(data){
                    overlay('hide');
                    if (data.srt) {
                        columnHighChart('serviceRequestTypeBar','Service Request Type',data.srt_title,data.srt,func_param_obj);
                    }
                },
                error: function(data){overlay('hide');}
            });
        } else if (tlbased == "ct") {
            $.ajax({
                type: "post",
                url: "{{url('Dashboards/ComplaintType')}}",
                data: {_token: _token, date_from:datefrom, date_to:dateto, qty:qty},
                dataType: "json",
                success: function(data){
                    overlay('hide');
                    if (data.ct) {
                        columnHighChart('complaintTypeBar','Complaint Type',data.ct_title,data.ct,func_param_obj);
                    }
                },
                error: function(data){overlay('hide');}
            });
        } else if (tlbased == "srpl") {
            $.ajax({
                type: "post",
                url: "{{url('Dashboards/ServiceRequestPendingList')}}",
                data: {_token: _token, date_from:datefrom, date_to:dateto, qty:qty},
                dataType: "json",
                success: function(data){
                    overlay('hide');
                    if (data.srpl) {
                        columnHighChart('serviceRequestPendingListBar','Service Request Pending List',data.srpl_title,data.srpl,func_param_obj);
                    }
                },
                error: function(data){overlay('hide');}
            });
        } else if (tlbased == "cpl") {
            $.ajax({
                type: "post",
                url: "{{url('Dashboards/ComplaintPendingList')}}",
                data: {_token: _token, date_from:datefrom, date_to:dateto, qty:qty},
                dataType: "json",
                success: function(data){
                    overlay('hide');
                    if (data.cpl) {
                        columnHighChart('complaintPendingListBar','Complaint Pending List',data.cpl_title,data.cpl,func_param_obj);
                    }
                },
                error: function(data){overlay('hide');}
            });
        } else if (tlbased == "srsb") {
            $.ajax({
                type: "post",
                url: "{{url('Dashboards/ServiceRequestSLABreach')}}",
                data: {_token: _token, date_from:datefrom, date_to:dateto, qty:qty},
                dataType: "json",
                success: function(data){
                    overlay('hide');
                    if (data.srsb) {
                        columnHighChart('serviceRequestSLABreachBar','Service Request SLA Breach',data.srsb_title,data.srsb,func_param_obj);
                    }
                },
                error: function(data){overlay('hide');}
            });
        } else if (tlbased == "csb") {
            $.ajax({
                type: "post",
                url: "{{url('Dashboards/ComplaintSLABreach')}}",
                data: {_token: _token, date_from:datefrom, date_to:dateto, qty:qty},
                dataType: "json",
                success: function(data){
                    overlay('hide');
                    if (data.csb) {
                        columnHighChart('complaintSLABreachBar','Complaint SLA Breach',data.csb_title,data.csb,func_param_obj);
                    }
                },
                error: function(data){overlay('hide');}
            });
        }

    }
    function pieHighChart(chartId="",title="",seriesData=[],funcParamObj=[]) {
        Highcharts.chart(chartId, {chart: {type: 'pie', options3d: {enabled: true, alpha: 45 } }, title: {text: title }, tooltip: {pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'}, series: [{name: 'Percentage', colorByPoint: true, data: seriesData }] });
    }
    function columnHighChart(chartId="",title="",categories=[],seriesData=[],funcParamObj=[]) {
        $('#' + chartId).highcharts({chart: {type: 'column', backgroundColor: '#FFFFFF', }, title: {text: title, style: {color: '#black'} }, xAxis: {tickWidth: 0, labels: {style: {color: '#black', } }, categories: categories }, yAxis: {gridLineWidth: .5, gridLineDashStyle: 'dash', gridLineColor: 'black', title: {text: '', style: {color: '#fff'} }, labels: {formatter: function() {return Highcharts.numberFormat(this.value, 0, '', ','); }, style: {color: '#fff', } } }, legend: {enabled: false, }, credits: {enabled: false }, tooltip: {valuePrefix: ''}, plotOptions: {column: {borderRadius: 0, pointPadding: 0, groupPadding: 0.05, point: {events: {click: function(event) {funcParamObj['req_name'] = this.name; if (!funcParamObj.preventClick) {DetailReport(funcParamObj, this.name); } } } } } }, series: [{lineColor: '#d8efe3', name: 'Total', data: seriesData, colorByPoint: true, }] });
    }
    function barHighChart(chartId="",title="",subtitle="",xAxisCat=[],yAxisTitle="",seriesData=[],funcParamObj=[]) {
        $('#' + chartId).highcharts({chart: {type: 'bar'}, title: {text: title }, subtitle: {text: subtitle }, xAxis: {categories: xAxisCat, title: {text: null } }, yAxis: {min: 0, title: {text: yAxisTitle, align: 'high'}, labels: {overflow: 'justify'} }, tooltip: {valueSuffix: ''}, colors : ['#16A085', '#9E2B3E', '#418E79'], plotOptions: {bar: {dataLabels: {enabled: true } }, column: {colorByPoint: true } }, credits: {enabled: false }, series: seriesData });
    }
    function DetailReport(params)
    {
        let paramsUrl = new URLSearchParams(params).toString();

        var link = "{{url('Dashboards/DetailsReport')}}?"+paramsUrl;
        // var url = encodeURI(link, "UTF-8");
        window.open(link, "_blank");
    }

    /* plotOptions: {column: {pointPadding: 0.2, borderWidth: 0, cursor: 'pointer', point:{events:{click: function(event) {DetailReport(formType,formSubType,this.name); } } } }, }, */

    /*Highcharts.chart('workingStatus', {chart: {type: 'pie', options3d: {enabled: true, alpha: 45 } }, title: {text: 'Working Status'}, tooltip: {pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'}, series: [{name: 'Brands', colorByPoint: true, data: [{name: 'Chrome', y: 60, }, {name: 'Edge', y: 40, sliced: true, }] }] });*/

    /*Highcharts.chart('requestComplain', {chart: {type: 'pie', options3d: {enabled: true, alpha: 45 } }, title: {text: 'Request & Complain Rate'}, tooltip: {pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'}, series: [{name: 'Brands', colorByPoint: true, data: [{name: 'Chrome', y: 60 }, {name: 'Edge', y: 40, sliced: true, }] }] }); Highcharts.setOptions({colors: ['#67BCE6'], chart: {style: {fontFamily: 'sans-serif', color: '#fff'} } });*/

    /*$('#requestComplainBar').highcharts({chart: {type: 'bar'}, title: {text: 'Number of Request & Complain'}, subtitle: {text: 'Source: P.A. Dept. of Education'}, xAxis: {categories: ['Young Scholars Charter', 'Folk Arts-Cultural Treasures', 'Christopher Columbus', 'MaST', 'Franklin Towne HS', 'Laboratory Charter', ], title: {text: null } }, yAxis: {min: 0, title: {text: '*Renaissance charter school', align: 'high'}, labels: {overflow: 'justify'} }, tooltip: {valueSuffix: ''}, plotOptions: {bar: {dataLabels: {enabled: true } } }, credits: {enabled: false }, series: [{name: 'Year 2020', data: [233, 55] }, {name: 'Year 2019', data: [22, 33] }] series: [{data: [89.2, 88.4, 87.7, 86.6, 86.1, ], name: 'School Performance Profile (SPP) Score'}] });*/

    /*$('#workingStatusBar').highcharts({chart: {type: 'column', backgroundColor: '#36394B'}, title: {text: 'Working status', style: {color: '#fff'} }, xAxis: {tickWidth: 0, labels: {style: {color: '#fff', } }, categories: ['Management', 'Marketing', 'Law', 'Back End', 'Front End', 'Audio/Video', 'Database', 'Servers', 'Writing', 'Design'] }, yAxis: {gridLineWidth: .5, gridLineDashStyle: 'dash', gridLineColor: 'black', title: {text: '', style: {color: '#fff'} }, labels: {formatter: function() {return Highcharts.numberFormat(this.value, 0, '', ','); }, style: {color: '#fff', } } }, legend: {enabled: false, }, credits: {enabled: false }, tooltip: {valuePrefix: ''}, plotOptions: {column: {borderRadius: 0, pointPadding: 0, groupPadding: 0.05 } }, series: [{name: 'People', data: [690, 938, 612, 4250, 2852, 1002, 728, 1156, 956, 4487 ] }] });*/
</script>
@endsection

