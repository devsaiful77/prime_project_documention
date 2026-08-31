<form action="{{ url('issues/common/config/store') }}" method="POST">@csrf
    <input type="hidden" value="{{ $issue->id }}" name="issue_id">
    <input type="hidden" value="inquiry_api" name="active_tab">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th class="vcenter text-center">Inquiry API Parameter </th>
            <th class="vcenter text-center">Label </th>
            <th class="vcenter text-right"><button type="button" class="btn btn-primary btn-sm addmoresubflow2"><i class="fa fa-plus"></i></button></th>
        </tr>
        </thead>
        <tbody class="appendsubflow2">
        <?php
        if (!empty(old('new2'))) {
            $exist_inq = old('new2');
        }
        ?>
        @if(!empty($exist_inq))
            @foreach($exist_inq AS $key => $e)
                <tr>
                    <th class="vcenter text-center">
                        <select class="form-control grpinfocls select2" name="new2[{{$key}}][api_parameter]" required style="width: 100%;">
                            <option value="">Please Select</option>
                            @foreach($inquiry as $ke => $val)
                                @php
                                    $optNameArr = explode("#",$val);
                                    $optKey = (!empty($optNameArr[0])) ? $optNameArr[0] : $val;
                                    $selected = '';
                                    if ($optKey == $e->api_parameter) {
                                        $selected = 'selected';
                                    }
                                    $val = preg_replace('/ns1/u', '', $val);
                                    $val = preg_replace('/\d+/u', '', $val);
                                @endphp
                                <option value="{{ $optKey }}" {{ $selected }}>{{ $val }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('new2.'.$key.'.api_parameter'))
                            <div class="error">
                                {!! $errors->first('new2.'.$key.'.api_parameter'); !!}
                            </div>
                        @endif
                    </th>
                    <th class="vcenter text-center">
                        <input type="text" class="form-control optcls" name="new2[{{$key}}][value]" placeholder="Please enter value" value="{{ $e['value'] }}" autocomplete="off" required>
                        @if($errors->has('new2.'.$key.'.value'))
                            <div class="error">
                                {!! $errors->first('new2.'.$key.'.value'); !!}
                            </div>
                        @endif
                    </th>
                    <th class="vcenter text-right"><button type="button" class="btn btn-danger btn-sm removesubflow2"><i class="fa fa-minus"></i></button></th>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <button type="button" class="btn btn-info gradient btn-sm" onclick="cancel('/Issues')">Back</button>
    <button type="submit" class="btn btn-primary btn-sm">Update</button>
</form>
<table class="hidden">
    <tbody class="newTr2">
    <tr>
        <th class="vcenter text-center">
            <select class="form-control grpinfocls" name="new2[0][api_parameter]" required>
                <option value="">Please Select</option>
                @foreach($inquiry AS $k => $v)
                <option value="{{ $v }}">{{ $v }}</option>
                @endforeach
            </select>
        </th>
        <th class="vcenter text-center">
            <input type="text" class="form-control optcls" name="new2[0][value]" placeholder="Please enter value" autocomplete="off" required>
        </th>
        <th class="vcenter text-right">
            <button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button>
        </th>
    </tr>
    </tbody>
</table>
