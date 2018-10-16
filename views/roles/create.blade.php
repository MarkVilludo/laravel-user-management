@extends('layouts.app')
@section('content')

    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Page-Title -->
                       <!-- //Main content page. -->
            <div class='col-lg-6 col-lg-offset-2'>
                <div class="row" style="padding-bottom: 20px">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <h4 class="pull-left">Add Role</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
                        <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
                        <br><br>
                    </div>
                </div>
                {{-- @include ('errors.list') --}}

                {{ Form::open(array('url' => 'roles')) }}

                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
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
                <div class='form-group'>
                    @foreach ($permissions as $permissionModule)
                        <p>
                            {{Form::label($permissionModule['module'], ucfirst($permissionModule['module'])) }}
                        </p>
                        <div class="row col-md-12" style="padding-left: 20px">
                            @foreach ($permissionModule['module_functions'] as $permission)
                                <div class="col-md-9">
                                    {{Form::label($permission['name'], ucfirst($permission['name'])) }}<br>
                                </div>
                                <div class="col-md-3" style="text-align:center">
                                    <div class="checkbox">
                                      <input name="permissions[]" type="checkbox" value="{{$permission['id']}}" class="checkbox"  style="border:2px dotted #00f;display:block;background:#ff0000;"> 
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <p style="padding-top: 50px">

                    {{ Form::submit('Save', array('class' => 'btn btn-block btn-primary')) }}
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
