<?php

namespace App\Models;

use App\Models\Student as Children;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Children> $family
 * @property-read int|null $family_count
 * @property-read string $family_code
 * @property-read string $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|ParentUser active()
 * @method static \Database\Factories\ParentUserFactory factory($count = null, $state = [])
 * @method static Builder|ParentUser inActive()
 * @method static Builder|ParentUser newModelQuery()
 * @method static Builder|ParentUser newQuery()
 * @method static Builder|ParentUser orWhereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null)
 * @method static Builder|ParentUser orWhereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null)
 * @method static Builder|ParentUser query()
 * @method static Builder|ParentUser whereDoesntHavePermissions()
 * @method static Builder|ParentUser whereDoesntHaveRoles()
 * @method static Builder|ParentUser whereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null, string $boolean = 'and')
 * @method static Builder|ParentUser whereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null, string $boolean = 'and')
 * @mixin \Eloquent
 */
class ParentUser extends Authenticatable implements LaratrustUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            Cache::forget('parent_count');
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        'first_name' => 'required|string|min:2|max:255',
        'last_name' => 'required|string|min:2|max:255',
        'email' => ['required', 'string', 'email', 'max:255', 'unique:parents'],
    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function family(): HasMany
    {
        return $this->hasMany(Children::class, 'parent_id', 'id');
    }

    /**
     *------------------------------------------------------------------
     * Scopes
     *------------------------------------------------------------------
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', true);
    }

    public function scopeInActive(Builder $query): void
    {
        $query->where('status', false);
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

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name}  {$this->last_name}";
    }
}
