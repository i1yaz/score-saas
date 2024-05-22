<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TutoringPackageType extends BaseModel
{
    use HasFactory;

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
        'name' => ['required', 'string', 'max:255'],
        'hours' => ['sometimes', 'numeric', 'gt:0'],
    ];
}
