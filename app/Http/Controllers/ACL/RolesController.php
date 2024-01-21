<?php

namespace App\Http\Controllers\ACL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Laratrust\Helper;

class RolesController
{
    protected $rolesModel;

    protected $permissionModel;

    public function __construct()
    {
        $this->rolesModel = Config::get('laratrust.models.role');
        $this->permissionModel = Config::get('laratrust.models.permission');
    }

    public function index()
    {
        return View::make('acl.roles.index', [
            'roles' => $this->rolesModel::withCount('permissions')
                ->simplePaginate(10),
        ]);
    }

    public function create()
    {
        $permissions = $this->permissionModel::all(['id', 'display_name', 'resource']);

        return View::make('acl.roles.create', [
            'model' => null,
            'permissions' => $permissions->groupBy('resource'),
            'type' => 'role',
        ]);
    }

    public function show(Request $request, $id)
    {
        $role = $this->rolesModel::query()
            ->with('permissions:id,name,display_name')
            ->findOrFail($id);

        return View::make('acl.roles.index', ['role' => $role]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:'.config('laratrust.tables.roles', 'roles').',name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $role = $this->rolesModel::create($data);
        $role->syncPermissions($request->get('permissions') ?? []);

        Session::flash('laratrust-success', 'Role created successfully');

        return redirect(route('acl.roles.index'));
    }

    public function edit($id)
    {
        $role = $this->rolesModel::query()
            ->with('permissions:id')
            ->findOrFail($id);

        if (! Helper::roleIsEditable($role)) {
            Session::flash('laratrust-error', 'The role is not editable');

            return redirect()->back();
        }

        $permissions = $this->permissionModel::all(['id', 'name', 'display_name', 'resource'])
            ->map(function ($permission) use ($role) {
                $permission->assigned = $role->permissions
                    ->pluck('id')
                    ->contains($permission->id);

                return $permission;
            });

        return View::make('acl.roles.edit', [
            'model' => $role,
            'permissions' => $permissions->groupBy('resource'),
            'type' => 'role',
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = $this->rolesModel::findOrFail($id);

        if (! Helper::roleIsEditable($role)) {
            Session::flash('laratrust-error', 'The role is not editable');

            return redirect()->back();
        }

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $role->update($data);
        $role->syncPermissions($request->get('permissions') ?? []);

        Session::flash('laratrust-success', 'Role updated successfully');
        Flash::success('Role updated successfully');
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'redirectTo' => route('acl.roles.index'),
            ]);
        }

        return redirect(route('acl.roles.index'));
    }

    public function destroy($id)
    {
        $usersAssignedToRole = DB::table(Config::get('laratrust.tables.role_user'))
            ->where(Config::get('laratrust.foreign_keys.role'), $id)
            ->count();
        $role = $this->rolesModel::findOrFail($id);

        if (! Helper::roleIsDeletable($role)) {
            Session::flash('laratrust-error', 'The role is not deletable');

            return redirect()->back();
        }

        if ($usersAssignedToRole > 0) {
            Session::flash('laratrust-warning', 'Role is added to one or more users. It can not be deleted');
        } else {
            Session::flash('laratrust-success', 'Role deleted successfully');
            $this->rolesModel::destroy($id);
        }

        return redirect(route('acl.roles.index'));
    }
}
