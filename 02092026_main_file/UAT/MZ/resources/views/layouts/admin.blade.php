<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Prime Bank PLC. # PrimeServe | {{ (!empty($title)) ? $title : "" }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/img/favicon.ico') }}">

    {{-- Stylesheets --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/bootstrap-5.3.1.min.css') }}"> {{-- bootstrap --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/meanmenu/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/animate.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/dataTables-1.13.6.min.css') }}"> {{-- datatable --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/notika-custom-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/icheck/all.css') }}">


    <link rel="stylesheet" href="{{ URL::asset('public/css/main.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/responsive.css') }}">

	<link rel="stylesheet" href="{{ URL::asset('public/vendors/jquery-ui/css/jquery-ui.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/select2-4.0.3.min.css') }}"> {{-- select 2 --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/common.css') }}"><!-- Custom CSS -->
    <link rel="stylesheet" href="{{ URL::asset('public/vendors/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/jquery-confirm-3.3.0.min.css') }}"> {{-- datatable --}}

    {{-- Asif For date picker --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/flatpickr.min.css') }}">


    {{-- JavaScript --}}
    <script src="{{ URL::asset('public/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/latest-v/jquery-3.7.1.min.js') }}"></script> {{-- jquery --}}
    <script src="{{ URL::asset('public/js/latest-v/highcharts-11.4.6.js') }}"></script>
    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
    <script src="{{ URL::asset('public/js/latest-v/jquery-ui-1.14.0.min.js') }}"></script>


    <script src="{{ URL::asset('public/js/latest-v/flatpickr-4.6.13.min.js') }}"></script>



    <script src="{{ URL::asset('public/js/vendor/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/latest-v/bootstrap-5.3.1.bundle.min.js') }}"></script> {{-- bootstrap --}}

    <script type="text/javascript">
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>;
        var base_url = "{{URL::to('/')}}";
        var controllerName = "{{$controller}}";
        var actionName = "{{$action}}";
        var currentUrl = "{{$currentUrl}}";
        var _token = "{{csrf_token()}}";
        var loginUsing = 1;
    </script>

    @stack('meta')
    @stack('stylesheets')

    <style type="text/css">
        .required { color: red; }
        .nav_menu .navbar-nav.navbar-right:last-child { margin-right: 0; }
        .jconfirm-scrollpane { overflow: hidden !important; }
        .custom-control-input:checked ~ .custom-control-label::before {
            color: #fff;
            border-color: #7B1FA2;
        }
        label {
            padding-top: 0.75rem;
        }
        .navbar li .nav-link.active{
            color: #fff;
        }
        .fixedHeader-floating{
            display: none !important;
        }.bd-highlight a:hover {
            background-color: transparent !important;
        }
        /* Additional styling for form inputs, radio buttons, tables, and footer */
    </style>
</head>

<body id="page-container">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Header Top Area -->
    @include('includes/header')

    <!-- Prompt to change password if needed -->
    @if(user_password_change())
        <div class="container">
            <div class="bg-info alert" role="alert">
                <strong>Please change your password.</strong>
                <a href="{{ url('/ResetPassword') }}">Change Password</a>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <!-- Main Menu Area -->
    @include('includes/main_menu')

    <!-- Main Content Area -->
    @include('includes/maincontent')

    <!-- Footer Area -->
    @include('includes/footer')

    <div class="overlay" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-4x"></i>
        <p>Please wait...</p>
    </div>


    @stack('scripts')

    {{-- Additional JavaScript --}}
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
    <script src="{{ URL::asset('public/js/common.js?'.time()) }}"></script>
    <script src="{{ URL::asset('public/vendors/summernote/summernote-bs4.min.js') }}"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('.summernote').summernote({
            fontNames: ['Exo 2', 'Sans-serif','Arial', 'Helvetica', 'Times New Roman', 'Courier New', 'Verdana'],
            height: 300 // Adjust the height as needed
        });

        $('#data-table-basic').DataTable();

        $('.commonDataTableAllAsc').DataTable({
            "order": [[ 0, "desc" ]],
            columnDefs: [{ orderable: false, targets: -1 }],
            fixedHeader: {
                header: true
            }
        });

        $('.commonDataTableAll').DataTable({
            "order": [[ 0, "desc" ]],
            fixedHeader: {
                header: true
            }
        });

        jQuery(".select2").select2({ placeholder: "Please Select" });
        jQuery(".select2_subgroup").select2({ placeholder: "Please Select" });
        jQuery(".select2_group").select2({ placeholder: "Please Select" });
        jQuery(".select2_department").select2({ placeholder: "Please Select" });
    });



    $(document).ready(function() {
        $(".btn-confirm").on("click", function () {
            var action = $(this).attr('data-action');
            $(".confirm").attr('href', action);
        });

        if ($.fn.DataTable.isDataTable('#data-table-basic')) {
            $('#data-table-basic').DataTable().destroy();
        }
        $('#data-table-basic').DataTable();

        if ($.fn.DataTable.isDataTable('.commonDataTableAllAsc')) {
            $('.commonDataTableAllAsc').DataTable().destroy();
        }
        $('.commonDataTableAllAsc').DataTable({
            "order": [[ 0, "desc" ]],
            columnDefs: [{ orderable: false, targets: -1 }],
            fixedHeader: {
                header: true
            }
        });

        if ($.fn.DataTable.isDataTable('.commonDataTableAll')) {
            $('.commonDataTableAll').DataTable().destroy();
        }
        $('.commonDataTableAll').DataTable({
            "order": [[ 0, "desc" ]],
            fixedHeader: {
                header: true
            }
        });

        jQuery(".select2").select2({ placeholder: "Please Select" });
        jQuery(".select2_subgroup").select2({ placeholder: "Please Select" });
        jQuery(".select2_group").select2({ placeholder: "Please Select" });
        jQuery(".select2_department").select2({ placeholder: "Please Select" });
    });


    $(document).ready(function() {
        $(".btn-confirm").on("click", function () {

            var action = $(this).attr('data-action');
            $(".confirm").attr('href', action);

        });
    });
    </script>

    <div class="modal fade" id="confirm-delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to request for delete?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-danger confirm btn-sm">Confirm</a>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="confirm-requested-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-danger confirm btn-sm">Confirm</a>
                </div>
            </div>
        </div>
    </div>
	
@yield('extrajssection')
@yield('script')

</body>
</html>
