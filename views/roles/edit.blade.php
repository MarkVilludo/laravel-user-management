@extends('layouts.app')
@section('content')

    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Page-Title -->

            <!-- //Main content page. -->
            <div class='col-lg-6 col-md-6 col-lg-offset-4'>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <h4 class="pull-left">Edit Role: {{$role->name}}</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
                        <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
                        <br><br>
                    </div>
                </div>
                {{-- @include ('errors.list') --}}
                {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}

                <div class="form-group">
                    {{ Form::label('name', 'Role Name') }}
                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                </div>

                <h5><b>Assign Permissions</b></h5>
                <div class="row" style="padding-bottom:20px">
                    <div class="col-md-7">
                        <strong>Module / Attributes</strong>
                    </div>
                    <div class="col-md-4" style="text-align:center">
                        <strong>Allow</strong>
                    </div>
                </div>
                @foreach ($permissions as $permissionModule)
                    {{Form::label($permissionModule['module'], ucfirst($permissionModule['module'])) }}<br>
                    <div class="row col-md-12" style="padding-left: 20px">
                        @foreach ($permissionModule['module_functions'] as $permission)
                            <div class="col-md-9">
                                {{Form::label($permission['name'], ucfirst($permission['name'])) }}<br>
                            </div>
                            <div class="col-md-3" style="text-align:center">
                                {{Form::checkbox('permissions[]',  $permission['id'], $role->permissions,["class"=> "checkbox"]) }}
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <br>
                <p style="padding-top: 30px">
                    {{ Form::submit('Update', array('class' => 'btn btn-block btn-primary')) }}
                </p>

                {{ Form::close() }}    
            </div>
            <!-- End main content page -->
            <!-- end row -->
        </div>
        <!-- end content -->
    </div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
 
@endsection


