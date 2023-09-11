<?php

namespace App\Http\Controllers\ACL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PermissionsController
{
    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = Config::get('laratrust.models.permission');
    }

    public function index()
    {
        return View::make('acl.permissions.index', [
            'permissions' => $this->permissionModel::simplePaginate(10),
        ]);
    }

    public function create()
    {
        return View::make('acl.permissions.create', [
            'model' => null,
            'permissions' => null,
            'type' => 'permission',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:'.config('laratrust.tables.roles', 'roles').',name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $permission = $this->permissionModel::create($data);

        Session::flash('laratrust-success', 'Permission created successfully');

        return redirect(route('laratrust.permissions.index'));
    }

    public function edit($id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        return View::make('acl.permissions.edit', [
            'model' => $permission,
            'type' => 'permission',
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $permission->update($data);

        Session::flash('laratrust-success', 'Permission updated successfully');

        return redirect(route('laratrust.permissions.index'));
    }
}
