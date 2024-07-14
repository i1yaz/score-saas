<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property-read \App\Models\ListData|null $types
 * @method static Builder|MockTestCode active()
 * @method static \Database\Factories\MockTestCodeFactory factory($count = null, $state = [])
 * @method static Builder|MockTestCode inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestCode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestCode withoutTrashed()
 * @mixin \Eloquent
 */
class MockTestCode extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'test_type',
        'auth_guard',
        'added_by',
    ];
    const LIST_DATA_LIST_ID = 2;

    public function types(): BelongsTo
    {
        return $this->belongsTo(ListData::class, 'test_type ', 'id');
    }

}
