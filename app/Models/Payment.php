<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payments';

    public $fillable = [
        'invoice_id',
        'amount',
        'payment_gateway',
        'transaction_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'invoice_id' => 'integer',
        'amount' => 'float',
        'payment_gateway' => 'integer',
        'transaction_id' => 'integer'
    ];

    public static array $rules = [
        
    ];

    
}
