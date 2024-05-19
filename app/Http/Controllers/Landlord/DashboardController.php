<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\AppBaseController;
use App\Http\Responses\Landlord\Home\IndexResponse;
use App\Models\Landlord\Payment;
use App\Models\Landlord\Tenant;
use Carbon\Carbon;

class DashboardController extends AppBaseController
{
    public function index()
    {
        $stats = $this->topStats();
        $income = $this->yearlyIncome([ 'period' => 'this_year']);
        return view('landlord.dashboard.home', compact('stats', 'income'));
    }

    private function topStats() {
        $stats = [];

        $today = Carbon::now()->format('Y-m-d');
        $this_year_start = Carbon::now()->startOfYear()->format('Y-m-d');
        $this_year_end = Carbon::now()->endOfYear()->format('Y-m-d');
        $this_month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this_month_end = Carbon::now()->endOfMonth()->format('Y-m-d');
        //income - today
        $stats['income_today'] = Payment::where('date', $today)->sum('amount');
        //income - this month
        $stats['income_this_month'] = Payment::where('date', '>=', $this_month_start)
            ->where('date', '<=', $this_month_end)
            ->sum('amount');
        //income - this year
        $stats['income_this_year'] = Payment::where('date', '>=', $this_year_start)
            ->where('date', '<=', $this_year_end)
            ->sum('amount');
        //count records
        $stats['count_customers'] = Tenant::count();
        return $stats;
    }

    public function yearlyIncome(array $params = []) {
        $year = Carbon::now()->format('Y');
        $stats = [
            'total' => 0,
            'monthly' => [],
            'year' => $year,
        ];
        for ($i = 1; $i <= 12; $i++) {
            $start_date = Carbon::create($year, $i)->startOfMonth()->format('Y-m-d');
            $end_date = Carbon::create($year, $i)->lastOfMonth()->format('Y-m-d');
            //Note::We can do that one query to get all the months income
            $amount = Payment::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->sum('amount');
            //get income for the month
            $stats['monthly'][] = $amount;
            //running total
            $stats['total'] += $amount;
        }

        return $stats;

    }
    private function pageSettings($section = '', $data = []) {

        return [
            'crumbs' => [
                __('lang.sales'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'meta_title' => __('lang.home'),
            'heading' => __('lang.home'),
            'page' => 'home',
            'mainmenu_home' => 'active',
        ];
    }

}
