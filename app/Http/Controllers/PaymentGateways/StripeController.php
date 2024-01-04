<?php

namespace App\Http\Controllers\PaymentGateways;

use App\Http\Controllers\AppBaseController;
use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\NonInvoicePackage;
use App\Models\StudentTutoringPackage;
use App\Models\User;
use App\Repositories\StripeRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Stripe\Price;
use Stripe\Subscription;

class StripeController extends AppBaseController
{
    /**
     * @var StripeRepository
     */
    private $stripeRepository;

    public function __construct(StripeRepository $stripeRepository)
    {
        $this->stripeRepository = $stripeRepository;
    }

    public function createSession(Request $request)
    {
        $invoice = Invoice::query()
            ->select(
                [
                    'invoices.id as invoice_id',
                    'non_invoice_packages.final_amount',
                    'non_invoice_packages.allow_partial_payment as nip_allow_partial_payment',
                    'student_tutoring_packages.allow_partial_payment as stp_allow_partial_payment',
                    'invoiceable_type',
                    'invoiceable_id',
                    'student_tutoring_packages.hourly_rate as stp_hourly_rate',
                    'student_tutoring_packages.hours as stp_hours',
                    'student_tutoring_packages.discount as stp_discount',
                    'student_tutoring_packages.discount_type as stp_discount_type',

                ])
            ->selectRaw('SUM(CASE WHEN payments.status = 1 THEN payments.amount ELSE 0 END) AS amount_paid')
            ->selectRaw('SUM(CASE WHEN payments.status = 1 THEN payments.amount_refunded ELSE 0 END) AS amount_refunded')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
            ->leftJoin('non_invoice_packages', function ($q) {
                $q->on('non_invoice_packages.id', '=', 'invoices.invoiceable_id')
                    ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('student_tutoring_packages.id', '=', 'invoices.invoiceable_id')
                    ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->where('invoices.id', $request->invoiceId)->firstOrFail();
        //Non Invoice Package
        if ($invoice->invoiceable_type == NonInvoicePackage::class) {
            $invoiceType = 3;
            $payable_amount = $invoice->final_amount - $invoice->amount_paid;
            $payable_amount = $payable_amount + $invoice->amount_refunded??0;
            $userEmail = NonInvoicePackage::query()
                ->select(['clients.email'])
                ->leftJoin('clients', 'clients.id', '=', 'non_invoice_packages.client_id')
                ->where('non_invoice_packages.id', $invoice->invoiceable_id)->firstOrFail()->email;
            $packageCode = getNonInvoicePackageCodeFromId($invoice->invoiceable_id);
        }
        //Student Tutoring Package
        if ($invoice->invoiceable_type == StudentTutoringPackage::class) {
            $invoiceType = 1;
            $payable_amount = cleanAmountWithCurrencyFormat(getPriceFromHoursAndHourlyWithDiscount($invoice->stp_hourly_rate, $invoice->stp_hours, $invoice->stp_discount, $invoice->stp_discount_type));
            $userEmail = StudentTutoringPackage::query()
                ->select(['students.email'])
                ->leftJoin('students', 'students.id', '=', 'student_tutoring_packages.student_id')
                ->where('student_tutoring_packages.id', $invoice->invoiceable_id)->firstOrFail()->email;

            $packageCode = getStudentTutoringPackageCodeFromId($invoice->invoiceable_id);
            $payable_amount = $payable_amount - $invoice->amount_paid;
            $payable_amount = $payable_amount + $invoice->amount_refunded??0;
        }
        $amount = null;
        if (filter_var($invoice->nip_allow_partial_payment, FILTER_VALIDATE_BOOLEAN) || filter_var($invoice->stp_allow_partial_payment, FILTER_VALIDATE_BOOLEAN)) {
            $amount = $request->partialAmount;
        }

        if (! is_numeric($amount)) {
            $amount = $payable_amount;
        }
        if ($amount > $payable_amount) {
            $amount = $payable_amount;
        }

        $invoiceId = $invoice->invoice_id;
        $invoiceCode = getInvoiceCodeFromId($invoiceId);
        $packageType = getInvoiceTypeFromClass($invoice->invoiceable_type);
        $userId = \Auth::id() ?? 'none';
        $guard = \Auth::guard()->name ?? 'none';
        try {
            setStripeApiKey();
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'customer_email' => $userEmail,
                'line_items' => [
                    [
                        'price_data' => [
                            'product_data' => [
                                'name' => 'Payment for '.$packageType.' Package #'.$packageCode,
                                'description' => 'Bill invoice #'.$invoiceCode,
                            ],
                            'unit_amount' => $amount * 100,
                            'currency' => getInvoiceCurrencyCode(),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'metadata' => [
                    'description' => 'Bill invoice #'.$invoiceCode,
                ],
                'billing_address_collection' => 'auto',
                'client_reference_id' => "{$invoiceType}-{$invoiceId}-{$userId}-{$guard}",
                'mode' => 'payment',
                'success_url' => route('payment-success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment-failed').'?error=payment_cancelled',
            ]);
            $result = [
                'sessionId' => $session['id'],
            ];

            return $this->sendResponse($result, 'Session created successfully.');
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function createSessionForSubscription(Request $request){
        setStripeApiKey();
        $monthlyInvoicePackage = MonthlyInvoicePackage::select([
            'monthly_invoice_packages.id','monthly_invoice_packages.hourly_rate','monthly_invoice_subscriptions.subscription_id',
            'monthly_invoice_subscriptions.stripe_price_id','monthly_invoice_package_id','monthly_invoice_packages.due_date','monthly_invoice_packages.start_date'
        ])
            ->join('monthly_invoice_subscriptions','monthly_invoice_subscriptions.monthly_invoice_package_id','monthly_invoice_packages.id')
            ->where('monthly_invoice_package_id',$request->monthlyInvoicePackageId)->first();

        if (!empty($monthlyInvoicePackage->subscription_id)){
            $subscription = Subscription::retrieve($monthlyInvoicePackage->subscription_id);
            if ($subscription->id===$monthlyInvoicePackage->subscription_id){
                return response()->json(['message'=>'Subscription Already Created'],409);
            }
        }
        $userId = \Auth::id() ?? 'none';
        $guard = \Auth::guard()->name ?? 'none';
        $monthlyInvoiceSubscription = MonthlyInvoiceSubscription::where('monthly_invoice_package_id',$request->monthlyInvoicePackageId)->first();
        if (!empty($monthlyInvoiceSubscription->subscription_id)){
            $subscription = Subscription::retrieve($monthlyInvoiceSubscription->subscription_id);
            if ($subscription->id===$monthlyInvoiceSubscription->subscription_id){
                return response()->json(['message'=>'Subscription Already Created'],409);
            }
        }
        try {
            $stripePrice = Price::create([
                'product_data' => [
                    'name' => getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage->id),
                    'statement_descriptor' => 'MIP #'.getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage->id),
                    'unit_label' => 'hour session'
                ],
                'currency' => 'USD',
                'billing_scheme' => 'per_unit',
                'unit_amount_decimal' => $monthlyInvoicePackage->hourly_rate * 100,
                'recurring' => [
                    'interval' => 'month',
                    'usage_type' => 'metered'
                ],
            ]);

            $stripeMinutesPrice = Price::create([
                'product_data' => [
                    'name' => getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage->id),
                    'statement_descriptor' => 'MIP #'.getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage->id),
                    'unit_label' => 'Minute'
                ],
                'currency' => 'USD',
                'billing_scheme' => 'per_unit',
                'unit_amount_decimal' => (formatAmountWithoutCurrency($monthlyInvoicePackage->hourly_rate/60)) * 100,
                'recurring' => [
                    'interval' => 'month',
                    'usage_type' => 'metered'
                ],
            ]);

            \Log::channel('stripe_success')->info('Price created successfully', json_decode(json_encode($stripePrice ,true),true));
            $monthlyInvoiceSubscription->stripe_price_id = $stripePrice->id;
            $monthlyInvoiceSubscription->stripe_minutes_price_id = $stripeMinutesPrice->id;
            $monthlyInvoiceSubscription->frequency = $stripePrice->recurring->interval;
            $monthlyInvoiceSubscription->metadata = json_encode($stripePrice);
            $monthlyInvoiceSubscription->save();

        }catch (\Exception $e){
            report($e);
        }


        $invoiceType = 3;
        $due_date = getFutureDueDate($monthlyInvoicePackage->due_date);
        $session = StripeSession::create([
            'customer_email' => \Auth::user()->email,
            'success_url' => route('payment-success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment-failed').'?error=payment_cancelled',
            'mode' => 'subscription',
            'line_items' => [
                [
                    'price' =>  $monthlyInvoicePackage->stripe_price_id,
                ],
                [
                    'price' =>  $monthlyInvoicePackage->stripe_minutes_price_id,
                ]
            ],
            "subscription_data" => [
                "billing_cycle_anchor"=> $due_date->unix(),
            ],
            'metadata' => [
                'invoiceType' => $invoiceType,
                'monthlyInvoicePackageId' => $request->monthlyInvoicePackageId,
                'userId' => $userId,
                'guard' => $guard,
            ],
            'client_reference_id' => "{$invoiceType}-{$request->monthlyInvoicePackageId}-{$userId}-{$guard}",
        ]);

        $result = [
            'sessionId' => $session['id'],
        ];

        return $this->sendResponse($result, 'Session created successfully.');
    }

    public function handleFailedPayment(): RedirectResponse
    {
        Flash::error('Your Payment is Cancelled');

        return redirect()->route('client.invoices.index');
    }
    public function webhooks(Request $request){
        $payload = $request->all();
        \Log::channel('stripe_success')->info('Stripe webhook',$request->all());
        if($payload['type'] === 'checkout.session.completed'){
            if ($payload['data']['object']['mode'] === 'subscription'){
                $sessionData = $payload['data']['object'];
                $this->stripeRepository->stripeSubscriptionPaymentSuccessfullyCompleted($sessionData);
                return response('success',200);
            }
            if ($payload['data']['object']['mode'] === 'payment'){
                $sessionData = $payload['data']['object'];
                $this->stripeRepository->stripePaymentSuccessfullyCompleted($sessionData);
                return response('success',200);
            }
        }
        if($payload['type'] === 'invoice.payment_succeeded'){
            $this->stripeRepository->stripeInvoicePaymentSuccessfullyCompleted($request->all());
            return response('success',200);

        }
        if ($payload['type']==='charge.refunded'){
            $this->stripeRepository->stripeRefundPayment($request->all());
            return response('success',200);


        }
        return response('webhook processing failed',500);
    }
    public function cancelMonthlyInvoicePackageSubscription(Request $request){
        $monthlyInvoiceSubscription = MonthlyInvoiceSubscription::where('monthly_invoice_package_id',$request->monthlyInvoicePackageId)->first();
        if ($monthlyInvoiceSubscription->payment_gateway == 'stripe' ) {
            setStripeApiKey();
            if (!empty($monthlyInvoiceSubscription->subscription_id)){
                $subscription = Subscription::retrieve($monthlyInvoiceSubscription->subscription_id);
                if ($subscription->id===$monthlyInvoiceSubscription->subscription_id && $subscription->status === 'active'){
                    $subscription->cancel();
                    $monthlyInvoiceSubscription->is_active = false;
                    $monthlyInvoiceSubscription->save();
                    $monthlyInvoiceSubscription = MonthlyInvoicePackage::findOrFail($monthlyInvoiceSubscription->monthly_invoice_package_id);
                    $monthlyInvoiceSubscription->status = false;
                    $monthlyInvoiceSubscription->save();
                    return response()->json(['message'=>'Subscription has been Cancelled'],200);
                }
            }
        }
        return response()->json(['message'=>'Subscription Not Found or Already canceled'],404);
    }
}
