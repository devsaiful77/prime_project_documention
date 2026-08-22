<?php

namespace App\Http\Controllers;

use App\Enum\AccessApp;
use Illuminate\Http\Request;
//use Spatie\Permission\Models\Role;
use \App\Permission;
use \App\Role;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Requests\RoleRequest;

use Session;

class RolesController extends Controller
{
    function __construct()
    {
        $this->middleware('role:superadmin');
    }

    public function index()
    {
        $this->modelName = new Role;

        $rolesData = array();

        $rolesDataObj = $this->modelName->where('name', '<>', 'superadmin')->where('module', AccessApp::ServiceComplaint)->get();

        if (!empty($rolesDataObj)) {
            $rolesData = $rolesDataObj->toArray();
        }

        $title = "Roles";
        $title_for_layout = 'Roles';

        return view('Roles/index', compact("title_for_layout", "title", "rolesData"));
    }

    public function create()
    {
        $permissions = [];
        $roleManagement = Permission::where('module', AccessApp::ServiceComplaint)->get();

        foreach ($roleManagement as $key => $value) {
            $permissions[$value['controller_name']][$value['id']] = $value['display_name'];
        }

        $title = "Roles";
        $title_for_layout = 'Create Roles';
        return view('Roles/create', compact("title_for_layout", "title", "permissions"));
    }

    public function store(RoleRequest $request)
    {
        $createRole = '';
        $createRole = $request->permission;

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'guard_name' => 'web',
            'module' => $request->module,
        ]);

        if (!empty($createRole)) {
            /* foreach ($request->permission as $key=>$value) {
                $role->attachPermission($value);
            }*/
            $role->syncPermissions($createRole);
        }

        flash('Role successfully created.', 'success');
        return redirect('/roles');
    }

    public function edit($id)
    {
        $title = "Roles";
        $title_for_layout = 'Edit Role';

        $role = Role::find($id);

        if ($role->name === "superadmin") {
            flash('Editing is not allowed.', 'danger');
            return redirect('/roles');
        } else {
            $permissions = [];
            $roleManagement = Permission::where('module', AccessApp::ServiceComplaint)->get();
            //pr($roleManagement); die;
            $role_permissions = $role->perms()->pluck('id', 'id')->toArray();

            foreach ($roleManagement as $key => $value) {
                $permissions[$value['controller_name']][$value['id']] = $value['display_name'];
            }

            return view('Roles/edit', compact(['title_for_layout', 'title', 'role', 'role_permissions', 'permissions']));
        }
    }

    public function update(RoleRequest $request, $id)
    {
        $updateRole = '';
        $updateRole = $request->permission;

        $role = Role::find($id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->guard_name = 'web';
        $role->save();
        // DB::table('permission_role')->where('role_id',$id)->delete();

        if (!empty($updateRole)) {
            /*foreach ($updateRole as $key=>$value) {
                $role->attachPermission($value);
            }*/
            $role->syncPermissions($updateRole);
        }

        flash('Role successfully updated.', 'success');
        return redirect('/roles');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        DB::table("role_user")->where('role_id', $id)->delete();
        DB::table("permission_role")->where('role_id', $id)->delete();
        flash('Role successfully deleted.', 'success');
        return back();
    }
}
