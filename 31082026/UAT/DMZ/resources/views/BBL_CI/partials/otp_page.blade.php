<style>
    .input-fecha:focus {
        outline: 1px solid #03427e;
        background: #03427e!important;
        color: #ffffff;
    }
</style>
<div class="__tab__body__content__verification">
    <b class="showOtp">Verification</b>
    @if(Session::has('otpMessage'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('otpMessage') }}</p>
    @endif
</div>
<div class="border p-4">
    <form id="verifyOtpForm" action="{{ route('CI.otp-submit') }}" method="post">
        @csrf
        <input type="hidden" name="product_type" value="{{ $data['product_type'] }}" id="product_type">
        <input type="hidden" name="request_type" value="{{ $data['request_type'] ?? ''}}" id="request_type">
        <input type="hidden" name="invalidCount" value="{{ $data['invalidCount'] }}" id="invalidCount">
        <input type="hidden" name="ci_token" value="{{ $data['ci_token'] }}" id="ci_token">
        <input type="hidden" name="reference_number" value="{{ $data['reference_number'] }}" id="reference_number">
        <input type="hidden" name="issue_name" value="{{ $data['issue_name'] }}" id="issue_name">
        <input type="hidden" class="otpGenId" name="otp_auto_id" value="{{@$data['otpGenId'] }}" id="otpGenId">
        <input type="hidden" name="currentTime" id="currentTime" value="{{date('Y-m-d H:i:s') }}" />
        <input type="hidden" name="otp_mode" id="otp_mode" value="{{ $data['otp_mode'] }}" />
        <div class="tab-content" id="pills-tabContent">
            <div class="__tab__body__content">
                <div class="__tab__body__content__bg">
                    @if($data['otp_mode'] == 1)
                        <div class="__tab__body__content__title text-center">
                            <p>Please enter the OTP from your registered mobile number {{$data['mobile_no']}}</p>
                            <!-- <p id="errorMessageShow" style="color: red"></p> -->
                            <div class="alert alert-danger d-none d-inline-block" id="errorMessageShow"></div>
                            <div class="alert alert-success d-none d-inline-block" id="successMessageShow"></div>
                        </div>
                    @elseif($data['otp_mode'] == 2)
                        <div class="__tab__body__content__title">
                            <p>Please enter the OTP from your registered Email address {{$data['mask_email']}}</p>
                            <!-- <p id="errorMessageShow" style="color: red"></p> -->
                            <div class="alert alert-danger d-none" id="errorMessageShow"></div>
                            <div class="alert alert-success d-none" id="successMessageShow"></div>
                        </div>
                    @endif
                    <div class="__tab__body__content__input__table__cell contenedor-fecha-interior">
                        <div class="__tab__body__content__input__item contenedor-input-fecha">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha" maxlength="1" name="otp1" required placeholder="" id="otp_1">
                        </div>
                        <div class="__tab__body__content__input__item contenedor-input-fecha">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha" maxlength="1" name="otp2" required placeholder="" id="otp_2" disabled>
                        </div>
                        <div class="__tab__body__content__input__item contenedor-input-fecha">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha" maxlength="1" name="otp3" required placeholder="" id="otp_3" disabled>
                        </div>
                        <div class="__tab__body__content__input__item contenedor-input-fecha">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha" maxlength="1" name="otp4" required placeholder="" id="otp_4" disabled>
                        </div>
                        <div class="__tab__body__content__input__item contenedor-input-fecha">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha" maxlength="1" name="otp5" required placeholder="" id="otp_5" disabled>
                        </div>
                        <div class="__tab__body__content__input__item contenedor-input-fecha">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" tabindex="0" class="input-fecha" maxlength="1" name="otp6" required placeholder="" id="otp_6" disabled>
                        </div>
                    </div>

                    <div class="otp_counter">
                        <div id="revese-timer" data-minute="3"></div>
                    </div>

                    <div class="__tab__body__content__btn__group">
                        <div class="__tab__body__content__btn__verify">
                            
			@if($data['is_send_back'] == 0)
                                <button type="button" class="cancelButton sendBackCancelWeb" data-url="{{ route('CI.account-verify', ['product_type' => $data['product_type'], 'CIToken' => $data['ci_token'], 'request_type' => $data['request_type'] ]) }}" >Cancel</button>
                            @elseif($data['is_send_back'] == 1)
                                <button type="button" class="cancelButton" data-url="{{ !empty($backUrl) ? $backUrl : url()->previous() }}">Cancel</button>
                            @endif

                            {{-- <button type="button" id="cancelBtn">Cancel</button> --}}
                        </div>
                        <div class="__tab__body__content__btn__verify">
                            <button type="submit" id="verifyOtp">Verify</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script nonce="{{ app('csp_nonce') }}">
    function validateNumericInput(event) {
      const input = event.target;
      const value = input.value;
      if (isNaN(value)) {
        input.value = '';
      }
    }
</script>

