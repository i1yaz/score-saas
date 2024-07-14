<?php

namespace App\Models;

use Laratrust\Models\Permission as PermissionModel;

/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @mixin \Eloquent
 */
class Permission extends PermissionModel
{

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'resource'
    ];
}
