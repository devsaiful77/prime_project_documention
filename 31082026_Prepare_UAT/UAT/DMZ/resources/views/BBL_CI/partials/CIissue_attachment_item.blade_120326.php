
@php
    $item = $attachment_item;
@endphp
@if(!empty($item))
    @foreach($item as $key => $row)
        <div class="card card-color">
            <div class="card-body">
                <div class="form-group">
                    @if(isset($send_back))
                        <label class="mb-1">{{ $row->name }} </label>
                    @else
                        <label class="mb-1">{{ $row->name }}  @if($row->is_required == 1) <span style="color: red">*</span> @endif </label>
                    @endif
                    <input type="file" name="file_name[{{$key}}][file]" class="form-control file-upload">
                    <input type="hidden" name="file_name[{{$key}}][name]" value="{{ $row->name }}">
                    <input type="hidden" name="file_name[{{$key}}][is_required]" value="{{ isset($send_back) ? '' : $row->is_required}}">
                    <div class="error-message file_name_{{$key}}_file"></div>
                </div>
            </div>
        </div>
    @endforeach
@endif
