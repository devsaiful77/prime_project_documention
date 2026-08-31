<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> {{ (!empty($title)) ? $title : "" }} </title>
        
        <link href="{{ URL::asset('public/favicon.ico') }}"rel="shortcut icon"/>

        <link href="{{ URL::asset('public/css/admin/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/nprogress.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/green.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/jqvmap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/select2.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/jquery-ui.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('public/css/admin/common.css') }}" rel="stylesheet">

     
        <script src="{{ URL::asset('public/js/admin/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('public/js/admin/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('public/js/admin/nprogress.js') }}"></script>
        <script src="{{ URL::asset('public/js/admin/jquery-ui.js') }}"></script>
        <script src="{{ URL::asset('public/js/admin/select2.full.js') }}"></script>
        <!-- Scripts -->
    </head>

    <body class="nav-md">
    <div class="container body clearfix">
        <div class="main_container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                
                  <div class="x_panel"> @yield('content') </div>
                </div>
            </div>
        </div>
    </div> 
    </body>
    <script src="{{ URL::asset('public/js/admin/custom.js') }}"></script>
    <script src="{{ URL::asset('public/js/admin/common.js') }}"></script>

</html>