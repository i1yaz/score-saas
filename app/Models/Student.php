<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;

    public $table = 'students';

    protected string $guard = 'student';

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            Cache::forget('students_count');
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
        'status' => 'boolean',
    ];

    public static array $rules = [
        'first_name' => 'required|string|min:2|max:255',
        'last_name' => 'required|string|min:2|max:255',
        'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
        'school_id' => ['required'],
        'parent_id' => ['sometimes'],
    ];

    public static $messages = [
        'school_id' => 'The school field is required.',
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

    public function tutoringPackages(): HasMany
    {
        return $this->hasMany(StudentTutoringPackage::class, 'student_id', 'id');
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
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {

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
