<?php

namespace App\Repositories;

use App\Mail\ClientMakePaymentMail;
use App\Models\Invoice;
use App\Models\MonthlyInvoicePackage;
use App\Models\NonInvoicePackage;
use App\Models\Payment;
use App\Models\StudentTutoringPackage;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
        'transaction_id',
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
        if (empty($sessionData)) {
            throw new UnprocessableEntityHttpException('Session not found');
        }
        Log::channel('stripe_success')->info('Stripe session',json_decode(json_encode($sessionData ,true),true));
        $stripeID = $sessionData->id;
        $amountPaidInLastSession = $sessionData->amount_total / 100;
        $reference = $sessionData->client_reference_id;
        $reference = explode('-', $reference);
        $invoiceId = $reference[0];
        $userId = $reference[1];
        $authGuard = $reference[2];

        $invoice = Invoice::query()
            ->select(['invoices.id as invoice_id', 'invoiceable_type', 'invoiceable_id'])
            ->selectRaw('SUM(CASE WHEN payments.status = 1 THEN payments.amount ELSE 0 END) AS amount_paid')
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

    /**
     * Paginate records for scaffold.
     */
    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator
    {
        $payments = Payment::query()
            ->select([
                'payments.id',
                'payments.invoice_id',
                'payments.amount',
                'payments.payment_gateway_id',
                'payments.status',
                'payments.created_at',
                'invoices.paid_status',
                'invoices.fully_paid_at',
                'invoices.invoiceable_id',
                'invoices.invoiceable_type',

            ])
            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('student_tutoring_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
            })
            ->leftJoin('monthly_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'monthly_invoice_packages.id')->where('invoices.invoiceable_type', '=', MonthlyInvoicePackage::class);
            })
            ->leftJoin('non_invoice_packages', function ($q) {
                $q->on('invoices.invoiceable_id', '=', 'non_invoice_packages.id')->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
            })
            ->leftJoin('clients', 'non_invoice_packages.client_id', '=', 'clients.id')
            ->leftJoin('students as s1', 'student_tutoring_packages.student_id', '=', 's1.id')
            ->leftJoin('parents as p1', 's1.parent_id', '=', 'p1.id')
            ->leftJoin('students as s2', 'monthly_invoice_packages.student_id', '=', 's2.id')
            ->leftJoin('parents as p2', 's2.parent_id', '=', 'p2.id');
        if (Auth::user()->hasRole('client')) {
            $payments = $payments->where('non_invoice_packages.client_id', Auth::id());
        }
        if (Auth::user()->hasRole('student')) {
            $payments = $payments->where(function ($q) {
                $q->where('student_tutoring_packages.student_id', Auth::id())
                    ->orWhere('monthly_invoice_packages.student_id', Auth::id());
            });
        }
        if (Auth::user()->hasRole('parent')) {
            $payments = $payments->where(function ($q) {
                $q->where('s1.parent_id', Auth::id())
                    ->orWhere('s2.parent_id', Auth::id());
            });
        }

        return $payments->paginate($perPage, $columns);
    }
}
