<?php

namespace MarkVilludo\Permission\Controllers\Api;

use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MarkVilludo\Permission\Models\Role;
use MarkVilludo\Permission\Models\RoleHasPermission;
use MarkVilludo\Permission\Models\Permission;
use Auth;
use Response;
use Validator;
use Config;

class RoleController extends Controller
{   
    public function __construct(Permission $permission, RoleHasPermission $rolePermission, Role $role) 
    {   
        $this->role = $role;
        $this->permission = $permission;
        $this->rolePermission = $rolePermission;
        // $this->middleware(['auth', 'isAdmin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->role->paginate(20);

        if ($roles) {
          $data['message'] = 'roles list';
          $statusCode = 200;
        } else {
          $data['message'] = 'No roles available';
          $statusCode = 200;
        }

        return RoleResource::collection($roles);
    }

    public function rolePermissions(Request $request)
    {
        
        $rolePermissions = $this->rolePermission->where('role_id', $request->role_id)->get();

        $getPermissionsArray = [];
        foreach ($rolePermissions as $rolePermission) {
            $getPermissionsArray[] = [
                'role_id' => $rolePermission->role_id,
                'permission_id' => $rolePermission->permission_id,
                'name' => $rolePermission->roleAccess->module.' '.$rolePermission->permission_name
            ];
        }
        $statusCode = 200;
        return Response::json(['data' => $getPermissionsArray], $statusCode);
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
        $rules = [
            'name' => 'required|unique:roles,name',
            'permissions' =>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['message'] = [$validator->errors()];
            $statusCode = 422;
        } else {

            $name = $request->name;
            $role = new Role();
            $role->name = $name;

            $permissions = $request['permissions'];

            if ($role->save()) {
                if (count($permissions) > 0) {
                    foreach ($permissions as $permission) {
                        $p = Permission::where('id', '=', $permission)->firstOrFail();
                        $role = Role::where('name', '=', $name)->first();
                        $role->givePermissionTo($p);
                    }
                }
                $data['message'] = Config::get('app_messages.SuccessCreateRole');
                $statusCode = 200;

            } else {
                $data['message'] = Config::get('app_messages.SomethingWentWrong');
                $statusCode = 400;
            }
        }
        return Response::json(['data' => $data], $statusCode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return 'test';
        $role = Role::findOrFail($id);
        $permissionsArray = Permission::all();

        // begin the iteration for grouping module name
        $permissions = [];
        $modulefunctionArray = [];
        $result = [];

        foreach ($permissionsArray as $key => $module) {
            $modulefunctionArray[$module->module] = ['module' => $module->module, 'guard_name' => $module->guard_name, 'id' => $module->id];

        }
        foreach ($modulefunctionArray as $keyModule => $value) {
            $moduleFunction = [];
            $moduleName = $value['module'];
            $isAllAccessModule = [];
            foreach ($permissionsArray as $key => $module) {
                if ($module->module == $moduleName) {
                    $rolePermissionExist = $this->rolePermission->where('permission_id', $module->id)
                                                                ->where('role_id', $id)
                                                                ->first();



                    $moduleFunction[] = ['id' => $module->id,'module' => $module->module,'name' => $module->name, 'checked' => $rolePermissionExist ? 1 : 0];

                 
                    $isAllAccessModule[] = $rolePermissionExist ? 1 : 0;
                }
            }
            //count all module function with access
            $permissions[] = ['module' => $value['module'],'id' => $value['id'], 'module_functions' => $moduleFunction, 'checked' => array_sum($isAllAccessModule) == 4 ? true : false];
        }

        $data['role'] = $role;
        $data['permissions'] = $permissions;


        return Response::json($data, 200);
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
        $rules = [
            'name' => 'required|unique:roles,name,'.$id,
            'permissions' =>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['message'] = [$validator->errors()];
            $statusCode = 422;
        } else {    
            // return $id;
            $role = Role::findOrFail($id);

            $input = $request->except(['permissions']);
            $permissions = $request->permissions;
            
            if ($role->fill($input)->save()) {
                $getPermissions = Permission::all();

                foreach ($getPermissions as $getPermission) {
                    $role->revokePermissionTo($getPermission);
                }

                foreach ($permissions as $permission) {
                    $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form permission in db
                    
                    $role->givePermissionTo($p);  
                }
                
                $data['message'] = Config::get('app_messages.SuccessUpdatedRole');
                $statusCode = 200;
            } else {
                $data['message'] = Config::get('app_messages.SomethingWentWrong');
                $statusCode = 400;
            }
        }
        return Response::json(['data' => $data], $statusCode);
    }
}
