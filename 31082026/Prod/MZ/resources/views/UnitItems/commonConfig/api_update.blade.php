<form action="{{ url('issues/common/config/store') }}" method="POST" id="apiUpdateSubmit">@csrf
    <input type="hidden" value="{{ $issue->id }}" name="issue_id">
    <input type="hidden" value="api_update" name="active_tab">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th class="vcenter text-center">API Update Parameter </th>
            <th class="vcenter text-center">Value </th>
            <th class="vcenter text-right"><button type="button" class="btn btn-primary btn-sm addmoresubflow"><i class="fa fa-plus"></i></button></th>
        </tr>
        </thead>
        <tbody class="appendsubflow">
        <?php
        if (!empty(old('new'))) {
            $exist = old('new');
        }
        ?>
        @if(!empty($exist))
            @foreach($exist AS $key => $e)
                <tr>
                    <th class="vcenter text-center">
                        <select class="form-control grpinfocls" name="new[{{$key}}][api_parameter]" required>
                            <option value="">Please Select</option>
                            @foreach($cif as $ke => $val)
                                @php
                                    $selected = '';
                                    if ($val == $e->api_parameter) {
                                        $selected = 'selected';
                                    }
                                @endphp
                                <option value="{{ $val }}" {{ $selected }}>{{ $val }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('new.'.$key.'.api_parameter'))
                            <div class="error">
                                {!! $errors->first('new.'.$key.'.api_parameter'); !!}
                            </div>
                        @endif
                    </th>
                    <th class="vcenter text-center">
                        <input type="text" class="form-control optcls" name="new[{{$key}}][value]" placeholder="Please enter value" value="{{ $e['value'] }}" autocomplete="off" required>
                        @if($errors->has('new.'.$key.'.value'))
                            <div class="error">
                                {!! $errors->first('new.'.$key.'.value'); !!}
                            </div>
                        @endif
                    </th>
                    <th class="vcenter text-right"><button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button></th>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <button type="button" class="btn btn-info gradient btn-sm" onclick="cancel('/Issues')">Back</button>
    <button type="button" class="btn btn-primary btn-sm" onclick="customUpdateConfirm('API Conf Update!','Are you sure want to update this data?','green','apiUpdateSubmit')">Update</button>
</form>
<table class="hidden">
    <tbody class="newTr">
    <tr>
        <th class="vcenter text-center">
            <select class="form-control grpinfocls" name="new[0][api_parameter]" required>
                <option value="">Please Select</option>
                @foreach($cif AS $k => $v)
                <option value="{{ $v }}">{{ $v }}</option>
                @endforeach
            </select>
        </th>
        <th class="vcenter text-center"><input type="text" class="form-control optcls" name="new[0][value]" placeholder="Please enter value" autocomplete="off" required> </th>
        <th class="vcenter text-right"><button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button></th>
    </tr>
    </tbody>
</table>
