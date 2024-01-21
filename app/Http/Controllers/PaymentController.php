<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentsDataTable;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Repositories\PaymentRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaymentController extends AppBaseController
{
    /** @var PaymentRepository */
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepo)
    {
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Display a listing of the Payment.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'invoice_code',
                'invoice_type',
                'package_code',
                'amount',
                'date',
                'payment_gateway',
            ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $search = $request->input('search');
            $totalData = PaymentsDataTable::totalRecords();
            $studentTutoringPackages = PaymentsDataTable::sortAndFilterRecords($search, $start, $limit, $order, $dir);
            $totalFiltered = PaymentsDataTable::totalFilteredRecords($search);
            $data = PaymentsDataTable::populateRecords($studentTutoringPackages);
            $json_data = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json($json_data);

        }

        return view('payments.index');
    }

    /**
     * Show the form for creating a new Payment.
     */
    public function create()
    {

    }

    /**
     * Store a newly created Payment in storage.
     */
    public function store(CreatePaymentRequest $request)
    {

    }

    /**
     * Display the specified Payment.
     */
    public function show($id)
    {
        $payment = $this->paymentRepository->find($id);

        if (empty($payment)) {
            Flash::error('Payment not found');

            return redirect(route('payments.index'));
        }

        return view('payments.show')->with('payment', $payment);
    }

    /**
     * Show the form for editing the specified Payment.
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified Payment in storage.
     */
    public function update($id, UpdatePaymentRequest $request)
    {

    }

    /**
     * Remove the specified Payment from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {

    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }
        Flash::success('Checkout sessions completed successfully your payment status will update soon.');

        return redirect(route('invoices.index'));
    }

    public function failed(Request $request)
    {
        Log::channel('stripe_failure')->info('Something happened!');
        Flash::error('Checkout sessions failed.Please try again');

        return redirect(route('invoices.index'));
    }
}
