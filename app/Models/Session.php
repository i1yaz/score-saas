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
        'home_work_completed',
        'practice_test_for_homework',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
