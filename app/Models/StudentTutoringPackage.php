<?php

namespace App\Models;

use App\Rules\StudentTutoringPackageHourlyRateRule;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentTutoringPackage extends BaseModel
{
    const FLAT_DISCOUNT = 1;

    const PERCENTAGE_DISCOUNT = 2;

    const CODE_START = 3000;

    const PREFIX_START = 'T';

    /**
     * @var \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public $table = 'student_tutoring_packages';

    public $fillable = [
        'student_id',
        'tutoring_package_type_id',
        'tutor_id',
        'notes',
        'internal_notes',
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
        'tutoring_package_type_id' => 'integer',
        'tutor_id' => 'integer',
        'notes' => 'string',
        'internal_notes' => 'string',
        'hours' => 'integer',
        'hourly_rate' => 'integer',
        'tutoring_location_id' => 'integer',
        'discount' => 'integer',
        'discount_type' => 'integer',
        'start_date' => 'date',
        'tutor_hourly_rate' => 'integer',
    ];


    public static mixed $messages = [
        'tutor_ids.required' => 'Please select at least one tutor',
        'subject_ids.required' => 'Please select at least one subject',
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

    public function tutors(): BelongsToMany
    {
        return $this->belongsToMany(Tutor::class);
    }
    public function student(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class,'student_tutoring_package_id','id');
    }

    /**
     *------------------------------------------------------------------
     * Accessor
     *------------------------------------------------------------------
     */
    public function getStudentTutoringPackageCodeAttribute(): string
    {
        return getStudentTutoringPackageCodeFromId($this->id);
    }
}
