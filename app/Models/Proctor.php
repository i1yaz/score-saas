<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

/**
 * 
 *
 * @property mixed $password
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|Proctor active()
 * @method static Builder|Proctor inActive()
 * @method static Builder|Proctor newModelQuery()
 * @method static Builder|Proctor newQuery()
 * @method static Builder|Proctor orWhereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null)
 * @method static Builder|Proctor orWhereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null)
 * @method static Builder|Proctor query()
 * @method static Builder|Proctor whereDoesntHavePermissions()
 * @method static Builder|Proctor whereDoesntHaveRoles()
 * @method static Builder|Proctor whereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null, string $boolean = 'and')
 * @method static Builder|Proctor whereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null, string $boolean = 'and')
 * @mixin \Eloquent
 */
class Proctor extends  Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;
    public $table = 'proctors';

    public $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'status',
        'auth_guard',
        'added_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'status' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static array $rules = [
        'email' => ['required','email','unique:proctors'],
        'first_name' => ['required','string'],
    ];

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


}
