<?php

namespace App\DataTables;

use App\Models\Session;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class SessionDataTable implements IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {

        $order = $columns[$order] ?? $order;
        $sessions = Session::query()->select(
            [
                'sessions.id','sessions.start_time as start','sessions.tutoring_location_id' ,'sessions.end_time as end', 'sessions.id as id', 'sessions.start_time', 'sessions.end_time', 'sessions.scheduled_date',
                'tutoring_locations.name as location_name', 'students.first_name as student_first_name', 'students.last_name as student_last_name', 'students.email as student_email',
                'sessions.session_completion_code', 'sessions.home_work_completed',
                'list_data.name as completion_code','student_tutoring_packages.id as student_tutoring_package_id'
            ])
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', '=', 'sessions.student_tutoring_package_id')
            ->leftJoin('tutoring_locations', 'tutoring_locations.id', '=', 'sessions.tutoring_location_id')
            ->leftJoin('list_data', function ($join) {
                $join->on('list_data.id', '=', 'sessions.session_completion_code')
                    ->where('list_data.list_id', '=', Session::LIST_DATA_LIST_ID);
            })
            ->leftJoin('students', 'students.id', '=', 'student_tutoring_packages.student_id');
        $sessions = static::getModelQueryBySearch($search, $sessions);
        $sessions = $sessions->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        return $sessions->get();

    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $sessions = Session::query()->select(['id']);
        $sessions = static::getModelQueryBySearch($search, $sessions,true);

        return $sessions->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $session) {

                $date = date('m/d/Y', strtotime($session->scheduled_date ?? ''));
                $start = date('H:i', strtotime($session->start ?? ''));
                $end = date('H:i', strtotime($session->end ?? ''));
                $nestedData['id'] = getSessionCodeFromId($session->id);
                $nestedData['student_tutoring_package'] = getStudentTutoringPackageCodeFromId($session->student_tutoring_package_id);
                $nestedData['scheduled_date'] = "$date $start - $end";
                $nestedData['location'] = $session->location_name;
                $nestedData['student'] = $session->student_email;
                $nestedData['completion_code'] = $session->completion_code ?? '';
                $nestedData['homework_completed_80'] = view('partials.status_badge', ['status' => $session->home_work_completed, 'text_success' => 'Completed', 'text_danger' => 'Not Completed'])->render();
                $nestedData['action'] = view('sessions.actions', ['session' => $session])->render();
                $data[] = $nestedData;
            }
        }

        return $data;

    }

    public static function getModelQueryBySearch(mixed $search, Builder $records,$count=false): Builder
    {
        if (! empty($search) && !$count) {
            $records = $records->where(function ($q) use ($search) {
                $q->where('tutoring_locations.name', 'like', "%{$search}%")
                    ->orWhere('sessions.id', 'like', "%{$search}%")
                    ->orWhere('student_tutoring_package_id', 'like', "%{$search}%");
            });
        }
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
            $records = $records->where('sessions.tutor_id', Auth::id());
        }

        return $records;
    }

    public static function totalRecords(): int
    {
        $sessions = Session::query()->select(['id']);
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
            $sessions = $sessions->where('sessions.tutor_id', Auth::id());
        }

        return $sessions->count();
    }
}
