<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyInvoicePackage extends Model
{
    public $table = 'monthly_invoice_packages';

    public $fillable = [
        'student_id',
        'notes',
        'internal_notes',
        'start_date',
        'hourly_rate',
        'tutor_hourly_rate',
        'tutoring_location_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'notes' => 'string',
        'internal_notes' => 'string',
        'start_date' => 'date',
        'hourly_rate' => 'integer',
        'tutor_hourly_rate' => 'integer',
        'tutoring_location_id' => 'integer'
    ];

    public static array $rules = [

    ];


}
