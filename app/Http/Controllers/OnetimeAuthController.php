<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnetimeAuthController extends Controller
{
    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The client instance.
     */
    protected $clientrepo;

    public function __construct() {
        //parent
        parent::__construct();
        //guest
        $this->middleware('guest');
    }
    public function OnetimeAuthentication() {

        Auth::logout();
        $settings = Setting::Where('id', 1)->first();
        $user = User::Where('id', 1)->first();
        if (request()->filled('id_key') && $settings->saas_onetime_login_key == request('id_key')) {
            Auth::login($user, true);

            // [awaiting-payment]
           if ($settings->saas_onetime_login_destination == 'payment') {
               //update db
               $settings->saas_onetime_login_key = null;
               $settings->saas_onetime_login_destination = null;
               $settings->save();
               return redirect('/');
           }

            //update db
            $settings->saas_onetime_login_key = null;
            $settings->saas_onetime_login_destination = null;
            $settings->save();
            return redirect('/');

        } else {
            //login
            return redirect('login');
        }
    }
}
