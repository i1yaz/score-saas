<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $creator_fullname
 * @property string|null $creator_email
 * @property string|null $gateway_name stripe | paypal | etc
 * @property string|null $gateway_ref the checkout_id (either from the gateway or system generated)
 * @property string|null $gateway_ref_2
 * @property string|null $amount amount of the payment
 * @property string|null $invoices [currently] - single invoice id | [future] - comma seperated list of invoice id's that are for this payment
 * @property int|null $subscription_id subscription id
 * @property string $payload
 * @property int|null $added_by user making the payment
 * @property string $status pending|completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|PaymentSession active()
 * @method static Builder|PaymentSession inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereCreatorEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereCreatorFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereGatewayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereGatewayRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereGatewayRef2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereInvoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSession whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentSession extends BaseModel
{
    use HasFactory;

}
