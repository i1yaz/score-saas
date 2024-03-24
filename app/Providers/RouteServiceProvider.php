<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        if (App::environment(['local'])) {
            Route::middleware('web')
                ->group(base_path('routes/infyome.php'));
        }


        $this->mapApiRoutes();

        if ($this->isFrontendRequest()) {
            $this->mapFrontendRoutes();
        }

        if ($this->isLandlordRequest()) {
            $this->mapLandlordRoutes();
        }

        $this->mapTenantRoutes();

    }


    /**
     * [NEXTLOOP]
     * Define the "tenant" routes for the application.
     *
     * @return void
     */
    protected function mapTenantRoutes(): void
    {
        Route::middleware('tenant')
            ->namespace($this->namespace)
            ->group(
                function ($router) {
                    require base_path('routes/web.php');
                    require base_path('routes/acl.php');
                    require base_path('routes/dashboards/tutor.php');
                    require base_path('routes/dashboards/student.php');
                    require base_path('routes/dashboards/parent.php');
                    require base_path('routes/dashboards/client.php');
                }
            );
    }


    /**
     * //[MT]
     *
     * Define the "landlord" routes for the application.
     *
     * @return void
     */
    protected function mapLandlordRoutes(): void
    {
        Route::middleware('landlord')
            ->namespace($this->namespace)
            ->group(
                function ($router) {
                    require base_path('routes/landlord/web.php');
                }
            );
    }

    /**
     * //[MT]
     *
     * Define the "landlord" routes for the application.
     *
     * @return void
     */
    protected function mapFrontendRoutes(): void
    {
        Route::middleware('frontend')
            ->namespace($this->namespace)
            ->group(
                function ($router) {
                    require base_path('routes/frontend/web.php');
                }
            );
    }


    /**
     * //[MT]
     * Define the "landlord" routes for the application. To do this, we do the following checks
     *
     *  (1) Check if the domain name of the current request matches the LANDLORD_DOMAIN in .env file
     *  (2) check if the current url contains '/app-admin'
     *
     * @return bool
     */
    private function isLandlordRequest() {

        if (config('system.landlord-domain') == '') {
            return true;
        }

        if (strpos($this->app->request->url(), '/app-admin') === false) {
            return false;
        }

        $domains_list = explode(',', preg_replace('/\s+/', '', config('system.landlord-domain')));

        $host = str_replace('www.', '', $this->app->request->getHost());

        if (in_array($host, $domains_list)) {
            return true;
        }

        return false;
    }

    /**
     * //[MT]
     * Define the "frontend" routes for the application. To do this, we do the following checks
     *
     *  (1) Check if the domain name of the current request matches the FRONTEND_DOMAIN in .env file
     *  (2) check if the current url contains '/app-admin'
     *
     * @return bool
     */
    private function isFrontendRequest(): bool
    {
        if (config('system.frontend-domain') == '') {
            return true;
        }

        if (str_contains($this->app->request->url(), '/app-admin')) {
            return false;
        }

        $host = str_replace('www.', '', $this->app->request->getHost());

        if (str_contains(trim(strtolower(config('system.frontend-domain'))),$host)) {
            return true;
        }

        return false;
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }
}
