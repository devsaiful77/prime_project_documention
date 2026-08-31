<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Prime Bank PLC. # CSCMS | {{ (!empty($title)) ? $title : "" }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/img/favicon.ico') }}">
    <!-- <link rel="stylesheet" href="{{ URL::asset('public/css/bootstrap.min.css') }}"> -->
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/bootstrap-5.3.1.min.css') }}"> {{-- bootstrap --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/common.css') }}"><!-- Custom CSS -->
    <script src="{{ URL::asset('public/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <!-- <script src="{{ URL::asset('public/js/vendor/jquery-3.6.0.min.js') }}"></script> -->
    <script src="{{ URL::asset('public/js/latest-v/jquery-3.7.1.min.js') }}"></script> {{-- jquery --}}
    <!-- <script src="{{ URL::asset('public/js/bootstrap.min.js') }}"></script> -->
    <script src="{{ URL::asset('public/js/latest-v/bootstrap-5.3.1.bundle.min.js') }}"></script> {{-- bootstrap --}}
    <title>{{ (!empty($title)) ? $title : "Print" }}</title>

    <style media="print"> @page {size: auto; /*margin: 0 50px;*/ } </style>
    <style type="text/css">
      tr {font-size: 12px;}
      .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {padding: 3px;
      }
      .h4, .h5, .h6, h4, h5, h6 {
          margin-top: 5px;
          margin-bottom: 5px;
      }
      .graybg{
        background-color: gainsboro;
      }
      .footersign{
        font-size: 12px;
      }
      .table {
          width: 100%;
          max-width: 100%;
          margin-bottom: 4px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      @yield('content')
    </div>
  </body>
</html>
