<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
