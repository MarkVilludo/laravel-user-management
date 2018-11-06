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
                    <label for="first_name"> Role Name
                        <small>(Required)</small>
                    </label>
                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                </div>

                 <h5><b>Permissions</b></h5>

                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-10">
                        <div class="row ">
                            <div class="text-block">
                                Full Access
                            </div>
                            <div class="col-md-2 text-center">
                                View
                            </div>
                            <div class="col-md-2 text-center">
                                Create
                            </div>
                            <div class="col-md-2 text-center">
                                Edit
                            </div>
                            <div class="col-md-2 text-center">
                                Delete
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($permissions as $permissionModule)

                    <div class="row" style="padding-left: 20px">
                        <div class="col-md-2">
                            {{Form::label($permissionModule['module'], ucfirst($permissionModule['module'])) }}
                        </div>
                        <div class="row col-md-10">
                            <div class="col-md-2">
                                @if (count($permissionModule['module_functions']) == 4)
                                    <input type="checkbox" name="full_access" class="{{$permissionModule['module']}}" class="form-control" @change="selectAll('{{$permissionModule['module']}}',this)">
                                @endif
                            </div>
                            @foreach ($permissionModule['module_functions'] as $permission)
                                <div class="col-md-2 text-center">
                                    {{Form::checkbox('permissions[]',  $permission['id'], $role->permissions,["class"=> "checkbox"]) }}
                                </div>
                            @endforeach
                        </div>
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


