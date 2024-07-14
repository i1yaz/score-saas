<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $status new|processing|failed|completed
 * @property string $gateway stripe|paypal | etc| all-gateways
 * @property string $type delete-database | cancel-subscription | delete-plan | update-plan-name |  update-plan-price
 * @property string $payload_1 subscription id | plan id | etc
 * @property string|null $payload_2 optional data
 * @property string|null $payload_3
 * @property string|null $payload_4
 * @property string|null $payload_5
 * @property int $attempts
 * @property string $manual_action_required yes|no (can use this in the future to show admin an action log)
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereManualActionRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule wherePayload1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule wherePayload2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule wherePayload3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule wherePayload4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule wherePayload5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    use HasFactory;
}
