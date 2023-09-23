<?php

namespace App\DataTables;

use App\Models\ParentUser;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ParentsDataTable implements IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $columns = [
            'family_code' => 'id',
        ];
        $order = $columns[$order] ?? $order;
        $parents = ParentUser::query()->select(['id', 'email', 'first_name', 'last_name', 'status', 'phone', 'created_at']);
        $parents = static::getModelQueryBySearch($search, $parents);
        $parents = $parents->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        return $parents->get();

    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $students = ParentUser::query()->select(['id']);
        $students = static::getModelQueryBySearch($search, $students);

        return $students->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $parent) {
                $nestedData['family_code'] = $parent->family_code;
                $nestedData['email'] = $parent->email;
                $nestedData['first_name'] = $parent->first_name;
                $nestedData['last_name'] = $parent->last_name;
                $nestedData['status'] = view('partials.status_badge', ['status' => $parent->status, 'text_success' => 'Active', 'text_danger' => 'Inactive'])->render();
                $nestedData['phone'] = $parent->phone;
                $nestedData['action'] = view('parents.actions', ['parent' => $parent])->render();
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

        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $records = $records->where('id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $records = $records->where('id', Auth::user()->parent_id);
        }

        return $records;
    }

    public static function totalRecords(): int
    {
        $students = ParentUser::query()->select(['id']);
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $students = $students->where('id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $students = $students->where('id', Auth::user()->parent_id);
        }

        return $students->count();
    }
}
