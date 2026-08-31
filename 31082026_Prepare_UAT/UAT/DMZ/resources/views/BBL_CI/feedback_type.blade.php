@extends('BBL_CI.layouts.master')
@push('app-title')
    Feedback
@endpush
@section('content')
<div class="bg-wrapper service-main-wrap">
    <div class="container-fluid custom-layout">
        <div id="feedbackContent">
            <div class="row">
                <div class="web-item-wrap">
                    @if(session('success_feedback'))
                        <div class="mb-2 p-0 font-weight-bold text-center alert alert-success" style="font-size: 14px;">
                            <strong>*</strong> {{ session('success_feedback') }}
                        </div>
                    @endif
                    <div id="form_log_wrap">
                        <form id="" action="{{ route('CI.submit_feedback') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="ci_token" value="{{ $ci_token }}">
			    
                            <div class="card">
                                <div class="card-body feedback-card-body">
                                    <div class="">
                                        <div class="col-12">
                                            <label for="" class="form-label">Please share your thoughts</label>
                                            <textarea name="comments" class="form-control mt-1" cols="40" placeholder="Write here........." required></textarea>
                                        </div>
					@error('comments')
					    <span class="text-danger">{{ $message }}</span>
					@enderror
					
                                        <div class="mt-3" style="text-align: right;">
                                            <button type="submit" class="btn btn-primary btn-sm form_condition" id="formSubmitBtn">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row note-wrap" id="noteArea">
            <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8 col-xxl-8 mt-4">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Success:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('success') }} </p>
                    </div>
                @elseif(session('info'))
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Note:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('info') }} </p>
                    </div>
                @elseif(session('warning'))
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Alert:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('warning') }} </p>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-error" role="alert">
                        <h6 class="alert-heading"><i class="fa-solid fa-circle-info"></i> Alert:</h6>
                        <p class="ps-3 alert-text"><strong>*</strong> {{ session('error') }} </p>
                    </div>
                @else

                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script nonce="{{ app('csp_nonce') }}">
        $(document).ready(function(){
            var getSession = sessionStorage.getItem('activeMenu');
            // var url =  document.referrer;
            // var parts = url.split("/");
            // var lastPart = parts[parts.length - 1];
            if (getSession == 'complaint'){
                $('input[name="request_type"][value="complaint"]').prop('checked', true);
                $('#seviceContent').addClass('d-none');
                $('#complaintContent').removeClass('d-none');
                $('#feedbackContent').addClass('d-none');
                $('#noteArea').removeClass('d-none');
            } else if(getSession == 'feedback'){
                $('input[name="request_type"][value="feedback"]').prop('checked', true);
                $('#seviceContent').addClass('d-none');
                $('#complaintContent').addClass('d-none');
                $('#feedbackContent').removeClass('d-none');
                $('#noteArea').addClass('d-none');
            } else if(getSession == 'service') {
                $('input[name="request_type"][value="service"]').prop('checked', true);
                $('#seviceContent').removeClass('d-none');
                $('#complaintContent').addClass('d-none');
                $('#feedbackContent').addClass('d-none');
                $('#noteArea').removeClass('d-none');
            }
            $('input[name="request_type"]').change(function(){
                $('#loading').removeClass('loader-none');
                sessionStorage.removeItem('activeMenu');
                let selectedValue = $('input[name="request_type"]:checked').val();

                if (selectedValue == 'service'){
                    sessionStorage.setItem('activeMenu', 'service');
                    $('#seviceContent').removeClass('d-none');
                    $('#complaintContent').addClass('d-none');
                    $('#feedbackContent').addClass('d-none');
                    $('#noteArea').removeClass('d-none');
                    setTimeout(function () {
                        $('#loading').addClass('loader-none');
                    }, 300);
                }else if (selectedValue == 'complaint'){
                    sessionStorage.setItem('activeMenu', 'complaint');
                    $('#seviceContent').addClass('d-none');
                    $('#complaintContent').removeClass('d-none');
                    $('#feedbackContent').addClass('d-none');
                    $('#noteArea').removeClass('d-none');
                    setTimeout(function () {
                        $('#loading').addClass('loader-none');
                    }, 300);
                }else {
                    sessionStorage.setItem('activeMenu', 'feedback');
                    $('#seviceContent').addClass('d-none');
                    $('#complaintContent').addClass('d-none');
                    $('#feedbackContent').removeClass('d-none');
                    $('#noteArea').addClass('d-none');
                    setTimeout(function () {
                        $('#loading').addClass('loader-none');
                    }, 300);
                }
            });
            setTimeout(function () {
                sessionStorage.removeItem('activeMenu');
            }, 2000000);
        });
    </script>
@endpush
