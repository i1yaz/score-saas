<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $status
 * @property string|null $source
 * @property string|null $reference subscription-payment | subscription-activated | subscription-cancelled | subscription-renewed | subscription-payment-failed
 * @property string|null $type
 * @property string|null $gateway_reference
 * @property string|null $gateway_id
 * @property string|null $transaction_type
 * @property int $added_by
 * @property string|null $amount
 * @property string|null $currency
 * @property string|null $payment_date
 * @property string|null $next_due_date
 * @property string|null $transaction_id
 * @property string|null $subscription_id
 * @property string|null $gateway_reference_type
 * @property string|null $attributes_1
 * @property string|null $attributes_2
 * @property string|null $payload
 * @property string|null $comment
 * @property int $attempts
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Webhook active()
 * @method static Builder|Webhook inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook query()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereAttributes1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereAttributes2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereGatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereGatewayReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereGatewayReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereNextDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Webhook extends BaseModel
{
    use HasFactory;
}
