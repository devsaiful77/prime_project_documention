@extends('layouts.admin')

@section('content')
<div class="row row-stat">
    <div class="col-md-4">
        <div class="panel panel-success-alt noborder">
            <div class="panel-heading noborder">              
                <div class="panel-icon"><i class="fa fa-dollar"></i></div>
                <div class="media-body">
                    <h5 class="md-title nomargin"><strong>Today's Sale</strong></h5>
                    <h1 class="mt5">${{ number_format($allSleSumOfTdyTotal,2) }}</h1>
                    <div class="clearfix">&nbsp;</div>
                    <div class="pull-left">
                        <h5 class="md-title nomargin">Paid</h5>
                        <h4 class="nomargin">${{ number_format($allSlePaidTdyTotal,2) }}</h4>
                    </div>
                    <div class="pull-right">
                        <h5 class="md-title nomargin">Due</h5>
                        <h4 class="nomargin">${{ number_format($allSleUnpaidTdyTotal,2) }}</h4>
                    </div>
                </div><!-- media-body -->
                <hr>
                <div class="clearfix mt20">
                    <div class="pull-left">
                        <h5 class="md-title nomargin">Yesterday</h5>
                        <h4 class="nomargin">${{ number_format($allSleSumOfYstDayTotal,2) }}</h4>
                        <div class="">
                            <h5 class="md-title nomargin">Paid</h5>
                            <h4 class="nomargin">${{ number_format($allSlePaidYstDayTotal,2) }}</h4>
                        </div>
                        <div class="">
                            <h5 class="md-title nomargin">Due</h5>
                            <h4 class="nomargin">${{ number_format($allSleUnpaidYstDayTotal,2) }}</h4>
                        </div>
                    </div>
                    <div class="pull-right">
                        <h5 class="md-title nomargin">This Week</h5>
                        <h4 class="nomargin">${{ number_format($allSleSumOfLstWeekTotal,2) }}</h4>
                        <div class="">
                        <h5 class="md-title nomargin">Paid</h5>
                            <h4 class="nomargin">${{ number_format($allSlePaidLstWeekTotal,2) }}</h4>
                        </div>
                        <div class="">
                            <h5 class="md-title nomargin">Due</h5>
                            <h4 class="nomargin">${{ number_format($allSleUnpaidLstWeekTotal,2) }}</h4>
                        </div>
                    </div>
                </div>
                
            </div><!-- panel-body -->
        </div><!-- panel -->
    </div><!-- col-md-4 -->
    
    <div class="col-md-4">
        <div class="panel panel-primary noborder">
            <div class="panel-heading noborder">              
                <div class="panel-icon"><i class="fa fa-dollar"></i></div>
                <div class="media-body">
                    <h5 class="md-title nomargin"><strong>Today's Purchase</strong></h5>
                    <h1 class="mt5">${{ number_format($allPurSumOfTdyTotal,2) }}</h1>
                    <div class="clearfix">&nbsp;</div>
                    <div class="pull-left">
                        <h5 class="md-title nomargin">Paid</h5>
                        <h4 class="nomargin">${{ number_format($allPurPaidTdyTotal,2) }}</h4>
                    </div>
                    <div class="pull-right">
                        <h5 class="md-title nomargin">Due</h5>
                        <h4 class="nomargin">${{ number_format($allPurUnpaidTdyTotal,2) }}</h4>
                    </div>
                </div><!-- media-body -->
                <hr>
                <div class="clearfix mt20">
                    <div class="pull-left">
                        <h5 class="md-title nomargin">Yesterday</h5>
                        <h4 class="nomargin">${{ number_format($allPurSumOfYstDayTotal,2) }}</h4>
                        <div class="">
                            <h5 class="md-title nomargin">Paid</h5>
                            <h4 class="nomargin">${{ number_format($allPurPaidYstDayTotal,2) }}</h4>
                        </div>
                        <div class="">
                            <h5 class="md-title nomargin">Due</h5>
                            <h4 class="nomargin">${{ number_format($allPurUnpaidYstDayTotal,2) }}</h4>
                        </div>
                    </div>
                    <div class="pull-right">
                        <h5 class="md-title nomargin">This Week</h5>
                        <h4 class="nomargin">${{ number_format($allPurSumOfLstWeekTotal,2) }}</h4>
                        <div class="">
                        <h5 class="md-title nomargin">Paid</h5>
                            <h4 class="nomargin">${{ number_format($allPurPaidLstWeekTotal,2) }}</h4>
                        </div>
                        <div class="">
                            <h5 class="md-title nomargin">Due</h5>
                            <h4 class="nomargin">${{ number_format($allPurUnpaidLstWeekTotal,2) }}</h4>
                        </div>
                    </div>
                </div>
            </div><!-- panel-body -->
        </div><!-- panel -->
    </div><!-- col-md-4 -->
    
    <div class="col-md-4">
        <div class="panel panel-dark noborder">
            <div class="panel-heading noborder">              
                <div class="panel-icon"><i class="fa fa-dollar"></i></div>
                <div class="media-body">
                    <h5 class="md-title nomargin"><strong>Today's Expense</strong></h5>
                    <h1 class="mt5">${{ number_format($allExpSumOfTdyTotal,2) }}</h1>
                    <div class="clearfix">&nbsp;</div>                    
                </div><!-- media-body -->
                <hr>
                <div class="clearfix mt20">
                    <div class="pull-left">
                        <h5 class="md-title nomargin">Yesterday</h5>
                        <h4 class="nomargin">${{ number_format($allExpSumOfYstDayTotal,2) }}</h4>                        
                    </div>
                    <div class="pull-right">
                        <h5 class="md-title nomargin">This Week</h5>
                        <h4 class="nomargin">${{ number_format($allExpSumOfLstWeekTotal,2) }}</h4>                        
                    </div>
                </div>
            </div><!-- panel-body -->
        </div><!-- panel -->
    </div><!-- col-md-4 -->
</div><!-- row -->

@endsection
