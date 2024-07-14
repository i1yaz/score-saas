<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ListData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListData query()
 * @mixin \Eloquent
 */
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
