<?php

namespace MarkVilludo\Permission\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Response;
use Illuminate\Validation\Rule;
use Validator;
use MarkVilludo\Permission\Models\Role;
use MarkVilludo\Permission\Models\Permission;
use Config;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(User $user) {
        $this->user = $user;
    }
    public function index(Request $request)
    {   

        if ($request->search || $request->role) {
            $users = $this->user->filterByName($request->search)
                                ->filterByRole($request->role)
                                ->paginate(10);
        } else {
            $users = $this->user->paginate(10);
        }


        if ($users) {
          $data['message'] = 'Users list';
          $statusCode = 200;
        } else {
          $data['message'] = 'No users available';
          $statusCode = 200;
        }
        return UserResource::collection($users);
    }

     /**
     * Display no pagination
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
       $user = User::filterByName($request->search)
                   ->filterByRole($request->role)
                   ->where('type', 'admin')
                   ->get();

       if ($user) {
           $data = UserResource::collection($user);
           return $data;
       } else {
           $data['message'] = 'There is no players available.';
           $statusCode = 200;
           return response()->json($data, $statusCode);
       }
    }
    
    /**
     * get profile details
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserAccountDetails(Request $request, $user_id) 
    {   
       
        // return $user_id;
        $user = $this->user->where('id', $user_id)
                            ->first();
        if ($user) {
            $data['user'] = new UserResource($user);
            $data['message'] = 'User details';
            $statusCode = 200;
        } else {
            $data['message'] = 'User not found.';
            $statusCode = 400;
        }

        return response()->json($data, $statusCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function checkExistEmail(Request $request)
    {
     
      $user = $this->user->where('email', $request->email)->first();

      if ($user) {
        $data['exist'] = true;
      } else {
        $data['exist'] = false;
      }
      
      return Response::json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $rules = ['first_name' => 'required',
                  'last_name' => 'required',
                   'email' => 'email|required|unique:users,email', 
                   'status' => 'required', 
                   'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $data['message'] = [$validator->errors()];
            $statusCode = 422;
        } else {
            $newUser = new $this->user;
            $newUser->first_name = $request->first_name;
            $newUser->last_name = $request->last_name;
            $newUser->email = $request->email;
            $newUser->status = $request->status;
            $newUser->password = bcrypt($request->password);
            
            if ($newUser->save()) {

                $roles = $request['roles']; //Retrieving the roles field
                //Checking if a role was selected
                if (isset($roles)) {
                    foreach ($roles as $role) {
                        $role_r = Role::where('id', '=', $role)->firstOrFail();            
                        $newUser->assignRole($role_r); //Assigning role to user
                    }
                }       

                $data['message'] = Config::get('app_messages.SuccessAddedUser');
                $statusCode = 200;
            } else {
                $data['message'] = 'Something went wrong, Please try again later.';
                $statusCode = 400;
            }
        }
        return Response::json($data, $statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (auth()->user()->can('check-role-permission','Show user details')) {
            $user =  $this->user->where('id', $id)
                                ->first();

            $roles = Role::get(); //Get all roles

            if ($user) {  
                $user = new UserResource($user);
                $data['message'] = Config::get('app_messages.ShowUserDetails');
                $statusCode = 200;
            } else {
                $data['message'] = Config::get('app_messages.NotFoundUser');
                $statusCode = 404;
            }
            $data['data'] = $user;
        } else {
            $data['message'] = Config::get('app_messages.NoAccess');
            $statusCode = 401;
        }
        return Response::json($data, $statusCode);
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        //update user details only.
        $rules = ['first_name' => 'required',
                  'last_name' => 'required',
                   'email' => 'required|'.Rule::unique('users')->ignore($id, 'id'),
                   'status' => 'required', 
                   'password'=>'required|min:6|confirmed'

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $data['message'] = [$validator->errors()];
            $statusCode = 422;
        } else {

            $user = $this->user->findOrFail($id);
            $user->first_name = $request->first_name;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->password = bcrypt($request->password);

            $roles = $request->roles;

            if ($user->save()) {
                if (isset($roles)) {        
                    $user->roles()->sync($roles);            
                } else {
                    $user->roles()->detach();
                }
                
                $data['message'] = Config::get('app_messages.SuccessUpdatedUserDetails');
                $statusCode = 200;
            } else {
                $data['message'] = Config::get('app_messages.SomethingWentWrong');
                $statusCode = 400;
            }
        }
        return Response::json($data, $statusCode);
    }
}
