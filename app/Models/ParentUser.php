<?php

namespace App\Models;

use App\Models\Student as Children;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class ParentUser extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;

    public $table = 'parents';

    protected string $guard = 'parent';

    const FAMILY_CODE_START = 5000;

    public $fillable = [
        'email',
        'first_name',
        'last_name',
        'password',
        'status',
        'phone',
        'address',
        'address2',
        'phone_alternate',
        'referral_source',
        'added_by',
        'auth_guard',
        'referral_from_positive_experience_with_tutor',
    ];

    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'status' => 'boolean',
        'phone' => 'string',
        'address' => 'string',
        'address2' => 'string',
        'phone_alternate' => 'string',
        'referral_source' => 'string',
        'added_by' => 'integer',
        'referral_from_positive_experience_with_tutor' => 'boolean',
    ];

    public static array $rules = [
        'email' => ['required', 'string', 'email', 'max:255', 'unique:parents'],
    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function family(): HasMany
    {
        return $this->hasMany(Children::class,'parent_id','id');
    }

    /**
     *------------------------------------------------------------------
     * Scopes
     *------------------------------------------------------------------
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status',true);
    }
    public function scopeInActive(Builder $query): void
    {
        $query->where('status',false);
    }

    /**
     *------------------------------------------------------------------
     * Accessor
     *------------------------------------------------------------------
     */
    public function getFamilyCodeAttribute(): string
    {
        return getFamilyCodeFromId($this->id);
    }
}
