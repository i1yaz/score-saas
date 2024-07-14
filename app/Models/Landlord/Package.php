<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string $subscription_options free|paid
 * @property string $amount_monthly
 * @property string $amount_yearly
 * @property string|null $gateway_stripe_product_monthly API Info - Product is the parent to a plan
 * @property string|null $gateway_stripe_price_monthly API Info - This is called "price:
 * @property string|null $gateway_stripe_product_yearly API Info - Product is the parent to a plan
 * @property string|null $gateway_stripe_price_yearly API Info - This is called "price:
 * @property string|null $gateway_paypal_plan_monthly
 * @property string|null $gateway_paypal_plan_yearly
 * @property string|null $gateway_razorpay_plan_monthly
 * @property string|null $gateway_razorpay_plan_yearly
 * @property string|null $gateway_paystack_plan_monthly
 * @property string|null $gateway_paystack_plan_yearly
 * @property string|null $description
 * @property string|null $icon
 * @property int $is_featured yes|no
 * @property bool $status
 * @property string $visibility visible|hidden
 * @property string|null $sync_date
 * @property string $sync_status awaiting-sync|syncing|synced
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $max_students -1 is unlimited
 * @property int $max_student_packages -1 is unlimited
 * @property int $max_monthly_packages -1 is unlimited
 * @property int|null $max_tutors -1 is unlimited
 * @method static Builder|Package active()
 * @method static Builder|Package inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereAmountMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereAmountYearly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayPaypalPlanMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayPaypalPlanYearly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayPaystackPlanMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayPaystackPlanYearly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayRazorpayPlanMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayRazorpayPlanYearly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayStripePriceMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayStripePriceYearly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayStripeProductMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereGatewayStripeProductYearly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereMaxMonthlyPackages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereMaxStudentPackages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereMaxStudents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereMaxTutors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereSubscriptionOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereSyncDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereSyncStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereVisibility($value)
 * @mixin \Eloquent
 */
class Package extends BaseModel
{

    protected $casts = [
        'status' => 'boolean'
    ];

    protected function maxTutors(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
    protected function maxStudents(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
    protected function maxStudentPackages(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
    protected function maxMonthlyPackages(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
}
