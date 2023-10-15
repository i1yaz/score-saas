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
        $columns = [
            'location' => 'location_name',
            'tutoring_package' => 'student_tutoring_package_id,monthly_invoice_package_id',
            'scheduled_date' => 'scheduled_date',
            'tutor' => 'tutor_email',
            'student' => 'student_email,student_email_s2',
            'completion_code' => 'completion_code',
            'homework_completed_80' => 'home_work_completed',
        ];
        $order = $columns[$order] ?? $order;
        $sessions = Session::query()->select(
            [
                'sessions.id','sessions.start_time as start',
                'sessions.tutoring_location_id' ,
                'sessions.end_time as end',
                'sessions.id as id',
                'sessions.start_time',
                'sessions.end_time',
                'sessions.scheduled_date',
                'sessions.session_completion_code',
                'sessions.home_work_completed',
                'tutoring_locations.name as location_name',
                's1.email as student_email',
                's2.email as student_email_s2',
                'list_data.name as completion_code',
                'student_tutoring_packages.id as student_tutoring_package_id',
                'monthly_invoice_packages.id as monthly_invoice_package_id',
                'tutors.email as tutor_email'
            ])
            ->leftJoin('student_tutoring_packages', 'student_tutoring_packages.id', '=', 'sessions.student_tutoring_package_id')
            ->leftJoin('monthly_invoice_packages', 'monthly_invoice_packages.id', '=', 'sessions.monthly_invoice_package_id')
            ->leftJoin('tutoring_locations', 'tutoring_locations.id', '=', 'sessions.tutoring_location_id')
            ->leftJoin('list_data', function ($join) {
                $join->on('list_data.id', '=', 'sessions.session_completion_code')
                    ->where('list_data.list_id', '=', Session::LIST_DATA_LIST_ID);
            })
            ->leftJoin('tutors', 'tutors.id', '=', 'sessions.tutor_id')
            ->leftJoin('students as s1', 's1.id', '=', 'student_tutoring_packages.student_id')
            ->leftJoin('students as s2', 's2.id', '=', 'monthly_invoice_packages.student_id');
        $sessions = static::getModelQueryBySearch($search, $sessions);
        $sessions = $sessions->offset($start)
            ->limit($limit);
        $columns = explode(',', $order);
        foreach ($columns as $column){
            $sessions = $sessions->orderBy($column, $dir);
        }

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
                $nestedData['tutoring_package'] = getPackageCodeFromId($session->student_tutoring_package_id,$session->monthly_invoice_package_id);
                $nestedData['scheduled_date'] = "$date $start - $end";
                $nestedData['location'] = $session->location_name;
                $nestedData['tutor'] = $session->tutor_email;
                $nestedData['student'] = $session->student_email??$session->student_email_s2;
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
