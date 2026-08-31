<!DOCTYPE html>
<html lang="en">
<head>
    <title>Prime Bank PLC. || PrimeServe</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/prime/prime.png') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/login_update.css') }}" />
    <style type="text/css">
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="limiter">
    <div class="container-login-update" style="background-image: url({{  URL::asset('public/login_asset_v3/images/img-01.jpg') }});">
        <div class="wrap-login100 p-b-30">
            <div class="" style="text-align: center; padding-bottom: 10px; padding-top: 100px;">
                <img class="brand-logo" src="{{asset('public/login_asset_v3/images/Logo-prime.png')}}" alt="Prime Bank PLC"/>
            </div>
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
