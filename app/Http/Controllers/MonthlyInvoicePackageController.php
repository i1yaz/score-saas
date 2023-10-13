<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMonthlyInvoicePackageRequest;
use App\Http\Requests\UpdateMonthlyInvoicePackageRequest;
use App\Models\Subject;
use App\Repositories\InvoiceRepository;
use App\Repositories\MonthlyInvoicePackageRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;

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
        $monthlyInvoicePackages = $this->monthlyInvoicePackageRepository->paginate(10);

        return view('monthly_invoice_packages.index')
            ->with('monthlyInvoicePackages', $monthlyInvoicePackages);
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
            $tutors = $input['tutor_ids'];
            $subjects = $input['subject_ids'];
            unset($input['tutor_ids'],$input['subject_ids']);

            $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->create($input);
            $monthlyInvoicePackage->tutors()->sync($tutors);
            $monthlyInvoicePackage->subjects()->sync($subjects);
            $this->invoiceRepository->createOrUpdateInvoiceForPackage($monthlyInvoicePackage, $input);

            DB::commit();

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

        Flash::success('Monthly Invoice Package saved successfully.');

        return redirect(route('monthlyInvoicePackages.index'));
    }

    /**
     * Display the specified MonthlyInvoicePackage.
     */
    public function show($id)
    {
        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->find($id);

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
