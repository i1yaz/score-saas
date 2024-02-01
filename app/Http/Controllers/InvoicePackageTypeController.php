<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoicePackageTypeRequest;
use App\Http\Requests\UpdateInvoicePackageTypeRequest;
use App\Repositories\InvoicePackageTypeRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class InvoicePackageTypeController extends AppBaseController
{
    /** @var InvoicePackageTypeRepository */
    private $invoicePackageTypeRepository;

    public function __construct(InvoicePackageTypeRepository $invoicePackageTypeRepo)
    {
        $this->invoicePackageTypeRepository = $invoicePackageTypeRepo;
    }

    /**
     * Display a listing of the InvoicePackageType.
     */
    public function index(Request $request)
    {
        $invoicePackageTypes = $this->invoicePackageTypeRepository->paginate(10);

        return view('invoice_package_types.index')
            ->with('invoicePackageTypes', $invoicePackageTypes);
    }

    /**
     * Show the form for creating a new InvoicePackageType.
     */
    public function create()
    {
        return view('invoice_package_types.create');
    }

    /**
     * Store a newly created InvoicePackageType in storage.
     */
    public function store(CreateInvoicePackageTypeRequest $request)
    {
        $input = $request->all();

        $invoicePackageType = $this->invoicePackageTypeRepository->create($input);

        Flash::success('Invoice Package Type saved successfully.');

        return redirect(route('invoice-package-types.index'));
    }

    /**
     * Display the specified InvoicePackageType.
     */
    public function show($id)
    {
        $invoicePackageType = $this->invoicePackageTypeRepository->find($id);

        if (empty($invoicePackageType)) {
            Flash::error('Invoice Package Type not found');

            return redirect(route('invoice-package-types.index'));
        }

        return view('invoice_package_types.show')->with('invoicePackageType', $invoicePackageType);
    }

    /**
     * Show the form for editing the specified InvoicePackageType.
     */
    public function edit($id)
    {
        $invoicePackageType = $this->invoicePackageTypeRepository->find($id);

        if (empty($invoicePackageType)) {
            Flash::error('Invoice Package Type not found');

            return redirect(route('invoice-package-types.index'));
        }

        return view('invoice_package_types.edit')->with('invoicePackageType', $invoicePackageType);
    }

    /**
     * Update the specified InvoicePackageType in storage.
     */
    public function update($id, UpdateInvoicePackageTypeRequest $request)
    {
        $invoicePackageType = $this->invoicePackageTypeRepository->find($id);

        if (empty($invoicePackageType)) {
            Flash::error('Invoice Package Type not found');

            return redirect(route('invoice-package-types.index'));
        }

        $invoicePackageType = $this->invoicePackageTypeRepository->update($request->all(), $id);

        Flash::success('Invoice Package Type updated successfully.');

        return redirect(route('invoice-package-types.index'));
    }

    /**
     * Remove the specified InvoicePackageType from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $invoicePackageType = $this->invoicePackageTypeRepository->find($id);

        if (empty($invoicePackageType)) {
            Flash::error('Invoice Package Type not found');

            return redirect(route('invoice-package-types.index'));
        }

        $this->invoicePackageTypeRepository->toggleStatus($id);

        Flash::success('Invoice Package Type deleted successfully.');

        return redirect(route('invoice-package-types.index'));
    }
}
