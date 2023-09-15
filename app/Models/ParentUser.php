<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Student as Children;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class ParentUser extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, Notifiable,HasRolesAndPermissions;

    public $table = 'parents';

    protected string $guard = "parent";

    public $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'status',
        'phone',
        'address',
        'address2',
        'phone_alternate',
        'referral_source',
        'added_by',
        'added_on',
        'referral_from_positive_experience_with_tutor'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'status' => 'boolean',
        'phone' => 'string',
        'address' => 'string',
        'address2' => 'string',
        'phone_alternate' => 'string',
        'referral_source' => 'string',
        'added_by' => 'integer',
        'referral_from_positive_experience_with_tutor' => 'boolean'
    ];

    public static array $rules = [

    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function addedBy():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function family(): HasMany
    {
        return $this->hasMany(Children::class);
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
