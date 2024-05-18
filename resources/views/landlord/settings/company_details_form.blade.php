<div class="form-group col-sm-6">
    {!! Form::label('company_name', 'Company Name:') !!}
    {!! Form::text('company_name', $settings->company_name, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('company_address_line_1', 'Address:') !!}
    {!! Form::text('company_address_line_1', $settings->company_address_line_1, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('company_city', 'City:') !!}
    {!! Form::text('company_city', $settings->company_city, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('company_state', 'State:') !!}
    {!! Form::text('company_state', $settings->company_state, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('company_zipcode', 'Zip Code:') !!}
    {!! Form::text('company_zipcode', $settings->company_zipcode, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('company_country', 'Country:') !!}
    {!! Form::text('company_country', $settings->company_country, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('company_telephone', 'Telephone:') !!}
    {!! Form::text('company_telephone', $settings->company_telephone, ['class' => 'form-control']) !!}
</div>
