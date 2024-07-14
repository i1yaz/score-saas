<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Package;
use App\Models\Landlord\Subscription;
use App\Models\Landlord\Webhook;
use App\Repositories\Landlord\CheckoutRepository;
use App\Repositories\Landlord\SubscriptionsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;

class SubscriptionController extends Controller
{
    private SubscriptionsRepository $subscriptionsRepo;
    private CheckoutRepository $checkoutRepo;

    public function __construct(SubscriptionsRepository $subscriptionsRepo,CheckoutRepository $checkoutRepo)
    {

        $this->middleware('auth')->except('packagePaymentWebhooks');
        $this->subscriptionsRepo = $subscriptionsRepo;
        $this->checkoutRepo = $checkoutRepo;
    }

    public function index()
    {
        $subscriptions = $this->subscriptionsRepo->search(
            [
                'subscriptions.id', 'subscriptions.first_name', 'subscriptions.last_name','subscriptions.created_at',
                'subscriptions.amount', 'subscriptions.date_renewed', 'subscriptions.gateway_billing_cycle','subscriptions.status',
                'subscriptions.gateway_name'
            ]);
        return view('landlord.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $packages = Package::all();
        return view('landlord.subscriptions.create', compact('packages'));
    }

    public function store(CreateSubscription $request)
    {
        $subscription = new Subscription();
        $this->saveSubscriptionDetails($request, $subscription);
        Flash::success('Subscription saved successfully.');
        return redirect(route('landlord.subscriptions.index'));
    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $packages = Package::all();
        return view('landlord.subscriptions.edit', compact('subscription', 'packages'));
    }

    public function update(CreateSubscription $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $this->saveSubscriptionDetails($request, $subscription);
        Flash::success('Subscription updated successfully.');
        return redirect(route('landlord.subscriptions.index'));
    }

    public function destroy($id)
    {
        if (Subscription::where('id', $id)->delete()) {
            Flash::success('Subscription deleted successfully.');
        } else {
            Flash::error('Subscription not deleted.');
        }
        return redirect(route('landlord.subscriptions.index'));
    }

    private function saveSubscriptionDetails($request, $subscription)
    {
        $subscription->package_id = $request->package_id;
        $subscription->name = $request->name;
        $subscription->price = $request->price;
        $subscription->save();
    }

    public function packagePaymentWebhooks(Request $request)
    {
        $payload = @file_get_contents('php://input');
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $_SERVER['HTTP_STRIPE_SIGNATURE'], config('system.stripe_webhooks_key')
            );
        } catch (\UnexpectedValueException$e) {
            Log::error("stripe webhook data is invalid", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $payload]);
            http_response_code(400);
            die('Stripe payload is invalid');
        } catch (\Stripe\Exception\SignatureVerificationException$e) {
            Log::critical("Stripe signing id (signature) does not match the one in database", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $payload]);
            http_response_code(400);
            die('Signing signature does not match');
        }

        switch ($event->type) {
            case 'invoice.paid':
                $this->subscriptionPaid($event);
                break;
            case 'checkout.session.completed':
                $this->checkoutSessionCompleted($event);
                break;
            case 'customer.subscription.deleted':
                $this->subscriptionCancelled($event);
                break;
            case 'invoice.payment_failed':
                //we will ignore this webhook and use the grace period to eventually cancel the subscription if no payment comes
                break;
            default:
                Log::info("webhook [$event->type] is not on expected list - will ignore and exit", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        }
    }

    private function subscriptionPaid(\Stripe\Event $event)
    {
        Log::info("webhook [" . $event->type . "] is for a subscription [payment/renewal] - will now process", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        if (Webhook::On('landlord')
            ->Where('gateway_id', $event->data->object->id)
            ->Where('reference', 'subscription-payment')
            ->whereNotNull('gateway_id')->exists()) {
            Log::info("this webhook has already been recorded - will now exit", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return response('Webhook Handled', 200);
        }
        if (\App\Models\Payment::On('landlord')
            ->Where('transaction_id', $event->data->object->charge)
            ->whereNotNull('transaction_id')
            ->exists()) {
            Log::info("the payment for this webhook has already been processed - will ignore'", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return response('Webhook Handled', 200);
        }
        if (!isset($event->data->object->subscription)) {
            Log::error("the webhook is missing the (subscription id) - will now exit", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return response('Webhook Handled', 200);
        }
        Log::info("webhook is for a subscription id (" . $event->data->object->subscription . ")", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        $webhook = new Webhook();
        $webhook->setConnection('landlord');
        $webhook->source = 'stripe';
        $webhook->gateway_id = $event->data->object->id;
        $webhook->type = $event->type;
        $webhook->crm_reference = 'subscription-payment';
        $webhook->transaction_type = 'subscription';
        $webhook->amount = $event->data->object->amount_paid / 100;
        $webhook->currency = $event->data->object->currency;
        $webhook->transaction_id = $event->data->object->charge;
        $webhook->gateway_reference = $event->data->object->subscription;
        $webhook->gateway_reference_type = 'gateway.subscription.id';
        $webhook->payment_date = date('Y-m-d', $event->data->object->created);
        $webhook->next_due_date = isset($event->data->object->lines->data[0]->period->end) ? date('Y-m-d', $event->data->object->lines->data[0]->period->end) : '';
        $webhook->payload = json_encode($event);
        $webhook->status = 'new';
        $webhook->save();
        Log::info("Webhook for [subscription-payment] has been scheduled for cronjob processing - will now exit", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return response('Webhook Handled', 200);
    }

    private function checkoutSessionCompleted(\Stripe\Event $event)
    {
        Log::info("webhook [$event->type] received - will now process directly'", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'event' => $event]);
        if (!$this->checkoutRepo->completeCheckoutSession([
            'checkout_session_id' => $event->data->object->id,
            'gateway_subscription_id' => $event->data->object->subscription,
            'gateway_name' => 'stripe',
            'gateway_subscription_status' => (isset($event->data->object->payment_status) && $event->data->object->payment_status == 'paid') ? 'completed' : 'pending',
        ])) {
            Log::error("webhook [$event->type] failed - unable to complete checkout session", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            http_response_code(419);
            exit('Webhook Processing Error');
        }
        http_response_code(200);
        exit('Webhook Received Ok');
    }

    private function subscriptionCancelled(\Stripe\Event $event)
    {
        Log::info("webhook [" . $event->type . "] is for a subscription [cancellation] - will now process", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        if (!isset($event->data->object->id)) {
            Log::error("the webhook is missing the (subscription id) - will now exit", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return response('Webhook Handled', 200);
        }

        if (Webhook::On('landlord')
            ->Where('gateway_id', $event->data->object->id)
            ->Where('reference', 'subscription-cancelled')
            ->whereNotNull('gateway_id')->exists()) {
            Log::info("this webhook has already been recorded - will now exit", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return response('Webhook Handled', 200);
        }

        //get the data
        Log::info("webhook is for a subscription id (" . $event->data->object->id . ")", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //record webhook for cron processing
        $webhook = new \App\Models\Landlord\Webhook();
        $webhook->setConnection('landlord');
        $webhook->source = 'stripe';
        $webhook->gateway_id = $event->data->object->id;
        $webhook->type = $event->type;
        $webhook->reference = 'subscription-cancelled';
        $webhook->transaction_type = 'subscription';
        $webhook->gateway_reference = $event->data->object->id;
        $webhook->gateway_reference_type = 'gateway.subscription.id';
        $webhook->payload = json_encode($event);
        $webhook->status = 'new';
        $webhook->save();
        Log::info("Webhook for [subscription-payment] has been scheduled for cronjob processing - will now exit", ['process' => '[landlord][stripe-webhooks]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        return response('Webhook Handled', 200);
    }

}
