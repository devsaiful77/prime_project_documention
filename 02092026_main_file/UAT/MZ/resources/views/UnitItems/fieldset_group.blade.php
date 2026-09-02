@extends('layouts.admin')
@section('content')
    <?php $addMoreStart = 0; ?>
    <div class="row"> <div class="col-md-12"> <h3 class="text-center">Fieldset Group Configuration</h3> </div> <h4>Issue:{{ $issue->name }}</h4> </div>
    <div class="row">
       <div class="col-xl-12">
           <form action="{{ url('fieldset-store/store') }}" method="POST" id="FieldSetUpdate">
               @csrf
               <input type="hidden" value="{{ $issue->id }}" name="issue_id">
               <table class="table" id="addMoreDynamicTable">
                   <tr>
                       <th>Group Name</th>
                       <th>Group ID (Unique)</th>
                       <th>Action</th>
                   </tr>
                   @IF(!empty($pbfieldsetGroup))
                        @FOREACH($pbfieldsetGroup AS $key=>$val)
                               <tr>
                                    <td>
                                        <input type="text" name="addmore[{{$key}}][group_name]" placeholder="Enter Group name" class="form-control" value="{{$val['group_name']}}" />
                                        <div class="error">{{ $errors->first('addmore.'.$key.'.group_name') }}</div>
                                    </td>

                                    <td>
                                        <input type="text" name="addmore[{{$key}}][group_id]" placeholder="Enter Group name" class="form-control" value="{{$val['group_id']}}" />
                                        <div class="error">{{ $errors->first('addmore.'.$key.'.group_id') }}</div>
                                    </td>

                                   <td>
                                       <button type="button" class="btn btn-danger remove-tr"><i class="fa fa-minus"></i></button>
                                   </td>
                               </tr>
                       @ENDFOREACH
                       <?php $addMoreStart = $key; ?>
                   @ELSE
                       @if(count($fieldsetGroup)!=0)
                           @foreach($fieldsetGroup as $key=>$r)
                               <tr>
                                   <td><input type="text" name="addmore[{{$key}}][group_name_old][{{ $r->id }}]" placeholder="Enter Label name" class="form-control" value="{{ $r->name }}" required/></td>
                                   <td><input type="text" name="addmore[{{$key}}][group_id_old][{{ $r->id }}]" placeholder="Enter Label id" class="form-control" value="{{ $r->group_id_name }}" required/></td>
                                   <td>
                                       @if($issue->id != '1103' && $issue->id != '1105')
                                           <a href="#" class="text-warning h4" onclick="customConfirm('Fieldset Group Delete!','Are you sure you want to delete this Items?','red','{{ url('/fieldset/destroy/'. $r->id) }}')"><i class="fa fa-trash"></i></a>
                                       @endif
                                   </td>
                               </tr>
                           @endforeach
                               <?php $addMoreStart = $key; ?>
                       @else
                           {{-- first stage --}}
                           <tr>
                               <td><input type="text" name="addmore[0][group_name]" placeholder="Enter Group name" class="form-control" required/></td>
                               <td><input type="text" name="addmore[0][group_id]" placeholder="Enter Group ID" class="form-control" required/></td>

                               <td>
                                   <button type="button" name="add" class="btn btn-success btn-sm addMoreBtn"><i class="fa fa-plus"></i> </button>
                               </td>
                           </tr>
                       @endif
                   @ENDIF
               </table>
               <button type="button" name="add" class="btn btn-success addMoreBtn"><i class="fa fa-plus"></i> </button>
               <button type="button" class="btn btn-primary" onclick="customUpdateConfirm('Are you sure want to update this data?','','green','FieldSetUpdate')">Update</button>
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
                '<tr><td><input type="text" name="addmore['+i+'][group_name]" placeholder="Enter Group name" class="form-control" required/></td>' +
                '<td><input type="text" name="addmore['+i+'][group_id]" placeholder="Enter Group ID" class="form-control" required/></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="fa fa-minus"></i></button></td></tr>'
            );
        });

        $(document).on('click', '.remove-tr', function(){
            $(this).parents('tr').remove();
        });
    </script>
@endsection
