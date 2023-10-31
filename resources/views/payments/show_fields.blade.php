<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $payment->id }}</p>
</div>

<!-- Invoice Id Field -->
<div class="col-sm-12">
    {!! Form::label('invoice_id', 'Invoice Id:') !!}
    <p>{{ $payment->invoice_id }}</p>
</div>

<!-- Amount Field -->
<div class="col-sm-12">
    {!! Form::label('amount', 'Amount:') !!}
    <p>{{ $payment->amount }}</p>
</div>

<!-- Payment Gateway Field -->
<div class="col-sm-12">
    {!! Form::label('payment_gateway', 'Payment Gateway:') !!}
    <p>{{ $payment->payment_gateway }}</p>
</div>

<!-- Transaction Id Field -->
<div class="col-sm-12">
    {!! Form::label('transaction_id', 'Transaction Id:') !!}
    <p>{{ $payment->transaction_id }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $payment->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $payment->updated_at }}</p>
</div>

