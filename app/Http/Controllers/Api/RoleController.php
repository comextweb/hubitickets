<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $roles = Role::where('created_by', creatorId())->with('permissions')->get();

        $data = [
            'role' => $roles
        ];

        return $this->success($data);
    }

    //new api
    public function store(Request $request)
    {    
        $validator = Validator::make(
            $request->all(),
            [
                'name'        => 'required|max:100|unique:roles,name,NULL,id,created_by,' . Auth::user()->id,
                'permissions' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $post = [
            'name'       => $request->name,
            'created_by' => creatorId(),
        ];
        
        $role = Role::create($post);
        $permissions = $request->permissions;
        foreach ($permissions as $key => $permission) {
            $checkPermission  = Permission::where('id', $permission)->first();
            $role->givePermission($checkPermission);
        }
        $data = [
            'role'=> $role,
        ]; 
        return $this->success($data);
    }

    public function update(Request $request)
    {
        $role = Role::find($request->id);
        if($role)
        {
            
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|max:100|unique:roles,name,' . $role['id'] . ',id,created_by,' . Auth::user()->id,
                    'permissions' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                $data     = [];
                return $this->error($data , $messages->first() , 200);
            }

            $role->name          = $request->name;
            $role->save();

            $getAllPermissions = Permission::all();
            foreach ($getAllPermissions as $key => $per) {
                $role->removePermission($per);
            }

            $permissions = $request->permissions;
            foreach ($permissions as  $permission) {
                $checkPermission = Permission::where('id', $permission)->first();
                $role->givePermission($checkPermission);
            }

            $data    = ['role'=> $role]; 
            return $this->success($data);    
        }
        else{
            $message = "Role does not exist";
            return $this->error([] , $message , 200);    
        }                
    }

    // new api
    public function destroy(Request $request)
    {      
        $role = Role::find($request->id);
        $data = ['role'=>[]]; 

        if($role)
        {
            if ($role->users->count() > 0) {
                $message = "You Can Not Delete This Role. This Role Is Already Assigned To The Users";
                return $this->error($data , $message , 200);
            }

            $role->delete();
            return $this->success($data);      
        }
        else
        {
            $message = "Role does not exist";
            return $this->error($data , $message , 200);      
        }
    }
}