<?php

namespace App\Repositories;

use App\Mail\ClientMakePaymentMail;
use App\Models\Invoice;
use App\Models\NonInvoicePackage;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StripeRepository
{
    public function clientPaymentSuccess($sessionId): void
    {
        setStripeApiKey();
        $sessionData = Session::retrieve($sessionId);

        $stripeID = $sessionData->id;
        $reference = $sessionData->client_reference_id;
        $reference = explode('-', $reference);
        $invoiceId = $reference[0];
        $userId = $reference[1];
        $guard = $reference[2];

        $invoice = Invoice::query()
            ->select(['invoices.id as invoice_id','non_invoice_packages.final_amount','invoiceable_type','invoiceable_id'])
            ->selectRaw('sum(payments.amount) as paid_amount')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
            ->leftJoin('non_invoice_packages', function ($q){
                $q->on('non_invoice_packages.id', '=', 'invoices.invoiceable_id')
                    ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->where('invoices.id',$invoiceId)->firstOrFail();

        $amountPaid = $sessionData->amount_total / 100 ;

//        try {
            DB::beginTransaction();

            $payment = new Payment();
            $payment->invoice_id = $invoiceId;
            $payment->payment_gateway = Payment::STRIPE;
            $payment->transaction_id = $stripeID;
            $payment->amount = $amountPaid;
            $payment->paid_by_modal = getAuthModelFromGuard($guard);
            $payment->paid_by_id = $userId;
            $payment->save();

            if ($amountPaid == $invoice->final_amount - $invoice->paid_amount) {
                $invoice = Invoice::findOrFail($invoiceId);
                $invoice->fully_paid_at = Carbon::now();
                $invoice->paid_status = Payment::PAID;
                $invoice->save();
            } else {
                $invoice = Invoice::findOrFail($invoiceId);
                $invoice->paid_status = Payment::PARTIAL_PAYMENT;
                $invoice->save();
            }
            DB::commit();
            $admins = User::whereHasRole(['super-admin'])->get(['email']);
            $admins = $admins->pluck('email')->toArray();
            Mail::to($admins)->send(new ClientMakePaymentMail($payment->toArray()));

//        } catch (Exception $e) {
//            DB::rollBack();
//            throw new UnprocessableEntityHttpException($e->getMessage());
//        }
    }

}
