@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Report
                </div>
            </div>
            <div class="panel-body" style="padding-bottom: 0">
                <div class="row">
{{--                    <section>--}}
                        <!-- class="sr-only" will hide from the screen -->
                        <form>
                            <table class="table table-bordered" style="margin-bottom: 0">
                                <tr>
                                    <td style="font-size: larger">Ticket Number</td>
                                    <td><input type="text" id="ticketNumber" name="ticketNumber" class="form-control" placeholder="Ticket Number" /></td>
                                    <td style="font-size: larger">Card / Account Number</td>
                                    <td><input type="text" id="cardAccountNumber" name="cardAccountNumber" class="form-control" placeholder="Card / Account Number" /></td>

                                    <td style="font-size: larger">Mobile Number</td>
                                    <td><input type="text" id="MobileNumber" name="MobileNumber" class="form-control" placeholder="Mobile Number" /></td>
                                    <td style="font-size: larger">Product Type</td>
                                    <td>
                                        <select id="productType" name="productType" class="form-control">
                                            <option>
                                                Type 1
                                            </option>
                                            <option>
                                                Type 2
                                            </option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td style="font-size: larger">Form Type</td>
                                    <td>
                                        <select id="formType" name="formType" class="form-control">
                                            <option>
                                                Type 1
                                            </option>
                                            <option>
                                                Type 2
                                            </option>
                                        </select>
                                    </td>
                                    <td style="font-size: larger">Form Sub Type</td>
                                    <td>
                                        <select id="formSubType" name="formSubType" class="form-control">
                                            <option>
                                                Type 1
                                            </option>
                                            <option>
                                                Type 2
                                            </option>
                                        </select>
                                    </td>

                                    <td style="font-size: larger">Date From</td>
                                    <td><input type="date" id="dateFrom" name="dateFrom" class="form-control" placeholder="Date From" /></td>
                                    <td style="font-size: larger">Date To</td>
                                    <td>
                                        <input type="date" id="dateTo" name="dateTo" class="form-control" placeholder="Date to" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form">
                                            <div class="col-xs-5 units" style="padding-left: 0;">
                                                <label style="display: flex; align-items: flex-start;">
                                                    <input style="margin-top: 0; margin-right: 3px" type="radio" id="actionDate" class="green reportRadio" name="dateType" value="actionDate" checked /> <span>Action Date.</span>
                                                </label>
                                            </div>
                                            <div class="col-xs-5 units">
                                                <label style="display: flex; align-items: flex-start">
                                                    <input style="margin-top: 0; margin-right: 3px" type="radio" id="createDate" class="green reportRadio" name="dateType" value="createDate" /> <span>Create Date</span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0!important;float: left!important;">
                                                <button type="submit"  class="btn btn-primary"><i class="fa fa-search"></i> &nbsp; Search</button>
{{--                                                <input type="submit" value="Submit" class="btn btn-primary" >--}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>


{{--                    </section>--}}
                </div>
            </div>
        <!-- row -->
    </div>
    <div class="panel panel-info">
        <div class="panel-body">
            <div class="row">
                <table class="commonDataTableAll table table-bordered">
                    <thead style="background-color: #337ab7;">
                    <tr>
                        <th style="color: white">Ticket Number</th>
                        <th style="color: white">Card / Acc Number</th>
                        <th style="color: white">Customer Name</th>
                        <th style="color: white">Product Type</th>
                        <th style="color: white">Service Request Type</th>
                        <th style="color: white">Log Time</th>
                        <th style="color: white">Status</th>
                        <th style="color: white">SLA</th>
                        <th style="color: white">Maker</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>John</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                        <td>John</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                        <td>John</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                    </tr>
                    <tr>
                        <td>rezaul</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                        <td>John</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                        <td>John</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- content container -->
@endsection

@section('extrajssection')

@endsection

