<?php

namespace App\Http\Controllers\Landlord\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Landlord\Validator;
use App\Http\Responses\Landlord\Authentication\AuthenticateResponse;
use App\Mail\Landlord\Admin\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthenticateController extends  AppBaseController
{
    public function logIn() {
        Auth::logout();
        return view('landlord/auth/login');
    }

    public function forgotPassword() {
        return view('landlord/auth/forgot-password');
    }

    public function resetPassword() {

        //1 hour expiry
        $expiry = \Carbon\Carbon::now()->subHours(1);

        //validate code
        if (\App\Models\User::Where('forgot_password_token', request('token'))
            ->where('forgot_password_token_expiry', '>=', $expiry)
            ->doesntExist()) {
            //set flass session
            request()->session()->flash('error-notification-longer', __('lang.url_expired_or_invalid'));
            //redirect
            return redirect('forgotpassword');
        }

        //show login page
        return view('landlord/auth/resetpassword');
    }

    public function logInAction(Request $request) {
        //validate

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
            if (auth()->user()->status != 'active') {
                auth()->logout();
                abort(409, __('lang.account_has_been_suspended'));
            }
        } else {
            //login failed message
            abort(409, __('lang.invalid_login_details'));
        }


        return response()->redirectToRoute('landlord.home');
    }

    public function forgotPasswordAction() {

        //validation
        if (!$user = \App\Models\User::Where('email', request('email'))->first()) {
            abort(409, __('lang.account_not_found'));
        }

        $code = Str::random(50);

        //update user - set expiry to 3 Hrs
        $user->forgot_password_token = $code;
        $user->forgot_password_token_expiry = \Carbon\Carbon::now()->addHours(3);
        $user->save();

        /** ----------------------------------------------
         * send email [comment
         * ----------------------------------------------*/
        Mail::to($user->email)->send(new ForgotPassword($user));


        //set flash session
        request()->session()->flash('success-notification-longer', __('lang.password_reset_email_sent'));

        //back to login
        $jsondata['redirect_url'] = url('app-admin/login');
        return response()->json($jsondata);
    }


    public function resetPasswordAction() {

        //1 hour expiry
        $expiry = \Carbon\Carbon::now()->subHours(1);

        $messages = [];

        //validate code
        if (\App\Models\User::Where('forgot_password_token', request('token'))
            ->where('forgot_password_token_expiry', '>=', $expiry)
            ->doesntExist()) {
            //set flass session
            request()->session()->flash('error-notification-longer', __('lang.url_expired_or_invalid'));
            //back to login
            $jsondata['redirect_url'] = url('forgotpassword');
            //redirect
            return response()->json($jsondata);
        }

        //validate password match
        $validator = Validator::make(request()->all(), [
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        $user = \App\Models\User::Where('forgot_password_token', request('token'))->first();
        $user->password = Hash::make(request('password'));
        $user->forgot_password_token = '';
        $user->save();

        //set flass session
        request()->session()->flash('success-notification-longer', __('lang.password_reset_success'));
        //back to login
        $jsondata['redirect_url'] = url('app-admin/login');
        return response()->json($jsondata);
    }

    private function pageSettings($section = '', $data = []) {

        //Login
        if ($section == 'login') {
            $page = [
                'meta_title' => __('lang.login_to_you_account'),
            ];
        }

        //Signup
        if ($section == 'signup') {
            $page = [
                'meta_title' => __('lang.create_a_new_account'),
            ];
        }

        //Forgot Password
        if ($section == 'forgot-password') {
            $page = [
                'meta_title' => __('lang.forgot_password'),
            ];
        }
        //return
        return $page;
    }

}
