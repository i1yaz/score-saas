<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class School extends BaseModel
{
    use HasFactory;

    public $table = 'schools';
    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            Cache::forget('school_count');
        });
    }
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
        'name' => ['required', 'string', 'max:255']
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
