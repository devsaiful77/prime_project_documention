@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="card card-default">
        <div class="card-header" style="color:#00AA00;">
            <strong>Welcome to PrimeServe (service accelerator platform)</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-6">Name : </div>
                <div class="col-lg-10 col-md-8 col-sm-6"> {{ Auth::user()->name }} </div>
                <div class="col-lg-2 col-md-4 col-sm-6">Email : </div>
                <div class="col-lg-10 col-md-8 col-sm-6">{{ Auth::user()->email }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6">Designation : </div>
                <div class="col-lg-10 col-md-8 col-sm-6">{{ Auth::user()->designation }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
