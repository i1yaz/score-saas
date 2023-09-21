<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends BaseModel
{
    public $table = 'subjects';

    public $fillable = [
        'name',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public static array $rules = [

    ];
}
