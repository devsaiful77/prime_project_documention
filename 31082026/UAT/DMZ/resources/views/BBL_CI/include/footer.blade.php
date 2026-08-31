
{{-- <script src="{{ URL::asset('public/BBL_CI/js/bootstrap.bundle.min.js') }}"></script> --}}
<script src="{{ URL::asset('public/BBL_CI/js/bootstrap-5.3.1.bundle.min.js') }}"></script>
<script src="{{ URL::asset('public/BBL_CI/js/modernizr-3.12.0.min.js') }}"></script>
<script src="{{ URL::asset('public/BBL_CI/css/fontawesome/js/all.js') }}"></script>
<script src="{{ URL::asset('public/BBL_CI/vendors/jquery-ui/js/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('public/BBL_CI/vendors/toastr/toastr.min.js') }}"></script>
{{-- <script src="{{ URL::asset('public/BBL_CI/js/select2.min.js') }}"></script> --}}
<script src="{{ URL::asset('public/BBL_CI/js/select2-4.0.3.min.js') }}"></script>
<script src="{{ URL::asset('public/BBL_CI/js/app.js') }}"></script>
<script nonce="{{ app('csp_nonce') }}">

    toastr.options = {
        "closeButton": true,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $('.single_select2_focus').on('select2:open', function() {
        if (Modernizr.touch) {
            $('.select2-search__field').prop('focus', true);
        }
    });
    $('.single_select2_focus').on('select2:opening', function(e) {
        $('.select2-search input').prop('focus', 1);
    });

    $(".single_select2").select2({
        placeholder: "Please Select",
        allowClear: true
    });

    $(window).on('load', function () {
        setTimeout(function () {
            $('#loading').addClass('loader-none');
        }, 0);
    });

    $(document).ready(function(){
        $(document).on('input', 'input[type="text"]:not(.js-ignore-global)', function () {
            let value = $(this).val();
            // remove old error span if exists
            $(this).next('.invalid-msg').remove();

            // Updated regex to include parentheses ()
            let cleaned = value.replace(/[^0-9a-zA-Z @._,\-()]/g, '');
            
            if (cleaned !== value) {
                $(this).val(cleaned);
                $(this).after('<span class="invalid-msg text-danger">Invalid character detected! Only Text, Number, Space, @ . _ , - ( ) are allowed.</span>');
                return;
            }

            // block JS keywords
            if (containsJSKeywords(cleaned)) {
                $(this).val('');
                $(this).after('<span class="invalid-msg text-danger">JavaScript keywords are not allowed!</span>');
            }
        });
    });

    function containsJSKeywords(str) {
        // Block dangerous JS keywords (case-insensitive)
        const jsKeywordPattern = /\b(alert|prompt|confirm|script|on\w+|eval|javascript|function|constructor|fetch|settimeout|setinterval|new|document|window|location)\b/i;
        
        return jsKeywordPattern.test(str);
    }
</script>

<script nonce="{{ app('csp_nonce') }}">
    $.ajaxSetup({
	headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
	    'Csp-Nonce': "{{ app('csp_nonce') }}"
	}
    });
</script>
@stack('js')
