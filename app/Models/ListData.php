<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListData extends Model
{
    protected $table = 'list_data';
    protected $primaryKey = 'id';
    protected $fillable = [
        'list_id',
        'name',
        'description',
    ];
    public static array $rules = [
        'list_id' => ['required', 'integer'],
        'name' => ['required', 'string'],
        'description' => ['required', 'string'],
    ];
}
