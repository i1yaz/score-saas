<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Faq;
use App\Models\Landlord\Frontend;

class FaqController extends Controller
{
    public function index() {

        $mainmenu = Frontend::Where('group', 'main-menu')->orderBy('name', 'asc')->get();
        $faqs = Faq::OrderBy('position', 'ASC')->get();
        $cta = Frontend::Where('name', 'page-footer-cta')->first();
        $footer = Frontend::Where('name', 'page-footer')->first();
        $content = Frontend::Where('name', 'page-faq')->first();
        $payload = [
            'page' => $this->pageSettings('index'),
            'mainmenu' => $mainmenu,
            'faqs' => $faqs,
            'footer' => $footer,
            'show_footer_cta' => true,
            'content' => $content
        ];

        return view('frontend/faq/page', compact('payload', 'mainmenu', 'faqs', 'cta', 'footer', 'content'))->render();

    }

    private function pageSettings($section = '', $data = []) {
        return [

        ];
    }
}
