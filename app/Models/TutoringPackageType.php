<?php

namespace App\Models;


class TutoringPackageType extends BaseModel
{
    public $table = 'tutoring_package_types';

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
