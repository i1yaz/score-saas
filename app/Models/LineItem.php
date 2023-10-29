<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LineItem extends Model
{
    public $table = 'line_items';

    public $fillable = [
        'name',
        'price',
        'auth_guard',
        'added_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'price' => 'string'
    ];

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
    /**
     *------------------------------------------------------------------
     * Relations
     *------------------------------------------------------------------
     */
    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class);
    }
}
