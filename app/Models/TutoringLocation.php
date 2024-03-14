<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TutoringLocation extends BaseModel
{
    use HasFactory;

    public $table = 'tutoring_locations';

    public $fillable = [
        'name',
        'added_by',
        'auth_guard',
    ];

    public static array $rules = [
        'name' => 'required|string|max:255',
    ];
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
        ];
    }
}
