<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonInvoicePackage extends Model
{
    use HasFactory;

    const CODE_START = 5000;
    const PREFIX_START = 'INP';
}
