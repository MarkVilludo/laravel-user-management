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
            <div class='col-lg-8 col-lg-offset-4'>
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
                    <div class="col-md-2 text-center">
                        Full Access
                    </div>
                    <div class="row col-lg-8 col-md-8">
                        <div class="col-md-2 text-center">
                            View
                        </div>
                        <div class="col-md-2 text-center">
                            Add
                        </div>
                        <div class="col-md-2 text-center">
                            Edit
                        </div>
                        <div class="col-md-2 text-center">
                            Delete
                        </div>
                    </div>
                </div>
                <div class='row form-group' v-for="permission in permissions">
                    <div class="col-md-2">
                        @{{permission.module}}
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="checkbox checkbox-primary" v-if="permission.module_functions.length == 4">
                            <input name="full_access[]" :checked="permission.checked" v-bind:id="permission.module+'-'+permission.id" v-bind:value="permission.id" type="checkbox" v-bind:class="permission.module" @change="selectAll(permission)">
                            <label v-bind:for="permission.module+'-'+permission.id">
                            </label>
                        </div>
                    </div>
                    <div class="row col-lg-8 col-md-8">
                        <div class="col-md-2 text-center" v-for="module_function in permission.module_functions">
                            <div class="checkbox checkbox-primary">
                                <input name="permissions[]" :checked="module_function.checked" v-bind:id="module_function.module+'-'+module_function.id" v-bind:value="module_function.id" type="checkbox" v-bind:class="module_function.module" @change="selectEachPermission(permission, module_function, permission.module_functions)">
                                <label v-bind:for="module_function.module+'-'+module_function.id">
                                </label>
                            </div>
                        </div>
                    </div>
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
        checkedAll: false,
        permissions: [],
       
    },
    components: {
    },
    methods: {
        getPermissions(url) {
            axios.get(url, {
                headers: {
                    'Authorization': this.header_authorization,
                    'Accept': this.header_accept
                }
            }).then((response) => {
                this.permissions = response.data.permissions;
            });
        },
        selectAll(selectModule) {
            console.log(selectModule)   
            //toggle true / false checked the selected module
            if (selectModule.checked) {
                selectModule.checked = false;
            } else {
                selectModule.checked = true;
            }

            //update module functions check all
            $.each(selectModule.module_functions, function( index, permission ) {
                if(permission.checked == false) {
                    permission.checked = true;
                } else{
                    permission.checked = false;
                }
            });   
        }, selectEachPermission(seletectedModule, selectedPermission, moduleFunctions) {
            console.log(seletectedModule)
            console.log(moduleFunctions)

            //toggle true / false checked the selected module
            if (selectedPermission.checked) {
                selectedPermission.checked = false;
            } else {
                selectedPermission.checked = true;
            }

            var countCheck = [];

            $.each(moduleFunctions, function( index, moduleFunction ) {
                if (moduleFunction.checked == true) {
                    countCheck.push(1);
                   
                }
            });  

            //count selected permissions
             console.log(countCheck.length)
            if (countCheck.length == 4) {
                seletectedModule.checked = true;
            } else {
                seletectedModule.checked = false;
            }
            console.log(countCheck)

        }
    },
    computed: {
          
        },
        mounted() {
            this.getPermissions("{{route('api.group_permissions')}}");
        }
    })

</script>
@endsection
