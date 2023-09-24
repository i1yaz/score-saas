<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $invoice->id }}</p>
</div>

<!-- Invoice Package Type Id Field -->
<div class="col-sm-12">
    {!! Form::label('invoice_package_type_id', 'Invoice Package Type Id:') !!}
    <p>{{ $invoice->invoice_package_type_id }}</p>
</div>

<!-- Due Date Field -->
<div class="col-sm-12">
    {!! Form::label('due_date', 'Due Date:') !!}
    <p>{{ $invoice->due_date }}</p>
</div>

<!-- Fully Paid At Field -->
<div class="col-sm-12">
    {!! Form::label('fully_paid_at', 'Fully Paid At:') !!}
    <p>{{ $invoice->fully_paid_at }}</p>
</div>

<!-- General Description Field -->
<div class="col-sm-12">
    {!! Form::label('general_description', 'General Description:') !!}
    <p>{{ $invoice->general_description }}</p>
</div>

<!-- Detailed Description Field -->
<div class="col-sm-12">
    {!! Form::label('detailed_description', 'Detailed Description:') !!}
    <p>{{ $invoice->detailed_description }}</p>
</div>

<!-- Email To Parent Field -->
<div class="col-sm-12">
    {!! Form::label('email_to_parent', 'Email To Parent:') !!}
    <p>{{ $invoice->email_to_parent }}</p>
</div>

<!-- Email To Student Field -->
<div class="col-sm-12">
    {!! Form::label('email_to_student', 'Email To Student:') !!}
    <p>{{ $invoice->email_to_student }}</p>
</div>

<!-- Amount Paid Field -->
<div class="col-sm-12">
    {!! Form::label('amount_paid', 'Amount Paid:') !!}
    <p>{{ $invoice->amount_paid }}</p>
</div>

<!-- Amount Remaining Field -->
<div class="col-sm-12">
    {!! Form::label('amount_remaining', 'Amount Remaining:') !!}
    <p>{{ $invoice->amount_remaining }}</p>
</div>

<!-- Paid Status Field -->
<div class="col-sm-12">
    {!! Form::label('paid_status', 'Paid Status:') !!}
    <p>{{ $invoice->paid_status }}</p>
</div>

<!-- Paid By Modal Field -->
<div class="col-sm-12">
    {!! Form::label('paid_by_modal', 'Paid By Modal:') !!}
    <p>{{ $invoice->paid_by_modal }}</p>
</div>

<!-- Paid By Id Field -->
<div class="col-sm-12">
    {!! Form::label('paid_by_id', 'Paid By Id:') !!}
    <p>{{ $invoice->paid_by_id }}</p>
</div>

<!-- Invoiceable Type Field -->
<div class="col-sm-12">
    {!! Form::label('invoiceable_type', 'Invoiceable Type:') !!}
    <p>{{ $invoice->invoiceable_type }}</p>
</div>

<!-- Invoiceable Id Field -->
<div class="col-sm-12">
    {!! Form::label('invoiceable_id', 'Invoiceable Id:') !!}
    <p>{{ $invoice->invoiceable_id }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $invoice->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $invoice->updated_at }}</p>
</div>

