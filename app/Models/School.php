<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
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
