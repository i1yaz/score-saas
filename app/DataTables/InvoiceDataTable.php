<?php

namespace App\DataTables;

use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class InvoiceDataTable implements IDataTables
{
    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $columns = [
            'invoice_type' => 'invoiceable_type',
            'parent' => 'parent_email,parent_email_p2',
            'student' => 'student_email,student_email_s2',
            'created_at' => 'invoice_created_at'
        ];

        $order = $columns[$order] ?? $order;
        $invoices = Invoice::query()->select(
            [
                'invoices.id as invoice_id',
                'invoices.paid_status as invoice_status',
                'invoices.invoiceable_type',
                's1.email as student_email',
                's2.email as student_email_s2',
                'p1.email as parent_email',
                'p2.email as parent_email_p2',
                'invoices.created_at as invoice_created_at',
                'invoices.due_date',
                'invoices.fully_paid_at',
                'invoices.amount_paid',

            ])
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('monthly_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'monthly_invoice_packages.id')->where('invoices.invoiceable_type', '=', MonthlyInvoicePackage::class);
            })
            ->leftJoin('students as s1', 'student_tutoring_packages.student_id', '=', 's1.id')
            ->leftJoin('students as s2', 'monthly_invoice_packages.student_id', '=', 's2.id')
            ->leftJoin('parents as p1', 's1.parent_id', '=', 'p1.id')
            ->leftJoin('parents as p2', 's2.parent_id', '=', 'p2.id');
        $invoices = static::getModelQueryBySearch($search, $invoices);
        $invoices = $invoices->offset($start)
            ->limit($limit);
        $columns = explode(',', $order);
        foreach ($columns as $column){
            $invoices = $invoices->orderBy($column, $dir);
        }

        return $invoices->get();
    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $invoices = Invoice::query()->select(
            [
                'invoices.id as invoice_id',
            ])
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('students', 'student_tutoring_packages.student_id', '=', 'students.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id');
        $invoices = static::getModelQueryBySearch($search, $invoices);

        return $invoices->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        if (! empty($records)) {
            foreach ($records as $invoice) {
                $nestedData['invoice_id'] = getInvoiceCodeFromId($invoice->invoice_id);
                $nestedData['invoice_status'] = getInvoiceStatusFromId($invoice->invoice_status);
                $nestedData['invoice_type'] = getInvoiceTypeFromClass($invoice->invoiceable_type);
                $nestedData['student'] = $invoice->student_email??$invoice->student_email_s2;
                $nestedData['parent'] = $invoice->parent_email??$invoice->parent_email_p2;
                $nestedData['created_at'] = formatDate($invoice->invoice_created_at);
                $nestedData['due_date'] = formatDate($invoice->due_date);
                $nestedData['amount_paid'] = formatAmountWithCurrency($invoice->amount_paid);
                $nestedData['fully_paid_at'] = $invoice->fully_paid_at;
                $nestedData['action'] = view('invoices.actions', ['invoice' => $invoice])->render();
                $data[] = $nestedData;
            }
        }

        return $data;
    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {
        if (! empty($search)) {
            $records = $records->where(function ($q) use ($search) {
                $q->where('student_email', 'like', "%{$search}%")
                    ->orWhere('parent_email', 'like', "%{$search}%")
                    ->orWhere('invoice_id', 'like', "%{$search}%");
            });
        }

        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $records = $records->where('parent_id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $records = $records->where('student_id', Auth::id())
                ->WhereRaw('CASE WHEN students.parent_id IS NULL THEN true ELSE false END');
        }

        return $records;
    }

    public static function totalRecords(): int
    {

        $invoices = Invoice::query()->select(
            [
                'invoices.id as invoice_id',
            ])
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('students', 'student_tutoring_packages.student_id', '=', 'students.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id');

        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $invoices = $invoices->where('parent_id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $invoices = $invoices->where('student_id', Auth::id())
                ->WhereRaw('CASE WHEN students.parent_id IS NULL THEN true ELSE false END');
        }

        return $invoices->count();

    }
}
