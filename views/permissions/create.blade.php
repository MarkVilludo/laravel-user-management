@extends('layouts.app')

@section('title', '| Create Permission')

@section('content')

 <!-- //Main content page. -->

<div class='col-lg-4 col-lg-offset-4' style="padding-top: 100px">

    {{-- @include ('errors.list') --}}

    {{ Form::open(array('url' => 'permissions')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    <br>
    <div class="form-group">
        {{ Form::label('module', 'Module Name') }}
        {{ Form::text('module', '', array('class' => 'form-control')) }}
    </div>
    <br>
    <h5><b>Assign Permission to Roles</b></h5>
        <div class="row">
            <div class='col-lg-6 form-group'>
                @foreach ($roles as $role)
                    {{ Form::checkbox('roles[]',  $role->id) }}
                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>

                @endforeach
            </div>
        </div>
    <br>
    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
<!-- End main content page -->
@endsection
