<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use Illuminate\Support\Facades\Auth;

class InvoiceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'invoice_package_type_id',
        'due_date',
        'fully_paid_at',
        'general_description',
        'detailed_description',
        'email_to_parent',
        'email_to_student',
        'amount_paid',
        'amount_remaining',
        'paid_status',
        'paid_by_modal',
        'paid_by_id',
        'invoiceable_type',
        'invoiceable_id',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Invoice::class;
    }

    public function show($id)
    {
        $invoice = Invoice::query()->select(
            [
                'invoices.id as invoice_id', 'invoices.paid_status as invoice_status', 'invoices.invoiceable_type', 'student_tutoring_packages.student_id',
                'students.parent_id as parent_id', 'students.id as student_id', 'students.email as student_email', 'parents.id as student_id', 'parents.email as parent_email', 'invoices.created_at as invoice_created_at',
                'invoices.due_date', 'student_tutoring_packages.hourly_rate', 'student_tutoring_packages.hours', 'student_tutoring_packages.discount',
                'student_tutoring_packages.discount_type',
                'invoices.fully_paid_at',
                'invoices.amount_paid',
                'invoices.general_description',
                'invoices.detailed_description',
                'invoices.paid_status as invoice_status',
                'tutoring_package_types.name as tutoring_package_type_name',

            ])
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('tutoring_package_types', 'student_tutoring_packages.tutoring_package_type_id', '=', 'tutoring_package_types.id')
            ->leftJoin('students', 'student_tutoring_packages.student_id', '=', 'students.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id');
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $invoice = $invoice->where('parent_id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $invoice = $invoice->where('student_id', Auth::id())
                ->WhereRaw('CASE WHEN students.parent_id IS NULL THEN true ELSE false END');
        }

        return $invoice->where('invoices.id', $id)->first();

    }

    public function createOrUpdateInvoiceForPackage($studentTutoringPackage, $input = []): Invoice
    {
        $invoice = Invoice::where('invoiceable_type', StudentTutoringPackage::class)
            ->where('invoiceable_id', $studentTutoringPackage->id)
            ->first();
        if (!$invoice){
            $invoice = new Invoice();
        }
        $invoice->invoice_package_type_id = 1;
        $invoice->due_date = $studentTutoringPackage->start_date;
        $invoice->general_description = $input['general_description'] ?? null;
        $invoice->detailed_description = $input['detailed_description'] ?? null;
        $invoice->email_to_parent = $input['email_to_parent'] ?? false;
        $invoice->amount_paid = 0;
        $invoice->paid_status = Invoice::PENDING;
        $invoice->invoiceable_type = StudentTutoringPackage::class;
        $invoice->invoiceable_id = $studentTutoringPackage->id;
        $invoice->auth_guard = Auth::guard()->name;
        $invoice->added_by = Auth::id();
        $invoice->save();

        return $invoice;
    }
}
