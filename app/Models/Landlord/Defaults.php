<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $timezone
 * @property string $date_format d-m-Y | d/m/Y | m-d-Y | m/d/Y | Y-m-d | Y/m/d | Y-d-m | Y/d/m
 * @property string $datepicker_format dd-mm-yyyy | mm-dd-yyyy
 * @property string|null $currency_code
 * @property string|null $currency_symbol
 * @property string $currency_position left|right
 * @property string|null $decimal_separator
 * @property string|null $thousand_separator
 * @property string $language_default english|french|etc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Defaults active()
 * @method static Builder|Defaults inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults query()
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereCurrencyPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereDateFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereDatepickerFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereLanguageDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereThousandSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defaults whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Defaults extends BaseModel
{
    protected $table = 'defaults';

}
