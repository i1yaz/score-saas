<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function stripeSettings()
    {
        $settings =  Setting::select(['stripe_secret_key','stripe_public_key','stripe_webhooks_key','stripe_status'])->where('id', 1)->orWhere('id', 'default')->first();
        return view('settings.stripe_settings', compact('settings'));
    }

    public function stripeSettingsUpdate(Request $request)
    {
        Setting::where('id', 1)->update([
            'stripe_secret_key' => $request->stripe_secret_key,
            'stripe_public_key' => $request->stripe_public_key,
            'stripe_webhooks_key' => $request->stripe_webhooks_key,
            'stripe_status' => $request->stripe_status=='on' ? 'enabled' : 'disabled',
        ]);
        return response()->json(['message' => 'Stripe settings updated successfully']);
    }
}
