<?php

/** ---------------------------------------------------------------------------------------------------------------
 *
 * This middleware is a copy of the service provider (ConfigSystemServiceProvider) in the main Grow CRM
 *      - We have used middleware because service providers were not working well in multi-tenancy
 *      - all the logic has been added to a helper function, so that we can also call it in cronjobs (becase
 *            middleware is not executed in the cronbobs)
 *
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 * @revised    29 April 2023
 *----------------------------------------------------------------------------------------------------------------*/

namespace App\Http\Middleware\General;
use Closure;

class BootSystem {

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $this->setupDatabase();
        middlwareBootSystem();
        return $next($request);

    }
    public function setupDatabase(): void
    {
        config()->set('database.default', 'tenant');

    }

}
