<?php

namespace App\Repositories;

use App\Mail\ClientMakePaymentMail;
use App\Mail\FlagSessionMail;
use App\Models\Invoice;
use App\Models\NonInvoicePackage;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaymentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'invoice_id',
        'amount',
        'payment_gateway',
        'transaction_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Payment::class;
    }

    public function stripePaymentSuccess($sessionId)
    {
        setStripeApiKey();
        $sessionData = Session::retrieve($sessionId);

        $stripeID = $sessionData->id;
        $paymentStatus = $sessionData->payment_status;
        $invoiceId = $sessionData->client_reference_id;
        $amountPaidInLastSession = $sessionData->amount_total;

        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->select(['invoices.id as invoice_id','invoiceable_type','invoiceable_id'])
            ->selectRaw('sum(payments.amount) as paid_amount')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
        ->findOrFail($invoiceId);
        if ($invoice->invoiceable_type == NonInvoicePackage::class) {
            $nonInvoicePackage = NonInvoicePackage::query()
                ->select(['clients.id as client_id','clients.email','non_invoice_packages.final_amount'])
                ->leftJoin('clients', 'clients.id', '=', 'non_invoice_packages.client_id')
                ->where('non_invoice_packages.id',$invoice->invoiceable_id)->firstOrFail();
            if (($invoice->paid_amount + $amountPaidInLastSession) < $nonInvoicePackage->final_amount) {
                $paymentStatus = Invoice::PARTIAL_PAYMENT;
            }else{
                $paymentStatus = Invoice::PAID;
            }
        }


        $amount = $sessionData->amount_total;

        $userId = \Auth::id();
        $authGuard = \Auth::guard()->name;

        $transactionData = [
            'transaction_id' => $stripeID,
            'invoice_id' => $invoiceId,
            'amount' => $amount/100,
            'payment_gateway_id' => '1',
            'status' => $paymentStatus,
            'meta' => $paymentStatus,
            'paid_by_id' => $userId,
            'paid_by_modal' => getAuthModelFromGuard($authGuard),
        ];

        try {
            DB::beginTransaction();
            Payment::create($transactionData);
            Invoice::find($invoiceId)->update([
                'fully_paid_at' => $paymentStatus == Invoice::PAID ? Carbon::now() : null,
                'paid_status' => $paymentStatus,
                'auth_guard' => $authGuard,
                'added_by' => $userId,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        $admins = User::whereHasRole(['super-admin'])->get(['email']);
        $admins = $admins->pluck('email')->toArray();
        Mail::to($admins)->send(new ClientMakePaymentMail($transactionData));
        return $sessionData;
    }

}
