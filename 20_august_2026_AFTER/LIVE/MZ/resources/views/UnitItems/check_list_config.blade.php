@extends('layouts.admin')
@section('content')
<?php $addMoreStart = 0; ?>

  <div class="row"> <div class="col-md-12"> <h3 class="text-center">Check List Configuration</h3> </div> <h4>Issue:{{ $row->name }}</h4> </div>
  <div class="row">
    <form action="{{ url('issues-check-list/store') }}" method="POST" id="CheckListSubmit">
      @csrf
      <input type="hidden" value="{{ $row->id }}" name="issue_id">
      <table class="table" id="dynamicTable">
        <tr>
          <th>Label Name</th>
          <th>Field Type</th>
          <th>Field Name</th>
          <th>Option</th>
          <th>Placeholder</th>
          <th>Maximum Length</th>
          <th>Required</th>
          <th>Action</th>
        </tr>
        @IF(!empty($pbIssuesCfg))
          @FOREACH($pbIssuesCfg AS $key=>$val)
            <tr>
              <td>
                <input type="hidden" value="{{ $row->id }}" name="issue_id">
                <input type="text" name="addmore[{{$key}}][label_name]" placeholder="Enter Label name" class="form-control" value="{{$val['label_name']}}" />
                <div class="error">{{ $errors->first('addmore.'.$key.'.label_name') }}</div>

              </td>
              <td>
                <select name="addmore[{{$key}}][field_type]" class="question-type form-control">
                  <option value="checkbox" {{($val['field_type'] == 'checkbox') ? 'checked' :''}}>Checkbox</option>
                </select>
              </td>
              <td>
                <input type="text" name="addmore[{{$key}}][field_name]" placeholder="Enter field name" class="form-control" value="{{$val['field_name']}}"/>
                <div class="error">{{ $errors->first('addmore.'.$key.'.field_name') }}</div>

              </td>
              <td class="options">
                <textarea  name="addmore[{{$key}}][options]" placeholder="Enter option" class="form-control">{{$val['options']}}</textarea>
              </td>
              <td>
                <input type="text" name="addmore[{{$key}}][placeholder]" class="form-control" placeholder="Enter Placeholder" value="{{$val['placeholder']}}">
              </td>
              <td>
                <input type="text" name="addmore[{{$key}}][maximum_length]" class="form-control" placeholder="Enter Maximum Length" value="{{$val['maximum_length']}}">
              </td>
              <td>
                <input type="hidden" name="addmore[{{$key}}][is_required]" value="0">
                <label><input type="checkbox" name="addmore[{{$key}}][is_required]" value="1" @if($val['is_required']==1) checked  @endif>Required</label>
              </td>
              <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>
            </tr>
          @ENDFOREACH
          <?php $addMoreStart = $key; ?>
        @ELSE
          @if(count($rows)!=0)
            @foreach($rows as $key=>$r)
              <tr>
                <td><input type="text" name="addmore[{{$key}}][label_name]" placeholder="Enter Label name" class="form-control" value="{{ $r->label_name }}" /></td>
                <td>
                  <select name="addmore[{{$key}}][field_type]" class="question-type form-control">
                    <option value="checkbox" @if($r->field_type=='checkbox') selected @endif>Checkbox</option>
                  </select>
                </td>
                <td><input type="text" name="addmore[{{$key}}][field_name]" placeholder="Enter field name" class="form-control" value="{{ $r->field_name }}" /></td>
                <td class="options">
                  <textarea  name="addmore[{{$key}}][options]" placeholder="Enter option" class="form-control">{{ $r->options }}</textarea>
                </td>
                <td><input type="text" name="addmore[{{$key}}][placeholder]" class="form-control" placeholder="Enter Placeholder" value="{{ $r->placeholder }}"></td>
                <td><input type="text" name="addmore[{{$key}}][maximum_length]" class="form-control" placeholder="Enter Maximum Length" value="{{ $r->maximum_length }}"></td>
                <td><input type="hidden" name="addmore[{{$key}}][is_required]" value="0" ><label><input type="checkbox" name="addmore[{{$key}}][is_required]" value="1" @if($r->is_required==1) checked  @endif>Required</label></td>
                <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>
              </tr>
            @endforeach
            <?php $addMoreStart = $key; ?>
          @else
            <tr>
              <td>
                <input type="hidden" value="{{ $row->id }}" name="issue_id">
                <input type="text" name="addmore[0][label_name]" placeholder="Enter Label name" class="form-control" />
              </td>
              <td>
                <select name="addmore[0][field_type]" class="question-type form-control">
                  <option value="checkbox">Checkbox</option>
                </select>
              </td>
              <td>
                <input type="text" name="addmore[0][field_name]" placeholder="Enter field name" class="form-control" />
              </td>
              <td class="options">
                <textarea  name="addmore[0][options]" placeholder="Enter option" class="form-control"></textarea>
              </td>
              <td>
                <input type="text" name="addmore[0][placeholder]" class="form-control" placeholder="Enter Placeholder">
              </td>
              <td>
                <input type="text" name="addmore[0][maximum_length]" class="form-control" placeholder="Enter Maximum Length">
              </td>
              <td>
                <input type="hidden" name="addmore[0][is_required]" value="0">
                <label><input type="checkbox" name="addmore[0][is_required]" value="1">Required</label>
              </td>
              <td><button type="button" name="add" class="btn btn-success addMoreBtn">Add More</button></td>
            </tr>
          @endif
        @ENDIF
      </table>
      <button type="button" name="add" class="btn btn-success addMoreBtn"><i class="fa fa-plus"></i> Add More</button>
      <button type="button" class="btn btn-info" onclick="customUpdateConfirm('Check List conf Update!','Are you sure want to update this data?','green','CheckListSubmit')">Update</button>
    </form>
  </div>
{{-- @endsection
@section('script') --}}
<script type="text/javascript">
  var i = '{{$addMoreStart}}';
  $(".addMoreBtn").click(function(){
      ++i;
      $("#dynamicTable").append(
          '<tr><td><input type="text" name="addmore['+i+'][label_name]" placeholder="Enter Label name" class="form-control" /></td>' +
          '<td><select name="addmore['+i+'][field_type]"  class="form-control"><option value="checkbox">Checkbox</option>\n' +

          '<td><input type="text" name="addmore['+i+'][field_name]" placeholder="Enter field name" class="form-control" /></td>' +
          '<td><textarea name="addmore['+i+'][options]" placeholder="Enter option" class="form-control" ></textarea></td>' +
          '<td><input type="text" name="addmore['+i+'][placeholder]" placeholder="Enter Placeholder" class="form-control" /></td>' +
          '<td><input type="text" name="addmore['+i+'][maximum_length]" placeholder="Enter Maximum Length" class="form-control" /></td>' +
          '<input type="hidden" name="addmore['+i+'][is_required]" value="0">' +
          '<td><label><input type="checkbox" name="addmore['+i+'][is_required]" value="1">Required</label></td>' +
          '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
  });
  $(document).on('click', '.remove-tr', function(){
    $(this).parents('tr').remove();
  });
</script>
@endsection
