<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frontend extends BaseModel
{
    protected $connection = 'landlord';
    protected $table = 'frontend';
}
