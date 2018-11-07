@extends('layouts.app')

@section('title', '| Users')

@section('content')

<div class="content col-lg-10 col-lg-offset-1">
    <div class="row" style="padding-bottom: 20px">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h4 class="pull-left">Users</h4>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
            <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
            <br><br>
        </div>
    </div>

        <div class="row pb-2">
            <div class="col-md-3">
                <a href="{{ route('users.create') }}">
                    <button class="btn btn-success btn-custom waves-effect w-md waves-light m-b-5 pull-left"> <i class="fa fa-plus"> </i> Add User</button>
                </a>
            </div>
            <div class="col-md-9">
                <div class="form-inline pull-right">
                      <label>Search User: </label>
                    <input type="text" name="search" v-model="search" @change="filter()" class="form-control">
                </div>
              
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-md-3">
                <select class="form-control" @change="filter()" v-model="filterByRoles">
                    <option value="">Filter Roles</option>
                    <option v-if="roles" v-for="role in roles" v-bind:value="role.id">@{{role.name}}</option>
                </select>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead align="center">
               <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date/Time Added</th>
                    <th>Expiration Date</th>
                    <th>User Roles</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody align="center">
                <tr v-if="users" v-for="user in users">
                    <td>@{{user.first_name+' '+user.last_name}}</td>
                    <td>@{{user.email}}</td>
                    <td>@{{user.created_at}}</td>
                    <td>@{{user.expiration_date}}</td>
                    <td>
                        <ul>
                            <li v-if="user.roles" v-for="role in user.roles">@{{role}}</li>
                        </ul>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-info btn-block" @click="editUser(user)"> Edit </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger btn-block" @click="deleteUser(user)"> Delete </button>
                            </div>
                        </div>


                    </td>
                </tr>
                <tr v-if="users.length == 0">
                    <td colspan="8" class="text-center">No data found</td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <ul class="pagination">
                    <li class="paginate_button page-item previous" id="datatable-editable_previous"><a href="#" aria-controls="datatable-editable" data-dt-idx="0" tabindex="0" class="page-link" :disabled="pagination.current_page == pagination.from" @click.prevent="changePage(pagination.current_page - 1,'previous')">Previous</a></li>
                    <li v-for="page in pages" class="paginate_button page-item active">
                        <a href="#" :class="isCurrentPage(page) ? 'is-current selected-page' : ''" @click.prevent="changePage(page)" aria-controls="datatable-editable" data-dt-idx="1" tabindex="0" class="page-link">@{{page}}</a></li>

                    <li class="paginate_button page-item next" id="datatable-editable_next"><a href="#" aria-controls="datatable-editable" data-dt-idx="7" tabindex="0" class="page-link" :disabled="pagination.current_page >= pagination.last_page" @click.prevent="changePage(pagination.current_page + 1,'next')">Next</a></li>
                </ul>
            </div>
        </div>
    
</div>
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
        users: [],
        roles: [],
        search: '',
        filterByRoles: '',
        pagination: '',
        pages: '',
        from: '',
        to: '',
        offset: '',
        firstPage: '',
        nextPage: '',
        prevPage: '',
        lastPage: '',
        noResultFound : false
    },
    components: {
    },
    methods: {
        getUsers(url){
            axios.get(url, {
                headers: {
                    'Authorization': this.header_authorization,
                    'Accept': this.header_accept
                }
            }).then((response) => {
                this.users = response.data.data;
                if (this.users.length > 0) {
                    this.noResultFound = true;
                } else {
                    this.noResultFound = false;
                }
                this.pagination = response.data.meta;  
                //get last page item
                this.pages = response.data.meta.last_page;
                this.path = response.data.meta.path;

                this.firstPage = response.data.links.first;
                this.nextPage = response.data.links.next;
                this.prevPage = response.data.links.prev;
                this.lastPage = response.data.meta.last_page;
                console.log(this.users)
            });
        },
        getRoles(url) {
            axios.get(url, {
                headers: {
                    'Authorization': this.header_authorization,
                    'Accept': this.header_accept
                }
            }).then((response) => {
                this.roles = response.data;
            });
        },
        filter() {
            if (this.search && this.search.length >= 3) {
                this.searchFunction();
            } else {
                this.searchFunction();
            }
        },
        searchFunction() {
            axios.get('{{route('api.users')}}'+"?search="+this.search+"&role="+this.filterByRoles)
            .then((response) => {
                console.log(response)
                this.users = response.data.data;
                if (this.users.length > 0) {
                    this.noResultFound = true;
                } else {
                    this.noResultFound = false;
                }

                this.pagination = response.data.meta;  
                //get last page item
                this.pages = response.data.meta.last_page;
                this.path = response.data.meta.path;

                this.firstPage = response.data.links.first;
                this.nextPage = response.data.links.next;
                this.prevPage = response.data.links.prev;
                this.lastPage = response.data.links.last_page;
            });
        },
        isCurrentPage(page) {
            return this.pagination.current_page === page;
        },
        editUser(user) {
            console.log(user.id)
            // console.log(window.location.href)
            window.location.href = "{{request()->root().'/users' }}/"+user.id+"/edit";

        },
        changePage(page, step) {
            // console.log(this.lastPage)
            console.log(page)
          
            if (page) {
                this.pagination.current_page = page;
                if (step == 'previous' && this.pagination.current_page >= 1) {
                    this.products = [];
                    this.getUsers(this.prevPage);
                } else if(step == 'next' && this.pagination.current_page <= this.pagination.last_page){
                    this.products = [];
                    this.getUsers(this.nextPage);
                } else if(!step){
                    this.products = [];
                    this.getUsers(this.path+ "?page=" + page+ "&role=" + this.filterByRoles);
                }
            }
        },
        deleteUser(user) {
                console.log(user)
                swal({
                    title: 'Delete',
                    text: "Are you sure you want to delete this user?",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3c8dbc',
                    cancelButtonColor: '#3c8dbc',
                    confirmButtonText: 'Yes, Please!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-info',
                    cancelButtonClass: 'btn btn-grey',
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        axios.delete("{{url('users')}}/"+user.id, {
                            headers: {
                                'Authorization': this.header_authorization,
                                'Accept': this.header_accept
                            }
                        })
                        .then((response) => {
                            swal("Success!",response.data.message, "success");
                            setTimeout(function(){
                               window.location.reload(1);
                            }, 2000);
                        })
                        .catch(error => {
                            console.log(error.response.data.errors)
                            swal("Failed!",  JSON.stringify(error.response.data.message), "info");
                        });
                    }
                });
        }
    },
    computed: {
      
    },
        mounted() {

            this.getUsers("{{route('api.users')}}");
            this.getRoles("{{route('api.roles')}}");
        }
    })

</script>
@endsection
