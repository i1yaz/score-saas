<?php

namespace App\Http\Controllers;

use App\DataTables\MonthlyInvoicePackageDataTable;
use App\Http\Requests\CreateMonthlyInvoicePackageRequest;
use App\Http\Requests\UpdateMonthlyInvoicePackageRequest;
use App\Mail\ParentInvoiceMailAfterMonthlyInvoicePackageCreationMail;
use App\Models\MonthlyInvoicePackage;
use App\Models\Student;
use App\Models\Subject;
use App\Repositories\InvoiceRepository;
use App\Repositories\MonthlyInvoicePackageRepository;
use Flash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MonthlyInvoicePackageController extends AppBaseController
{
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
            $input['discount'] = $input['discount'] ?? 0;
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
                try {
                    Mail::to($parentEmail->parent_email)->send(new ParentInvoiceMailAfterMonthlyInvoicePackageCreationMail($monthlyInvoicePackage));
                } catch (\Exception $exception) {
                    report($exception);
                }
            }
            $redirectRoute = route('monthly-invoice-packages.show', ['monthly_invoice_package' => $monthlyInvoicePackage->id]);
            $this->monthlyInvoicePackageRepository->createStripeSubscription($monthlyInvoicePackage);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Monthly Invoice Package saved successfully.', 'redirectTo' => $redirectRoute]);
            }
            Flash::success('Monthly Invoice Package saved successfully.');

            return redirect($redirectRoute);
        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            \Laracasts\Flash\Flash::error('something went wrong');

            return redirect(route('monthly-invoice-packages.index'));
        } catch (\Exception $exception) {
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

        $this->authorize('update', $monthlyInvoicePackage);
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
        $monthlyInvoicePackage = MonthlyInvoicePackage::query()
            ->select(['monthly_invoice_packages.*', 'students.email as student_email',  'tutoring_locations.name as tutoring_location_name'])
            ->with(['subjects', 'tutors', 'invoice'])
            ->join('students', 'students.id', '=', 'monthly_invoice_packages.student_id')
            ->join('tutoring_locations', 'tutoring_locations.id', '=', 'monthly_invoice_packages.tutoring_location_id')
            ->find($id);
        $this->authorize('update', $monthlyInvoicePackage);
        $selectedSubjects = $monthlyInvoicePackage->subjects->pluck(['id'])->toArray();
        $selectedStudent[$monthlyInvoicePackage->student_id] = $monthlyInvoicePackage->student_email;
        $tutoringLocation[$monthlyInvoicePackage->tutoring_location_id] = $monthlyInvoicePackage->tutoring_location_name;
        $selectedTutors = [];
        foreach ($monthlyInvoicePackage->tutors as $tutor) {
            $selectedTutors[$tutor->id] = $tutor->email;
        }
        if (empty($monthlyInvoicePackage)) {
            Flash::error('Monthly Invoice Package not found');

            return redirect(route('monthlyInvoicePackages.index'));
        }
        $subjects = Subject::get(['id', 'name']);

        return view('monthly_invoice_packages.edit')
            ->with('monthlyInvoicePackage', $monthlyInvoicePackage)
            ->with('subjects', $subjects)
            ->with('selectedSubjects', $selectedSubjects)
            ->with('selectedStudent', $selectedStudent)
            ->with('selectedTutors', $selectedTutors)
            ->with('tutoringLocation', $tutoringLocation);
    }

    /**
     * Update the specified MonthlyInvoicePackage in storage.
     */
    public function update($id, UpdateMonthlyInvoicePackageRequest $request)
    {
        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->find($id);
        $this->authorize('update', $monthlyInvoicePackage);
        if (empty($monthlyInvoicePackage)) {
            Flash::error('Monthly Invoice Package not found');

            return redirect(route('monthlyInvoicePackages.index'));
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            unset($input['name']);
            $input['is_score_guaranteed'] = yesNoToBoolean($input['is_score_guaranteed']);
            $input['is_free'] = yesNoToBoolean($input['is_free']);
            $input['discount'] = $input['discount'] ?? 0;
            $tutors = $input['tutor_ids'];
            $subjects = $input['subject_ids'];
            unset($input['tutor_ids'],$input['subject_ids']);

            $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->update($input, $id);
            $monthlyInvoicePackage->tutors()->sync($tutors);
            $monthlyInvoicePackage->subjects()->sync($subjects);
            $this->invoiceRepository->createOrUpdateInvoiceForMonthlyPackage($monthlyInvoicePackage, $input);
            DB::commit();
            if ($input['email_to_parent'] == 1) {
                $parentEmail = Student::select(['parents.email as parent_email', 'students.id', 'students.parent_id'])->where('students.id', $input['student_id'])
                    ->join('parents', 'students.parent_id', '=', 'parents.id')->first();
                try {
                    Mail::to($parentEmail->parent_email)->send(new ParentInvoiceMailAfterMonthlyInvoicePackageCreationMail($monthlyInvoicePackage));
                } catch (\Exception $exception) {
                    report($exception);
                }
            }
            $redirectRoute = route('monthly-invoice-packages.show', ['monthly_invoice_package' => $monthlyInvoicePackage->id]);
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Monthly Invoice Package Updated successfully.', 'redirectTo' => $redirectRoute]);
            }
            Flash::success('Monthly Invoice Package Updated successfully.');

            return redirect($redirectRoute);
        } catch (QueryException $queryException) {
            DB::rollBack();
            report($queryException);
            \Laracasts\Flash\Flash::error('something went wrong');

            return redirect(route('monthly-invoice-packages.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            \Laracasts\Flash\Flash::error('something went wrong');

            return redirect(route('monthly-invoice-packages.index'));
        }

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
