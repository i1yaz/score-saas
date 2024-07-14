<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyInvoiceSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyInvoiceSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyInvoiceSubscription query()
 * @mixin \Eloquent
 */
class MonthlyInvoiceSubscription extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;//only for migration

    const STATUS_INACTIVE = 1;

    const MONTHLY_INVOICE_FREQUENCY = 'monthly';

    const ACTIVE = true;

    const INACTIVE = false;

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
