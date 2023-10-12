<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\Session;
use App\Models\StudentTutoringPackage;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentTutoringPackageRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'student_id',
        'tutoring_package_type_id',
        'tutor_id',
        'notes',
        'internal_notes',
        'hours',
        'hourly_rate',
        'tutoring_location_id',
        'discount',
        'discount_type',
        'start_date',
        'tutor_hourly_rate',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return StudentTutoringPackage::class;
    }

    public function create(array $input): Model|StudentTutoringPackage
    {
        $input['auth_guard'] = Auth::guard()->name;
        $input['added_by'] = Auth::id();

        $model = $this->model->newInstance($input);
        $model->save();

        return $model;
    }

    public function show($id): StudentTutoringPackage
    {
        return StudentTutoringPackage::query()
            ->with(['tutors', 'subjects'])
            ->with('sessions', function ($q) {
                $q = $q->select('sessions.*','tutors.email as tutor_email','list_data.name as completion_code_name')
                    ->selectRaw("CONCAT(tutors.first_name,' ',tutors.last_name) as tutor_name")
                    ->leftJoin('list_data',function ($q){
                        $q->on('list_data.id','=','sessions.session_completion_code')
                            ->where('list_data.list_id','=',Session::LIST_DATA_LIST_ID);
                    })
                    ->join('tutors', 'tutors.id', 'sessions.tutor_id');
                if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
                    $q->where('tutor_id', Auth::id());
                }

            })
            ->select([
                'student_tutoring_packages.*',
                'students.email as student_email',
                'parents.email as parent_email',
                'tutoring_package_types.name as package_name',
                'tutoring_package_types.hours as package_hours',
                'tutoring_locations.name as location_name',
                'invoices.id as invoice_id',
                'invoices.invoiceable_type as invoiceable_type',
                'invoices.due_date as due_date',
                'invoices.general_description as general_description',
                'invoices.detailed_description as detailed_description',
                'invoices.paid_status as invoice_status',
                'invoices.created_at as invoice_created_at',
                'invoice_package_types.name as invoice_package_type_name',
            ])
            ->join('students', 'student_tutoring_packages.student_id', 'students.id')
            ->leftJoin('parents', 'students.parent_id', 'parents.id')
            ->join('tutoring_package_types', 'student_tutoring_packages.tutoring_package_type_id', 'tutoring_package_types.id')
            ->join('tutoring_locations', 'student_tutoring_packages.tutoring_location_id', 'tutoring_locations.id')
            ->join('invoices', function ($query) {
                $query->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')
                    ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->join('invoice_package_types', 'invoice_package_types.id', 'invoices.invoice_package_type_id')
            ->where('student_tutoring_packages.id', $id)
            ->first();

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
