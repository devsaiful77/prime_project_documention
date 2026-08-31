@extends('layouts.admin')
@section('content')
<div class="col-lg-12">
    <h3 class="text-center">Set Child Node</h3>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th class="vcenter text-right">Inquiry API :</th>
            <td class="vcenter text-left">{{ $parent->url }}</td>
            <th class="vcenter text-right">Search Index :</th>
            <td class="vcenter text-left">{{ $parent->search_idx }}</td>
            <th class="vcenter text-right">Node Index :</th>
            <td class="vcenter text-left">{{ $parent->node_idx }}</td>
            <th class="vcenter text-right">Node Value :</th>
            <td class="vcenter text-left">{{ $parent->node_value }}</td>
        </tr>
        </thead>
    </table>
</div>
<div class="col-lg-12">
    <form action="{{ url('issues/inquiry/config/child', $parent->id) }}" method="POST">@csrf
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="vcenter text-center">Child Node Index </th>
                <th class="vcenter text-center">Label </th>
                <th class="vcenter text-right"><button type="button" class="btn btn-primary btn-sm addmoresubflow"><i class="fa fa-plus"></i></button></th>
            </tr>
            </thead>
            <tbody class="appendsubflow">
            @if(!empty($childs))
                @foreach($childs AS $key => $e)
                    <tr>
                        <th class="vcenter text-center">
                            <input type="text" class="form-control grpinfocls" name="node[{{$key}}][index]"
                                   placeholder="Please enter node index" value="{{ $e->node_idx }}" autocomplete="off" required>
                            @if($errors->has('node.'.$key.'.index'))
                                <div class="error">
                                    {!! $errors->first('node.'.$key.'.index'); !!}
                                </div>
                            @endif
                        </th>
                        <th class="vcenter text-center">
                            <input type="text" class="form-control optcls" name="node[{{$key}}][label]"
                                   placeholder="Please enter label" value="{{ $e->node_value }}" autocomplete="off" required>
                            @if($errors->has('node.'.$key.'.label'))
                                <div class="error">
                                    {!! $errors->first('node.'.$key.'.label'); !!}
                                </div>
                            @endif
                        </th>
                        <th class="vcenter text-right"><button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button></th>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <a href="{{ url('issues/inquiry/config',$parent->issue_id) }}" class="btn btn-info btn-sm">Back</a>
        <button type="submit" class="btn btn-primary btn-sm">Update</button>
    </form>
    <table class="hidden">
        <tbody class="newTr">
        <tr>
            <th class="vcenter text-center">
                <input type="text" class="form-control grpinfocls" name="node[0][index]" placeholder="Please enter node index" autocomplete="off" required>
            </th>
            <th class="vcenter text-center">
                <input type="text" class="form-control optcls" name="node[0][label]" placeholder="Please enter label" autocomplete="off" required>
            </th>
            <th class="vcenter text-right">
                <button type="button" class="btn btn-danger btn-sm removesubflow"><i class="fa fa-minus"></i></button>
            </th>
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
                var newOptName = 'node['+idx+'][label]';
                $(this).attr('name',newOptName);
                ++idx;
            });
            var idx = 0;
            $('.grpinfocls').each(function(event){
                var optname = $(this).attr('name');
                var newOptName = 'node['+idx+'][index]';
                $(this).attr('name',newOptName);
                ++idx;
            });
        }
    </script>

@endsection
