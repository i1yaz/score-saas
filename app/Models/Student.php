<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class Student extends  Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, Notifiable,HasRolesAndPermissions;

    public $table = 'students';

    protected string $guard = "student";

    public $fillable = [
        'user_id',
        'school_id',
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
        'added_on',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'school_id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'email_known' => 'boolean',
        'testing_accommodation' => 'boolean',
        'testing_accommodation_nature' => 'string',
        'official_baseline_act_score' => 'string',
        'official_baseline_sat_score' => 'string',
        'test_anxiety_challenge' => 'boolean',
        'parent_id' =>'integer',
        'added_by' => 'integer',
        'added_on' => 'timestamp',
        'status' => 'boolean',
    ];

    public static array $rules = [

    ];


    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */

    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(ParentUser::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
