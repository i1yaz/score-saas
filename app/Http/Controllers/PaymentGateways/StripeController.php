<?php

namespace App\Http\Controllers\PaymentGateways;

use App\Http\Controllers\AppBaseController;
use App\Models\Invoice;
use App\Models\NonInvoicePackage;
use App\Models\StudentTutoringPackage;
use App\Repositories\StripeRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Laracasts\Flash\Flash;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class StripeController extends AppBaseController
{

    /**
     * @var StripeRepository
     */
    private $stripeRepository;

    public function __construct(StripeRepository $stripeRepository)
    {
        $this->stripeRepository = $stripeRepository;
    }

    public function createSession(Request $request)
    {
        $invoice = Invoice::query()
            ->select(
                [
                    'invoices.id as invoice_id',
                    'non_invoice_packages.final_amount',
                    'invoiceable_type',
                    'invoiceable_id',
                    'student_tutoring_packages.hourly_rate as stp_hourly_rate',
                    'student_tutoring_packages.hours as stp_hours',
                    'student_tutoring_packages.discount as stp_discount',
                    'student_tutoring_packages.discount_type as stp_discount_type',

                ])
            ->selectRaw('sum(payments.amount) as paid_amount')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
            ->leftJoin('non_invoice_packages', function ($q){
                $q->on('non_invoice_packages.id', '=', 'invoices.invoiceable_id')
                    ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->leftJoin('student_tutoring_packages', function ($q){
                $q->on('student_tutoring_packages.id', '=', 'invoices.invoiceable_id')
                    ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->where('invoices.id',$request->invoiceId)->firstOrFail();

        if ($invoice->invoiceable_type == NonInvoicePackage::class) {
            $payable_amount = $invoice->final_amount - $invoice->paid_amount;
            $userEmail = NonInvoicePackage::query()
                ->select(['clients.email'])
                ->leftJoin('clients', 'clients.id', '=', 'non_invoice_packages.client_id')
                ->where('non_invoice_packages.id',$invoice->invoiceable_id)->firstOrFail()->email;
            $packageCode = getNonInvoicePackageCodeFromId($invoice->invoiceable_id);
        }
        if ($invoice->invoiceable_type == StudentTutoringPackage::class) {
            $payable_amount  = cleanAmountWithCurrencyFormat(getPriceFromHoursAndHourlyWithDiscount($invoice->stp_hourly_rate,$invoice->stp_hours,$invoice->stp_discount,$invoice->stp_discount_type));
            $userEmail = StudentTutoringPackage::query()
                ->select(['students.email'])
                ->leftJoin('students', 'students.id', '=', 'student_tutoring_packages.student_id')
                ->where('student_tutoring_packages.id',$invoice->invoiceable_id)->firstOrFail()->email;

            $packageCode = getStudentTutoringPackageCodeFromId($invoice->invoiceable_id);
            $payable_amount = $payable_amount - $invoice->paid_amount;
        }
        $amount = $request->partialAmount;

        if (!is_numeric($amount)) {
            $amount = $payable_amount;
        }
        if ($amount > $payable_amount) {
            $amount = $payable_amount;
        }

        $invoiceId = $invoice->invoice_id;
        $invoiceCode = getInvoiceCodeFromId($invoiceId);
        $packageType = getInvoiceTypeFromClass($invoice->invoiceable_type);
        $userId = \Auth::id()??'none';
        $guard = \Auth::guard()->name??'none';
        try {
            setStripeApiKey();
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'customer_email' => $userEmail,
                'line_items' => [
                    [
                        'price_data' => [
                            'product_data' => [
                                'name' => 'Payment for '.$packageType.' Package #'.$packageCode,
                                'description' => 'Bill invoice #'.$invoiceCode,
                            ],
                            'unit_amount' =>  $amount * 100 ,
                            'currency' => getInvoiceCurrencyCode(),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'metadata' => [
                    'description' =>  'Bill invoice #'.$invoiceCode,
                ],
                'billing_address_collection' => 'auto',
                'client_reference_id' => "{$invoiceId}-{$userId}-{$guard}",
                'mode' => 'payment',
                'success_url' => route('payment-success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment-failed').'?error=payment_cancelled',
            ]);
            $result = [
                'sessionId' => $session['id'],
            ];

            return $this->sendResponse($result, 'Session created successfully.');
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }


    public function handleFailedPayment(): RedirectResponse
    {
        Flash::error('Your Payment is Cancelled');

        return redirect()->route('client.invoices.index');
    }
}
