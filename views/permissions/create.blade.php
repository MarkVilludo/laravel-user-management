@extends('layouts.app')

@section('title', '| Create Permission')

@section('content')

 <!-- //Main content page. -->

<div class='col-lg-6 col-md-6 col-lg-offset-4' style="padding-top: 40px">
    <div class="row" style="padding-bottom: 20px">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h4 class="pull-left">Add Permission</h4>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
            <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
            <br><br>
        </div>
    </div>
    {{-- @include ('errors.list') --}}
    {{ Form::open(array('url' => 'permissions')) }}

    <div class="form-group">
        {{ Form::label('name', 'Permission Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    <br>
    <div class="form-group">
        {{ Form::label('module', 'Module') }}
        {{ Form::text('module', '', array('class' => 'form-control')) }}
    </div>
    <br>
    <h5><b>Assign to Specific Roles</b></h5>
        <div class="row">
            <div class='col-lg-6 form-group'>
                @foreach ($roles as $role)
                    {{ Form::checkbox('roles[]',  $role->id) }}
                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>

                @endforeach
            </div>
        </div>
    <br>
    {{ Form::submit('Save', array('class' => 'btn btn-block btn-primary')) }}

    {{ Form::close() }}

</div>
<!-- End main content page -->
@endsection
