<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnUrl
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }
        $url = $request->url();
        if (str_contains($url, '/app-admin') && !str_contains($url, '/app-admin/login')) {
            return redirect()->route('landlord.login');
        }

        return $next($request);
    }
}
