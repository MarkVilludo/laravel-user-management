<?php

namespace MarkVilludo\Permission\Controllers;

use MarkVilludo\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use MarkVilludo\Permission\Models\Role;
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
        $users = User::all();
        $permissions = Permission::all();
        $data['users'] = $users;
        $data['permissions'] = $permissions;

        if (View::exists('users.index')) {
            return view('users.index', $data);
        } else if (View::exists('superadmin.users.index')) {
            return view('superadmin.users.index')->with('users', $users);
        } else {
            return view('laravel-permission::users.index', $data);
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
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);

        $user = User::create($request->only('email', 'first_name', 'last_name', 'password', 'is_expire_access', 'expiration_date'));

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
            'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'required|min:6|confirmed'
        ]);

        $input = $request->only(['name', 'email', 'password']);
        $roles = $request['roles'];
        $user->fill($input)->save();

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
        $user = User::findOrFail($id);
        $user->delete();

        if (View::exists('users.index')) {
            return redirect()->route('users.index')->with('flash_message', 'User successfully deleted.');
        } else if (View::exists('superadmin.users.index')) {
            return redirect()->route('superadmin.users.index')->with('flash_message', 'User successfully deleted.');
        } else {
            return redirect()->route('laravel-permission::users.index')
                ->with('flash_message',
                 'User successfully deleted.');
        }
    }
}
