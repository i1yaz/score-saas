<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use Carbon\Carbon;

class ProctorDashboardController extends Controller
{
    public function index()
    {
//        $currentMonth = now()->month;
//        $currentYear = now()->year;
//        $mockTests = MockTest::whereYear('date', $currentYear)
//            ->whereMonth('date', $currentMonth)
//            ->get();
        return view('dashboards.proctor.index');
    }
}
