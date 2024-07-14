<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static Builder|Tax active()
 * @method static Builder|Tax inActive()
 * @method static Builder|Tax newModelQuery()
 * @method static Builder|Tax newQuery()
 * @method static Builder|Tax query()
 * @mixin \Eloquent
 */
class Tax extends Model
{
    public $table = 'taxes';

    public $fillable = [
        'name',
        'value',
        'auth_guard',
        'added_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'value' => 'decimal:2',
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
}
