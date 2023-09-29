<?php

namespace App\DataTables;

use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SessionDataTable implements IDataTables
{

    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {

        $order = $columns[$order] ?? $order;
        $sessions = Session::query()->select(
            [
                'sessions.start_time as start','sessions.end_time as end','sessions.id as id','sessions.start_time','sessions.end_time','sessions.scheduled_date',
                'tutoring_locations.name as location_name','students.first_name as student_first_name','students.last_name as student_last_name','students.email as student_email',
                'sessions.session_completion_code','sessions.home_work_completed'
            ])
            ->leftJoin('student_tutoring_packages','student_tutoring_packages.id','=','sessions.student_tutoring_package_id')
            ->leftJoin('tutoring_locations','tutoring_locations.id','=','sessions.tutoring_location_id')
            ->leftJoin('students','students.id','=','student_tutoring_packages.student_id');
        $sessions = static::getModelQueryBySearch($search, $sessions);
        $sessions = $sessions->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        return $sessions->get();

    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $sessions = Session::query()->select(['id']);
        $sessions = static::getModelQueryBySearch($search, $sessions);

        return $sessions->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $session) {

                $date = date('m/d/Y',strtotime($session->scheduled_date??''));
                $start = date('H:i',strtotime($session->start??''));
                $end = date('H:i',strtotime($session->end??''));
                $nestedData['scheduled_date'] = "$date $start - $end";
                $nestedData['location'] = $session->location_name;
                $nestedData['student'] = $session->student_email;
                $nestedData['completion_code'] = Session::SESSION_COMPLETION_CODE[$session->session_completion_code??''];
                $nestedData['homework_completed_80'] = view('partials.status_badge', ['status' => $session->home_work_completed, 'text_success' => 'Yes', 'text_danger' => 'No'])->render();
                $nestedData['action'] = view('sessions.actions', ['session' => $session])->render();
                $data[] = $nestedData;
            }
        }

        return $data;

    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        if (! empty($search)) {
            $records = $records->where(function ($q) use ($search) {
                $q->where('tutoring_locations.name', 'like', "%{$search}%")
                    ->orWhere('students.first_name.', 'like', "%{$search}%")
                    ->orWhere('students.last_name.', 'like', "%{$search}%")
                    ->orWhere('students.email', 'like', "%{$search}%");
            });
        }
//        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
//            $records = $records->where('parent_id', Auth::id());
//        }
//        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
//            $records = $records->where('id', Auth::id());
//        }

        return $records;
    }

    public static function totalRecords(): int
    {
        $sessions = Session::query()->select(['id']);
//        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
//            $students = $students->where('parent_id', Auth::id());
//        }
//        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
//            $students = $students->where('id', Auth::id());
//        }

        return $sessions->count();
    }
}
