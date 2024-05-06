<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MockTest extends Model
{
    public $table = 'mock_tests';

    public $fillable = [
        'date',
        'location_id',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'location_id' => 'integer',
        'start_time' => 'string',
        'end_time' => 'string',

    ];

    public static array $rules = [
        'date' => 'required',
        'location_id' => 'required',
        'proctor_id' => 'required'
    ];

    //Relationships
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)->withPivot('mock_test_code_id', 'notes_to_proctor')->withTimestamps();
    }


}
