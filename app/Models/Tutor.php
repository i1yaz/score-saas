<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class Tutor extends Authenticatable implements LaratrustUser
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
        'hourly_rate' => ['required', 'numeric', 'gt:0'],
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
