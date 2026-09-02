
@php
    $item=$attachment_item;
@endphp
@if(!empty($item))
    @foreach($item as $row)
        <div class="form-group">
            <label class="font-weight-bold">{{ $row->name }} <span class="required">@if($row->is_required==1) * @endif</span></label>
            <input type="file" name="file_name[]" class="form-control" required>
        </div>
    @endforeach
@endif
