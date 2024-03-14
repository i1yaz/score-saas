<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends BaseModel
{
    public $table = 'subjects';

    public $fillable = [
        'name',
        'added_by',
        'auth_guard',
    ];

    public static array $rules = [
        'name' => ['required', 'string', 'max:255'],
    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function tutoringPackages(): BelongsToMany
    {
        return $this->belongsToMany(StudentTutoringPackage::class);
    }

    public function monthlyInvoicePackages(): BelongsToMany
    {
        return $this->belongsToMany(MonthlyInvoicePackage::class);
    }
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
        ];
    }
}
