<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Database\Factories\InvoicePackageTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePackageType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePackageType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePackageType query()
 * @mixin \Eloquent
 */
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

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public static array $rules = [

    ];
}
