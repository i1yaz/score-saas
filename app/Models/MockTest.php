<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockTest extends Model
{
    public $table = 'mock_tests';

    public $fillable = [
        'date',
        'location_id',
        'proctor_id',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'location_id' => 'integer',
        'proctor_id' => 'integer',
        'start_time' => 'string',
        'end_time' => 'string'
    ];

    public static array $rules = [
        
    ];

    
}
