<?php

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Landlord\Settings\CompanyDetailRequest;
use App\Models\Landlord\Setting;

class CompanyDetailController extends Controller
{
    public  function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function show() {

        $settings = Setting::Where('id', 'default')->first();
        return view('landlord.settings.company_details', compact('settings'));
    }

    public function update(CompanyDetailRequest $request) {

        Setting::where('id', 'default')
            ->update([
                'company_name' => $request->company_name,
                'company_address_line_1' => $request->company_address_line_1,
                'company_city' => $request->company_city,
                'company_state' => $request->company_state,
                'company_zipcode' => $request->company_zipcode,
                'company_country' => $request->company_country,
                'company_telephone' => $request->company_telephone,
            ]);
        return response()->json(['message' => __('Company Details updated successfully')]);

    }
}
