@extends('layouts.admin')
@section('content')
<div class="col-lg-12">
    <h3 class="text-center">Issue Conditional Field Configuration</h3>
</div>
<div class="col-lg-12">
    <div class="tab-content">
        <div class="clearfix">&nbsp;</div>
        <h5>Issue : {{ $issue->name }}</h5>
    </div>
    <form action="{{ url('issue/conditional/field') }}" method="POST" id="conditionalFieldSubmit">@csrf
        <input type="hidden" value="{{ $issue->id }}" name="issue_id">
        <table class="table table-condensed">
            <colgroup>
                <col width="30%">
                <col width="35%">
                <col width="30%">
                <col width="5%">
            </colgroup>
            <thead>
            <tr>
                <th class="vcenter text-center">Conditional Field</th>
                <th class="vcenter text-center">Conditional Value</th>
                <th class="vcenter text-center">Dependant Field</th>
                <th class="vcenter text-right">
                    <button type="button" class="btn btn-primary btn-sm addmoresubflow"><i class="fa fa-plus"></i></button>
                </th>
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
                            <select class="form-control conditionalcls" name="new[{{$key}}][conditional_field]" required>
                                <option value="">Please Select</option>
                                @foreach($issue_config_conditional as $val)
                                    @php
                                        $selected = '';
                                        if ($val->id == $e->conditional_field) {
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value="{{ $val->id }}" {{ $selected }}>{{ $val->label_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('new.'.$key.'.conditional_field'))
                                <div class="error">
                                    {!! $errors->first('new.'.$key.'.conditional_field'); !!}
                                </div>
                            @endif
                        </th>
                        <th class="vcenter text-center">
                            <select class="form-control optcls" name="new[{{$key}}][value]" required>
                                <option value="{{ $e->value }}">{{ $e->value }}</option>
                            </select>
                            @if($errors->has('new.'.$key.'.value'))
                                <div class="error">
                                    {!! $errors->first('new.'.$key.'.value'); !!}
                                </div>
                            @endif
                        </th>
                        <th class="vcenter text-center">
                            <select class="form-control dependantcls" name="new[{{$key}}][dependant_field]" required>
                                <option value="">Please Select</option>
                                @foreach($issue_config_dependant as $val)
                                    @php
                                        $selected = '';
                                        if ($val->id == $e->dependant_field) {
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value="{{ $val->id }}" {{ $selected }}>{{ $val->label_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('new.'.$key.'.dependant_field'))
                                <div class="error">
                                    {!! $errors->first('new.'.$key.'.dependant_field'); !!}
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
        <button type="button" class="btn btn-primary btn-sm" onclick="customUpdateConfirm('Conditional Field Update!','Are you sure want to update this data?','green','conditionalFieldSubmit')">Update</button>
    </form>
    <table class="hidden">
        <tbody class="newTr">
        <tr>
            <th class="vcenter text-center">
                <select class="form-control conditionalcls" name="new[0][conditional_field]" required>
                    <option value="">Please Select</option>
                    @foreach($issue_config_conditional as $v)
                    <option value="{{ $v->id }}">{{ $v->label_name }}</option>
                    @endforeach
                </select>
            </th>
            <th class="vcenter text-center">
                <select class="form-control optcls" name="new[0][value]" required>
                    <option value="">Please Select</option>
                </select>
            </th>
            <th class="vcenter text-center">
                <select class="form-control dependantcls" name="new[0][dependant_field]" required>
                    <option value="">Please Select</option>
                    @foreach($issue_config_dependant as $v)
                    <option value="{{ $v->id }}">{{ $v->label_name }}</option>
                    @endforeach
                </select>
            </th>
            <th class="vcenter text-right"><button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button></th>
        </tr>
        </tbody>
    </table>
</div>
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
                var newOptName = 'new['+idx+'][value]';
                $(this).attr('name',newOptName);
                ++idx;
            });
            var idx = 0;
            $('.conditionalcls').each(function(event){
                var optname = $(this).attr('name');
                var newOptName = 'new['+idx+'][conditional_field]';
                $(this).attr('name',newOptName);
                ++idx;
            });
            var idx = 0;
            $('.dependantcls').each(function(event){
                var optname = $(this).attr('name');
                var newOptName = 'new['+idx+'][dependant_field]';
                $(this).attr('name',newOptName);
                ++idx;
            });
        }

        var conditionalfields = $('.conditionalcls').val();
        $(".dependantcls option").each(function() {
            if ($(this).val() == conditionalfields) {
                $(this).remove();
            }
        });
        var conditionalDropdownHtml = null;
        var dependentDropdownHtml = null;
        $(document).ready(function() {
            conditionalDropdownHtml = $(".conditionalcls").html();
            dependentDropdownHtml = $(".dependantcls").html();
            var $conditionalOptions = $(conditionalDropdownHtml);
            var $dependentOptions = $(dependentDropdownHtml);
            $conditionalOptions.each(function() {
                var conditionalOptionValue = $(this).val();
                $dependentOptions.find("option[value='" + conditionalOptionValue + "']").remove();
            });
        });
        // console.log(dependentDropdownHtml);
        $(document.body).on('change', '.conditionalcls', function() {
            var tableRow = $(this).closest('tr');
            var conditionalfields = $(this).val();
            $(".dependantcls").html(dependentDropdownHtml);
            $(".dependantcls").each(function() {
                $(this).find("option[value='" + conditionalfields + "']").remove();
            });
            // console.log(dependentDropdownHtml);
            var dropdown1Element = tableRow.find('.optcls');
            dropdown1Element.empty();
            if (conditionalfields) {
                $.ajax({
                    url: "{{ url('/issue/conditional/field/value/') }}"+ "/" + conditionalfields,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        dropdown1Element.empty();
                        let data = response.options.split(",");
                        $.each(data, function (key, value) {
                            let result = value.includes("~");
                            if (result === true) {
                                var value = value.split("~");
                                var value = value[1];
                                value = value;
                            }
                            let postBackCompType = '';
                            let selected = "";
                            dropdown1Element.append('<option value="' + value + '" '+selected+' >' + value + '</option>');
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });

    </script>

@endsection
