<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ACL
{
    public function handle(Request $request, Closure $next)
    {
        if (in_array(Auth::id(), User::CAN_ACCESS_ACL)){
            return $next($request);
        }
        return false;
    }
}
