<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Frontend;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        //menus
        $mainmenu = Frontend::Where('group', 'main-menu')->orderBy('name', 'asc')->get();

        //get section
        $hero = Frontend::Where('name', 'hero-header')->first();

        //section 1
        $section1_title = Frontend::Where('name', 'section-1-title')->first();
        $section1_content = Frontend::Where('group', 'section-1')->get();

        //sections
        for ($i = 2; $i <= 4; $i++) {
            $section = "section$i";
            $$section = Frontend::Where('name', "section-$i")->first();
        }

        //splash
        $splash_title = Frontend::Where('name', 'section-splash-title')->first();
        for ($i = 1; $i <= 6; $i++) {
            $splash = "splash$i";
            $$splash = Frontend::Where('name', "section-splash-$i")->first();
        }

        //section 5
        $section5_title = Frontend::Where('name', 'section-5-title')->first();
        $section5_content = Frontend::Where('group', 'section-5')->get();

        //footer
        $footer = Frontend::Where('name', 'page-footer')->first();

        //response payload
        $payload = [
            'page' => $this->pageSettings('index'),
            'mainmenu' => $mainmenu,
            'hero' => $hero,
            'section1_title' => $section1_title,
            'section1_content' => $section1_content,
            'section2' => $section2,
            'section3' => $section3,
            'section4' => $section4,
            'section5_title' => $section5_title,
            'section5_content' => $section5_content,
            'splash_title' => $splash_title,
            'splash1' => $splash1,
            'splash2' => $splash2,
            'splash3' => $splash3,
            'splash4' => $splash4,
            'splash5' => $splash5,
            'splash6' => $splash6,
        ];

        return view('frontend/home/page', compact('payload', 'mainmenu', 'footer'))->render();
    }
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [

        ];

        //return
        return $page;
    }
}
