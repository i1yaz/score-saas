
<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:', ['class' => 'required']) !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:', ['class' => 'required']) !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:', ['class' => 'required']) !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Secondary Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('secondary_email', 'Secondary Email:') !!}
    {!! Form::email('secondary_email', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Secondary Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('secondary_phone', 'Secondary Phone:') !!}
    {!! Form::text('secondary_phone', null, ['class' => 'form-control']) !!}
</div>

@if(Auth::user()->hasRole(['admin','super-admin']))
    <!-- Secondary Hourly Rate Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('hourly_rate', 'Hourly Rate:', ['class' => 'required']) !!}
        {!! Form::number('hourly_rate', null, ['class' => 'form-control','min'=>0]) !!}
    </div>
@endif

<!-- Picture Field -->
<div class="form-group col-sm-6">
    {!! Form::label('picture', 'Picture:') !!}
    <div class="input-group">
        <div class="custom-file">
            {!! Form::file('picture', ['class' => 'custom-file-input']) !!}
            {!! Form::label('picture', 'Choose file', ['class' => 'custom-file-label']) !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>

<!-- Resume Field -->
<div class="form-group col-sm-6">
    {!! Form::label('resume', 'Resume:') !!}
    <div class="input-group">
        <div class="custom-file">
            {!! Form::file('resume', ['class' => 'custom-file-input']) !!}
            {!! Form::label('resume', 'Choose file', ['class' => 'custom-file-label']) !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>

<!-- Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_date', 'Start Date:') !!}
    {!! Form::text('start_date', null, ['class' => 'form-control','id'=>'start_date']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', ['yes' =>'YES','no'=>'NO'],booleanSelect($tutor->status??null), ['class' => 'form-control custom-select'])  !!}
</div>

