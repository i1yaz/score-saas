<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;

    public $table = 'students';

    protected string $guard = 'student';

    public $fillable = [
        'school_id',
        'email',
        'password',
        'first_name',
        'last_name',
        'email_known',
        'testing_accommodation',
        'testing_accommodation_nature',
        'official_baseline_act_score',
        'official_baseline_sat_score',
        'test_anxiety_challenge',
        'parent_id',
        'added_by',
        'added_at',
        'auth_guard',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'school_id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'email_known' => 'boolean',
        'testing_accommodation' => 'boolean',
        'testing_accommodation_nature' => 'string',
        'official_baseline_act_score' => 'string',
        'official_baseline_sat_score' => 'string',
        'test_anxiety_challenge' => 'boolean',
        'parent_id' => 'integer',
        'added_by' => 'integer',
        'added_at' => 'timestamp',
        'status' => 'boolean',
    ];

    public static array $rules = [
        'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(ParentUser::class, 'parent_id', 'id');
    }

    /**
     *------------------------------------------------------------------
     * Scopes
     *------------------------------------------------------------------
     */
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {

    }
}
