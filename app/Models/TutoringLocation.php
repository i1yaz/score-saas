<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TutoringLocation extends BaseModel
{
    use HasFactory;
    protected $connection = 'tenant';

    public $table = 'tutoring_locations';

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
        'name' => 'required|string|max:255',
    ];
}
