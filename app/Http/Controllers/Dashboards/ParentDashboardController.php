<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\ListData;
use App\Models\Session;
use Illuminate\Support\Facades\Cache;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $listData = Cache::remember('list_data_session_completion_codes', 60 * 60 * 24, function () {
            return ListData::select(['id', 'name'])->where('list_id', Session::LIST_DATA_LIST_ID)->get();
        });
        $completionCodes = [];
        foreach ($listData as $data) {
            $completionCodes[$data->id] = $data->name;
        }

        return view('dashboards.parent.index', compact('completionCodes'));
    }
}
