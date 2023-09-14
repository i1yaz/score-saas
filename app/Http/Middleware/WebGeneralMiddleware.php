<?php

namespace App\Http\Middleware;

use App\Models\ParentUser;
use App\Models\Student;
use Closure;
use Illuminate\Http\Request;

class WebGeneralMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (\Auth::user()->hasRole('parent')){
            \Session::put('parent_table_id',ParentUser::where('user_id',\Auth::id())->first(['id'])->id??false);
        }
        if (\Auth::user()->hasRole('student')){
            \Session::put('student_table_id',Student::where('user_id',\Auth::id())->first(['id'])->id??false);
        }
        return $next($request);
    }
}
