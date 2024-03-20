<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Frontend;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index() {

        $mainmenu = Frontend::Where('frontend_group', 'main-menu')->orderBy('frontend_name', 'asc')->get();
        $section = Frontend::Where('frontend_name', 'page-login')->first();
        $payload = [
            'page' => $this->pageSettings('index'),
        ];

        return view('frontend/signin/page', compact('payload', 'mainmenu', 'section'))->render();

    }

    public function getAccount() {

        if (!request()->filled('domain_name')) {
            return response()->json(array(
                'notification' => [
                    'type' => 'error',
                    'value' => __('lang.account_could_not_be_found'),
                ],
                'skip_dom_reset' => true,
                'skip_dom_tinymce' => true,
            ));
        }

        if (!$tenant = \App\Models\Landlord\Tenant::On('landlord')->Where('subdomain', request('domain_name'))->first()) {
            return response()->json(array(
                'notification' => [
                    'type' => 'error',
                    'value' => __('lang.account_could_not_be_found'),
                ],
                'skip_dom_reset' => true,
                'skip_dom_tinymce' => true,
            ));
        }

        $jsondata['redirect_url'] = 'https://' . $tenant->domain;

        return response()->json($jsondata);
    }
    private function pageSettings($section = '', $data = []) {
        return [

        ];
    }
}
