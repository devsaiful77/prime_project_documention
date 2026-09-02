@extends('layouts.admin')

@section('content')

<legend><h3>{{ $title_for_layout }}</h3></legend>

<form action="{{url('/roleUpdate/'.$role->id)}}" method="post" role="form">
    {{method_field('PATCH')}}
    {{csrf_field()}}

    <div class="form-group">
        <label for="name">Name of role</label>
        <input type="text" class="form-control" name="name" id="" placeholder="Name of role" readonly="readonly" value="{{$role->name}}">
        <div class="error">{{ $errors->first('name') }}</div>
    </div>

    <div class="form-group pt-2">
        <label for="display_name">Display name</label>
        <input type="text" class="form-control pt-1" name="display_name" id="" value="{{$role->display_name}}" placeholder="Display name">
        <div class="error">{{ $errors->first('display_name') }}</div>
    </div>

    <div class="form-group pt-2">
        <label for="description">Description</label>
        <input type="text" class="form-control pt-1" name="description" id="" placeholder="Description" value="{{$role->description}}">
    </div>

    <div class="form-group text-left pt-3">
        <h3>Permissions</h3>
        
        {{-- ($key == 'UpdateCompanyProfilesController') || ($key == 'UserTypeController') || ($key == 'UsersController')--}}

        @foreach($permissions as $key => $permission)
            @if($key == 'RoleController')

            @else
                <div class="row roles">
                    <div class="col-md-3 controllersName">
                        <b> {{ $key }} </b>
                    </div>

                    <div class="col-md-9 methodsName">
                        @if(!empty($permission))
                            @foreach($permission as $ikey => $value)
                                <div class="checkbox-inline"> <label><input type="checkbox" {{ in_array($ikey, $role_permissions) ? "checked" : "" }}  name="permission[]" value="<?= $ikey ?>">{{ $value }}</label> </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="button" class="btn btn-danger back" onclick="cancel('/roles')">Back</button>
</form>
@endsection