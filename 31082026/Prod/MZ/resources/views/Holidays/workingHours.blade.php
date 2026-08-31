@extends('layouts.admin')

@section('content')
<?php
$hour = array('00'=>'00', '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'04', '05'=>'05', '06'=>'06', '07'=>'07', '08'=>'08', '09'=>'09', '10'=>'10', '11'=>'11', '12'=>'12', '13'=>'13', '14'=>'14', '15'=>'15', '16'=>'16', '17'=>'17', '18'=>'18', '19'=>'19', '20'=>'20', '21'=>'21', '22'=>'22', '23'=>'23');
$minSecond = array('00'=>'00', '05'=>'05', '10'=>'10', '15'=>'15', '20'=>'20', '25'=>'25', '30'=>'30', '35'=>'35', '40'=>'40', '45'=>'45', '50'=>'50', '55'=>'55');
?>
<h3>{{$title_for_layout}}</h3>
<div class="clearfix">&nbsp;</div>
{!! Form::open([
    'method'=>'get',
    'action' => ['HolidaysController@workingHours'],
    'enctype' => 'multipart/form-data'
]) !!}
<div class="table-responsive">
   <table class="table table-condensed table-bordered">
      <tr>
         <th></th>
         <th>Hour</th>
         <th>Minute</th>
         <th>Second</th>
      </tr>
      <tr>
         <th>Start</th>
         <td>
           {{ Form::select('start_hour', $hour, old('start_hour', $dataForView["start_hour"] ?? ""), ['class' => 'form-control']) }}
           @error('start_hour')
               <div class="text-danger">{{ $message }}</div>
           @enderror
         </td>
         <td>
           {{ Form::select('start_minute', $minSecond, old('start_minute', $dataForView["start_minute"] ?? ""), ['class' => 'form-control']) }}
           @error('start_minute')
               <div class="text-danger">{{ $message }}</div>
           @enderror
         </td>
         <td>
           {{ Form::select('start_second', $minSecond, old('start_second', $dataForView["start_second"] ?? ""), ['class' => 'form-control']) }}
           @error('start_second')
               <div class="text-danger">{{ $message }}</div>
           @enderror
         </td>
      </tr>
      <tr>
         <th>End</th>
         <td>
           {{ Form::select('end_hour', $hour, old('end_hour', $dataForView["end_hour"] ?? ""), ['class' => 'form-control']) }}
           @error('end_hour')
               <div class="text-danger">{{ $message }}</div>
           @enderror
         </td>
         <td>
           {{ Form::select('end_minute', $minSecond, old('end_minute', $dataForView["end_minute"] ?? ""), ['class' => 'form-control']) }}
           @error('end_minute')
               <div class="text-danger">{{ $message }}</div>
           @enderror
         </td>
         <td>
           {{ Form::select('end_second', $minSecond, old('end_second', $dataForView["end_second"] ?? ""), ['class' => 'form-control']) }}
           @error('end_second')
               <div class="text-danger">{{ $message }}</div>
           @enderror
         </td>
      </tr>
      @if (!$checker)
      <tr>
         <th><button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Save</button></th>
         <th colspan="3"></th>
      </tr>
      @endif
   </table>
</div>
{!! Form::close() !!}
@endsection
