<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property-read \App\Models\Invoice|null $invoice
 * @method static Builder|Installment active()
 * @method static Builder|Installment inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Installment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Installment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Installment query()
 * @mixin \Eloquent
 */
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
