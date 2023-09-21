<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentTutoringPackage extends BaseModel
{
    const FLAT_DISCOUNT = 0;
    const PERCENTAGE_DISCOUNT = 1;
    public $table = 'student_tutoring_packages';

    public $fillable = [
        'student_id',
        'package_type_id',
        'tutor_id',
        'notes',
        'internal_noted',
        'hours',
        'hourly_rate',
        'tutoring_location_id',
        'discount',
        'discount_type',
        'start_date',
        'tutor_hourly_rate',
        'added_by',
        'auth_guard',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'package_type_id' => 'integer',
        'tutor_id' => 'integer',
        'notes' => 'string',
        'internal_noted' => 'string',
        'hours' => 'integer',
        'hourly_rate' => 'integer',
        'tutoring_location_id' => 'integer',
        'discount' => 'integer',
        'discount_type' => 'integer',
        'start_date' => 'date',
        'tutor_hourly_rate' => 'integer'
    ];

    public static array $rules = [

    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }
    public function tutors():BelongsToMany
    {
        return $this->belongsToMany(Tutor::class);
    }


}
