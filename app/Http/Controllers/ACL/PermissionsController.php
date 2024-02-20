<?php

namespace App\Http\Controllers\ACL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

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
            'resource' => 'nullable|string',
        ]);

        $this->permissionModel::create($data);
        Flash::success('Permission created successfully');
        return redirect(route('acl.permissions.index'));
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
            'resource' => 'nullable|string',
        ]);

        $permission->update($data);
        Flash::success('Permission updated successfully');
        return redirect(route('acl.permissions.index'));
    }
}
