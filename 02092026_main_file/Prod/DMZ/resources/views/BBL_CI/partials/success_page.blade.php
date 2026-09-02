{{-- @php dd($data); @endphp --}}
@php
    $token = $data['ci_token'];
       try {
           $token = $token;
           $callbackUrl = \App\CustomerInterfaceToken::where('token', $token)->first('callback_url');
       } catch (Throwable $e) {
           $callbackUrl = '';
       }
@endphp
<div class="border p-4 mt-5">
    <div class="text-center">
        <p class="text-center text-success" style="font-size: 50px; text-align:center;margin: 0";><i class="fa-regular fa-circle-check"></i></p>
        <p>Success</p>
        <p>{{ $data['issue_name'] }} <br> Successfully Logged. Ticket No: {{ $data['reference_number'] }}</p>
    </div>
    <div style="display: flex;justify-content: space-around;margin-top: 40px;">
        <a href="{{ route('CI.service', ['CIToken' => $data['ci_token']]) }}" class="btn btn-sm btn-success">Make Another</a>
        @if ($data['request_type'] == 'complaint')
            <a href="{{ route('CI.comaplaint-ticket-status', ['CIToken' => $data['ci_token'], 'request_type' => 'complaint']) }}" class="btn btn-sm btn-info">Ticket Status</a>
        @else
            <a href="{{ route('CI.ticket-status-details', ['CIToken' => $data['ci_token'], 'request_type' => 'service']) }}" class="btn btn-sm btn-info">Ticket Status</a>
        @endif

        <a href="#" class="btn btn-sm btn-danger" id="callBackUrlSubmit">Back To Home</a>
    </div>
</div>

<script nonce="{{ app('csp_nonce') }}">
        $(document).on('click','#callBackUrlSubmit',function (e) {
            var token = '{{ $data['ci_token'] }}';
            var callbackUrl = '{{ $callbackUrl->callback_url }}';
            $.ajax({
                type: 'POST',
                url: '{{ route('CI.back-to-home') }}',
                data: {
                    'token' : token,
                    'callbackUrl': callbackUrl,
                    '_token': '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    $('.loadingOverlay').removeClass('loader-none');
                },
                success: function (data) {
                    sessionStorage.removeItem('activeMenu');
                    var url = data.callback_url
                    $('.loadingOverlay').addClass('loader-none');
                    window.location.href = url;
                },
                error: function (data) {
                    $('.loadingOverlay').addClass('loader-none');
                    console.error(data);
                },
            });
        });

    </script>