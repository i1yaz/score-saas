<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $name e.g. hero-header
 * @property string|null $group optional
 * @property string|null $directory
 * @property string|null $filename
 * @property string|null $data_1
 * @property string|null $data_2
 * @property string|null $data_3 optional
 * @property string|null $data_4 optional
 * @property string|null $data_5 optional
 * @property string|null $data_6 optional
 * @property string|null $data_7
 * @property string|null $data_8
 * @property string|null $data_9
 * @property string|null $data_10
 * @property string|null $data_11
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Frontend active()
 * @method static Builder|Frontend inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend query()
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereData9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereDirectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frontend whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Frontend extends BaseModel
{
    protected $table = 'frontend';
}
