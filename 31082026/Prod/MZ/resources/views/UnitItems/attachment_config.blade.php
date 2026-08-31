@extends('layouts.admin')
@section('content')
    <?php $addMoreStart = 0; ?>
    <div class="row"> <div class="col-md-12"> <h3 class="text-center">Attachment Configuration</h3> </div> <h4>Issue:{{ $issue->name }}</h4> </div>
    <div class="row">
       <div class="col-xl-12">
           <form action="{{ url('issues/attachment/store') }}" method="POST" id="AttachmentUpdate">
               @csrf
               <input type="hidden" value="{{ $issue->id }}" name="issue_id">
               <table class="table" id="addMoreDynamicTable">
                   <tr>
                       <th>Name</th>
                       <th>Order</th>
                       <th>is Required</th>
                       <th>Action</th>
                   </tr>
                   @IF(!empty($pbfieldsetGroup))
                       @FOREACH($pbfieldsetGroup AS $key=>$val)
                           <tr>
                               <td>
                                   <input type="text" name="addmore[{{$key}}][name]" placeholder="Enter Attachment name" class="form-control" value="{{$val['name']}}" />
                                   <div class="error">{{ $errors->first('addmore.'.$key.'.name') }}</div>
                               </td>
                               <td><input type="number" name="addmore[{{$key}}][order_by]" placeholder="Enter order" class="form-control" value="{{$val['order_by']}}" /></td>
                               <td>
                                   <input type="hidden" name="addmore[{{$key}}][is_required]" value="0">
                                   <label><input type="checkbox" name="addmore[{{$key}}][is_required]" value="1" @if($val['is_required']==1) checked  @endif> Required</label>
                               </td>
                               <td>
                                   <button type="button" class="btn btn-danger remove-tr"><i class="fa fa-minus"></i></button>
                               </td>
                           </tr>
                       @ENDFOREACH
                       <?php $addMoreStart = $key; ?>
                   @ELSE
                       @if(count($attachmentConfig)!=0)
                           @foreach($attachmentConfig as $key=>$r)
                               <tr>
                                   <td><input type="text" name="addmore[{{$key}}][name]" placeholder="Enter Attachment name" class="form-control" value="{{ $r->name }}" /></td>
                                   <td><input type="number" name="addmore[{{$key}}][order_by]" placeholder="Enter order" class="form-control" value="{{ $r->order_by }}" /></td>
                                   <td>
                                       <input type="hidden" name="addmore[{{$key}}][is_required]" value="0" >
                                       <label><input type="checkbox" name="addmore[{{$key}}][is_required]" value="1" @if($r->is_required==1) checked  @endif> Required</label>
                                   </td>
                                   <td><button type="button" class="btn btn-danger remove-tr"><i class="fa fa-minus"></i></button></td>
                               </tr>
                           @endforeach
                               <?php $addMoreStart = $key; ?>
                       @else
                           <tr>
                               <td><input type="text" name="addmore[0][name]" placeholder="Enter Attachment name" class="form-control" /></td>
                               <td><input type="number" name="addmore[0][order_by]" placeholder="Enter order" class="form-control" /></td>
                               <td>
                                   <input type="hidden" name="addmore[0][is_required]" value="0">
                                   <label><input type="checkbox" name="addmore[0][is_required]" value="1"> Required</label>
                               </td>
                               <td>
                                   <button type="button" name="add" class="btn btn-success addMoreBtn"><i class="fa fa-plus"></i> </button>
                               </td>
                           </tr>
                       @endif
                   @ENDIF
               </table>
               <button type="button" name="add" class="btn btn-success addMoreBtn"><i class="fa fa-plus"></i> </button>
               <button type="button" class="btn btn-primary" onclick="customUpdateConfirm('Are you sure want to update this data?','','green','AttachmentUpdate')">Update</button>
               <button type="button" class="btn btn-info gradient" onclick="cancel('/Issues')">Back</button>
           </form>
       </div>
    </div>
{{-- @endsection
@section('script') --}}
    <script type="text/javascript">
        var i = '{{$addMoreStart}}';
        $(".addMoreBtn").click(function(){
            ++i;
            $("#addMoreDynamicTable").append(
                '<tr><td><input type="text" name="addmore['+i+'][name]" placeholder="Enter Attachment name" class="form-control" /></td>' +
                '<td><input type="number" name="addmore['+i+'][order_by]" placeholder="Enter order" class="form-control" /></td>' +
                '<input type="hidden" name="addmore['+i+'][is_required]" value="0">' +
                '<td><label><input type="checkbox" name="addmore['+i+'][is_required]" value="1"> Required</label></td>' +
                '<td><button type="button" class="btn btn-danger remove-tr"><i class="fa fa-minus"></i></button></td></tr>'
            );
        });

        $(document).on('click', '.remove-tr', function(){
            $(this).parents('tr').remove();
        });
    </script>
@endsection
