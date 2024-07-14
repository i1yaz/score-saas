<?php

namespace App\Http\Controllers\Landlord\Settings\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Responses\Landlord\Settings\Gateways\Stripe\ShowResponse;
use App\Models\Landlord\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{
    public function __construct(
    ) {
        parent::__construct();
        $this->middleware('auth');

    }
    public function show() {
        $settings = Setting::Where('id', 'default')->first();
        return view('landlord.settings.stripe_settings',compact('settings'));
    }

    public function update(Request $request) {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'stripe_secret_key' => 'required',
            'stripe_public_key' => 'required',
            'stripe_webhooks_key' => 'required',
            'stripe_display_name' => 'required',
        ], $messages);

        //errors
        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }


        try {
            \Stripe\Stripe::setApiKey($request->stripe_secret_key);
            $endpoints = \Stripe\WebhookEndpoint::all(['limit' => 1]);
        } catch (\Stripe\Exception\AuthenticationException$e) {
            abort(409, __('lang.stripe_authentication_error'));
        } catch (\Stripe\Exception\ApiConnectionException$e) {
            abort(409, __('lang.stripe_network_error'));
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            abort(409, __('lang.stripe_generic_error') . ' - ' . $error_message);
        }

        Setting::where('id', 'default')
            ->update([
                'stripe_secret_key' => $request->stripe_secret_key,
                'stripe_public_key' =>$request->stripe_public_key,
                'stripe_webhooks_key' => $request->stripe_webhooks_key,
                'stripe_display_name' =>$request->stripe_display_name,
                'stripe_status' => $request->stripe_status == 'on' ? 'enabled' : 'disabled',
            ]);

        //are we resetting or disabling the gateway
//        if (request('stripe_reset_plans') == 'on' || request('stripe_status') != 'on') {
//            //remove prices and plan id's
//            DB::table('packages')->update([
//                'package_gateway_stripe_product_monthly' => '',
//                'package_gateway_stripe_price_monthly' => '',
//                'package_gateway_stripe_product_yearly' => '',
//                'package_gateway_stripe_price_yearly' => '',
//            ]);
//
//            //remove the customers stripe user account (when resetting only)
//            if (request('stripe_reset_plans') == 'on') {
//                DB::table('tenants')->update([
//                    'tenant_stripe_customer_id' => '',
//                ]);
//            }
//        }

        return response()->json(['message' => 'Stripe settings updated successfully']);
    }
}
