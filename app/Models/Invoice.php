<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $table = 'invoices';
    public const DRAFT = 0;
    public const PENDING = 1;
    public const PARTIAL_PAYMENT = 2;
    public const PAID = 3;
    public const VOID = 4;
    const ID_START = 1000;

    const PREFIX_START = 'INV-';

    public $fillable = [
        'invoice_package_type_id',
        'due_date',
        'fully_paid_at',
        'general_description',
        'detailed_description',
        'email_to_parent',
        'email_to_student',
        'amount_paid',
        'amount_remaining',
        'paid_status',
        'paid_by_modal',
        'paid_by_id',
        'invoiceable_type',
        'invoiceable_id'
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
        'invoiceable_id' => 'integer'
    ];

    public static array $rules = [

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
}
