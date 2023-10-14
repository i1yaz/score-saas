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

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public static array $rules = [

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
}
