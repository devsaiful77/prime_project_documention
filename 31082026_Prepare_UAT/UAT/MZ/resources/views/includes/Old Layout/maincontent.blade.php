<div class="right_col" role="main"> <div class="">
  <!-- <div class="page-title"> <div class="title_left"> <h3>Form Elements</h3> </div> </div> <div class="clearfix"></div> -->
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      @if (session()->has('flash_notification.message'))
          <div class="text-center alert alert-{{ session('flash_notification.level') }}" style="font-weight: bold; font-size: 14px;" onclick="this.classList.add('hidden')">
              <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
              {!! session('flash_notification.message') !!}
          </div>
      @endif       
      <div class="x_panel"> 
        <div class="x_title"> <h2>{{(!empty($title)) ? $title : '-' }}<small><strong>{{(!empty($sub_title)) ? '('.$sub_title.')' : '' }}</strong></small></h2> <div class="clearfix"></div></div>
        @yield('content') 
      </div>
    </div>
  </div>
</div> </div>
<script> // $('div.alert').not('.alert-important').delay(8000).fadeOut(350); </script>