<!doctype html>
<html class="no-js" lang="">

@include('BBL_CI.include.header')
    <style>
        .loadingOverlay {
            background: rgba(0, 0, 0, 0.7);
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 9999;
        }


        .loadingOverlay .spinWrap {
            position: relative;
            width: 100%;
            height: 90px;
            margin: 0 auto;
            top: calc(50% - 45px);
            background: #fff;
        }

        @keyframes spinner {
            0%    { opacity: 1; transform: translate(0 0) }
        49.99% { opacity: 1; transform: translate(40px,0) }
        50%    { opacity: 0; transform: translate(40px,0) }
        100%    { opacity: 0; transform: translate(0,0) }
        }
        @keyframes loader {
            0% { transform: translate(0,0) }
        50% { transform: translate(40px,0) }
        100% { transform: translate(0,0) }
        }
        .loader div {
        position: absolute;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        top: 10px;
        left: 20px;
        }
        .loader div:nth-child(1) {
        background: #263d88;
        animation: loader 1.3333333333333333s linear infinite;
        animation-delay: -0.6666666666666666s;
        }
        .loader div:nth-child(2) {
        background: #2193d1;
        animation: loader 1.3333333333333333s linear infinite;
        animation-delay: 0s;
        }
        .loader div:nth-child(3) {
        background: #0072bc;
        animation: spinner 1.3333333333333333s linear infinite;
        animation-delay: -0.6666666666666666s;
        }
        .spiner_outer {
        width: 120px;
        height: 100px;
        display: inline-block;
        overflow: hidden;
        position: relative;
        top: calc(50% - 50px);
        left: calc(50% - 60px);
        text-align: center;
        }
        .Margin_set{
            margin-bottom: -10%;
            margin-left: -10%;
        }
        .spiner_outer p{color:#fff;}
        .loader {
        width: 100%;
        height: 60%;
        position: relative;
        transform: translateZ(0) scale(1);
        backface-visibility: hidden;
        transform-origin: 0 0; /* see note above */
        }
        .loader div { box-sizing: content-box; }
        .loader-none{
            display: none;
        }

        label {
            color: #fff !important;
            font-size: 16px !important;
            font-weight: 400 !important;
        }

        #applicant_signature{
            display: none;
        }

    </style>
    <link rel="stylesheet" href="{{ URL::asset('public/BBL_CI/css/style_update.css') }}">

<body class="body-bg">
    @include('BBL_CI.layouts.header')

    <div class="loadingOverlay" id="loading">
        <div class="spiner_outer" style="position: absolute;">
            <div class="loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <p>Loading...</p>
        </div>
    </div>

    @yield('content')

    @include('BBL_CI.include.footer')
</body>

</html>
