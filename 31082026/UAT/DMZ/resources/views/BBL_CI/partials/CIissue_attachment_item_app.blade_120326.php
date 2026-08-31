
@php
    $item = $attachment_item;
@endphp
@if(!empty($item))
    @foreach($item as $key => $row)
        <div class="input-wrapper mb-3">
            @if(isset($send_back))
                <label for="" class="input-label">{{ $row->name }}</label>
            @else
                <label for="" class="input-label">{{ $row->name }}@if( $row->is_required == 1 ) <span style="color: red">*</span> @endif</label>
            @endif
            <input type="file" name="file_name[{{$key}}][file]" class="form-control file-upload">
            <input type="hidden" name="file_name[{{$key}}][name]" value="{{ $row->name }}">
            <input type="hidden" name="file_name[{{$key}}][is_required]" value="{{ isset($send_back) ? '' : $row->is_required}}">
            <div class="error-message file_name_{{$key}}_file"></div>
        </div>
    @endforeach
@endif
