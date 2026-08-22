<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Prime Bank Limited # PrimeServe | {{ (!empty($title)) ? $title : "" }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/BBL_CI/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/BBL_CI/css/style.css') }}">


    <style>
        .service_txt{
            font-size: 14px;
            font-weight: 500;
            color: #fff;
        }
        .card{
            background: #03427e;
        }
    </style>

</head>

<body class="body-bg">

<div class="container ci-btn">
    <div class="row g-0">
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 align-self-center">
            <div class="card border-0">
                <div class="card-body">
                    <div class="">
                        @php $uniqueId = mt_rand(100000, 999999); @endphp
                       <a href="{{ url('/') . '/access-CI?CIFNumber=988503&sessionId=' . $uniqueId}}" class="text-decoration-none text-center text-dark fs-5">
                            <img class="img-fluid mx-auto d-block"
                                 src="{{ url('/public/img/logo/Logo.png') }}"
                                 width="250px"
                                 alt="">
                            <p class="service_txt">Customer Service</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('public/BBL_CI/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('public/BBL_CI/js/modernizr-3.12.0.min.js') }}"></script>
<script src="{{ URL::asset('public/BBL_CI/js/app.js') }}"></script>
@stack('js')
</body>
</html>
