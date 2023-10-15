<?php

namespace App\Http\Controllers;

use App\DataTables\MonthlyInvoicePackageDataTable;
use App\DataTables\StudentTutoringPackageDataTable;
use App\Http\Requests\CreateMonthlyInvoicePackageRequest;
use App\Http\Requests\UpdateMonthlyInvoicePackageRequest;
use App\Mail\ParentInvoiceMailAfterMonthlyInvoicePackageCreationMail;
use App\Mail\ParentInvoiceMailAfterStudentTutoringPackageCreation;
use App\Models\MonthlyInvoicePackage;
use App\Models\Student;
use App\Models\Subject;
use App\Repositories\InvoiceRepository;
use App\Repositories\MonthlyInvoicePackageRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MonthlyInvoicePackageController extends AppBaseController
{
    /** @var MonthlyInvoicePackageRepository $monthlyInvoicePackageRepository*/
    private MonthlyInvoicePackageRepository $monthlyInvoicePackageRepository;
    private InvoiceRepository $invoiceRepository;

    public function __construct(MonthlyInvoicePackageRepository $monthlyInvoicePackageRepo, InvoiceRepository $invoiceRepository)
    {
        $this->monthlyInvoicePackageRepository = $monthlyInvoicePackageRepo;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Display a listing of the MonthlyInvoicePackage.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'package_id',
                'student',
                'notes',
                'internal_notes',
                'start_date',
                'tutoring_location_id',
                'total_sessions',
                'status',
                'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = MonthlyInvoicePackageDataTable::totalRecords();
            $studentTutoringPackages = MonthlyInvoicePackageDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = MonthlyInvoicePackageDataTable::totalFilteredRecords($search);
            $data = MonthlyInvoicePackageDataTable::populateRecords($studentTutoringPackages);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);

        }

        return view('monthly_invoice_packages.index');
    }

    /**
     * Show the form for creating a new MonthlyInvoicePackage.
     */
    public function create()
    {
        $subjects = Subject::get(['id', 'name']);
        return view('monthly_invoice_packages.create', compact('subjects'));
    }

    /**
     * Store a newly created MonthlyInvoicePackage in storage.
     */
    public function store(CreateMonthlyInvoicePackageRequest $request)
    {

        DB::beginTransaction();
        try {
            $input = $request->all();
            unset($input['name']);
            $input['is_score_guaranteed'] = yesNoToBoolean($input['is_score_guaranteed']);
            $input['is_free'] = yesNoToBoolean($input['is_free']);
            $tutors = $input['tutor_ids'];
            $subjects = $input['subject_ids'];
            unset($input['tutor_ids'],$input['subject_ids']);
            $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->create($input);
            $monthlyInvoicePackage->tutors()->sync($tutors);
            $monthlyInvoicePackage->subjects()->sync($subjects);
            $this->invoiceRepository->createOrUpdateInvoiceForMonthlyPackage($monthlyInvoicePackage, $input);
            DB::commit();
            if ($input['email_to_parent'] == 1) {
                $parentEmail = Student::select(['parents.email as parent_email', 'students.id', 'students.parent_id'])->where('students.id', $input['student_id'])
                    ->join('parents', 'students.parent_id', '=', 'parents.id')->first();

                Mail::to($parentEmail->parent_email)->send(new ParentInvoiceMailAfterMonthlyInvoicePackageCreationMail($monthlyInvoicePackage));
            }
            $redirectRoute = route('monthly-invoice-packages.show', ['monthly_invoice_package' => $monthlyInvoicePackage->id]);
            if ($request->ajax()){
                return response()->json(['success' => true, 'message' => 'Monthly Invoice Package saved successfully.','redirectTo' => $redirectRoute]);
            }
            Flash::success('Monthly Invoice Package saved successfully.');
            return redirect($redirectRoute);
        }catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            \Laracasts\Flash\Flash::error('something went wrong');

            return redirect(route('monthly-invoice-packages.index'));
        }catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            \Laracasts\Flash\Flash::error('something went wrong');

            return redirect(route('monthly-invoice-packages.index'));
        }

    }

    /**
     * Display the specified MonthlyInvoicePackage.
     */
    public function show($id)
    {
        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->show($id);

        if (empty($monthlyInvoicePackage)) {
            Flash::error('Monthly Invoice Package not found');

            return redirect(route('monthlyInvoicePackages.index'));
        }

        return view('monthly_invoice_packages.show')->with('monthlyInvoicePackage', $monthlyInvoicePackage);
    }

    /**
     * Show the form for editing the specified MonthlyInvoicePackage.
     */
    public function edit($id)
    {
        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->find($id);

        if (empty($monthlyInvoicePackage)) {
            Flash::error('Monthly Invoice Package not found');

            return redirect(route('monthlyInvoicePackages.index'));
        }

        return view('monthly_invoice_packages.edit')->with('monthlyInvoicePackage', $monthlyInvoicePackage);
    }

    /**
     * Update the specified MonthlyInvoicePackage in storage.
     */
    public function update($id, UpdateMonthlyInvoicePackageRequest $request)
    {
        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->find($id);

        if (empty($monthlyInvoicePackage)) {
            Flash::error('Monthly Invoice Package not found');

            return redirect(route('monthlyInvoicePackages.index'));
        }

        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->update($request->all(), $id);

        Flash::success('Monthly Invoice Package updated successfully.');

        return redirect(route('monthlyInvoicePackages.index'));
    }

    /**
     * Remove the specified MonthlyInvoicePackage from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->find($id);

        if (empty($monthlyInvoicePackage)) {
            Flash::error('Monthly Invoice Package not found');

            return redirect(route('monthlyInvoicePackages.index'));
        }

        $this->monthlyInvoicePackageRepository->delete($id);

        Flash::success('Monthly Invoice Package deleted successfully.');

        return redirect(route('monthlyInvoicePackages.index'));
    }
}
