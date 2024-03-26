<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\PaymentSession;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Collection;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription;

class StripeRepository
{

    public $api_version;
    public function __construct() {

        $this->api_version = '2020-03-02';

    }
    public function initialiseStripe($data = []): bool
    {
        try {
            Stripe::setApiKey($data['settings_stripe_secret_key']);
            Stripe::setApiVersion($this->api_version);
        } catch (Exception $e) {
            Log::critical("unable to connect to stripe - error: " . $e->getMessage(), ['process' => '[initialiseStripe]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        return true;
    }
    public function getProductPrices($data = []): Collection|bool
    {

        $product_id = $data['product_id'];

        Log::info("getting product prices ($product_id) from stripe - started", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);

        //validate
        if ($product_id == '') {
            Log::error('no product id was specified', ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }

        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $prices = $stripe->prices->all(['product' => $product_id]);
        } catch (AuthenticationException$e) {
            Log::error("getting product prices ($product_id) from stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("getting product prices ($product_id) from stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        }

        if (!is_object($prices)) {
            Log::error("unable to retrieve the products prices ($product_id) from stripe", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }
        Log::info("getting product prices ($product_id) from stripe - completed", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'prices' => $prices]);
        return $prices;
    }
    public function getProduct($data = []): bool|Product
    {
        $product_id = $data['product_id'];
        Log::info("getting product ($product_id) from stripe - started", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        if ($product_id == '') {
            Log::error('no product id was specifid', ['process' => '[stripe-get-product]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $product = $stripe->products->retrieve($product_id, []);
        } catch (AuthenticationException$e) {
            Log::error("retrieving a product ($product_id) from stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-get-product]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("retrieving a product ($product_id) from stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-get-product]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-get-product]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        }
        if (!is_object($product)) {
            Log::error("unable to retrieve the product ($product_id) from stripe", ['process' => '[stripe-get-product]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }
        Log::info("retrieving a poduct ($product_id) from stripe - completed", ['process' => '[stripe-get-product]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $product;
    }
    public function getPrice($data = []): bool|Price
    {
        $price_id = $data['price_id'];
        Log::info("retrieving a price ($price_id) from stripe - started", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        if ($price_id == '') {
            Log::error('no stripe price_id was specifid', ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $price = $stripe->prices->retrieve($price_id, []);
        } catch (AuthenticationException$e) {
            Log::error("retrieving a price ($price_id) from stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("retrieving a price ($price_id) from stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error') . ' - (' . $e->getMessage() . ')');
            return false;
        }
        if (!is_object($price)) {
            Log::error("unable to retrieve the price ($price_id) from stripe", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        Log::info("retrieving a price ($price_id) from stripe - completed", ['process' => '[stripe-get-products-prices]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $price;
    }
    public function getSubscription($data = []): bool|Subscription
    {
        $subscription_stripe_id = $data['subscription_stripe_id'];
        Log::info("retrieving a subscription ($subscription_stripe_id) from stripe - started", ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        if ($subscription_stripe_id == '') {
            Log::error("retrieving a subscription ($subscription_stripe_id) from stripe failed - a subscription id was not provided", ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $subscription = $stripe->subscriptions->retrieve(
                $subscription_stripe_id,
                []
            );
        } catch (AuthenticationException$e) {
            Log::error("retrieving a subscription ($subscription_stripe_id) from stripe failed - unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("retrieving a subscription ($subscription_stripe_id) from stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        if (!is_object($subscription)) {
            Log::error("unable to retrieve the subscription ($subscription_stripe_id) from stripe", ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        Log::info("retrieving a subscription ($subscription_stripe_id) from stripe - completed", ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $subscription;
    }
    public function getCheckoutSession($data = []): bool|Session
    {
        $checkout_session_id = $data['checkout_session_id'] ?? '';
        Log::info("retrieving a checkout session ($checkout_session_id) from stripe - started", ['process' => '[stripe-get-checkout session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        if ($checkout_session_id == '') {
            Log::error("retrieving a checkout session ($checkout_session_id) from stripe failed - a checkout session id was not provided", ['process' => '[stripe-get-checkout session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $checkout_session = $stripe->checkout->sessions->retrieve(
                $checkout_session_id,
                []
            );
        } catch (AuthenticationException$e) {
            Log::error("retrieving a checkout session ($checkout_session_id) from stripe failed - unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-get-checkout session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("retrieving a checkout session ($checkout_session_id) from stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-get-checkout session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-get-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        if (!is_object($checkout_session)) {
            Log::error("unable to retrieve the checkout session ($checkout_session_id) from stripe", ['process' => '[stripe-get-checkout session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        Log::info("retrieving a checkout session ($checkout_session_id) from stripe - completed", ['process' => '[stripe-get-checkout session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $checkout_session;
    }
    public function cancelSubscription($data = []): bool
    {
        $subscription_stripe_id = $data['subscription_stripe_id'];
        Log::info("cancelling a subscription ($subscription_stripe_id) at stripe- started", ['process' => '[stripe-cancel-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        if ($subscription_stripe_id == '') {
            Log::error("cancelling subscription ($subscription_stripe_id) failed - a subscription id was not provided", ['process' => '[stripe-cancel-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            request()->session()->flash('flash-error-message', __('lang.gateway_error_see_logs'));
            return false;
        }
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $stripe->subscriptions->cancel(
                $subscription_stripe_id,
                []
            );
        } catch (AuthenticationException$e) {
            Log::error("cancelling stripe subscription ($subscription_stripe_id) failed  - unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-cancel-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("cancelling stripe subscription ($subscription_stripe_id) failed  - the server was unable to connect to api.stripe.com", ['process' => '[stripe-cancel-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (InvalidRequestException$e) {
            Log::error($e->getMessage(), ['process' => '[stripe-cancel-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-cancel-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        Log::info("cancelling a subscription ($subscription_stripe_id) at stripe- completed", ['process' => '[stripe-cancel-subscription]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return true;
    }
    public function createPlan($data = []) {
        Log::info("creating a plan at stripe - started", ['process' => '[stripe-create-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $plan = $stripe->plans->create([
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'interval' => $data['cycle'],
                'product' => [
                    'name' => $data['name'],
                ],
            ]);
        } catch (AuthenticationException$e) {
            Log::error("creating a plan at stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-create-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("creating a plan at stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-create-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (InvalidRequestException$e) {
            Log::error($e->getMessage(), ['process' => '[stripe-create-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (Exception $e) {
            Log::error('creating a plan at stripe failed - error: ' . $e->getMessage(), ['process' => '[stripe-create-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        Log::info("creating a plan ($plan) at stripe - completed", ['process' => '[stripe-create-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $plan;
    }
    public function createPrice($data = []) {
        $product_id = $data['product_id'];
        Log::info("creating a new price at stripe - started", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $price = $stripe->prices->create([
                'unit_amount' => $data['price_amount'],
                'currency' => $data['price_currency'],
                'recurring' => [
                    'interval' => $data['price_cycle'],
                ],
                'product' => $product_id,
            ]);
        } catch (AuthenticationException$e) {
            Log::error("creating a new price at stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("creating a new price at stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (InvalidRequestException|Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        Log::info("creating a new price at stripe ($price->id) - completed", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $price;

    }
    public function archivePlan($data = []): bool
    {
        $plan_id = $data['plan_id'];
        Log::info("archiving a plan ($plan_id) at stripe - started", ['process' => '[stripe-archive-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $stripe->products->update(
                $data['plan_id'],
                [
                    'active' => false,
                ]
            );
        } catch (AuthenticationException$e) {
            Log::error("archiving a plan at stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-archive-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("archiving a plan at stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-archive-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (InvalidRequestException$e) {
            Log::error($e->getMessage(), ['process' => '[stripe-archive-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-archive-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        Log::info("archiving a plan ($plan_id) at stripe - completed", ['process' => '[stripe-archive-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return true;
    }
    public function archivePrice($data = []): bool
    {
        $price_id = $data['price_id'];
        Log::info("archiving a price ($price_id) at stripe - started", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $stripe->prices->update(
                $data['price_id'],
                [
                    'active' => false,
                ]
            );
        } catch (AuthenticationException$e) {
            Log::error("archiving a price ($price_id) at stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("archiving a price ($price_id) at stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (InvalidRequestException$e) {
            Log::error($e->getMessage(), ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        Log::info("archiving a price ($price_id) at stripe - completed", ['process' => '[stripe-archive-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return true;

    }
    public function updatePlanName($data = []): bool
    {
        $plan_id = $data['plan_id'];
        Log::info("updating plan name ($plan_id) at stripe - started", ['process' => '[stripe-update-product-name]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        try {
            $stripe = new StripeClient($data['settings_stripe_secret_key']);
            $stripe->products->update(
                $data['plan_id'],
                [
                    'name' => $data['plan_name'],
                ]
            );

        } catch (AuthenticationException$e) {
            Log::error("updating plan name ($plan_id) at stripe failed - Unable to authenticate with Stripe. Check your API keys", ['process' => '[stripe-update-product-name]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (ApiConnectionException$e) {
            Log::error("updating plan name ($plan_id) at stripe failed - Your server was unable to connect to api.stripe.com", ['process' => '[stripe-update-product-name]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (InvalidRequestException$e) {
            Log::error($e->getMessage(), ['process' => '[stripe-update-product-name]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-update-product-name]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        Log::info("updating plan name ($plan_id) at stripe - completed", ['process' => '[stripe-update-product-name]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return true;

    }
    public function initiateSubscriptionPayment($data = []) {

        Log::info("initiating a subscription payment session at stripe - started", ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        if (!$this->initialiseStripe($data)) {
            return false;
        }
        if (!is_array($data)) {
            Log::error("initiating a subscription payment session at stripee failed - invalid paymment payload data", ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        if ($package = $this->validateGatewayPlans($data)) {
            $data['price_id'] = ($data['billing_cycle'] == 'monthly') ? $package->package_gateway_stripe_price_monthly : $package->package_gateway_stripe_price_yearly;
        } else {
            Log::error("stripe subscription plans could not be validated for this package", ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        if (!$price = $this->getPrice($data)) {
            Log::error("initiating a subscription payment session at stripee failed - unable to get the price", ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        if ($customer = $this->getCustomer($data)) {
            $data['customer_id'] = $customer->id;
        } else {
            Log::error("initiating a subscription payment session at stripee failed - unable to retrieve the customer", ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        if (!$session = $this->createSubscriptionPaymentSession($data, $package)) {
            Log::error("initiating a subscription payment session at stripee failed - unable to create a payment session", ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        $payment_session = new PaymentSession();
        $payment_session->setConnection('landlord');
        $payment_session->session_creatorid = auth()->id();
        $payment_session->session_creator_fullname = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $payment_session->session_creator_email = auth()->user()->email;
        $payment_session->session_gateway_name = 'stripe';
        $payment_session->session_gateway_ref = $session->id;
        $payment_session->session_amount = $price->unit_amount / 100;
        $payment_session->session_invoices = null;
        $payment_session->session_subscription_id = $data['subscription_id'];
        $payment_session->session_payload = json_encode($session);
        $payment_session->save();
        Log::info("initiating a subscription payment session at stripee - completed", ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $session->id;

    }
    public function getCustomer($data = []) {
        Log::info("fetching a customer from stripe - started", ['process' => '[get-stripe-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        $tenant_id = $data['tenant_id'];
        if (!$tenant = \App\Models\Landlord\Tenant::On('landlord')->Where('tenant_id', $tenant_id)->first()) {
            Log::error("getting a customer from stripe failed - the tenant could not be found in the landlord db", ['process' => '[get-stripe-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        $tenant_stripe_customer_id = $tenant->tenant_stripe_customer_id;
        if ($tenant->tenant_stripe_customer_id != '') {
            try {
                $customer = \Stripe\Customer::retrieve($tenant->tenant_stripe_customer_id);
                Log::info("getting a customer ($tenant->tenant_stripe_customer_id) from stripe- completed", ['process' => '[get-stripe-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                return $customer;
            } catch (exception $e) {
                Log::info("this tenant has a stripe customer id ($tenant_stripe_customer_id), but the user was not found in stripe - will now create a new user", ['process' => '[get-stripe-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            }
        }
        $user = \App\Models\User::Where('id', auth()->id())->first();
        Log::info("the customer ($tenant_stripe_customer_id) was not found at stripe - will now create one", ['process' => '[stripe-validate-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        try {
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'metadata' => [
                    'userid' => $user->id,
                    'tenant_id' => $tenant_id,
                ],
            ]);
            $tenant->tenant_stripe_customer_id = $customer->id;
            $tenant->save();
            Log::info("creating a new customer ($customer->id) at stripe - completed", ['process' => '[get-stripe-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return $customer;
        } catch (exception $e) {
            Log::error("error creating a new customer at stripe - error: " . $e->getMessage(), ['process' => '[get-stripe-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        }
        Log::error("fetching a customer from stripe - failed", ['process' => '[get-stripe-customer]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return false;

    }
    public function createSubscriptionPaymentSession($data = []) {
        try {
            $session = Session::create([
                'customer' => $data['customer_id'],
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $data['price_id'],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => url('app/settings/account/thankyou/stripe?checkout_session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => $data['cancel_url'],
                'metadata' => [
                    'subscription_id' => $data['subscription_id'],
                ],
            ]);
            return $session;
        } catch (exception $e) {
            Log::error($e->getMessage(), ['process' => '[stripe-initiating-a-payment-session]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        }

        return false;
    }
    public function validateGatewayPlans($data) {
        Log::info("validating the package's plans (prices) at stripe", ['process' => '[stripe-validate-gateway-plans]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        $package = $data['package'];
        if ($package->package_gateway_stripe_price_monthly) {
            if ($this->getPrice([
                'settings_stripe_secret_key' => $data['settings_stripe_secret_key'],
                'price_id' => $package->package_gateway_stripe_price_monthly,
            ])) {
            } else {
                $package->package_gateway_stripe_price_monthly = '';
                $package->package_gateway_stripe_product_monthly = '';
                $package->save();
                Log::info("the plan (price) could not be loaded from the stripe. Will now delete it from the package and recreate in stripe", ['process' => '[stripe-validate-gateway-plans]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            }
        }
        if ($package->package_gateway_stripe_price_yearly) {
            if ($this->getPrice([
                'settings_stripe_secret_key' => $data['settings_stripe_secret_key'],
                'price_id' => $package->package_gateway_stripe_price_yearly,
            ])) {
            } else {
                $package->package_gateway_stripe_price_yearly = '';
                $package->package_gateway_stripe_product_yearly = '';
                $package->save();
                Log::info("the plan (price) could not be loaded from the stripe. Will now delete it from the package and recreate in stripe", ['process' => '[stripe-validate-gateway-plans]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            }
        }
        if ($package->package_gateway_stripe_price_monthly == '') {
            if ($plan = $this->createPlan([
                'settings_stripe_secret_key' => $data['settings_stripe_secret_key'],
                'amount' => $package->package_amount_monthly * 100,
                'currency' => $data['currency'],
                'cycle' => 'month',
                'name' => $package->package_name,
            ])) {
                $package->package_gateway_stripe_price_monthly = $plan->id;
                $package->package_gateway_stripe_product_monthly = $plan->product;
                $package->save();
            }
        }

        if ($package->package_gateway_stripe_price_yearly == '') {
            if ($plan = $this->createPlan([
                'settings_stripe_secret_key' => $data['settings_stripe_secret_key'],
                'amount' => $package->package_amount_yearly * 100,
                'currency' => $data['currency'],
                'cycle' => 'year',
                'name' => $package->package_name,
            ])) {
                $package->package_gateway_stripe_price_yearly = $plan->id;
                $package->package_gateway_stripe_product_yearly = $plan->product;
                $package->save();
            }
        }
        return $package;

    }
    public function updatePlanPrice($package = '', $data = []): bool
    {
        Log::info("updating package ($package->package_name) [price] at stripe - started", ['process' => '[stripe-update-plan-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        Log::info("we will be creating a new plan for the packge ($package->package_name)", ['process' => '[stripe-update-plan-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);
        if (empty($data['product_id'])) {
            Log::info("the package does not have a stripe [product_id] - this update process is not needed - will now exit", ['process' => '[stripe-update-plan-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return true;
        }
        $required = ['stripe_secret_key', 'price_id', 'price_amount', 'price_cycle', 'price_currency'];
        foreach ($required as $key) {
            if (empty($data[$key])) {
                Log::error("updating a plan - failed - [$key] was not provided", ['process' => '[stripe-update-plan-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                return false;
            }
        }
        Log::info("we now create a new [price] for the packge ($package->package_name) and archiving the old price (".$data['price_id'].")", ['process' => '[stripe-update-plan-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'data' => $data]);

        //(1) archive current price
        $this->archivePrice([
            'settings_stripe_secret_key' => $data['stripe_secret_key'],
            'price_id' => $data['price_id'],
        ]);

        //(2) create a new price
        if ($price = $this->createPrice([
            'settings_stripe_secret_key' => $data['stripe_secret_key'],
            'product_id' => $data['product_id'],
            'price_amount' => $data['price_amount'],
            'price_currency' => $data['price_currency'],
            'price_cycle' => $data['price_cycle'],
        ])) {

            //update package with new price id
            if ($data['price_cycle'] == 'month') {
                $package->package_gateway_stripe_price_monthly = $price->id;
            }
            if ($data['price_cycle'] == 'year') {
                $package->package_gateway_stripe_price_yearly = $price->id;
            }
            $package->save();

            Log::info("updating package ($package->package_name) with new price ($price->id) at stripe - completed", ['process' => '[stripe-update-plan-price]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return true;

        } else {
            return false;
        }
    }

}
