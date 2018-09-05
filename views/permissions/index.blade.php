 <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title"><i class="fa fa-key"></i>Permissions</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{url('permissions/index')}}">Permissions</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- //Main content page. -->
            <div class="row col-lg-12">
                <a href="{{ URL::to('permissions/create') }}">
                    <button class="btn btn-success btn-custom waves-effect w-md waves-light m-b-5 pull-right"> <i class="fa fa-plus"> </i> Add Permission</button>
                </a>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Permissions</th>
                                <th>Module</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td> 
                                <td>{{ $permission->module }}</td> 
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-success waves-effect waves-light">
                                            <i class="ion-gear-a"> </i>
                                        </button>
                                        <button type="button" class="btn btn-success dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(109px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item" href="{{ URL::to('permissions/'.$permission->id.'/edit') }}">
                                                <button class="btn btn-primary w-sm">Edit</button>
                                            </a>
                                            <a class="dropdown-item" href="#"> 
                                                 {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn w-sm btn-danger']) !!}
                                                {!! Form::close() !!}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $permissions->links() }}
                </div>
            </div>
            <!-- End main content page -->
        </div>
    <!-- end content -->
    </div>
