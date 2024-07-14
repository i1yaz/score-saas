<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Frontend;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index() {
        $mainmenu = Frontend::Where('group', 'main-menu')->orderBy('name', 'asc')->get();
        $content = Frontend::Where('name', 'page-contact')->first();
        $footer = Frontend::Where('name', 'page-footer')->first();
        $cta = Frontend::Where('name', 'page-footer-cta')->first();
        $payload = [
            'page' => $this->pageSettings('index'),
            'mainmenu' => $mainmenu,
            'show_footer_cta' => true,
            'cta' => $cta,
        ];

        return view('frontend/contact/page', compact('payload', 'mainmenu', 'content', 'footer', 'cta'))->render();

    }

    public function submitForm() {

        $messages = [
            'contact_name.required' => __('lang.name') . '-' . __('lang.is_required'),
            'contact_email.required' => __('lang.email') . '-' . __('lang.is_required'),
            'contact_message.required' => __('lang.message') . '-' . __('lang.is_required'),
        ];

        $validator = Validator::make(request()->all(), [
            'contact_name' => [
                'required',
            ],
            'contact_email' => [
                'required',
            ],
            'contact_message' => [
                'required',
            ],
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }
            abort(409, $messages);
        }

        /** ----------------------------------------------
         * send email to user
         * ----------------------------------------------*/
        $data = [
            'contact_name' => request('contact_name'),
            'contact_email' => request('contact_email'),
            'message' => request('contact_message'),
        ];
        if ($admins = \App\Models\User::On('landlord')->Where('type', 'admin')->get()) {
            foreach ($admins as $user) {
                $mail = new \App\Mail\Landlord\Admin\ContactUs($user, $data, []);
                $mail->build();
            }
        }

        $jsondata['dom_visibility'][] = [
            'selector' => '#contact-us-form',
            'action' => 'hide',
        ];
        $jsondata['dom_visibility'][] = [
            'selector' => '#contact-us-thank-you',
            'action' => 'show',
        ];

        //ajax response
        return response()->json($jsondata);

    }

    private function pageSettings($section = '', $data = []) {

        return [

        ];
    }
}
