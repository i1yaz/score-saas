<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class Tutor extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions,Notifiable;

    protected string $guard = 'tutors';

    public $table = 'tutors';

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
        'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
        'picture' => ['sometimes', 'mimes:jpg,bmp,png,jpeg,JPG,BMP,PNG,JPEG', 'max:2048'],
        'resume' => ['sometimes', 'mimes:doc,docx,docm,pdf', 'max:2048'],
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
}
