<?php

namespace App\DataTables;

use App\Models\ParentUser;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TutorDataTable implements IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {

        $order = $columns[$order] ?? $order;
        $tutors = Tutor::query()->select(['id', 'email', 'first_name', 'last_name', 'status', 'status', 'phone', 'start_date']);
        $tutors = static::getModelQueryBySearch($search, $tutors);
        $tutors = $tutors->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        return $tutors->get();
    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $tutors = Tutor::query()->select(['id']);
        $tutors = static::getModelQueryBySearch($search, $tutors);

        return $tutors->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $tutor) {
                $nestedData['email'] = $tutor->email;
                $nestedData['first_name'] = $tutor->first_name;
                $nestedData['last_name'] = $tutor->last_name;
                $nestedData['phone'] = $tutor->phone;
                $nestedData['start_date'] = Carbon::parse($tutor->start_date)->toDateString();
                $nestedData['status'] = view('partials.status_badge', ['status' => $tutor->status, 'text_success' => 'Active', 'text_danger' => 'Inactive'])->render();
                $nestedData['action'] = view('tutors.actions', ['tutor' => $tutor])->render();
                $data[] = $nestedData;
            }
        }

        return $data;
    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        if (! empty($search)) {
            $records = $records->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
            $records = $records->where('id', Auth::id());
        }

        return $records;
    }

    public static function totalRecords(): int
    {
        $tutors = Tutor::query()->select(['id']);
        if (Auth::user()->hasRole('tutor') && Auth::user() instanceof ParentUser) {
            $tutors = $tutors->where('id', Auth::id());
        }

        return $tutors->count();
    }
}
