<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePackageType extends Model
{
    public $table = 'invoice_package_types';

    public $fillable = [
        'name'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    public static array $rules = [
        
    ];

    
}
