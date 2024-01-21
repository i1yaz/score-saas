<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class ClientMakePaymentMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public mixed $invoice_id;

    public mixed $payment_gateway;

    public mixed $transaction_id;

    public mixed $amount;

    public function __construct(array $invoiceData)
    {
        $this->invoice_id = $invoiceData['invoice_id'];
        $this->payment_gateway = $invoiceData['payment_gateway_id'] == 1 ? 'Stripe' : $invoiceData['payment_gateway_id'];
        $this->transaction_id = $invoiceData['transaction_id'];
        $this->amount = $invoiceData['amount'];
    }
}
