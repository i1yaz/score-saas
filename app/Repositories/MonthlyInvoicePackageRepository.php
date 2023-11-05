<?php

namespace App\Repositories;

use App\Models\MonthlyInvoicePackage;
use App\Models\Session;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MonthlyInvoicePackageRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'student_id',
        'notes',
        'internal_notes',
        'start_date',
        'hourly_rate',
        'tutor_hourly_rate',
        'tutoring_location_id',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MonthlyInvoicePackage::class;
    }

    public function create(array $input): Model|MonthlyInvoicePackage
    {
        $input['auth_guard'] = Auth::guard()->name;
        $input['added_by'] = Auth::id();

        $model = $this->model->newInstance($input);
        $model->save();

        return $model;
    }

    public function show($id): MonthlyInvoicePackage
    {
        return MonthlyInvoicePackage::query()
            ->with(['tutors', 'subjects'])
            ->with('sessions', function ($q) {
                $q = $q->select('sessions.*', 'tutors.email as tutor_email', 'list_data.name as completion_code_name')
                    ->selectRaw("CONCAT(tutors.first_name,' ',tutors.last_name) as tutor_name")
                    ->leftJoin('list_data', function ($q) {
                        $q->on('list_data.id', '=', 'sessions.session_completion_code')
                            ->where('list_data.list_id', '=', Session::LIST_DATA_LIST_ID);
                    })
                    ->join('tutors', 'tutors.id', 'sessions.tutor_id');
                if (Auth::user()->hasRole('tutor') && Auth::user() instanceof Tutor) {
                    $q->where('tutor_id', Auth::id());
                }
            })
            ->select([
                'monthly_invoice_packages.*',
                'students.email as student_email',
                'parents.email as parent_email',
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
            ->join('students', 'monthly_invoice_packages.student_id', 'students.id')
            ->leftJoin('parents', 'students.parent_id', 'parents.id')
            ->join('tutoring_locations', 'monthly_invoice_packages.tutoring_location_id', 'tutoring_locations.id')
            ->join('invoices', function ($query) {
                $query->on('invoices.invoiceable_id', '=', 'monthly_invoice_packages.id')
                    ->where('invoices.invoiceable_type', '=', MonthlyInvoicePackage::class);
            })
            ->join('invoice_package_types', 'invoice_package_types.id', 'invoices.invoice_package_type_id')
            ->where('monthly_invoice_packages.id', $id)
            ->first();
    }

    public function update(array $input, int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);
        $model->due_date = $input['due_date'];

        $model->save();

        return $model;
    }
}
