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

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date/Time Added</th>
                    <th>Expiration Date</th>
                    <th>User Roles</th>
                    <th>Operations</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->first_name.' '.$user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
                    <td>{{ $user->expiration_date ? $user->expiration_date : 'Never' }}</td>
                    <td>{{ $user->roles ?  $user->roles()->pluck('name')->implode(' ') : null }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}

                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('users.edit', $user->id) }}">
                                    <button class="btn btn-info btn-block"> Edit </button>
                                </a>
                            </div>
                            <div class="col-md-6">
                               <!--  {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id] ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!} -->
                                <button class="btn btn-danger btn-block" @click="deleteUser({{$user}})"> Delete </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    <a href="{{ route('users.create') }}">
        <button class="btn btn-success btn-custom waves-effect w-md waves-light m-b-5 pull-left"> <i class="fa fa-plus"> </i> Add User</button>
    </a>
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
       
    },
    components: {
    },
    methods: {
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
        }
    })

</script>
@endsection
