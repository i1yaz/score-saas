<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Repositories\BaseRepository;
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
        'invoiceable_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Invoice::class;
    }

    public function show($id){
        $invoice = Invoice::query()->select(
            [
                'invoices.id as invoice_id', 'invoices.paid_status as invoice_status', 'invoices.invoiceable_type','student_tutoring_packages.student_id',
                'students.parent_id as parent_id','students.id as student_id','students.email as student_email','parents.id as student_id','parents.email as parent_email','invoices.created_at as invoice_created_at',
                'invoices.due_date','student_tutoring_packages.hourly_rate','student_tutoring_packages.hours','student_tutoring_packages.discount',
                'student_tutoring_packages.discount_type',
                'invoices.fully_paid_at',
                'invoices.amount_paid',
                'invoices.general_description',
                'invoices.detailed_description',
                'invoices.paid_status as invoice_status',
                'tutoring_package_types.name as tutoring_package_type_name'

            ])
            ->leftJoin('student_tutoring_packages', function ($q){
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('tutoring_package_types', 'student_tutoring_packages.tutoring_package_type_id', '=', 'tutoring_package_types.id')
            ->leftJoin('students', 'student_tutoring_packages.student_id', '=', 'students.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.id');
        if (Auth::user()->hasRole('parent') && Auth::user() instanceof ParentUser) {
            $invoice = $invoice->where('parent_id', Auth::id());
        }
        if (Auth::user()->hasRole('student') && Auth::user() instanceof Student) {
            $invoice = $invoice->where('student_id', Auth::id());
        }
        return $invoice->where('invoices.id', $id)->first();

    }
}
