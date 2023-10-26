<?php

namespace App\Http\Controllers;

use App\DataTables\InvoiceDataTable;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Tax;
use App\Repositories\InvoiceRepository;
use Flash;
use Illuminate\Http\Request;

class InvoiceController extends AppBaseController
{
    private InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepository = $invoiceRepo;
    }

    /**
     * Display a listing of the Invoice.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'invoice_id',
                'package',
                'invoice_status',
                'invoice_type',
                'student',
                'parent',
                'created_at',
                'due_date',
                'amount_paid',
                'fully_paid_at',
                'action',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = InvoiceDataTable::totalRecords();
            $invoices = InvoiceDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = InvoiceDataTable::totalFilteredRecords($search);
            $data = InvoiceDataTable::populateRecords($invoices);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);
        }

        return view('invoices.index');
    }

    /**
     * Show the form for creating a new Invoice.
     */
    public function create()
    {
        $taxes = Tax::select(['id','name','value'])->get();
        return view('invoices.create',['taxes'=>$taxes]);
    }

    /**
     * Store a newly created Invoice in storage.
     */
    public function store(CreateInvoiceRequest $request)
    {
        $input = $request->all();

        $invoice = $this->invoiceRepository->create($input);

        Flash::success('Invoice saved successfully.');

        return redirect(route('invoices.index'));
    }

    /**
     * Display the specified Invoice.
     */
    public function show($id)
    {
        $invoice = $this->invoiceRepository->show($id);

        if (empty($invoice)) {
            Flash::error('Invoice not found');

            return redirect(route('invoices.index'));
        }

        return view('invoices.show')->with('invoice', $invoice);
    }

    /**
     * Show the form for editing the specified Invoice.
     */
    public function edit($id)
    {
        $invoice = $this->invoiceRepository->find($id);

        if (empty($invoice)) {
            Flash::error('Invoice not found');

            return redirect(route('invoices.index'));
        }

        return view('invoices.edit')->with('invoice', $invoice);
    }

    /**
     * Update the specified Invoice in storage.
     */
    public function update($id, UpdateInvoiceRequest $request)
    {
        $invoice = $this->invoiceRepository->find($id);

        if (empty($invoice)) {
            Flash::error('Invoice not found');

            return redirect(route('invoices.index'));
        }

        $invoice = $this->invoiceRepository->update($request->all(), $id);

        Flash::success('Invoice updated successfully.');

        return redirect(route('invoices.index'));
    }

    /**
     * Remove the specified Invoice from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $invoice = $this->invoiceRepository->find($id);

        if (empty($invoice)) {
            Flash::error('Invoice not found');

            return redirect(route('invoices.index'));
        }

        $this->invoiceRepository->toggleStatus($id);

        Flash::success('Invoice deleted successfully.');

        return redirect(route('invoices.index'));
    }
}
