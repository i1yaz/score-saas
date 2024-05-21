<?php

namespace App\Models;

use Laratrust\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'resource'
    ];
}
