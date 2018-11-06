@extends('layouts.app')
@section('content')

    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title"><i class="fa fa-key"></i>Add role</h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{url('roles/index')}}">Roles</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- //Main content page. -->
            <div class='col-lg-6 col-lg-offset-4'>
                <div class="row col-lg-12 col-md-12 pb-2">
                    <h4>Role Details</h4>
                </div>
                {{ Form::open(array('url' => 'roles')) }}

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
                <div class='row form-group'>
                    @foreach ($permissions as $permissionModule)
                        <div class="col-md-2">
                            {{Form::label($permissionModule['module'], ucfirst($permissionModule['module'])) }}
                        </div>
                        <div class="row col-md-10 pl-4">
                                <div class="col-md-2">
                                    @if (count($permissionModule['module_functions']) == 4)
                                        <input type="checkbox" name="full_access" class="{{$permissionModule['module']}}" class="form-control" @change="selectAll('{{$permissionModule['module']}}',this)">
                                    @endif
                                </div>
                            @foreach ($permissionModule['module_functions'] as $permission)
                             <!-- {{Form::label($permission['name'], ucfirst($permission['name'])) }}<br> -->
                                <div class="col-md-2 text-center">
                                    <!-- <p>{{$permission['module']}}</p> -->
                                    <div class="checkbox checkbox-primary">
                                        <input name="permissions[]" id="{{$permission['module'].'-'.$permission['id']}}" value="{{$permission['id']}}" type="checkbox" class="{{$permission['module']}}">
                                        <label for="{{$permission['module'].'-'.$permission['id']}}">
                                            <!-- {{$permission['name']}} -->
                                        </label>
                                    </div>
<!-- 
                                    {{Form::checkbox('permissions[]',  $permission['id'], array('class' => $permission['module'])) }} -->
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{ Form::submit('Save', array('class' => 'btn btn-block btn-primary')) }}

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
<script src="{{url('assets/js/vue.js')}}"></script>
<!-- <script src="{{url('assets/js/vue.min.js')}}"></script> -->
<script src="{{url('assets/js/vue-resource.js')}}"></script>
<script src="{{url('assets/js/axios.js')}}"></script>
<!-- Include the Quill library -->
<script src="{{url('assets/js/sweetalert2.all.min.js')}}"></script>
<!-- jQuery  -->
<script src="{{url('/assets/js/jquery.min.js')}}"></script>
<script src="{{url('/assets/js/popper.min.js"></script><!-- Popper for Bootstrap --><!-- ')}}Tether for Bootstrap -->
<script src="{{url('/assets/js/bootstrap.min.js')}}"></script>
<!-- App js -->
<script src="{{asset('assets/js/jquery.core.js')}}"></script>
<script src="{{asset('assets/js/jquery.app.js')}}"></script>
<script>
    new Vue({
    el: '.content',
    data: {
       
    },
    components: {
    },
    methods: {
        selectAll(moduleClass, selectedPermission) {
            console.log(selectedPermission)
            if($('.'+moduleClass).is(":checked")){
                $("."+moduleClass).attr("checked",true);
            } else {
                $("."+moduleClass).removeAttr("checked");
            }
            console.log(moduleClass)
            
        }
    },
    computed: {
      
    },
        mounted() {
        }
    })

</script>
@endsection
