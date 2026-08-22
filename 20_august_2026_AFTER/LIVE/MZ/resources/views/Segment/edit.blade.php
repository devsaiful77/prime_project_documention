<form action="{{ route('segment.update', $data->id) }}" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="name">Name <span class="required">*</span></label>
        <input name="name" value="{{ $data->name ?? '' }}" class="form-control" autocomplete="off" type="text"
            placeholder="Name"/>
        <div class="error">{{ $errors->first('name') }}</div>
    </div>
    <div class="form-group">
        <label for="code">Code <span class="required">*</span></label>
        <input type="text" name="code" value="{{ $data->code ?? '' }}" class="form-control" autocomplete="off" type="text"
            placeholder="Code"/>
        <div class="error">{{ $errors->first('code') }}</div>
    </div>
    <div class="form-group float-right">
        <button type="submit" class="btn-primary btn">Submit</button>
    </div>
</form>
