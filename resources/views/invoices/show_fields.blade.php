<!-- Id Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('id', 'Invoice ID:') !!}
    <p>{{getInvoiceCodeFromId($invoice->invoice_id)}}</p>
</div>

<!-- Invoice Package Type Id Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('invoice_package_type', 'Tutoring Package Type:') !!}
    <p>{{$invoice->tutoring_package_type_name}}</p>
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

<!-- Email To Parent Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('email_to_parent', 'Email To Parent:') !!}
    <p>{{ $invoice->parent_email }}</p>
</div>

<!-- Email To Student Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('email_to_student', 'Email To Student:') !!}
    <p>{{ $invoice->student_email }}</p>
</div>

<!-- Amount Paid Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('amount_paid', 'Amount Paid:') !!}
    <p>{{ formatAmountWithCurrency($invoice->amount_paid) }}</p>
</div>
@php
    $invoice_total = getPriceFromHoursAndHourlyWithDiscount($invoice->hourly_rate,$invoice->hours,$invoice->discount,$invoice->discount_type);
@endphp
<!-- Amount Remaining Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('amount_remaining', 'Amount Remaining:') !!}
    <p>{{  getRemainingAmountFromTotalAndPaidAmount(total:cleanAmountWithCurrencyFormat( $invoice_total), paid: $invoice->amount_paid) }}</p>
</div>

<!-- Paid Status Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('paid_status', 'Paid Status:') !!}
    <p>{!! getInvoiceStatusFromId($invoice->invoice_status)  !!}</p>
</div>

<!-- Paid By Modal Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('paid_by_modal', 'Paid By:') !!}
    <p>{{ $invoice->paid_by_modal }}</p>
</div>


<!-- Invoiceable Type Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('invoiceable_type', 'Invoiceable Type:') !!}
    <p>{{ getInvoiceTypeFromClass($invoice->invoiceable_type) }}</p>
</div>


