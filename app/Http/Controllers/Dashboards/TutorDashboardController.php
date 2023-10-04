<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\AppBaseController;
use App\Models\ListData;
use App\Models\Session;
use Illuminate\Support\Facades\Cache;

class TutorDashboardController extends AppBaseController
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

        return view('dashboards.tutors.index', compact('completionCodes'));
    }
}
