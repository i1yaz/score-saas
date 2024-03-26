<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\AppBaseController;
use App\Http\Responses\Landlord\Home\IndexResponse;

class DashboardController extends AppBaseController
{
    public function index() {

        $stats = $this->topStats();

        $payload['income'] = $this->yearlyIncome([
            'period' => 'this_year',
        ]);

        $payload = [
            'page' => $this->pageSettings('index'),
            'stats' => $stats,
            'income' => $this->yearlyIncome(),
        ];

        return new IndexResponse($payload);
    }

    public function topStats() {

        //vars
        $stats = [];

        //dates
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $this_year_start = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $this_year_end = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d');
        $this_month_start = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
        $this_month_end = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');

        //income - today
        $stats['income_today'] = 1000;

        //income - this month
        $stats['income_this_month'] = 800*30;

        //income - this year
        $stats['income_this_year'] = 1100*365;

        //count records
        $stats['count_customers'] =50;

        //return
        return $stats;
    }

    public function yearlyIncome() {

        $year = \Carbon\Carbon::now()->format('Y');

        //vars
        $stats = [
            'total' => 0,
            'monthly' => [],
            'year' => $year,
        ];

        //every month of the year
        for ($i = 1; $i <= 12; $i++) {
            //amount
            $start_date = \Carbon\Carbon::create($year, $i)->startOfMonth()->format('Y-m-d');
            $end_date = \Carbon\Carbon::create($year, $i)->lastOfMonth()->format('Y-m-d');

            //amount
            $amount = 800;

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
