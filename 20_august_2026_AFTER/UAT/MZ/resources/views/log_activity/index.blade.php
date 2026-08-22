<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/2/2020.
 */
?>
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
            <a href="{{ url('audit/create') }}" class="btn btn-primary float-right">Add New</a>
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Model</th>
            <th scope="col">Action</th>
            <th scope="col">Message</th>
            <th scope="col">Option</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $key=>$row)
            <tr>
                <th scope="row">{{ ++$key }}</th>
                <td>{{ $row->model }}</td>
                <td>{{ $row->action }}</td>
                <td>{{ $row->message }}</td>
                <td>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection


