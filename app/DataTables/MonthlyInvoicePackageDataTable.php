<?php

namespace App\DataTables;

use App\DataTables\IDataTables;
use App\Models\MonthlyInvoicePackage;
use App\Models\StudentTutoringPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MonthlyInvoicePackageDataTable implements IDataTables
{

    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $columns = [
            'package_id' => 'id',
            'total_sessions' => 'sessions_count',
        ];
        $order = $columns[$order] ?? $order;
        $monthlyInvoicePackage = MonthlyInvoicePackage::query()
            ->select([
                'monthly_invoice_packages.id',
                'monthly_invoice_packages.internal_notes as internal_notes',
                'monthly_invoice_packages.status as status',
                'monthly_invoice_packages.notes as notes',
                'monthly_invoice_packages.start_date as start_date',
                'students.email as student',
                'tutoring_locations.name as location'
            ])
            ->selectRaw('(SELECT COUNT(id) FROM sessions WHERE sessions.monthly_invoice_package_id = monthly_invoice_packages.id) as sessions_count')
            ->join('students', 'monthly_invoice_packages.student_id', 'students.id')
            ->join('tutoring_locations', 'monthly_invoice_packages.tutoring_location_id', 'tutoring_locations.id');
        $monthlyInvoicePackage = static::getModelQueryBySearch($search, $monthlyInvoicePackage);
        $monthlyInvoicePackage = $monthlyInvoicePackage->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        return $monthlyInvoicePackage->get();
    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $monthlyInvoicePackage = MonthlyInvoicePackage::query()->select(['id']);
        $monthlyInvoicePackage = static::getModelQueryBySearch($search, $monthlyInvoicePackage);

        return $monthlyInvoicePackage->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $monthlyInvoicePackage) {
                $nestedData['package_id'] = getMonthlyInvoicePackageCodeFromId($monthlyInvoicePackage->id);
                $nestedData['student'] = $monthlyInvoicePackage->student;
                $nestedData['notes'] = $monthlyInvoicePackage->notes;
                $nestedData['internal_notes'] = $monthlyInvoicePackage->internal_notes;
                $nestedData['start_date'] = Carbon::parse($monthlyInvoicePackage->start_date)->format('j F,Y');
                $nestedData['tutoring_location_id'] = $monthlyInvoicePackage->location;
                $nestedData['total_sessions'] = $monthlyInvoicePackage->sessions_count;
                $nestedData['status'] = view('partials.status_badge', ['status' => $monthlyInvoicePackage->status,'text_success' => 'Active','text_danger' => 'Inactive'])->render();
                $nestedData['action'] = view('monthly_invoice_packages.actions', ['monthlyInvoicePackage' => $monthlyInvoicePackage])->render();
                $data[] = $nestedData;

            }
        }

        return $data;
    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        if (! empty($search)) {
            $records = $records->where(function ($q) use ($search) {
                $q->where('monthly_invoice_packages.id', 'like', "%{$search}%")
                    ->orWhere('students.email', 'like', "%{$search}%");
            });
        }
        if (!(Auth::user()->hasRole(['super-admin']) && Auth::user() instanceof User)){
            $records = $records->where('monthly_invoice_packages.status', true);
        }
        return $records;
    }

    public static function totalRecords(): int
    {
        $students = MonthlyInvoicePackage::query()->select(['id']);

        return $students->count();
    }
}
