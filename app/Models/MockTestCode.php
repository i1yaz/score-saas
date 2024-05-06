<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MockTestCode extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'test_type'
    ];


}
