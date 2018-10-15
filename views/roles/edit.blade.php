@extends('layouts.app')
@section('content')

    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title"><i class="fa fa-key"></i>Edit Role: {{$role->name}}</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{url('roles/index')}}">Roles</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- //Main content page. -->
            <div class='col-lg-4 col-lg-offset-4'>
                <hr>
                {{-- @include ('errors.list') --}}
                {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}

                <div class="form-group">
                    {{ Form::label('name', 'Role Name') }}
                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                </div>

                <h5><b>Assign Permissions</b></h5>
                <div class="row" style="padding-bottom:20px">
                    <div class="col-md-8">
                        <strong>Module / Attributes</strong>
                    </div>
                    <div class="col-md-3" style="text-align:center">
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
                {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

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


