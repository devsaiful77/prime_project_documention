@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <legend><h3>{{ $title_for_layout }}</h3></legend>

        <form action="{{url('/restrictedIpUpdate/'.$restrictedIp->id)}}" method="post" role="form">
            {{method_field('PATCH')}}
            {{csrf_field()}}
            <div class="form-group">
                <label>User</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($user->id==$restrictedIp->user_id) selected @endif>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group pt-3">
                <label for="name">Ip Address</label>
                <input type="text" class="form-control" name="ipAddress" id="" placeholder="Ip Address" value="{{$restrictedIp->ip}}">
                <div class="error">{{ $errors->first('ipAddress') }}</div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
            <button type="button" class="btn btn-danger back mt-3" onclick="cancel('/restrictedIps')">Back</button>
        </form>
    </div>
</div>

@endsection
