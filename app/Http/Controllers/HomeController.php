<?php

namespace App\Http\Controllers;

use App\Models\ParentUser;
use App\Models\School;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;

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
        $students = Student::query()->selectRaw(
            'SUM(CASE WHEN `status` = 1 THEN 1 ELSE 0 END) AS active_students,
                       SUM(CASE WHEN `status` = 0 THEN 1 ELSE 0 END) AS inactive_students'
        )->first();
        $parents = ParentUser::query()->selectRaw(
            'SUM(CASE WHEN `status` = 1 THEN 1 ELSE 0 END) AS active_parents,
                       SUM(CASE WHEN `status` = 0 THEN 1 ELSE 0 END) AS inactive_parents'
        )->first();
        $tutors = Tutor::query()->selectRaw(
            'SUM(CASE WHEN `status` = 1 THEN 1 ELSE 0 END) AS active_tutors,
                       SUM(CASE WHEN `status` = 0 THEN 1 ELSE 0 END) AS inactive_tutors'
        )->first();
        $schools = School::count();

        $data = [];
        $data['students'] = $students;
        $data['parents'] = $parents;
        $data['tutors'] = $tutors;
        $data['schools'] = $schools;

        return view('home', compact('data'));
    }
}
