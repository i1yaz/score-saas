<!-- Date Format Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_from_address', 'Email From Address:') !!}
    {!! Form::text('email_from_address',$settings->email_from_address, ['class' => 'form-control']) !!}
</div>
<!-- System Renewal Grace Period Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_from_name', 'From Name:') !!}
    {!! Form::text('email_from_name', $settings->email_from_name, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('email_server_type', 'Email Delivery:') !!}
    {!! Form::select('email_server_type',['sendmail'=>'sendmail','smtp'=>'smtp'] ,$settings->email_server_type, ['class' => 'form-control']) !!}
</div>
