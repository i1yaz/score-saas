<?php

namespace App\Http\Controllers;

use App\Models\ParentUser;
use App\Models\School;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return mixed
     */
    public function index()
    {
        if (Auth::user()->hasRole('tutor')) {
            return redirect()->route('tutor-dashboard.index');
        }
        if (Auth::user()->hasRole('student')) {
            return redirect()->route('student-dashboard.index');
        }
        if (Auth::user()->hasRole('parent')) {
            return redirect()->route('parent-dashboard.index');
        }
        if (Auth::user()->hasRole('client')) {
            return redirect()->route('client-dashboard.index');
        }

        $students = Cache::rememberForever('students_count', function () {
            return Student::query()
                ->selectRaw('SUM(CASE WHEN `status` = 1 THEN 1 ELSE 0 END) AS active_students,
                     SUM(CASE WHEN `status` = 0 THEN 1 ELSE 0 END) AS inactive_students')
                ->first();
        });
        $parents = Cache::rememberForever('parent_count', function () {
            return ParentUser::query()->selectRaw(
                'SUM(CASE WHEN `status` = 1 THEN 1 ELSE 0 END) AS active_parents,
                           SUM(CASE WHEN `status` = 0 THEN 1 ELSE 0 END) AS inactive_parents'
            )->first();
        });
        $tutors = Cache::rememberForever('tutor_count', function () {
            return Tutor::query()->selectRaw(
                'SUM(CASE WHEN `status` = 1 THEN 1 ELSE 0 END) AS active_tutors,
                       SUM(CASE WHEN `status` = 0 THEN 1 ELSE 0 END) AS inactive_tutors'
            )->first();
        });
        $schools = Cache::rememberForever('school_count', function () {
            return School::active()->count();
        });
        $data = [];
        $data['students'] = $students;
        $data['parents'] = $parents;
        $data['tutors'] = $tutors;
        $data['schools'] = $schools;
        $data['lastThirtyDays'] = getLastThirtyDays();
        $data['sessionsCountOfThirtyDays'] = getLastThirtyDaySessionCountDateWise();
        $data['sessionsThisWeek'] = array_sum($data['sessionsCountOfThirtyDays']);
        $data['twelveMonthsName'] = getTwelveMonthsName();
        $data['oneYearEarnings'] = getOneYearEarning();
        $data['thisMonthEarning'] = $data['oneYearEarnings'][11];

        return view('dashboards.super-admin.home', compact('data'));
    }
}
