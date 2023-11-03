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
use Illuminate\Support\Facades\Log;
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
        $amountPaidInLastSession = $sessionData->amount_total/100;
        $reference = $sessionData->client_reference_id;
        $reference = explode('-', $reference);
        $invoiceId = $reference[0];
        $userId = $reference[1];
        $authGuard = $reference[2];

        $invoice = Invoice::query()
            ->select(['invoices.id as invoice_id','invoiceable_type','invoiceable_id'])
            ->selectRaw('sum(payments.amount) as paid_amount')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
        ->findOrFail($invoiceId);
        $paymentStatus = Payment::PENDING;
        if ($invoice->invoiceable_type == NonInvoicePackage::class) {
            $nonInvoicePackage = NonInvoicePackage::findOrFail($invoice->invoiceable_id);
            if (($amountPaidInLastSession + $invoice->paid_amount) == $nonInvoicePackage->final_amount ) {
                $paymentStatus = Payment::PAID;
                Log::critical('Payment total paid : '.($amountPaidInLastSession + $invoice->paid_amount).' Final: '.$nonInvoicePackage->final_amount);
            } else {
                $paymentStatus = Payment::PARTIAL_PAYMENT;
                Log::critical('Partial Payment total paid : '.($amountPaidInLastSession + $invoice->paid_amount).' Final: '.$nonInvoicePackage->final_amount);
            }

        }


        $paymentTransactionData = [
            'invoice_id' => $invoiceId,
            'payment_gateway_id' => 1,
            'transaction_id' => $stripeID,
            'amount' => $amountPaidInLastSession,
            'paid_by_id' => $userId,
            'paid_by_modal' => getAuthModelFromGuard($authGuard),
        ];

        try {
            DB::beginTransaction();
            Payment::create($paymentTransactionData);
            DB::table('invoices')
                ->where('id', $invoiceId)
                ->update(
                    [
                        'paid_status' => $paymentStatus,
                        'fully_paid_at' => $paymentStatus === Invoice::PAID ? Carbon::now() : null,
                        'updated_at' => Carbon::now()
                    ]
                );

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        $admins = User::whereHasRole(['super-admin'])->get(['email']);
        $admins = $admins->pluck('email')->toArray();
        try {
            Mail::to($admins)->send(new ClientMakePaymentMail($paymentTransactionData));
        }catch (Exception $e){
            report($e);
        }
        return $sessionData;
    }

}
