<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends BaseModel
{
    use HasFactory;
    public $table = 'invoices';

    public const DRAFT = 0;

    public const PENDING = 1;

    public const PARTIAL_PAYMENT = 2;

    public const PAID = 3;

    public const VOID = 4;

    const ID_START = 1000;

    const PREFIX_START = 'INV-';
    const FLAT_DISCOUNT = 1;
    const PERCENTAGE_DISCOUNT = 2;
    public $fillable = [
        'invoice_package_type_id',
        'due_date',
        'fully_paid_at',
        'general_description',
        'detailed_description',
        'email_to_parent',
        'email_to_student',
        'amount_remaining',
        'paid_status',
        'paid_by_modal',
        'paid_by_id',
        'invoiceable_type',
        'invoiceable_id',
        'auth_guard',
        'added_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'invoice_package_type_id' => 'integer',
        'general_description' => 'string',
        'detailed_description' => 'string',
        'email_to_parent' => 'boolean',
        'email_to_student' => 'boolean',
        'amount_paid' => 'float',
        'amount_remaining' => 'float',
        'paid_status' => 'boolean',
        'paid_by_modal' => 'string',
        'paid_by_id' => 'integer',
        'invoiceable_type' => 'string',
        'invoiceable_id' => 'integer',
    ];

    public static array $rules = [
        'client_id' => 'required',
        'due_date' => ['required', 'date', 'after_or_equal:today'],
        'item_id' => ['required','array','min:1'],

    ];

    /**
     *------------------------------------------------------------------
     * Accessor
     *------------------------------------------------------------------
     */
    public function getInvoiceCodeAttribute(): string
    {
        return getInvoiceCodeFromId($this->id);
    }

    /**
     *------------------------------------------------------------------
     * Relations
     *------------------------------------------------------------------
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(LineItem::class, 'invoice_line_item')
            ->withPivot(['tax_ids', 'price', 'qty', 'tax_amount', 'final_amount', 'status', 'auth_guard', 'added_by']);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
