<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Prime Bank PLC {{ (!empty($title)) ? $title : "" }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="Prime Bank PLC">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/img/favicon.ico') }}">
    {{-- <link rel="stylesheet" href="{{ asset('public/BBL_CI/css/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('public/BBL_CI/css/bootstrap-5.3.1.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/BBL_CI/vendors/jquery-ui/css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{ asset('/public/BBL_CI/vendors/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('public/BBL_CI/css/normalize.css') }}">
    {{-- <link href="{{ URL::asset('public/BBL_CI/css/select2.min.css') }}" rel="stylesheet" /> --}}
    <link href="{{ URL::asset('public/BBL_CI/css/select2-4.0.3.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('public/BBL_CI/css/style.css') }}">
    {{-- <script src="{{ URL::asset('public/BBL_CI/js/jquery.min.js') }}"></script> --}}
    <script src="{{ URL::asset('public/BBL_CI/js/jquery-3.7.1.min.js') }}"></script>


    @stack('css')

</head>
