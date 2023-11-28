<?php

namespace App\Repositories;

use App\Mail\ClientMakePaymentMail;
use App\Models\Invoice;
use App\Models\MonthlyInvoiceSubscription;
use App\Models\NonInvoicePackage;
use App\Models\Payment;
use App\Models\StudentTutoringPackage;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StripeRepository
{
    public function stripePaymentSuccessfulyCompleted(array $sessionData)
    {
        setStripeApiKey();

        if (empty($sessionData)) {
            throw new UnprocessableEntityHttpException('Session not found');
        }

        $stripeID = $sessionData['id'];
        $amountPaidInLastSession = $sessionData['amount_total'] / 100;
        $reference = $sessionData['client_reference_id'];
        $reference = explode('-', $reference);
        $invoiceType = $reference[0];
        $invoiceId = $reference[1];
        $userId = $reference[2];
        $authGuard = $reference[3];

        $invoice = Invoice::query()
            ->select(['invoices.id as invoice_id', 'invoiceable_type', 'invoiceable_id'])
            ->selectRaw('SUM(CASE WHEN payments.status = 1 THEN payments.amount ELSE 0 END) AS amount_paid')
            ->selectRaw('SUM(CASE WHEN payments.status = 1 THEN payments.amount_refunded ELSE 0 END) AS amount_refunded')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
            ->findOrFail($invoiceId);
        $paymentStatus = Payment::PENDING;
        if ($invoice->invoiceable_type == NonInvoicePackage::class) {
            $nonInvoicePackage = NonInvoicePackage::findOrFail($invoice->invoiceable_id);
            if (($amountPaidInLastSession + $invoice->paid_amount) == $nonInvoicePackage->final_amount) {
                $paymentStatus = Payment::PAID;
            } else {
                $paymentStatus = Payment::PARTIAL_PAYMENT;
            }

        }
        if ($invoice->invoiceable_type == StudentTutoringPackage::class) {
            $studentTutoringPackage = StudentTutoringPackage::findOrFail($invoice->invoiceable_id);
            $payable_amount = cleanAmountWithCurrencyFormat(getPriceFromHoursAndHourlyWithDiscount($studentTutoringPackage->hourly_rate, $studentTutoringPackage->hours, $studentTutoringPackage->discount, $studentTutoringPackage->discount_type));

            if (($amountPaidInLastSession + $invoice->paid_amount) == $payable_amount) {
                $paymentStatus = Payment::PAID;
            } else {
                $paymentStatus = Payment::PARTIAL_PAYMENT;
            }

        }

        $paymentTransactionData = [
            'invoice_id' => $invoiceId,
            'payment_gateway_id' => 1,
            'transaction_id' => $stripeID,
            'amount' => $amountPaidInLastSession,
            'paid_by_id' => $userId,
            'paid_by_modal' => getAuthModelFromGuard($authGuard),
            'payment_intent' => $sessionData['payment_intent'],
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
                        'updated_at' => Carbon::now(),
                    ]
                );

            DB::commit();

        } catch (Exception $e) {
            report($e);
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        $admins = User::whereHasRole(['super-admin'])->get(['email']);
        $admins = $admins->pluck('email')->toArray();
        try {
            Mail::to($admins)->send(new ClientMakePaymentMail($paymentTransactionData));
        } catch (Exception $e) {
            report($e);
        }

        return $sessionData;
    }

    public function stripeRefundPayment(array $refundAmountData)
    {
        DB::beginTransaction();
        try {
            $refundAmountData = $refundAmountData['data']['object'];
            $payment = Payment::where('payment_intent',$refundAmountData['payment_intent'])->firstOrFail();
            $RefundedPayment = $payment->replicate();
            $RefundedPayment->amount = 0;
            $RefundedPayment->amount_refunded = $refundAmountData['amount_refunded']/100;
            $RefundedPayment->save();
            Invoice::where('id',$payment->invoice_id)->update(['paid_status'=>Invoice::PARTIAL_PAYMENT]);
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
            report($e);
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

    }

    public function stripeSubscriptionPaymentSuccessfullyCompleted(array $sessionData)
    {
        \Log::channel('stripe_success')->info('stripeSubscriptionPaymentSuccessfullyCompleted', $sessionData);
        setStripeApiKey();
        $clientReference = $sessionData['client_reference_id'];
        $clientReference = explode('-', $clientReference);
        $invoiceType = $clientReference[0];
        $monthlyInvoiceId = $clientReference[1];
        $userId = $clientReference[2];
        $authGuard = $clientReference[3];
        $monthlyInvoiceSubscription = MonthlyInvoiceSubscription::where('monthly_invoice_package_id',$monthlyInvoiceId)->firstOrFail();
        if ($monthlyInvoiceSubscription->payment_gateway == 'stripe' ) {
            $monthlyInvoiceSubscription->subscription_id = $sessionData['subscription'];
            $monthlyInvoiceSubscription->save();
        }
    }
}
