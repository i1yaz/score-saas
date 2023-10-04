<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_tutoring_package_id',
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
    ];

    protected $casts = [

    ];

    const CODE_START = 5000;

    const LIST_DATA_LIST_ID = 1;
}
