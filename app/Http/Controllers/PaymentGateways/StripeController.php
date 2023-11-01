<?php

namespace App\Http\Controllers\PaymentGateways;

use App\Http\Controllers\AppBaseController;
use App\Models\Invoice;
use App\Models\NonInvoicePackage;
use App\Repositories\StripeRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Laracasts\Flash\Flash;
use Stripe\Checkout\Session;
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
            ->select(['invoices.id as invoice_id','non_invoice_packages.final_amount','invoiceable_type','invoiceable_id'])
            ->selectRaw('sum(payments.amount) as paid_amount')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
            ->leftJoin('non_invoice_packages', function ($q){
                $q->on('non_invoice_packages.id', '=', 'invoices.invoiceable_id')
                    ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->where('invoices.id',$request->invoiceId)->firstOrFail();

        $payable_amount = $invoice->final_amount - $invoice->paid_amount;
        $amount = $request->partialAmount;
        if (!is_numeric($amount)) {
            $amount = $payable_amount;
        }
        if ($amount > $payable_amount) {
            $amount = $payable_amount;
        }

        $invoiceId = $invoice->invoice_id;
        if ($invoice->invoiceable_type == NonInvoicePackage::class) {
            $userEmail = NonInvoicePackage::query()
                ->select(['clients.email'])
                ->leftJoin('clients', 'clients.id', '=', 'non_invoice_packages.client_id')
                ->where('non_invoice_packages.id',$invoice->invoiceable_id)->firstOrFail()->email;
        }
        $invoiceType = getInvoiceTypeFromClass($invoice->invoiceable_type);
        $invoiceCode = getInvoiceCodeFromId($invoice->invoiceable_id);

        try {
            setStripeApiKey();
            $session = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $userEmail,
                'line_items' => [
                    [
                        'price_data' => [
                            'product_data' => [
                                'name' => 'Payment for '.$invoiceType,
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
                'client_reference_id' => $invoiceId,
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

    /**
     * @return Application|RedirectResponse|Redirector
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentSuccess(Request $request): RedirectResponse
    {
        $sessionId = $request->get('session_id');

        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }

        $this->stripeRepository->clientPaymentSuccess($sessionId);

        $sessionData = Session::retrieve($sessionId);
        $invoiceId = $sessionData->client_reference_id;

        /** @var Invoice $invoice */
        $invoice = Invoice::with(['payments', 'client'])->findOrFail($invoiceId);

        Flash::success('Payment successfully done.');
        if (! Auth()->check()) {
            return redirect(route('invoice-show-url', $invoice->invoice_id));
        }

        return redirect(route('client.invoices.index'));
    }

    public function handleFailedPayment(): RedirectResponse
    {
        Flash::error('Your Payment is Cancelled');

        return redirect()->route('client.invoices.index');
    }
}
