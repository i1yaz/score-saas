<?php

namespace App\Repositories;

use App\Models\Session;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SessionRepository extends BaseRepository
{
    protected $fieldSearchable = [

    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Session::class;
    }

    public function create(array $input): Model
    {
        $input['auth_guard'] = Auth::guard()->name;
        $input['added_by'] = Auth::id();
        $model = $this->model->newInstance($input);
        $model->save();

        return $model;
    }

    public function getFullCalenderEvents($request): array
    {
        $start = $request->start;
        $end = $request->end;
        $sessions = Session::select([
            'sessions.scheduled_date as start',
            'sessions.scheduled_date as end',
            'sessions.id as id',
            'sessions.start_time',
            'sessions.end_time',
            'sessions.scheduled_date',
            'sessions.monthly_invoice_package_id',
            'sessions.student_tutoring_package_id',
        ])
            ->selectRaw("CONCAT(s1.first_name,' ',s1.last_name) as title")
            ->selectRaw("CONCAT(s2.first_name,' ',s2.last_name) as title_s2")
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', '=', 'sessions.student_tutoring_package_id')
            ->leftJoin('monthly_invoice_packages', 'monthly_invoice_packages.id', '=', 'sessions.monthly_invoice_package_id')
            ->leftJoin('students as s1', 's1.id', '=', 'student_tutoring_packages.student_id')
            ->leftJoin('students as s2', 's2.id', '=', 'monthly_invoice_packages.student_id')
            ->where('sessions.scheduled_date', '>=', $start)
            ->where('sessions.scheduled_date', '<=', $end);
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
            $sessions = $sessions->where('tutor_id', Auth::id());
        }

        $sessions = $sessions->get();
        $data = [];
        $i = 0;
        foreach ($sessions as $session) {
            $start_time = date('H:i', strtotime($session->start_time ?? ''));
            $start_Date = date('Y-m-d', strtotime($session->scheduled_date));
            $scheduleDateTime = Carbon::createFromFormat('Y-m-d H:i', $start_Date.' '.$start_time);
            $tickMark = '';
            if ($scheduleDateTime->isPast()) {
                $tickMark = '✓';
            }
            $start_time = date('H:i', strtotime($session->start_time ?? ''));
            $title = $session->title??$session->title_s2;
            $packagePrefix = $session->monthly_invoice_package_id ? 'M' : ($session->student_tutoring_package_id ? 'T' : '');
            $session['color'] = getHexColors($i);
            $session['allDay'] = true;
            $session['title'] = "{$packagePrefix} {$start_time} {$tickMark} {$title} ";
            $data[] = $session;
            $i++;
        }

        return $data;
    }
}
