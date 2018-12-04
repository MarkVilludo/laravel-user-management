<?php

namespace MarkVilludo\Permission\Controllers;

use MarkVilludo\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use MarkVilludo\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use View;

class UserController extends Controller
{
    public function __construct() 
    {
        // $this->middleware(['auth']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (View::exists('users.index')) {
            return view('users.index');
        } else if (View::exists('superadmin.users.index')) {
            return view('superadmin.users.index');
        } else {
            return view('laravel-permission::users.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        $permissions = Permission::all();
        $data['roles'] = $roles;
        $data['permissions'] = $permissions;

        if (View::exists('users.create')) {
            return view('users.create', $data);
        } else if (View::exists('superadmin.users.create')) {
            return view('superadmin.users.create', $data);
        } else {
            return view('laravel-permission::users.create', $data);
        }
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
        // return $request['roles'];
        $this->validate($request, [
            'first_name'=>'required|max:120',
            'last_name'=>'required|max:120',
            'email'=>'required|email|unique:users'
        ]);

        $user = new User;
        $randomPassword = str_random(8);

        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->type = 'admin';
        $user->password = bcrypt($randomPassword);
        $user->is_expire_access = $request->is_expire_access;
        $user->expiration_date = $request->expiration_date;
        $user->save();


        //$this->sendCredentials($user, $randomPassword);

        $roles = $request['roles'];

        if (count($request['roles']) > 0) {

            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();            
                $user->assignRole($role_r);
            }
        }        

        $users = User::all();

        if (View::exists('users.index')) {
            return view('users.index')->with('users', $users)->with('flash_message','User successfully added.');
        } else if (View::exists('superadmin.users.index')) {
            return view('superadmin.users.index')->with('users', $users)->with('flash_message','User successfully added.');
        } else {
            return view('laravel-permission::users.index')->with('users', $users)->with('flash_message','User successfully added.');
        }
    }

    public function sendCredentials($user, $randomPassword)
    {
        $emails = $user->email;
        Mail::send('emails.send_credentials', ['project_title' => 'MPBL', 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'email' => $user->email, 'newPassword' => $randomPassword], function ($message) use ($emails) {
           $message->from(env('MAIL_FROM', 'mark.villudo@synergy88digital.com'));
           $message->to($emails);
           $message->subject("MPBL Credentials");
       });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();

        if (View::exists('users.edit')) {
            return view('users.edit', compact('user', 'roles'));
        } else if (View::exists('superadmin.users.edit')) {
            return view('superadmin.users.edit', compact('user', 'roles'));
        } else {
            return view('laravel-permission::users.edit', compact('user', 'roles'));
        }
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
        // return $request->all();
        $user = User::findOrFail($id);
        $this->validate($request, [
            'first_name'=>'required|max:120',
            'last_name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id
        ]);

        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->type = 'admin';
        $user->is_expire_access = $request->is_expire_access;
        $user->expiration_date = $request->expiration_date;
        $user->update();

        $roles = $request['roles'];
     

        //collect roles name and syn in user roles
        $rolesArray = [];
        if (count($request['roles']) > 0) {
            foreach ($request['roles'] as $key => $role) {
                # code...
                $role = Role::find($role);
                $rolesArray[] = $role->name;
            }
            $user->syncRoles($rolesArray);   
        }        
        else {
            $user->roles()->detach();
        }

        $users = User::all();

        if (View::exists('users.index')) {
            return view('users.index')->with('users', $users)->with('flash_message', 'User successfully edited.');
        } else if (View::exists('superadmin.users.index')) {
            return view('superadmin.users.index')->with('users', $users)->with('flash_message', 'User successfully edited.');
        } else {
            return view('laravel-permission::users.index')->with('users', $users)
                        ->with('flash_message', 'User successfully edited.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            $data['message'] = 'Successfully deleted user.';
            $statusCode = 200;

        } else {
            $data['message'] = 'Not found user.';
            $statusCode = 404;
        }

        return response()->json($data, $statusCode);
    }
}
