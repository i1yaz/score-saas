<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Payment extends Model
{

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            Cache::forget('earning_sum');
        });
    }

    const STRIPE = '1';

    const PAYPAL = '2';

    public $table = 'payments';

    public const DRAFT = 0;

    public const PENDING = 1;

    public const PARTIAL_PAYMENT = 2;

    public const PAID = 3;

    public const VOID = 4;

    public const REFUND = 5;

    public const FAILED = 6;

    public const CANCELLED = 7;

    public $fillable = [
        'transaction_id',
        'invoice_id',
        'amount',
        'payment_gateway_id',
        'payment_intent',
        'status',
        'meta',
        'paid_by_id',
        'paid_by_modal',
        'is_subscription_payment',
    ];

    protected $casts = [
        'id' => 'integer',
        'invoice_id' => 'integer',
        'amount' => 'float',
        'payment_gateway' => 'integer',
        'transaction_id' => 'integer',
        'is_subscription_payment' => 'boolean',
    ];

    public static array $rules = [

    ];
}
