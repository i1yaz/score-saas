<?php

namespace App\Models;


class PackageType extends BaseModel
{
    public $table = 'package_types';

    public $fillable = [
        'name',
        'hours',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'hours' => 'integer',
    ];

    public static array $rules = [
        'hours' => ['sometimes', 'numeric', 'gt:0'],
    ];
}
