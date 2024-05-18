<div class="form-group col-sm-6">
    {!! Form::label('email_smtp_host', 'SMTP Host:') !!}
    {!! Form::text('email_smtp_host', $settings->email_smtp_host, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('email_smtp_port', 'SMTP Port:') !!}
    {!! Form::text('email_smtp_port', $settings->email_smtp_port, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('email_smtp_username', 'SMTP Username:') !!}
    {!! Form::text('email_smtp_username', $settings->email_smtp_username, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('email_smtp_password', 'SMTP Password:') !!}
    {!! Form::text('email_smtp_password', $settings->email_smtp_password, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('email_smtp_encryption', 'Encryption:') !!}
    {!! Form::select('email_smtp_encryption', ['' => 'None','tls'=>'TLS','starttls'=>'STARTTLS','ssl'=>'SSL'],$settings->email_smtp_encryption, ['class' => 'form-control']) !!}
</div>
