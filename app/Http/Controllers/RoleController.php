<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAbleTo('role manage')) {
            $roles = Role::where('created_by', creatorId())->with('permissions')->get();
            return view('admin.roles.index', compact('roles'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('role create')) {
            $user = Auth::user();
            $permissions = new Collection();
            foreach ($user->roles as $role) {
                $permissions = $permissions->merge($role->permissions);
            }
            $modules = array_merge(['General'],getshowModuleList()); 
            return view('admin.roles.create', compact('permissions', 'modules'));
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('role create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100|unique:roles,name,NULL,id,created_by,' . \Auth::user()->id,
                    'permissions' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $role = new Role();
            $role->name = $request->name;
            $role->created_by = creatorId();
            $role->save();
            $permissions = $request->permissions;
            foreach ($permissions as $key => $permission) {
                $checkPermission  = Permission::where('id', $permission)->first();
                $role->givePermission($checkPermission);
            }
            return redirect()->route('admin.roles')->with('success', 'Role Created Successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function edit($roleId)
    {
        if (Auth::user()->isAbleTo('role edit')) {
            $role = Role::find($roleId);
            if ($role) {
                $permissions = Permission::all()->pluck('name', 'id');
                $modules = array_merge(['General'],getshowModuleList());
                return view('admin.roles.edit', compact('permissions', 'modules', 'role'));
            } else {
                return response()->json(['error' => 'Role Not Found.'], 401);
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function update(Request $request, $roleId)
    {
        if (Auth::user()->isAbleTo('role edit')) {
            $role = Role::find($roleId);
            if ($role) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:100|unique:roles,name,' . $role['id'] . ',id,created_by,' . Auth::user()->id,
                        'permissions' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $role->name = $request->name;
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

                return redirect()->back()->with('success', 'Role Updated Successfully.');
            } else {
                return redirect()->back()->with('error', 'Role Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function destroy(Request $request, $roleId)
    {
        if (Auth::user()->isAbleTo('role delete')) {
            $role = Role::find($roleId);
            if ($role) {
                if ($role->users->count() > 0) {
                    return redirect()->back()->with('error', 'You Can Not Delete This Role. This Role Is Already Assigned To The Users.');
                }
                $role->delete();
                return redirect()->back()->with('success', 'Role Deleted Successfully.');
            } else {
                return redirect()->back()->with('error', 'Role Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
