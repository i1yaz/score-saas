<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Frontend;
use App\Models\Landlord\Package;

class PricingController extends Controller
{
    public function index() {

        $mainmenu = Frontend::Where('group', 'main-menu')->orderBy('name', 'asc')->get();
        $footer = Frontend::Where('name', 'page-footer')->first();

        $packages = Package::Where('visibility', 'visible')
            ->Where('status', 'active')
            ->orderBy('amount_monthly', 'asc')->get();
        $content = Frontend::Where('name', 'page-pricing')->first();
        $cta = Frontend::Where('name', 'page-footer-cta')->first();
        $payload = [
            'show_footer_cta' => true,
            'cta' => $cta,
        ];

        return view('frontend/pricing/page', compact('payload', 'packages', 'mainmenu', 'content', 'footer', 'cta'))->render();

    }

    private function pageSettings($section = '', $data = []) {

        return [

        ];
    }
}
