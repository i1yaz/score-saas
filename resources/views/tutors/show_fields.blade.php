<!-- First Name Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('first_name', 'First Name:') !!}
    <p>{{ $tutor->first_name }}</p>
</div>

<!-- Last Name Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{{ $tutor->last_name }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $tutor->email }}</p>
</div>

<!-- Secondary Email Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('secondary_email', 'Secondary Email:') !!}
    <p>{{ $tutor->secondary_email }}</p>
</div>

<!-- Phone Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('phone', 'Phone:') !!}
    <p>{{ $tutor->phone }}</p>
</div>

<!-- Secondary Phone Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('secondary_phone', 'Secondary Phone:') !!}
    <p>{{ $tutor->secondary_phone }}</p>
</div>


<!-- Start Date Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('start_date', 'Start Date:') !!}
    <p>{{ $tutor->start_date }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $tutor->created_at }}</p>
</div>

