<!-- Id Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('id', 'Invoice ID:') !!}
    <p>{{getInvoiceCodeFromId($invoice->invoice_id)}}</p>
</div>

<!-- Invoiceable Type Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('package_type', 'Package Type:') !!}
    <p>{{ getInvoiceTypeFromClass($invoice->invoiceable_type) }}</p>
</div>
<!-- Due Date Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('due_date', 'Due Date:') !!}
    <p>{{ formatDate($invoice->due_date) }}</p>
</div>

<!-- Fully Paid At Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('fully_paid_at', 'Fully Paid At:') !!}
    <p>{{ formatDate($invoice->fully_paid_at) }}</p>
</div>

<!-- General Description Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('general_description', 'General Description:') !!}
    <p>{{ $invoice->general_description }}</p>
</div>

<!-- Detailed Description Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('detailed_description', 'Detailed Description:') !!}
    <p>{{ $invoice->detailed_description }}</p>
</div>


<!-- Amount Paid Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('amount_paid', 'Amount Paid:') !!}
    <p>{{ formatAmountWithCurrency($invoice->amount_paid) }}</p>
</div>
@if($invoice->invoiceable_type == \App\Models\NonInvoicePackage::class)
    <!-- Client EMail -->
    <div class="col-sm-12 col-md-6">
        {!! Form::label('paid_status', 'Client Email:') !!}
        <p>{!! $invoice->client_email  !!}</p>
    </div>
@endif

<!-- Paid Status Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('paid_status', 'Paid Status:') !!}
    <p>{!! getInvoiceStatusFromId($invoice->invoice_status,$invoice->invoiceable_type,$invoice->subscription_status,$invoice->subscription_id,$invoice->start_date)  !!}</p>
</div>

@if($invoice->invoiceable_type===\App\Models\StudentTutoringPackage::class && $invoice->has_installments==true)
    @include('invoices.show_installments')

@endif




