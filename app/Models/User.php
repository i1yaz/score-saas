<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
 * @property int $id
 * @property string|null $email
 * @property mixed $password
 * @property string|null $remember_token
 * @property string $type admin|staff
 * @property string $first_name
 * @property string $last_name
 * @property string $primary_admin yes | no (only 1 primary admin - created during setup)
 * @property string|null $avatar_directory
 * @property string|null $avatar_filename
 * @property string $status active|suspended|deleted
 * @property string|null $last_seen
 * @property string $theme
 * @property string|null $forgot_password_token random token
 * @property string|null $forgot_password_token_expiry
 * @property string $pref_leftmenu_position collapsed | open
 * @property string $pref_email_notifications yes | no
 * @property string $welcome_email_sent yes|no
 * @property int|null $added_by
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|User active()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User inActive()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User orWhereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null)
 * @method static Builder|User orWhereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null)
 * @method static Builder|User query()
 * @method static Builder|User whereAddedBy($value)
 * @method static Builder|User whereAvatarDirectory($value)
 * @method static Builder|User whereAvatarFilename($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereDoesntHavePermissions()
 * @method static Builder|User whereDoesntHaveRoles()
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereForgotPasswordToken($value)
 * @method static Builder|User whereForgotPasswordTokenExpiry($value)
 * @method static Builder|User whereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null, string $boolean = 'and')
 * @method static Builder|User whereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null, string $boolean = 'and')
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereLastSeen($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePrefEmailNotifications($value)
 * @method static Builder|User wherePrefLeftmenuPosition($value)
 * @method static Builder|User wherePrimaryAdmin($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereTheme($value)
 * @method static Builder|User whereType($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereWelcomeEmailSent($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements LaratrustUser , MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const CAN_ACCESS_ACL = [1];
    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */

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
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name}  {$this->last_name}";
    }
}
