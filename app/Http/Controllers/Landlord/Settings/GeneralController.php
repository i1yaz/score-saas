<?php

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Landlord\Settings\GeneralRequest;
use App\Models\Landlord\Setting;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function __construct(
    ) {
        parent::__construct();
        $this->middleware('auth');

    }

    public function show() {
        $settings = Setting::Where('id', 'default')->first();
        return view('landlord.settings.general', compact('settings'));
    }

    public function update(GeneralRequest $request) {
        Setting::where('id', 'default')
            ->update([
                'system_date_format' => $request->system_date_format,
                'system_renewal_grace_period' => $request->system_renewal_grace_period,
            ]);
        return response()->json(['message' => __('General Settings updated successfully')]);
    }

}
