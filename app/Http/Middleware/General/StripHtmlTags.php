<?php

namespace App\Http\Middleware\General;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StripHtmlTags {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (config('app.env') == 'staging' && !Str::contains(request()->url(), ['webhook','list-routes'])){
            $AUTH_USER = 'tutorcentrum';
            $AUTH_PASS = 'TutorcenTrum@123';
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
            $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
            $is_not_authenticated = (
                !$has_supplied_credentials ||
                $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
                $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
            );
            if ($is_not_authenticated) {
                header('HTTP/1.1 401 Authorization Required');
                header('WWW-Authenticate: Basic realm="Access denied"');
                exit;
            }
        }

        //skip for specified 'named routes';
        if (in_array($request->route()->getName(), $this->excludedRoutes())) {
            return $next($request);
        }

        //skip for specified 'url patterns';
        if (in_array(request()->segment(1), $this->excludedURLs())) {
            return $next($request);
        }

        // Check if the request is a POST or PUT request
        if ($request->isMethod('post') || $request->isMethod('put')) {
            // Loop through each input field
            foreach ($request->input() as $key => $value) {
                // only do this for non-array fields (otherwise it will break many things like assigning tasks to users)
                if (!is_array($value)) {
                    // skip whitelisted fields and fields with names that start with 'html_'
                    if (!in_array($key, $this->whitelist()) && !Str::startsWith($key, 'html_')) {
                        $request->merge([$key => strip_tags($value)]);
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Array of field names to whitelist
     *
     */
    private function whitelist() {

        return [
            'offline_payments_details',
            'body',
            'code_head',
            'code_body',
            'frontend_data_3',
            'html_frontend_data_3',
            'html_frontend_data_2',
            'html_frontend_data_5',
            'doc_body',
            'checklist_text',
            'comment_text',
            'lead_mynotes',
            'lead_description',
            'message_text',
            'note_description',
            'payment_notes',
            'reminder_notes',
            'email_body',
            'blog_text',
            'foo_description',
            'code_head',
            'code_body',
            'faq_content',
            'frontend_data_3',
            'code_meta_description',
            'offline_payments_details',
        ];
    }

    /**
     * Array of named routes to whitelist
     *
     */
    private function excludedRoutes() {

        return [

            'webhooks/stripe',
            'webhooks/paypal',
            'webhooks/mollie',
            'webhooks/razorpay',
            'webhooks/paystack',
        ];

    }

    /**
     * Array of excluded url's based on teh first segment
     * e.g. http://yourdomain.com/webhooks/foo/bar
     *
     */
    private function excludedURLs() {

        return [
            'webhooks',
            'api',
            'thankyou',
        ];

    }

}
