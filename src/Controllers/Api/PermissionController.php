<?php

namespace MarkVilludo\Permission\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use MarkVilludo\Permission\Models\Role;
use MarkVilludo\Permission\Models\Permission;
use Response;
use Validator;
use Config;

class PermissionController extends Controller
{   
    public function __construct(Permission $permission, Role $role) 
    {   
        $this->role = $role;
        $this->permission = $permission;
        // $this->middleware(['auth', 'isAdmin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $permissions = $this->permission->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            foreach ($permissionsArray as $key => $module) {
                if ($module->module == $moduleName) {
                    $moduleFunction[] = ['id' => $module->id,'module' => $module->module,'name' => $module->name, 'checked' => false];
                }
            }
            $permissions[] = ['module' => $value['module'],'id' => $value['id'],  'checked' => false, 'module_functions' => $moduleFunction];
        }

        $data['permissions'] = $permissions;
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
        $rules = ['name' => 'required|unique:permissions,name'];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $data['message'] = [$validator->errors()];
            $statusCode = 422;
        } else {

            $name = $request->name;
            $permission = new Permission();
            $permission->name = $name;

            $roles = $request->roles;
            
            if ($permission->save()) {

                if (!empty($request->roles)) {
                    foreach ($roles as $role) {
                        $r = $this->role->where('id', '=', $role)->firstOrFail(); //Match input role to db record

                        $permission = $this->permission->where('name', '=', $name)->first();   
                        $r->givePermissionTo($permission);
                    }
                }
                $data['message'] = Config::get('app_messages.SuccessCreatePermission');
                $statusCode = 200;
            } else {
                $data['message'] = Config::get('app_messages.SomethingWentWrong');
                $statusCode = 400;
            }
        }
        return Response::json(['data' => $data], $statusCode);
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
  
        $rules = ['name' => 'required|unique:permissions,name,'.$id];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $data['message'] = [$validator->errors()];
            $statusCode = 422;
        } else {
            $permission = Permission::findOrFail($id);
            $permission->name = $request->name;
            $permission->save();

            $data['message'] = Config::get('app_messages.SuccessUpdatePermission');
            $statusCode = 200;
        }
        return Response::json(['data' => $data], $statusCode);
    }
}
