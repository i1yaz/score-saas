<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    const FLAT_DISCOUNT = 1;

    const PERCENTAGE_DISCOUNT = 2;
    public static array $rules = [

    ];

    /**
     *------------------------------------------------------------------
     * Scopes
     *------------------------------------------------------------------
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', true);
    }

    public function scopeInActive(Builder $query): void
    {
        $query->where('status', false);
    }
}
