<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePackageType extends Model
{
    use HasFactory;

    public $table = 'invoice_package_types';

    public $fillable = [
        'name',
        'status',
        'added_by',
        'auth_guard',
    ];

    public static array $rules = [

    ];
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
        ];
    }
}
