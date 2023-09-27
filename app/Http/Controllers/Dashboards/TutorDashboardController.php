<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\AppBaseController;

class TutorDashboardController extends AppBaseController
{
    public function index()
    {
        return view('dashboards.tutors.index');
    }

}
