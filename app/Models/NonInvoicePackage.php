<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Database\Factories\NonInvoicePackageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|NonInvoicePackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NonInvoicePackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NonInvoicePackage query()
 * @mixin \Eloquent
 */
class NonInvoicePackage extends Model
{
    use HasFactory;

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
