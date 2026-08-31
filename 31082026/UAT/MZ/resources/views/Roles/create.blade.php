@extends('layouts.admin')

@section('content')

    {!! Form::open(['url' => 'rolesStore', 'method' => 'post']) !!}
        {{csrf_field()}}

        <input type="hidden" name="module" value="1">
        <div class="form-group">
            <label for="name">Name of role <span class="required">*</span></label>
            {!!
              Form::text('name', '' ,[
                'class' => 'form-control',
                'label'=>false,
                'autocomplete'=>'off',
                'type'=>'text',
                'autofocus'=>'true',
                'placeholder'=>'Name of role'
              ]);
            !!}
            <div class="error pt-1">{{ $errors->first('name') }}
            </div>
        </div>

        <div class="form-group">
            <label for="display_name">Display name <span class="required">*</span></label>
            {!!
              Form::text('display_name', '' ,[
                'class' => 'form-control',
                'label'=>false,
                'autocomplete'=>'off',
                'type'=>'text',
                'autofocus'=>'true',
                'placeholder'=>'Display Name'
              ]);
            !!}
            <div class="error pt-1">{{ $errors->first('display_name') }}</div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            {!!
              Form::text('description', '' ,[
                'class' => 'form-control',
                'label'=>false,
                'autocomplete'=>'off',
                'type'=>'text',
                'placeholder'=>'Description'
              ]);
            !!}
        </div>

        <div class="form-group text-left">
            <h3>Permissions</h3>

            {{-- ($key == 'UpdateCompanyProfilesController') || ($key == 'UserTypeController')|| ($key == 'UsersController')--}}

            @foreach($permissions as $key => $permission)
                @if($key == 'RoleController')

                @else
                    <div class="row">
                        <div class="col-md-3 controllersName">
                            <b> {{ $key }} </b>
                        </div>

                        <div class="col-md-9 methodsName">
                            @foreach($permission as $ikey => $value)
                                <div class="checkbox-inline"> <label><input type="checkbox" name="permission[]" value="<?= $ikey ?>">{{ $value }}</label> </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {!!
            Form::submit((!empty($id)) ? 'Update':'Submit',array(
                'class'=>'btn btn-primary',
                'title'=>'Add',
                'escape'=>false
            ));
        !!}
        <button type="button" class="btn btn-danger back" onclick="cancel('/roles')">Back</button>
    {!! Form::close() !!}

@endsection
