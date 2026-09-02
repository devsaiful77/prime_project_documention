<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/17/2020.
 */
?>
@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ url('workflow/special-permission/create') }}" class="btn btn-primary float-right btn-sm">Add New</a>
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Issue</th>
            <th scope="col">Subgroup</th>
            <th scope="col">Log</th>
            <th scope="col">Execute</th>
            {{--<th scope="col">Send Back</th>
            <th scope="col">Can't Reach to customer</th>--}}
            <th scope="col">Option</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $key=>$row)
            <tr>
                <th scope="row">{{ ++$key }}</th>
                <td>{{ $row->issue_name}}</td>
                <td>{{ $row->name }}</td>
                <td>@if($row->log==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                <td>@if($row->execute==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                {{--<td>@if($row->send_back==1) {{'Yes'}} @else {{ 'No' }} @endif</td>
                <td>@if($row->cant_reach_to_customer==1) {{'Yes'}} @else {{ 'No' }} @endif</td>--}}
                <td>
                    <a href="{{ url('workflow/special-permission/destroy/'.$row->issue_subgroup_workflow_id) }}" class="btn btn-danger btn-sm">Delete</a>
                </td>
                <td>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
