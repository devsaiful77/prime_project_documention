@php
    $token = $ci_token;
       try {
          /* $token = decrypt($token);*/
           $callbackUrl = \App\CustomerInterfaceToken::where('token', $token)->where('is_verify', 1)->first('callback_url');
       } catch (Throwable $e) {
           $callbackUrl = '';
       }
@endphp
<div class="header-copyright-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="header_button">
                    <div class="header-logo-wrap">
                        @if(\Route::currentRouteName() != 'CI.service')
                            <a href="{{ !empty($backUrl) ? url($backUrl) : url()->previous() }}" class=" back-btn">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        <h3 class="app-title-head" style="margin-bottom: 2px;">
			    @stack('app-title')
			    

			</h3>
                        {{--<img class="logo-white" src="{{ asset('public/img/logo/logo.png') }}" alt="CI logo" width="160px" style="margin-left: 10px">--}}
                        <img class="logo-primary" src="{{ asset('public/img/logo/banking_services.jpeg') }}" alt="CI logo" width="120px" height="30px" style="margin-left: 10px">
                    </div>
			
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger float-end" style="font-size: 12px!important;font-weight: 500!important;" id="callBackUrlSubmit">
                            <i class="fas fa-sign-out-alt" style="font-size: 14px"></i> <span>Back to Home</span></a>
                </div>
            </div>
        </div>
    </div>
</div>


    <script nonce="{{ app('csp_nonce') }}">
        $('#callBackUrlSubmit').click(function (e) {
	    var token = '{{ $ci_token }}';
	    $('.loadingOverlay').removeClass('loader-none');

            
            var callbackUrl = '{{ $callbackUrl->callback_url }}';

	    window.location.href = callbackUrl;

        });

    </script>

