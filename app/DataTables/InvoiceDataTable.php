<?php

namespace App\DataTables;

use App\DataTables\IDataTables;
use App\Models\Invoice;
use App\Models\StudentTutoringPackage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class InvoiceDataTable implements IDataTables
{

    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $order = $columns[$order] ?? $order;
        $invoices = Invoice::query()->select(
            [
                'invoices.id', 'invoices.paid_status', 'invoices.invoiceable_type','student_tutoring_packages.student_id',
            ])
            ->leftJoin('student_tutoring_packages', function ($q){
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('students', 'student_tutoring_packages.student_id', '=', 'students.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id')
        ;
    }

    public static function totalFilteredRecords(mixed $search): int
    {
        // TODO: Implement totalFilteredRecords() method.
    }

    public static function populateRecords($records): array
    {
        // TODO: Implement populateRecords() method.
    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        // TODO: Implement getModelQueryBySearch() method.
    }

    public static function totalRecords(): int
    {
        return Invoice::all()->count();
    }
}
