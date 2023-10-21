<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyInvoicePackage extends Model
{
    use HasFactory;
    public $table = 'monthly_invoice_packages';
    const CODE_START = 4000;
    const PREFIX_START = 'M';
    const FLAT_DISCOUNT = 1;

    const PERCENTAGE_DISCOUNT = 2;
    public $fillable = [
        'student_id',
        'notes',
        'internal_notes',
        'start_date',
        'hourly_rate',
        'tutor_hourly_rate',
        'tutoring_location_id',
        'discount',
        'discount_type',
        'is_free',
        'is_score_guaranteed',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'notes' => 'string',
        'internal_notes' => 'string',
        'start_date' => 'date',
        'tutoring_location_id' => 'integer'
    ];

    public static array $rules = [
        'student_id' => 'required|integer',
        'notes' => 'nullable|string',
        'internal_notes' => 'nullable|string',
        'start_date' => 'nullable|date',
        'hourly_rate' => 'required|integer',
        'tutor_hourly_rate' => 'required|integer',
        'tutoring_location_id' => 'required|integer'
    ];
    public static array $messages = [
        'student_id.required' => 'Student is required',
        'student_id.integer' => 'Student must be an integer',
        'notes.string' => 'Notes must be a string',
        'internal_notes.string' => 'Internal notes must be a string',
        'start_date.date' => 'Start date must be a date',
        'hourly_rate.required' => 'Hourly rate is required',
        'hourly_rate.integer' => 'Hourly rate must be an integer',
        'tutor_hourly_rate.required' => 'Tutor hourly rate is required',
        'tutor_hourly_rate.integer' => 'Tutor hourly rate must be an integer',
        'tutoring_location_id.required' => 'Tutoring location is required',
        'tutoring_location_id.integer' => 'Tutoring location must be an integer',
        'subject_ids.required' => 'Subject is required',
        'subject_ids.exists' => 'Subject does not exist',
        'tutor_ids.required' => 'Tutor is required',
        'tutor_ids.exists' => 'Tutor does not exist',

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
        return $this->hasMany(Session::class, 'monthly_invoice_package_id', 'id');
    }
}
