@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6">
        {!! Form::open(['url' => 'restrictedIpsStore', 'method' => 'post']) !!}
        {{ csrf_field() }}
        
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="ipAddress" class="form-label">IP Address</label>
            {!! Form::text('ipAddress', '', [
                'class' => 'form-control',
                'id' => 'ipAddress',
                'autocomplete' => 'off',
                'placeholder' => 'IP Address',
                'autofocus' => 'true'
            ]) !!}
            <div class="text-danger">{{ $errors->first('ipAddress') }}</div>
        </div>
        
        <div class="mb-3">
            {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            <button type="button" class="btn btn-danger" onclick="cancel('/restrictedIps')">Back</button>
        </div>
        
    {!! Form::close() !!}
    </div>
</div>


    {{-- {!! Form::open(['url' => 'restrictedIpsStore', 'method' => 'post']) !!}
        {{csrf_field()}}
        <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Ip address</label>
            {!!
              Form::text('ipAddress', '' ,[
                'class' => 'form-control',
                'label'=>false,
                'autocomplete'=>'off',
                'type'=>'text',
                'autofocus'=>'true',
                'placeholder'=>'Ip Address'
              ]);
            !!}
            <div class="error">{{ $errors->first('ipAddress') }}</div>
        </div>
        {!!
            Form::submit('Submit',array(
                'class'=>'btn btn-primary',
                'escape'=>false
            ));
        !!}
        <button type="button" class="btn btn-danger back" onclick="cancel('/restrictedIps')">Back</button>
    {!! Form::close() !!} --}}

@endsection
