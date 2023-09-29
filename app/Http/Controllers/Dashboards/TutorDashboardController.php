<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\AppBaseController;
use App\Models\Session;

class TutorDashboardController extends AppBaseController
{
    public function index()
    {
        $completionCodes = Session::SESSION_COMPLETION_CODE;
        return view('dashboards.tutors.index', compact('completionCodes'));
    }

}
