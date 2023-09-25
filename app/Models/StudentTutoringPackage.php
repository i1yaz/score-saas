<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public static array $rules = [
        'student_id' => 'required',
        'tutoring_package_type_id' => 'required',
        'tutor_ids' => ['required', 'array', 'min:1'],
        'subject_ids' => ['required', 'array', 'min:1'],
        'tutoring_location_id' => 'required',
        'internal_notes' => 'string',
        'hours' => ['required', 'numeric', 'min:1'],
        'hourly_rate' => ['required', 'numeric', 'min:1'],
        'discount_type' => 'required',
        'start_date' => 'required',
        'tutor_hourly_rate' => ['sometimes', 'numeric', 'min:1'],
    ];

    public static array $rulesEdit = [
        'subject_ids' => ['required', 'array', 'min:1'],
        'internal_notes' => 'string',
        'hours' => ['required', 'numeric', 'min:1'],
        'hourly_rate' => ['required', 'numeric', 'min:1'],
        'discount_type' => 'required',
        'start_date' => 'required',
        'tutor_hourly_rate' => ['sometimes', 'numeric', 'min:1'],
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
