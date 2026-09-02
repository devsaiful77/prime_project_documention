@extends('layouts.login_layout_update')
@section('content')
    <form action="{{ url('/login') }}" method="post">
        {{ csrf_field() }}

        <span class="login-update-form-title">Welcome to PrimeServe</span>

        <div class="wrap-input100 validate-input m-b-10">
            <input class="input100" type="text" name="username_or_email" placeholder="Username" value="{{ old('username_or_email') }}" autocomplete="off">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-user"></i>
            </span>
        </div>
        @if ($errors->has('username_or_email')) <div class="error help-block text-center"> <strong>{{ $errors->first('username_or_email') }}</strong><div class="clearfix">&nbsp;</div></div> @endif

        <div class="wrap-input100 validate-input m-b-10">
            <input class="input100" type="password" name="password" placeholder="Password" autocomplete="off" value="{{ old('password') }}" id="myInput">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
                <i class="fa fa-lock"></i>
            </span>
        </div>
        <div class="pass-show" style="padding-left: 20px; margin-left: 10px; font-size: 10px;">
            <input type="checkbox" onclick="myFunction()"> <span class="">Show Password</span>
        </div>
        @if ($errors->has('password'))
            <div class="error help-block text-center">
                <strong>{{ $errors->first('password') }}</strong><div class="clearfix">&nbsp;</div>
            </div>
        @endif

        <div class="container-login100-form-btn p-t-10 p-b-100">
            <button type="submit" class="login100-form-btn">Sign In</button>
        </div>

        <!-- <div class="text-center w-full p-t-25 p-b-100">
            <a href="{{ url('/password/reset') }}" > Forgot Password!!! </a>
        </div> -->

        <div class="footer-copyright-area footer-rap" id="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="footer-copy-center" style="text-align:center; font-color:white">
                            <p><a href="http://www.fieldbooster.com/" target="_blank">Developed by : <img class="img-responsive" src="{{ URL::asset('public/img/logo/fbl.png') }}"  alt="Fieldbooster Limited." style="height: 18px; width: 18px;"> Fieldbooster Limited</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>


    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
