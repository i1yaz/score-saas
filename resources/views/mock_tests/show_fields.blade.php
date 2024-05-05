<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $mockTest->id }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $mockTest->date }}</p>
</div>

<!-- Location Id Field -->
<div class="col-sm-12">
    {!! Form::label('location_id', 'Location Id:') !!}
    <p>{{ $mockTest->location_id }}</p>
</div>

<!-- Proctor Id Field -->
<div class="col-sm-12">
    {!! Form::label('proctor_id', 'Proctor Id:') !!}
    <p>{{ $mockTest->proctor_id }}</p>
</div>

<!-- Start Time Field -->
<div class="col-sm-12">
    {!! Form::label('start_time', 'Start Time:') !!}
    <p>{{ $mockTest->start_time }}</p>
</div>

<!-- End Time Field -->
<div class="col-sm-12">
    {!! Form::label('end_time', 'End Time:') !!}
    <p>{{ $mockTest->end_time }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $mockTest->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $mockTest->updated_at }}</p>
</div>

