<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/2/2020.
 */
?>
@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12 text-right">
            @if($checker == false)
                @if(count($rows)==0)
                <a href="{{ url('settings/create') }}" class="btn btn-primary float-right btn-sm">Add New</a>
                @endif
            @endif
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Session Time (Minute)</th>
            <th scope="col">CI Session Time (Minute)</th>
            {{--<th scope="col">CI OTP Sent</th>--}}
            <th scope="col">Password Change Time</th>
            <th scope="col">Is Ip Restriction</th>
            <th scope="col">SLA Blink Time(Minute)</th>
            <th scope="col">SLA Email Time(Minute)</th>
            <th scope="col">Forward Email Time(Minute)</th>
			<th scope="col">Non Customer SMS/Email</th>
            @if($checker == false)
            <th scope="col">Option</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $key=>$row)
        <tr>
            <th scope="row">{{ ++$key }}</th>
            <td>{{ $row->session_lifetime }}</td>
            <td>{{ $row->ci_session_time }}</td>
            {{--<td>{{ $row->ci_otp_sms_email }}</td>--}}
            <td>{{ $row->password_change_time }}</td>
            <td>@if($row->allow_ip_restriction == 1) <button class="btn btn-info">{{'Yes'}}</button> @else <button class="btn btn-danger">{{'No'}}</button> @endif</td>
            <td>{{ $row->sla_blink }}</td>
            <td>{{ $row->sla_email_time }}</td>
            <td>{{ $row->forward_time }}</td>
			<td>@if($row->noncustomersms == 1) <button class="btn btn-info">{{'Yes'}}</button> @else <button class="btn btn-danger">{{'No'}}</button> @endif</td>
            @if($checker == false)
            <td>
                <a href="{{ url('/settings/edit/'.encrypt($row['setting_id'])) }}" class="btn btn-primary btn">Edit</a>
            </td>
            @endif
            {{-- <th class="vcenter text-left"> <a href="{{ url('/settings/approve',5) }}" class="btn btn-primary gradient ajax_page" title="Approve" escape="false"> <i class="fa fa-plus"></i> Approve</a> </th> --}}

        </tr>
        @endforeach
        </tbody>
    </table>
@endsection

