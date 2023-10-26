<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    public $table = 'taxes';

    public $fillable = [
        'name',
        'value'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'value' => 'decimal:2'
    ];

    public static array $rules = [
        
    ];

    
}
