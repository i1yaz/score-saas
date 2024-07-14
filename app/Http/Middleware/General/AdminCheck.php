<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [admin validation] precheck processes for general processes
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\General;

use Closure;
use Illuminate\Support\Facades\Auth;
use Log;


class AdminCheck {
    /**
     * Check if incoming request in from a user with the role 'administrator'
     * if not, show 403 error
     * @usage $this->middleware('adminCheck')->only(['create']);
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        //check if user is admin. If user is not, show errors
        if (!Auth::user()->hasRole(['super-admin','admin'])) {
            abort(403);
        }
        return $next($request);
    }
}
