<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Prime Bank PLC. # CSCMS | {{ (!empty($title)) ? $title : "" }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/img/favicon.ico') }}">

    <!--<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">-->
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/bootstrap-5.3.1.min.css') }}"> {{-- bootstrap --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/meanmenu/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/animate.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/dataTables-1.13.6.min.css') }}"> {{-- datatable --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/notika-custom-icon.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/main.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/vendors/jquery-ui/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/select2-4.0.3.min.css') }}"> {{-- select 2 --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/jquery-confirm-3.3.0.min.css') }}"> {{-- datatable --}}

    <link rel="stylesheet" href="{{ URL::asset('public/css/data-table/fixedHeader.dataTables.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('public/css/common.css') }}"><!-- Custom CSS -->
    <script src="{{ URL::asset('public/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/latest-v/jquery-3.7.1.min.js') }}"></script> {{-- jquery --}}
    <script src="{{ URL::asset('public/js/vendor/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/latest-v/bootstrap-5.3.1.bundle.min.js') }}"></script> {{-- bootstrap --}}


    <script type="text/javascript">
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;

        var base_url = "{{URL::to('/')}}";
        var controllerName = "{{$controller}}";
        var actionName = "{{$action}}";
        var currentUrl = "{{$currentUrl}}";
        var _token     = "{{csrf_token()}}";
        var loginUsing = 1;
    </script>

    @stack('meta')
    @stack('stylesheets')

    <style type="text/css">
        .required{color: red; }
        .nav_menu .navbar-nav.navbar-right:last-child {margin-right: 0; }
        .jconfirm-scrollpane {overflow: hidden !important; }
    </style>
    <?php $accessControlData = array(); $branchName = ""; $roleName = ""; $branchName = ""; ?>
</head>

<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <!-- Start Header Top Area -->
    @include('includes/root_header')
    @if(user_password_change())
        <div class="container">
            <div class="bg-info alert" role="alert">
                <strong>Please change your password.</strong>
                <a href="{{ url('/ResetPassword') }}">
                    Change Password
                </a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <!-- End Header Top Area -->
    <!-- Mobile Menu start -->
    {{--@include('includes/mobile_menu')--}}
    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
    {{--@include('includes/main_menu')--}}
    <!-- Main Menu area End-->
    <!-- Breadcomb area Start-->
    {{-- @include('includes/breadcomb') --}}
    <!-- Breadcomb area End-->
    <!-- Main Content area Start-->
    @include('includes/maincontent')
    <!-- Main Content area End-->
    <!-- Start Footer area-->
    @include('includes/footer')
    <!-- End Footer area-->
    <div class="overlay" style="display: none;"><i class="fa fa-spinner fa-spin fa-4x"></i><p>Please wait...</p></div>

    @stack('scripts')

    <script src="{{ URL::asset('public/js/wow.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/meanmenu/jquery.meanmenu.js') }}"></script>
    <script src="{{ URL::asset('public/js/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/counterup/waypoints.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/counterup/counterup-active.js') }}"></script>
    <script src="{{ URL::asset('public/js/icheck/icheck.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/icheck/icheck-active.js') }}"></script>
    <script src="{{ URL::asset('public/js/plugins.js') }}"></script>
    <script src="{{ URL::asset('public/js/main.js') }}"></script>
    <script src="{{ URL::asset('public/js/latest-v/jquery.dataTables-1.13.6.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/data-table/dataTables.fixedHeader.js') }}"></script>
    <script src="{{ URL::asset('public/vendors/jquery-ui/js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('public/js/latest-v/select2-4.0.3.min.js') }}"></script>
    <script src="{{ URL::asset('public/vendors/jquery-confirm/js/jquery-confirm.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/common.js') }}"></script>
    <script type="text/javascript">
        (function ($){
            "use strict";
            $(document).ready(function() {
                $('#data-table-basic').DataTable();
                $('.commonDataTableAllAsc').DataTable({"order": [[ 0, "desc" ]], fixedHeader: {header: true } });
                $('.commonDataTableAll').DataTable({"order": [[ 0, "desc" ]], fixedHeader: {header: true } });
                jQuery(".select2").select2({placeholder: "Please Select"});
            })
        })(jQuery);
    </script>
    @yield('extrajssection')
    @yield('script')
</body>

</html>
