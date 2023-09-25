<!-- Name Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $school->name }}</p>
</div>

<!-- Address Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('address', 'Address:') !!}
    <p>{{ $school->address }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $school->created_at }}</p>
</div>
