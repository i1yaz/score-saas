<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

/**
 * 
 *
 * @property-read string $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MonthlyInvoicePackage> $monthlyInvoicePackages
 * @property-read int|null $monthly_invoice_packages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentTutoringPackage> $studentTutoringPackages
 * @property-read int|null $student_tutoring_packages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|Tutor active()
 * @method static \Database\Factories\TutorFactory factory($count = null, $state = [])
 * @method static Builder|Tutor inActive()
 * @method static Builder|Tutor newModelQuery()
 * @method static Builder|Tutor newQuery()
 * @method static Builder|Tutor orWhereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null)
 * @method static Builder|Tutor orWhereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null)
 * @method static Builder|Tutor query()
 * @method static Builder|Tutor whereDoesntHavePermissions()
 * @method static Builder|Tutor whereDoesntHaveRoles()
 * @method static Builder|Tutor whereHasPermission(\BackedEnum|array|string $permission = '', ?mixed $team = null, string $boolean = 'and')
 * @method static Builder|Tutor whereHasRole(\BackedEnum|array|string $role = '', ?mixed $team = null, string $boolean = 'and')
 * @mixin \Eloquent
 */
class Tutor extends Authenticatable implements LaratrustUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;
    protected string $guard = 'tutors';

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            Cache::forget('tutor_count');
        });
    }

    public $table = 'tutors';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $fillable = [
        'first_name',
        'last_name',
        'email',
        'secondary_email',
        'phone',
        'password',
        'secondary_phone',
        'picture',
        'resume',
        'start_date',
        'added_by',
        'auth_guard',
        'status',
        'hourly_rate',
    ];

    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'secondary_email' => 'string',
        'phone' => 'string',
        'secondary_phone' => 'string',
        'picture' => 'string',
        'resume' => 'string',
        'start_date' => 'date',
        'added_by' => 'integer',
        'status' => 'boolean',
    ];

    public static array $rules = [
        'first_name' => ['required', 'string', 'min:2', 'max:255'],
        'last_name' => ['required', 'string', 'min:2', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:tutors'],
        'picture' => ['sometimes', 'mimes:jpg,bmp,png,jpeg,JPG,BMP,PNG,JPEG', 'max:2048'],
        'resume' => ['sometimes', 'mimes:doc,docx,docm,pdf', 'max:2048'],
        'hourly_rate' => ['required', 'numeric', 'gte:0'],
    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function studentTutoringPackages(): BelongsToMany
    {
        return $this->belongsToMany(StudentTutoringPackage::class);
    }

    public function monthlyInvoicePackages(): BelongsToMany
    {
        return $this->belongsToMany(MonthlyInvoicePackage::class);
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
     * Accessors
     *------------------------------------------------------------------
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
