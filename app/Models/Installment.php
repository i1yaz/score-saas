<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends BaseModel
{

    protected $fillable = [
       'invoice_id',
       'amount',
       'due_date',
       'is_paid',
       'auth_guard',
       'added_by'
   ];
   protected $casts = [
       'id' => 'integer',
       'invoice_id' => 'integer',
       'amount' => 'float',
       'is_paid' => 'bool',
       'added_by' => 'integer'
       ];

    /**
     *------------------------------------------------------------------
     * Relations
     *------------------------------------------------------------------
     */

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }
}
