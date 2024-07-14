<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static Builder|MockTestStudent active()
 * @method static Builder|MockTestStudent inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestStudent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestStudent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MockTestStudent query()
 * @mixin \Eloquent
 */
class MockTestStudent extends BaseModel
{
    protected $table = 'mock_test_student';
}
