<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @method static Builder|TutoringPackageType active()
 * @method static \Database\Factories\TutoringPackageTypeFactory factory($count = null, $state = [])
 * @method static Builder|TutoringPackageType inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|TutoringPackageType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TutoringPackageType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TutoringPackageType query()
 * @mixin \Eloquent
 */
class TutoringPackageType extends BaseModel
{
    use HasFactory;

    public $table = 'tutoring_package_types';

    public $fillable = [
        'name',
        'hours',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'hours' => 'integer',
    ];

    public static array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'hours' => ['sometimes', 'numeric', 'gt:0'],
    ];
}
