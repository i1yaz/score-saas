<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    public $table = 'taxes';

    public $fillable = [
        'name',
        'value',
        'auth_guard',
        'added_by',
    ];

    const FLAT_DISCOUNT = 1;

    const PERCENTAGE_DISCOUNT = 2;

    public static array $rules = [
        'name' => ['required', 'string'],
        'value' => ['required', 'numeric'],
    ];

    public static array $messages = [
        'name.required' => 'Line item name is required',
        'name.string' => 'Line Item name must be string',
        'value.required' => 'Percentage is required',
        'value.string' => 'Percentage must be numeric',
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
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'value' => 'decimal:2',
        ];
    }
}
