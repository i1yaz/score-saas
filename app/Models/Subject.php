<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MonthlyInvoicePackage> $monthlyInvoicePackages
 * @property-read int|null $monthly_invoice_packages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentTutoringPackage> $tutoringPackages
 * @property-read int|null $tutoring_packages_count
 * @method static Builder|Subject active()
 * @method static Builder|Subject inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Subject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subject query()
 * @mixin \Eloquent
 */
class Subject extends BaseModel
{
    public $table = 'subjects';

    public $fillable = [
        'name',
        'added_by',
        'auth_guard',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public static array $rules = [
        'name' => ['required', 'string', 'max:255'],
    ];

    /**
     *------------------------------------------------------------------
     * Relationships
     *------------------------------------------------------------------
     */
    public function tutoringPackages(): BelongsToMany
    {
        return $this->belongsToMany(StudentTutoringPackage::class);
    }

    public function monthlyInvoicePackages(): BelongsToMany
    {
        return $this->belongsToMany(MonthlyInvoicePackage::class);
    }
}
