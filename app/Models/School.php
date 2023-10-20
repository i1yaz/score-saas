<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends BaseModel
{
    use HasFactory;

    public $table = 'schools';

    public $fillable = [
        'name',
        'address',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'address' => 'string',
    ];

    public static array $rules = [

    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function studentsEnrolled(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
