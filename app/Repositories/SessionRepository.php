<?php

namespace App\Repositories;

use App\Models\Session;
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
        $sessions = Session::select(['sessions.scheduled_date as start','sessions.scheduled_date as end','sessions.id as id','sessions.start_time','sessions.end_time','sessions.scheduled_date'])
            ->selectRaw("CONCAT(students.first_name,' ',students.last_name) as title")
            ->join('student_tutoring_packages','student_tutoring_packages.id','=','sessions.student_tutoring_package_id')
            ->join('students','students.id','=','student_tutoring_packages.student_id')
            ->where('sessions.scheduled_date','>=',$start)
            ->where('sessions.scheduled_date','<=',$end)
            ->get();
        $data = [];
        $i = 0;
        foreach ($sessions as $session){
            $start_time = date('H:i',strtotime($session->start_time??''));
            $start_Date = date('Y-m-d',strtotime($session->scheduled_date));
            $scheduleDateTime = Carbon::createFromFormat('Y-m-d H:i', $start_Date.' '.$start_time);
            $tickMark = '';
            if ($scheduleDateTime->isPast())
            {
                $tickMark = "✓";
            }
            $start_time =  date('H:i',strtotime($session->start_time??''));
            $title = $session->title;
            $session['color'] = getHexColors($i);
            $session['allDay'] = true;
            $session['title'] = "{$start_time} {$tickMark} {$title} ";
            $data[] = $session;
            $i++;
        }
        return $data;
    }
}
