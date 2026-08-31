<div class="form-element-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                @if (flash()->message)
                {{-- <div class="alert alert-warning alert-dismissible fade show" role="alert"> --}}

                    <div class="mb-1 font-weight-bold text-center alert alert-{{ flash()->class }}" style="font-size: 14px;">
                      {!! flash()->message !!}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                      {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> --}}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="form-element-list">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
