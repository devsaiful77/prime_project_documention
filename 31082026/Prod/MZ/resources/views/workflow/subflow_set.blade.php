@extends('layouts.admin')
@section('content')
<?php
// prd($errors->toArray());
// prd($errors->all());
?>
<legend>Set Subflow</legend>
<div class="row">
<div class="col-md-12">
  <div class="form-group"><strong>{{(!empty($issueItems->issue_cat_name))?$issueItems->issue_cat_name:'N/A'}}</strong> </div>
  <div class="form-group"><strong>{{(!empty($issueItems->name))?$issueItems->name:'N/A'}}</strong> </div>
  <form class="" method="post" action="{{ url('workflow/subflow/set/'.$issueItems->id) }}">
    @csrf
    <input type="hidden" name="issue_workflow_id" value="{{$issueItems->issue_workflow_id}}">
    <input type="hidden" name="issue_id" value="{{$issueItems->id}}">
    <div class="col-md-6">
      <div class="table-responsive">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th class="vcenter text-center">Option Name </th>
              <th class="vcenter text-center">Group Name </th>
              <th class="vcenter text-center"><button type="button" class="btn btn-primary btn-sm addmoresubflow"><i class="fa fa-plus"></i></button></th>
            </tr>
          </thead>
          <tbody class="appendsubflow">
            <?php
            if (!empty(old('newsubgroup'))) {
              $existsSubWorkFlow = old('newsubgroup');
            }
            ?>
            @if(!empty($existsSubWorkFlow))
              @foreach($existsSubWorkFlow AS $key=> $existsSubWFlow)
                <tr>
                  <th class="vcenter text-center">
                    <input type="text" class="form-control optcls" name="newsubgroup[{{$key}}][options]" placeholder="Opion Name" value="{{$existsSubWFlow['options']}}" autocomplete="off"> 
                    @if($errors->has('newsubgroup.'.$key.'.options'))
                        <div class="error">
                            {!! $errors->first('newsubgroup.'.$key.'.options'); !!}
                        </div>
                    @endif
                  </th>
                  <th class="vcenter text-center">
                    <select class="form-control grpinfocls" name="newsubgroup[{{$key}}][group_info_id]">
                      <option value="">Please Select</option>
                        @foreach($wFlowNextLevelGroup AS $wFlowData)
                          <?php
                          $selected = '';
                          if ($existsSubWFlow['group_info_id'] == $wFlowData->group_info_id) {
                            $selected = 'selected';
                          }
                          ?>
                          <option value="{{$wFlowData->group_info_id}}" {{$selected}}>{{$wFlowData->group_name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('newsubgroup.'.$key.'.group_info_id'))
                        <div class="error">
                            {!! $errors->first('newsubgroup.'.$key.'.group_info_id'); !!}
                        </div>
                    @endif
                  </th>
                  <th class="vcenter text-center"><button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button></th>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
    <div class="clearfix">&nbsp;</div>

    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12"> 
          <input class="btn btn-primary gradient" title="Add" type="submit" value="Submit"> 
          <button type="button" class="btn btn-info gradient" onclick="cancel('/workflow/subflow')">Back</button> 
        </div>
    </div>
  </form>
</div>
</div>
<table class="hidden">
  <tbody class="newTr">
    <tr>
      <th class="vcenter text-center"><input type="text" class="form-control optcls" name="newsubgroup[0][options]" placeholder="Opion Name" autocomplete="off"> </th>
      <th class="vcenter text-center">
        <select class="form-control grpinfocls" name="newsubgroup[0][group_info_id]">
          <option value="">Please Select</option>
            @foreach($wFlowNextLevelGroup AS $wFlowData)
              <option value="{{$wFlowData->group_info_id}}">{{$wFlowData->group_name}}</option>
            @endforeach
        </select>
      </th>
      <th class="vcenter text-center"><button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button></th>
    </tr>
  </tbody>
</table>
@endsection
@section('extrajssection')
<script type="text/javascript">
  regenarteIdx();

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
      var newOptName = 'newsubgroup['+idx+'][options]'; 
      $(this).attr('name',newOptName);
      ++idx;
    });
    var idx = 0;
    $('.grpinfocls').each(function(event){
      var optname = $(this).attr('name');
      var newOptName = 'newsubgroup['+idx+'][group_info_id]'; 
      $(this).attr('name',newOptName);
      ++idx;
    });
  }
</script>

@endsection

