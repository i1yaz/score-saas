<!-- Date Format Field -->
<div class="form-group col-sm-6">
    {!! Form::label('system_date_format', 'Date Format:') !!}
    {!! Form::select('system_date_format', getDateFormats(),$settings->system_date_format, ['class' => 'form-control']) !!}
</div>
<!-- System Renewal Grace Period Field -->
<div class="form-group col-sm-6">
    {!! Form::label('system_renewal_grace_period', 'System Renewal Grace Period:') !!}
    {!! Form::text('system_renewal_grace_period', $settings->system_renewal_grace_period, ['class' => 'form-control']) !!}
</div>
