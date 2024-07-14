<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\MailTemplate;
use Illuminate\Console\Command;
use App\Models\NonInvoicePackage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\StudentTutoringPackage;
use App\Mail\SendInvoicePaymentReminderMail;

class InvoicePaymentReminderCommand extends Command
{
    protected $signature = 'invoice:payment-reminder';

    protected $description = 'This Command will send Reminders Emails to daily if the due date is in one week.';

    public function handle(): void
    {
        $template = MailTemplate::where('mailable',SendInvoicePaymentReminderMail::class)->firstOrFail();
        if ($template->status === true) {
            $now = Carbon::now();
            $invoices = Invoice::select(
                [
                    'invoices.invoiceable_type',
                    'invoices.due_date',
                    'invoices.price',
                    'students.email as student_email',
                    'students.first_name as student_first_name',
                    'students.last_name as student_last_name',
                    'parents.email as parent_email',
                    'parents.first_name as parent_first_name',
                    'parents.last_name as parent_last_name',
                    'clients.email as client_email',
                    'clients.first_name as client_first_name',
                    'clients.last_name as client_last_name',
                ])
                ->leftJoin('student_tutoring_packages', function ($q) {
                    $q->on('invoices.invoiceable_id', '=', 'student_tutoring_packages.id')
                        ->where('invoices.invoiceable_type', '=', StudentTutoringPackage::class);
                })
                ->leftJoin('students', 'student_tutoring_packages.student_id', '=', 'students.id')
                ->leftJoin('parents', 'students.parent_id', '=', 'parents.id')
                ->leftJoin('non_invoice_packages', function ($q) {
                    $q->on('invoices.invoiceable_id', '=', 'non_invoice_packages.id')
                        ->where('invoices.invoiceable_type', '=', NonInvoicePackage::class);
                })
                ->leftJoin('clients', 'non_invoice_packages.client_id', '=', 'clients.id')
                ->whereBetween('due_date', [$now->toDateTimeString(), $now->clone()->addWeek()->toDateTimeString()])
                ->whereIn('paid_status', [Invoice::PENDING])
                ->where('has_installments', false)
                ->whereIn('invoiceable_type', [StudentTutoringPackage::class, NonInvoicePackage::class])
                ->get();

            foreach ($invoices as $invoice) {
                $data = [
                    'invoiceable_type' => $invoice->invoiceable_type,
                    'due_date' => $invoice->due_date,
                    'price' => $invoice->price,
                    'student_email' => $invoice->student_email,
                    'student_first_name' => $invoice->student_first_name,
                    'student_last_name' => $invoice->student_last_name,
                    'parent_email' => $invoice->parent_email,
                    'parent_first_name' => $invoice->parent_first_name,
                    'parent_last_name' => $invoice->parent_last_name,
                    'client_email' => $invoice->client_email,
                    'client_first_name' => $invoice->client_first_name,
                    'client_last_name' => $invoice->client_last_name,
                ];

                Mail::to($invoice->student_email)->send(new SendInvoicePaymentReminderMail($data));

            }
        }
        Log::info('InvoicePaymentReminderCommand: Ran successfully');
    }
}
