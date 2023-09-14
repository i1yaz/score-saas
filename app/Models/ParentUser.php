<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Student as Children;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentUser extends Model
{

    public $table = 'parents';

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
        static::addGlobalScope('filterByRoles', function (Builder $query) {
            if (\Auth::user()->hasRole(['parent'])){

                $query->where('user_id',\Auth::id());
            }elseif (\Auth::user()->hasRole(['student'])){

//                $query->whereHas('family',function ($q){
//                    $q->where('status',true);
//                });
            }elseif (\Auth::user()->hasRole(['tutor'])){

            }elseif (\Auth::user()->hasRole(['proctor'])){

            }elseif (\Auth::user()->hasRole(['client'])){

            } elseif(\Auth::user()->hasRole(['super-admin', 'admin'])){

            }

        });
    }


}
