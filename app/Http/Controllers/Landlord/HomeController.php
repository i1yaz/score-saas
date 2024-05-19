<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = $this->topStats();

        $payload['income'] = $this->yearlyIncome([
            'period' => 'this_year',
        ]);
        return view('landlord.dashboard.home');
    }

}
