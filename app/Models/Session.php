<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Session extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            Cache::forget('session_count');
        });
    }

    const VOID_COMPLETION_CODE = 5;

    const CANCELED_COMPLETION_CODE = 4;

    const PARTIAL_COMPLETION_CODE = 2;

    const UN_BILLED = false;

    const BILLED = true;

    protected $fillable = [
        'student_tutoring_package_id',
        'monthly_invoice_package_id',
        'scheduled_date',
        'start_time',
        'end_time',
        'location_id',
        'pre_session_notes',
        'session_completion_code',
        'how_was_session',
        'student_parent_session_notes',
        'homework',
        'internal_notes',
        'flag_session',
        'tutor_id',
        'home_work_completed',
        'tutoring_location_id',
        'auth_guard',
        'added_by',
        'attended_start_time',
        'attended_end_time',
        'charge_missed_time',
    ];

    public static $messages = [
        'tutoring_location_id' => 'Please select a tutoring location',
        'tutor_id' => 'Please select a tutor',
        'student_tutoring_package_id' => 'Please select a tutoring package',
    ];

    protected $casts = [

    ];

    const CODE_START = 5000;

    const LIST_DATA_LIST_ID = 1;

    const PREFIX = 'SRN-';
}
