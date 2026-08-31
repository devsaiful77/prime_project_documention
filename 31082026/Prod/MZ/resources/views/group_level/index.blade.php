<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 4/12/2020.
 */
?>
@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ url('group-level/create') }}" class="btn btn-primary float-right">Add New</a>
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Group Level</th>
            <th scope="col">Option</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $key=>$row)
            <tr>
                <th scope="row">{{ ++$key }}</th>
                <td>{{ $row->name }}</td>

                <td>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
