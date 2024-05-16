<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = $this->topStats();

        //[income][yearly]
        $payload['income'] = $this->yearlyIncome([
            'period' => 'this_year',
        ]);
        return view('landlord.home');
    }

    private function topStats() {
        $stats = [];

        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $this_year_start = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $this_year_end = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d');
        $this_month_start = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
        $this_month_end = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');

        //income - today
        $stats['income_today'] = \App\Models\Landlord\Payment::where('payment_date', $today)->sum('payment_amount');

        //income - this month
        $stats['income_this_month'] = \App\Models\Landlord\Payment::where('payment_date', '>=', $this_month_start)
            ->where('payment_date', '<=', $this_month_end)
            ->sum('payment_amount');

        //income - this year
        $stats['income_this_year'] = \App\Models\Landlord\Payment::where('payment_date', '>=', $this_year_start)
            ->where('payment_date', '<=', $this_year_end)
            ->sum('payment_amount');

        //count records
        $stats['count_customers'] = \App\Models\Landlord\Tenant::count();

        //return
        return $stats;
    }
}
