@extends('layouts.admin')

@section('content')
    <h1>Edit User</h1>
    
    <div class="row">

    <div class="col-sm-3">
      <img src="{{$user->photo ? $user->photo->file : 'http://placehold.it/400x400' }}" alt="User Image" class="img-responsive img-rounded">
      
      {{--  using Gravatar  --}}
      {{--  <img src="{{$user->photo ? $user->photo->file : Auth::user()->gravatar }}" alt="User Image" class="img-responsive img-rounded">  --}}
    </div>

    <div class="col-sm-9">

    {!! Form::model($user, ['method'=>'PATCH', 'action'=>['AdminUsersController@update', $user->id], 'files'=>true]) !!}

    {{ csrf_field() }}

    <div class="form-group">
        {!! Form::label('name', 'Name:' ) !!}
        {!! Form::text('name', null, ['class'=>'form-control']) !!}
    </div>
    
    <div class="form-group">
        {!! Form::label('email', 'Email:') !!}
        {!! Form::email('email', null, ['class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('role_id', 'Role:') !!}
        {!! Form::select('role_id', [''=>'Choose Options'] + $roles , null,['class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('is_active', 'Status:') !!}
        {!! Form::select('is_active', array('1'=>'Active', '0'=>'Not Active'), null, ['class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('password', 'Password:') !!}
        {!! Form::Password('password', ['class'=>'form-control']) !!}
    </div>    

    <div class="form-group">
        {!! Form::label('photo_id', 'File:') !!}
        {!! Form::file('photo_id', null) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Update user', ['class'=>'btn btn-info col-sm-2']) !!} 
    </div>

    {!! Form::close() !!}

    {!! Form::open(['method'=>'DELETE', 'action'=>['AdminUsersController@destroy', $user->id]]) !!}
    
        <span class="col-sm-8"></span>

        <div class="form-group">
            {!! Form::submit('Delete user', ['class'=>'btn btn-danger col-sm-2']) !!}
        </div>
        
    {!! Form::close() !!}

    @include('includes.form_error')

    </div>
    </div>
    

@endsection