<?php

namespace App\DataTables;

use App\DataTables\IDataTables;
use App\Models\Client;
use App\Models\MonthlyInvoicePackage;
use App\Models\NonInvoicePackage;
use App\Models\ParentUser;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PaymentsDataTable implements IDataTables
{

    public static function sortAndFilterRecords(mixed $search, mixed $start, mixed $limit, string $order, mixed $dir): Collection|array
    {
        $columns = [
            'invoice_code' => 'invoices.id' ,
        ];
        $order = $columns[$order] ?? $order;
        $payments = Payment::query()
            ->select(
                [
                    'invoices.id as invoice_id',
                    'invoices.invoiceable_id',
                    'invoices.invoiceable_type',
                    'payments.amount',
                    'payments.created_at'
                ])
            ->join('invoices', 'invoices.id', 'payments.invoice_id')
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')
                    ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('monthly_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'monthly_invoice_packages.id')
                    ->where('invoices.invoiceable_type', '=', MonthlyInvoicePackage::class);
            })
            ->leftJoin('non_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'non_invoice_packages.id')
                    ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->leftJoin('students as s1', 'student_tutoring_packages.student_id', '=', 's1.id')
            ->leftJoin('students as s2', 'monthly_invoice_packages.student_id', '=', 's2.id')
            ->leftJoin('parents as p1', 's1.parent_id', '=', 'p1.id')
            ->leftJoin('clients', 'non_invoice_packages.client_id', '=', 'clients.id')
            ->leftJoin('parents as p2', 's2.parent_id', '=', 'p2.id');

        $payments = static::getModelQueryBySearch($search, $payments);
        $payments = $payments->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);
        return $payments->get();
    }

    public static function totalFilteredRecords(mixed $search): int
    {
        $payments = Payment::query()
            ->select(
                [
                    'invoices.id as invoice_id',
                ])
            ->join('invoices', 'invoices.id', 'payments.invoice_id')
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')
                    ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('monthly_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'monthly_invoice_packages.id')
                    ->where('invoices.invoiceable_type', '=', MonthlyInvoicePackage::class);
            })
            ->leftJoin('non_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'non_invoice_packages.id')
                    ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->leftJoin('students as s1', 'student_tutoring_packages.student_id', '=', 's1.id')
            ->leftJoin('students as s2', 'monthly_invoice_packages.student_id', '=', 's2.id')
            ->leftJoin('parents as p1', 's1.parent_id', '=', 'p1.id')
            ->leftJoin('clients', 'non_invoice_packages.client_id', '=', 'clients.id')
            ->leftJoin('parents as p2', 's2.parent_id', '=', 'p2.id');

        $payments = static::getModelQueryBySearch($search, $payments);
        return $payments->count();
    }

    public static function populateRecords($records): array
    {
        $data = [];
        foreach ($records as $payment){
            $nestedData['invoice_code'] = getInvoiceCodeFromId($payment->invoice_id);
            $nestedData['invoice_type'] = getInvoiceTypeFromClass($payment->invoiceable_type);
            $nestedData['package_code'] = getPackageCodeFromModelAndId($payment->invoiceable_type, $payment->invoiceable_id);
            $nestedData['amount'] = formatAmountWithCurrency($payment->amount);
            $nestedData['date'] = formatDate($payment->created_at);
            $nestedData['payment_gateway'] = 'Stripe';
            $data[] = $nestedData;
        }
        return $data;
    }

    public static function getModelQueryBySearch(mixed $search, Builder $records): Builder
    {

        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $records = $records->where(function ($q) {
                $q->where('s1.parent_id', Auth::id())
                    ->orWhere('s2.parent_id', Auth::id());
            });
        }
        if (Auth::user()->hasRole('client') && Auth::user() instanceof ParentUser) {
            $records = $records->where(function ($q) {
                $q->where('s1.parent_id', Auth::id())
                    ->orWhere('s2.parent_id', Auth::id());
            });
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $records = $records->where(function ($q) {
                $q->where('student_tutoring_packages.student_id', Auth::id())
                    ->orWhere('monthly_invoice_packages.student_id', Auth::id())
                    ->WhereRaw('(CASE WHEN s1.parent_id IS NULL THEN true ELSE false END OR CASE WHEN s2.parent_id IS NULL THEN true ELSE false END)');
            });
        }
        if (Auth::user()->hasRole('client') && Auth::user() instanceof Client) {
            $records = $records->where(function ($q) {
                $q->where('non_invoice_packages.client_id', Auth::id())
                    ->where('invoices.invoiceable_type', NonInvoicePackage::class)
                    ->where('invoices.invoice_package_type_id', 3);
            });

        }
        return $records;
    }

    public static function totalRecords(): int
    {
        $records = Payment::query()
            ->select(
                [
                    'invoices.id as invoice_id',
                ])
            ->join('invoices', 'invoices.id', 'payments.invoice_id')
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')
                    ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('monthly_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'monthly_invoice_packages.id')
                    ->where('invoices.invoiceable_type', '=', MonthlyInvoicePackage::class);
            })
            ->leftJoin('non_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'non_invoice_packages.id')
                    ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->leftJoin('students as s1', 'student_tutoring_packages.student_id', '=', 's1.id')
            ->leftJoin('students as s2', 'monthly_invoice_packages.student_id', '=', 's2.id')
            ->leftJoin('parents as p1', 's1.parent_id', '=', 'p1.id')
            ->leftJoin('clients', 'non_invoice_packages.client_id', '=', 'clients.id')
            ->leftJoin('parents as p2', 's2.parent_id', '=', 'p2.id');

        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $records = $records->where(function ($q) {
                $q->where('s1.parent_id', Auth::id())
                    ->orWhere('s2.parent_id', Auth::id());
            });
        }
        if (Auth::user()->hasRole('client') && Auth::user() instanceof ParentUser) {
            $records = $records->where(function ($q) {
                $q->where('s1.parent_id', Auth::id())
                    ->orWhere('s2.parent_id', Auth::id());
            });
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $records = $records->where(function ($q) {
                $q->where('student_tutoring_packages.student_id', Auth::id())
                    ->orWhere('monthly_invoice_packages.student_id', Auth::id())
                    ->WhereRaw('(CASE WHEN s1.parent_id IS NULL THEN true ELSE false END OR CASE WHEN s2.parent_id IS NULL THEN true ELSE false END)');
            });
        }
        if (Auth::user()->hasRole('client') && Auth::user() instanceof Client) {
            $records = $records->where(function ($q) {
                $q->where('non_invoice_packages.client_id', Auth::id())
                    ->where('invoices.invoiceable_type', NonInvoicePackage::class)
                    ->where('invoices.invoice_package_type_id', 3);
            });

        }
        return $records->count();
    }
}