<script nonce="{{ app('csp_nonce') }}">
	
	$(document).ready(function () {
           $(document).on('click', '.sendBackCancelWeb', function () {
            

	    let url = $(this).data('url');
            
            const reference_number = "{{ $data['reference_number'] }}";
            const storedFiles = @json($data['storedFiles'] ?? []);
            
            if(storedFiles.length != 0){
                $.ajax({
                    url: "{{ route('CI.attachment.remover') }}",
                    type: "POST",
                    data:{
                        files : storedFiles,
                        reference_number : reference_number,
                        _token : "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        console.log('Success:', response);
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseText);
                    },
                    complete: function () {
                        if (url) {
                            window.location.href = url;
                        }
                    }
                })
            }else{
                if (url) {
                    window.location = url;
                }
            }



            
           });
    	});

    $('#verifyOtp').on('click', function(e){
        e.preventDefault();
        var otp_1 = $('#otp_1').val();
        var otp_2 = $('#otp_2').val();
        var otp_3 = $('#otp_3').val();
        var otp_4 = $('#otp_4').val();
        var otp_5 = $('#otp_5').val();
        var otp_6 = $('#otp_6').val();
        if(otp_1 !="" && otp_2 !="" && otp_3 !="" && otp_4 !="" && otp_5 !="" && otp_6 !=""){

            // if (window.innerWidth < 800) {
            $('#errorMessageShow').text("").addClass('d-none');
            $('#successMessageShow').text("").addClass('d-none');
            $('#request_mode_in_otp').val('web');
            var url = $('#verifyOtpForm').attr('action');
            var request = $('#verifyOtpForm').serialize();
            $('.loadingOverlay').removeClass('loader-none');
            today = new Date().toLocaleString();
            $('#currentTime').val(today);
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: request,
                success: function(data){
                    if(data.success){
                        $.post('{{ url('CI/otp/request/submit/page') }}', {
                            _token: '{{ csrf_token() }}',
                            data: data,
                            beforeSend: function() {
                                $('.loadingOverlay').removeClass('d-none');
                            },

                        }, function (data) {
                            $(document).find('.step1').removeClass('done');
                            $(document).find('.step2').removeClass('done');
                            $(document).find('.step3').addClass('done');
                            $('.loadingOverlay').addClass('loader-none');
                            $('#form_log_wrap').html('');
                            $('#form_log_wrap').html(data);
                        });
                    } else {
                        $('#errorMessageShow').removeClass('d-none').text(data.otpMessage);
                        setTimeout(function() {
                            $('#errorMessageShow').addClass('d-none');
                        }, 7000);
                        $('#invalidCount').val(data.invalidCount);
                        $('.loadingOverlay').addClass('loader-none');
                        if($('#invalidCount').val() > 2){
                            $("#verifyOtp").attr("disabled", true);
                            $("#regenerateOtpForWeb").attr("disabled", true);
                        }
                    }

                }
            })
            .fail(function(xhr) {
                if(xhr.status == 422){
                    $('#errorMessageShow').text('Please try again !');
                    $('.loadingOverlay').addClass('loader-none');
                } else {
                    $('#errorMessageShow').text('Something went wrong!');
                    $('.loadingOverlay').addClass('loader-none');
                    toastr.error('Something went wrong!');
                }
            });
        } else{
            $('#errorMessageShow').text('Please input otp').removeClass('d-none');
            setTimeout(function() {;
                $('#errorMessageShow').addClass('d-none');
            }, 7000);
        }
    });

    $(document).ready(function(){
        $(".input-fecha").keypress(function(event){
            var currentValue = $(this).val();

            // if(currentValue.length !=0){
            //     event.preventDefault();
            if(event.which !== 8 && (event.which < 48 || event.which > 57) || currentValue.length !=0){
                event.preventDefault();
            }
            // }
        });

        let $inputs = $('.contenedor-fecha-interior input').on('input', e => {
            let $input = $(e.target);
            $('.input-fecha').attr('max', 1);
            let index = $inputs.index($input);
            if ($input.val().length >= $input.prop('maxlength')) {
                $inputs.eq(index + 1).prop('disabled', false).focus();
            }
        });
    });

    // resend OTP For Web
    function resendOTP(){
        var ci_token = $('#ci_token').val();
        $('.loadingOverlay').removeClass('loader-none');
        // otp resend
        var otp_1 = $('#otp_1').val('');
        var otp_2 = $('#otp_2').val('');
        var otp_3 = $('#otp_3').val('');
        var otp_4 = $('#otp_4').val('');
        var otp_5 = $('#otp_5').val('');
        var otp_6 = $('#otp_6').val('');
        var otp_mode = $('#otp_mode').val();
        $('#inputedOtpId').val("");
        $('#inputOtp').val("");
        $('.error').html('');
        $('#successMessageShow').text("").addClass('d-none');
        $('#errorMessageShow').text("").addClass('d-none');
        var otpGenId = $('.otpGenId').val();

        $.ajax({
            url: "{{ url('/CI/otp/re-generate') }}",
            type: "POST",
            dataType: "json",
            data: {otpGenId:otpGenId, ci_token:ci_token, otp_mode:otp_mode},
            success: function(data){
                $(".showOtp").text('Verification '+data.otpCode);
                $(".otpGenId").val(data.otpGenId);
                $('#invalidCount').val(data.invalidCount);
                $("#verifyOtp").attr("disabled", false).removeAttr('disabled');
                otpCall();
                // count down start
                $('.resend_otp').addClass('d-none');
                $('.loadingOverlay').addClass('loader-none');

                // toastr.success('OTP resend Successfully!','Success');
                $('#successMessageShow').text("OTP resend Successfully!").removeClass('d-none');
                setTimeout(function() {
                    $('#successMessageShow').addClass('d-none');
                }, 7000);
            },
            error: function(error) {
                $('.loadingOverlay').addClass('loader-none');
                // toastr.error(error.responseJSON.message,'warning');
                $('#errorMessageShow').text('Please try again later').removeClass('d-none');
                setTimeout(function() {
                    $('#errorMessageShow').addClass('d-none');
                }, 7000);
            }
        });
    }

</script>



