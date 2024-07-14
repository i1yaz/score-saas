<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $domain
 * @property string|null $subdomain
 * @property string $domain_type subdomain|custom
 * @property string|null $database
 * @property int|null $added_by
 * @property string|null $name
 * @property string|null $email
 * @property string|null $stripe_customer_id used for stripe checkout
 * @property string|null $paystack_customer_id used for paystack checkout
 * @property string|null $razorpay_customer_id used for razorpay checkout
 * @property string $status unsubscribed|free-trial|awaiting-payment|failed|active|cancelled
 * @property string|null $password
 * @property string|null $last_synced
 * @property string $email_config_type local|smtp
 * @property string $email_config_status
 * @property string|null $email_local_email
 * @property string|null $email_forwarding_email
 * @property string|null $sync_status awaiting-sync|null
 * @property string|null $sync_user awaiting-sync|null
 * @property string|null $updating_current_version
 * @property string|null $updating_target_version
 * @property string $updating_status completed|failed
 * @property string $updating_log
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Tenant active()
 * @method static Builder|Tenant inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDatabase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDomainType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmailConfigStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmailConfigType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmailForwardingEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmailLocalEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereLastSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant wherePaystackCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereRazorpayCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereStripeCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereSubdomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereSyncStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereSyncUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatingCurrentVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatingLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatingTargetVersion($value)
 * @mixin \Eloquent
 */
class Tenant extends BaseModel
{
    protected $guarded = ['id'];

    const RESTRICTED_SUBDOMAINS = ['admin', 'administrator', 'ads', 'webmail', 'mail', 'smtp', 'pop', 'www', 'blog', 'blogs', 'forums', 'store', 'help', 'status', 'doc', 'docs', 'api', 'support', 'contact', 'news', 'press', 'about', 'careers', 'terms', 'privacy', 'legal', 'copyright', 'faq', 'help', 'info','ssl', 'dns', 'cpanel', 'whm', 'ssh', 'ftp', 'vpn',
        'autodiscover', 'autoconfig', 'webdisk', 'webmail', 'ns1', 'ns2', 'ns3', 'ns4', 'ns5', 'ns6', 'secure', 'demo', 'm', 'mail', 'webmaster', 'host', 'hosting', 'admin',
        'chat', 'calendar', 'projects', 'tasks', 'integrations','xxx','sex','porn','adult','ns','nsfw'];
}
