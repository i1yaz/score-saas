<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @method static Builder|TutoringLocation active()
 * @method static \Database\Factories\TutoringLocationFactory factory($count = null, $state = [])
 * @method static Builder|TutoringLocation inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|TutoringLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TutoringLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TutoringLocation query()
 * @mixin \Eloquent
 */
class TutoringLocation extends BaseModel
{
    use HasFactory;

    public $table = 'tutoring_locations';

    public $fillable = [
        'name',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public static array $rules = [
        'name' => 'required|string|max:255',
    ];
}
