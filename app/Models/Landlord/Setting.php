<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $version
 * @property string|null $frontend_domain
 * @property string|null $base_domain
 * @property string|null $email_domain
 * @property string|null $company_name
 * @property string|null $company_address_line_1
 * @property string|null $company_state
 * @property string|null $company_city
 * @property string|null $company_zipcode
 * @property string|null $company_country
 * @property string|null $company_telephone
 * @property string|null $code_meta_title
 * @property string|null $code_meta_description
 * @property string|null $code_head
 * @property string|null $code_body
 * @property string|null $email_general_variables
 * @property string|null $email_from_address
 * @property string|null $email_from_name
 * @property string|null $email_server_type
 * @property string|null $email_smtp_host
 * @property string|null $email_smtp_port
 * @property string|null $email_smtp_username
 * @property string|null $email_smtp_password
 * @property string|null $email_smtp_encryption
 * @property string|null $favicon_landlord_filename
 * @property string|null $favicon_frontend_filename
 * @property string $free_trial yes|no
 * @property int $free_trial_days optional
 * @property string $frontend_status enabled|disabed
 * @property string|null $purchase_code
 * @property string|null $reserved_words
 * @property string|null $system_timezone
 * @property string|null $system_date_format
 * @property string|null $system_datepicker_format
 * @property string|null $system_default_leftmenu
 * @property string|null $system_pagination_limits
 * @property string|null $system_currency_code
 * @property string|null $system_currency_symbol
 * @property string|null $system_currency_position
 * @property string|null $system_decimal_separator
 * @property string|null $system_thousand_separator
 * @property string|null $system_language_default
 * @property string $system_renewal_grace_period days
 * @property string|null $system_logo_large_name
 * @property string|null $system_logo_small_name
 * @property string|null $system_logo_frontend_name
 * @property string $offline_payments_status enabled|disabled
 * @property string|null $offline_payments_display_name
 * @property string|null $offline_payments_details
 * @property string|null $offline_proof_of_payment_message
 * @property string|null $offline_proof_of_payment_thank_you
 * @property string $onboarding_status enabled|disabled
 * @property string|null $onboarding_content
 * @property string|null $stripe_secret_key
 * @property string|null $stripe_public_key
 * @property string|null $stripe_webhooks_key
 * @property string|null $stripe_default_subscription_plan_id
 * @property string|null $stripe_display_name
 * @property string $stripe_status enabled|disabled
 * @property string|null $paypal_display_name
 * @property string|null $paypal_sandbox_client_id
 * @property string|null $paypal_sandbox_secret_key
 * @property string|null $paypal_live_client_id
 * @property string|null $paypal_live_secret_key
 * @property string|null $paypal_subscription_product_id the product that wilb used to contain all plans
 * @property string $paypal_mode sandbox | live
 * @property string $paypal_status enabled|disabled
 * @property string|null $paystack_secret_key
 * @property string|null $paystack_public_key
 * @property string|null $paystack_display_name
 * @property string $paystack_status enabled|disabled
 * @property string|null $razorpay_api_key
 * @property string|null $razorpay_display_name
 * @property string|null $razorpay_webhooks_secret
 * @property string $razorpay_status enabled|disabled
 * @property string|null $razorpay_secret_key
 * @property string|null $gateways_default_product_name used to create a default product in some payment gateways (like paypal)
 * @property string|null $gateways_default_product_description used to create a default product in some payment gateways (like paypal)
 * @property string|null $files_max_size_mb
 * @property string|null $system_javascript_versioning
 * @property string|null $terms_of_service
 * @property string|null $terms_of_service_text
 * @property string $terms_of_service_status enabled|disabled
 * @property string|null $theme_name
 * @property string $cronjob_has_run yes|no
 * @property string|null $cronjob_last_run
 * @property string $cronjob_has_run_tenants yes|no
 * @property string|null $cronjob_last_run_tenants
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Setting active()
 * @method static Builder|Setting inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereBaseDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCodeBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCodeHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCodeMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCodeMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyZipcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCronjobHasRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCronjobHasRunTenants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCronjobLastRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCronjobLastRunTenants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailFromAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailFromName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailGeneralVariables($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailServerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailSmtpEncryption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailSmtpHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailSmtpPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailSmtpPort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmailSmtpUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFaviconFrontendFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFaviconLandlordFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFilesMaxSizeMb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFreeTrial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFreeTrialDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFrontendDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFrontendStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereGatewaysDefaultProductDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereGatewaysDefaultProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOfflinePaymentsDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOfflinePaymentsDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOfflinePaymentsStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOfflineProofOfPaymentMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOfflineProofOfPaymentThankYou($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOnboardingContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOnboardingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalLiveClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalLiveSecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalSandboxClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalSandboxSecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaypalSubscriptionProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaystackDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaystackPublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaystackSecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePaystackStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePurchaseCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereRazorpayApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereRazorpayDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereRazorpaySecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereRazorpayStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereRazorpayWebhooksSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereReservedWords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStripeDefaultSubscriptionPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStripeDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStripePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStripeSecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStripeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStripeWebhooksKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemCurrencyPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemDateFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemDatepickerFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemDefaultLeftmenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemJavascriptVersioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemLanguageDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemLogoFrontendName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemLogoLargeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemLogoSmallName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemPaginationLimits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemRenewalGracePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemThousandSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSystemTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereTermsOfService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereTermsOfServiceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereTermsOfServiceText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereVersion($value)
 * @mixin \Eloquent
 */
class Setting extends BaseModel
{
}
