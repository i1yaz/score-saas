<?php

namespace App\Http\Controllers;

use App\DataTables\InvoiceDataTable;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\LineItem;
use App\Models\MonthlyInvoicePackage;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\Payment;
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
                //                'student',
                //                'parent',
                'created_at',
                'due_date',
                'amount_paid',
                'amount_remaining',
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
        $items = LineItem::all();
        $taxes = Tax::select(['id', 'name', 'value'])->get();

        return view('invoices.create', ['taxes' => $taxes, 'items' => $items]);
    }

    /**
     * Store a newly created Invoice in storage.
     *
     * @throws \Exception
     */
    public function store(CreateInvoiceRequest $request)
    {
        $input = $request->all();

        $this->invoiceRepository->create($input);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Invoice created successfully', 'redirectTo' => route('invoices.index')]);
        }

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

    public function showPaymentPage(Request $request, $invoice)
    {

        if (empty($invoice)) {
            Flash::error('Invoice not found');

            return redirect(route('invoices.index'));
        }
        $stripeKey = config('services.stripe.key');
        $paymentModes = $this->invoiceRepository->getPaymentGateways();

        if ($request->type === 'non-package-invoice') {
            $invoice = $this->invoiceRepository->showNonPackageInvoice($invoice);

            return view('invoices.non-package-payment-create', ['invoice' => $invoice, 'stripeKey' => $stripeKey, 'paymentModes' => $paymentModes]);
        }
        if ($request->type === 'tutoring-package') {
            $invoice = $this->invoiceRepository->showTutoringPackageInvoice($invoice);
            $totalAmount = cleanAmountWithCurrencyFormat(getPriceFromHoursAndHourlyWithDiscount($invoice->hourly_rate, $invoice->hours, $invoice->discount, $invoice->discount_type));
            $remainingAmount = $totalAmount - $invoice->amount_paid ?? 0;
            $remainingAmount = $remainingAmount + $invoice->amount_refunded;
            return view('invoices.tutoring-package-payment-create', [
                'invoice' => $invoice,
                'stripeKey' => $stripeKey,
                'paymentModes' => $paymentModes,
                'totalAmount' => $totalAmount,
                'remainingAmount' => $remainingAmount]);
        }
        if ($request->type === 'monthly-invoice-package'){
            $invoice = $this->invoiceRepository->showMonthlyInvoicePackage($invoice);
            $monthlyInvoicePackage = MonthlyInvoicePackage::select(['id','hourly_rate'])->findOrFail($invoice->monthly_invoice_package_id);
            $subscription = MonthlyInvoiceSubscription::select('subscription_id')->where('monthly_invoice_package_id', $invoice->monthly_invoice_package_id)->firstOrFail();
            $price = (getTotalInvoicePriceFromMonthlyInvoicePackage($monthlyInvoicePackage));
            return view('invoices.monthly-invoice-package-payment-create', [
                'monthlyInvoicePackageId' => $invoice->monthly_invoice_package_id,
                'stripeKey' => $stripeKey,
                'paymentModes' => $paymentModes,
                'subscriptionAmount' => $price,
                'subscriptionId' => $subscription->subscription_id,
                ]);
        }
    }

    public function showPublicInvoice($invoice, $type = null)
    {
        if ($type == 'non-package-invoice') {
            $invoiceData = $this->invoiceRepository->getNonPackageInvoiceData($invoice);
        }
        $invoiceData['statusArr'] = Invoice::STATUS_ARR;
        $invoiceData['status'] = $invoice->status;
        unset($invoiceData['statusArr'][Invoice::DRAFT]);
        $invoiceData['paymentType'] = Payment::PAYMENT_TYPE;
        $invoiceData['paymentMode'] = $this->invoiceRepository->getPaymentGateways();
        $invoiceData['stripeKey'] = getSettingValue('stripe_key');
        $invoiceData['userLang'] = $invoice->client->user->language;

        return view('invoices.public-invoice.public_view')->with($invoiceData);
    }
}
