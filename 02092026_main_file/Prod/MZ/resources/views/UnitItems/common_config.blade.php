@extends('layouts.admin')
@section('content')
<div class="col-lg-12">
    <h3 class="text-center">API Update Common Configuration</h3>
</div>
@php
    $apiActBtn = " active";
    $inqActBtn = "";
    $apiActTab = " in active";
    $inqActTab = "";
    if ($active_tab == "api_update") {
        $apiActBtn = " active";
        $inqActBtn = "";
        $apiActTab = " in active";
        $inqActTab = "";
    } elseif ($active_tab == "inquiry_api") {
        $apiActBtn = "";
        $inqActBtn = " active";
        $apiActTab = "";
        $inqActTab = " in active";
    }
@endphp
<div class="col-lg-12">
    {{--<ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $apiActBtn }} APIUP" data-toggle="tab" href="#api_update">API Update Static Value</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $inqActBtn }} INQUIR" data-toggle="tab" href="#inquiry_api">Inquiry Parameter Label</a>
        </li>
    </ul>--}}
    <div class="tab-content">
        <div id="api_update" class="tab-pane {{ $apiActTab }}">
            <div class="clearfix">&nbsp;</div>
            <h4>Issue : {{ $issue->name }}</h4>
            @include('UnitItems.commonConfig.api_update')
        </div>
        {{--<div id="inquiry_api" class="tab-pane fade {{ $inqActTab }}">
            <div class="clearfix">&nbsp;</div>
            <h4>Issue : {{ $issue->name }}</h4>
            @include('UnitItems.commonConfig.inquiry_api')
        </div>--}}
    </div>
</div>
@endsection
@section('extrajssection')
    @if ($active_tab == "api_update")
        <script type="text/javascript"> regenarteIdx(); </script>
    @elseif ($active_tab == "inquiry_api") {
        <script type="text/javascript"> regenarteIdx2(); </script>
    @endif
    <script type="text/javascript">
        $('.APIUP').on('click',function(event){
            regenarteIdx();
        });
        $('.INQUIR').on('click',function(event){
            regenarteIdx2();
        });
        $(document).off('click','.removesubflow');
        $(document).on('click','.removesubflow',function(event){
            $(this).parent().parent().remove();
            regenarteIdx();
        });
        $('.addmoresubflow').on('click',function(event){
            var newTrHtml = $('.newTr').html();
            $('.appendsubflow').append(newTrHtml);
            regenarteIdx();
        });
        function regenarteIdx(){
            var idx = 0;
            $('.optcls').each(function(event){
                var optname = $(this).attr('name');
                var newOptName = 'new['+idx+'][value]';
                $(this).attr('name',newOptName);
                ++idx;
            });
            var idx = 0;
            $('.grpinfocls').each(function(event){
                var optname = $(this).attr('name');
                var newOptName = 'new['+idx+'][api_parameter]';
                $(this).attr('name',newOptName);
                ++idx;
            });
        }
        $(document).off('click','.removesubflow2');
        $(document).on('click','.removesubflow2',function(event){
            $(this).parent().parent().remove();
            regenarteIdx2();
        });
        $('.addmoresubflow2').on('click',function(event){
            var newTrHtml = $('.newTr2').html();
            $('.appendsubflow2').append(newTrHtml);
            regenarteIdx2();
        });
        function regenarteIdx2(){
            var idx = 0;
            $('.optcls').each(function(event){
                var optname = $(this).attr('name');
                var newOptName = 'new2['+idx+'][value]';
                $(this).attr('name',newOptName);
                ++idx;
            });
            var idx = 0;
            $('.grpinfocls').each(function(event){
                var optname = $(this).attr('name');
                var newOptName = 'new2['+idx+'][api_parameter]';
                $(this).attr('name',newOptName);
                ++idx;
            });
        }
    </script>

@endsection
