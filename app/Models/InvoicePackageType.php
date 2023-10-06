<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePackageType extends Model
{
    public $table = 'invoice_package_types';

    public $fillable = [
        'name',
        'status',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public static array $rules = [

    ];
}
