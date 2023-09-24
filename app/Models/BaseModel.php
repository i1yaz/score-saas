<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
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
