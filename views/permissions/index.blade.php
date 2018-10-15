 @extends('layouts.app')

@section('title', '| Users')

@section('content')

 <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Page-Title -->
            <!-- //Main content page. -->
            <div class="col-lg-10 col-lg-offset-1">
                <div class="row col-lg-12">
                   <h4 class="page-title"><i class="fa fa-key"></i>Permissions</h4>
                </div>
                <a href="{{ URL::to('permissions/create') }}">
                    <button class="btn btn-success btn-custom waves-effect w-md waves-light m-b-5 pull-right"> <i class="fa fa-plus"> </i> Add Permission</button>
                </a>
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

                                    <a class="btn btn-primary" href="{{ URL::to('permissions/'.$permission->id.'/edit') }}">
                                      <i class="fa fa-pencil"></i> Edit
                                    </a>
                                
                                     {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn w-sm btn-danger']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End main content page -->
        </div>
    <!-- end content -->
    </div>


</div>

@endsection
