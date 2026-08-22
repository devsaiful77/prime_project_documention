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
        .d-done {
            display: none;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            color: rgb(34 30 31);
            font-weight: bold;
            text-decoration: none;
            z-index: 999;
            border: 1px solid #166834;
            border-radius: 4px;
            padding: 5px;
        }
        .logout-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="limiter">
    <div class="container-login-update" style="background-image: url({{  URL::asset('public/login_asset_v3/images/img-01.jpg') }});">
        <div class="wrap-login100 p-b-30">
            <a href="{{ url('/logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="logout-btn d-done">
                Logout&nbsp;<i class="fa fa-sign-out-alt"></i>
            </a>
            <div class="" style="text-align: center; padding-bottom: 10px; padding-top: 100px;">
                <img class="brand-logo" src="{{asset('public/login_asset_v3/images/Logo-prime.png')}}" alt="Prime Bank PLC"/>
            </div>
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
