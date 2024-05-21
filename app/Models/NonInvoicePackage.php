<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonInvoicePackage extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    const CODE_START = 5000;

    const PREFIX_START = 'INP-';

    protected $fillable = [
        'name',
        'description',
        'final_amount',
        'code',
        'prefix',
        'is_active',
    ];

    protected $casts = [
    ];
    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
}
