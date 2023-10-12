<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMonthlyInvoicePackageRequest;
use App\Http\Requests\UpdateMonthlyInvoicePackageRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MonthlyInvoicePackageRepository;
use Illuminate\Http\Request;
use Flash;

class MonthlyInvoicePackageController extends AppBaseController
{
    /** @var MonthlyInvoicePackageRepository $monthlyInvoicePackageRepository*/
    private $monthlyInvoicePackageRepository;

    public function __construct(MonthlyInvoicePackageRepository $monthlyInvoicePackageRepo)
    {
        $this->monthlyInvoicePackageRepository = $monthlyInvoicePackageRepo;
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
        return view('monthly_invoice_packages.create');
    }

    /**
     * Store a newly created MonthlyInvoicePackage in storage.
     */
    public function store(CreateMonthlyInvoicePackageRequest $request)
    {
        $input = $request->all();

        $monthlyInvoicePackage = $this->monthlyInvoicePackageRepository->create($input);

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
