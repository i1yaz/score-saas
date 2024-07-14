<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $gateway_id from gateway
 * @property string $status free-trial | awaiting-payment | active | failed | cancelled
 * @property int|null $customer_id
 * @property string|null $unique_id
 * @property int|null $added_by
 * @property string $type paid|free
 * @property string $payment_method automatic|offline
 * @property string|null $amount
 * @property string|null $trial_end
 * @property string|null $date_started
 * @property string|null $date_renewed from gateway
 * @property string|null $date_next_renewal from gateway
 * @property int|null $package_id the planid, when subscription was created
 * @property string|null $gateway_name stripe|paypal
 * @property string|null $gateway_plan_id [stripe = product] [paypal = foo]
 * @property string|null $gateway_price_id [stripe = price] [paypal = null]
 * @property string|null $gateway_billing_cycle monthly|yearly
 * @property string|null $gateway_last_message (optional) from gateway
 * @property string|null $gateway_last_date (optional) from gateway
 * @property string|null $checkout_reference (optional) any additional data
 * @property string|null $checkout_reference_2 (optional) any additional data
 * @property string|null $checkout_reference_3 (optional) any additional data
 * @property string|null $checkout_reference_4 (optional) any additional data
 * @property string|null $checkout_reference_5 (optional) any additional data
 * @property string|null $checkout_payload (optional) any additional data
 * @property string $archived yes|no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Subscription active()
 * @method static Builder|Subscription inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCheckoutPayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCheckoutReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCheckoutReference2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCheckoutReference3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCheckoutReference4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCheckoutReference5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDateNextRenewal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDateRenewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDateStarted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereGatewayBillingCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereGatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereGatewayLastDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereGatewayLastMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereGatewayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereGatewayPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereGatewayPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereTrialEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Subscription extends BaseModel
{
    use HasFactory;
}
