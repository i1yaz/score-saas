<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $monthlyInvoicePackage->id }}</p>
</div>

<!-- Student Id Field -->
<div class="col-sm-12">
    {!! Form::label('student_id', 'Student Id:') !!}
    <p>{{ $monthlyInvoicePackage->student_id }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('notes', 'Notes:') !!}
    <p>{{ $monthlyInvoicePackage->notes }}</p>
</div>

<!-- Internal Notes Field -->
<div class="col-sm-12">
    {!! Form::label('internal_notes', 'Internal Notes:') !!}
    <p>{{ $monthlyInvoicePackage->internal_notes }}</p>
</div>

<!-- Start Date Field -->
<div class="col-sm-12">
    {!! Form::label('start_date', 'Start Date:') !!}
    <p>{{ $monthlyInvoicePackage->start_date }}</p>
</div>

<!-- Hourly Rate Field -->
<div class="col-sm-12">
    {!! Form::label('hourly_rate', 'Hourly Rate:') !!}
    <p>{{ $monthlyInvoicePackage->hourly_rate }}</p>
</div>

<!-- Tutor Houlry Rate Field -->
<div class="col-sm-12">
    {!! Form::label('tutor_hourly_rate', 'Tutor Houlry Rate:') !!}
    <p>{{ $monthlyInvoicePackage->tutor_hourly_rate }}</p>
</div>

<!-- Tutoring Location Id Field -->
<div class="col-sm-12">
    {!! Form::label('tutoring_location_id', 'Tutoring Location Id:') !!}
    <p>{{ $monthlyInvoicePackage->tutoring_location_id }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $monthlyInvoicePackage->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $monthlyInvoicePackage->updated_at }}</p>
</div>

