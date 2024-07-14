<?php

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Schedule;
use App\Models\Landlord\Setting;
use App\Models\Landlord\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GatewayController extends Controller
{
    public function __construct(
    ) {
        parent::__construct();
        $this->middleware('auth');

    }
    public function show() {

        $settings = Setting::Where('id', 'default')->first();
        return view('landlord.settings.gateway_settings',compact('settings'));

    }

    public function update(Request $request) {

        //custom error messages
        $messages = [
            'gateways_default_product_name.required' => __('lang.default_product_name') . ' - ' . __('lang.is_required'),
            'gateways_default_product_description.required' => __('lang.default_product_description') . ' - ' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'gateways_default_product_name' => [
                'required',
            ],
            'gateways_default_product_description' => [
                'required',
            ],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        //get settings
        $settings = Setting::Where('id', 'default')->first();

        //schedule for updating at the [product name] at various payment gateways
        if ($settings->gateways_default_product_name != $request->gateways_default_product_name || $settings->gateways_default_product_description != $request->gateways_default_product_description) {

            //delete previously scheduled changes
            Schedule::where('type', 'update-default-product-name')
                ->Where('status', 'new')
                ->delete();

            //schedule for cronjob
            $schedule = new Schedule();
            $schedule->gateway = 'all';
            $schedule->type = 'update-default-product-name';
            $schedule->payload_1 = $request->gateways_default_product_name;
            $schedule->save();
        }

        //update the settings
        Setting::where('id', 'default')
            ->update([
                'gateways_default_product_name' => $request->gateways_default_product_name,
                'gateways_default_product_description' => $request->gateways_default_product_description,
            ]);
        return response()->json(['message' => __('General Gateways settings updated successfully')]);
    }

}
