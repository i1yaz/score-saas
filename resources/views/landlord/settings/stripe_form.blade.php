<div class="form-group col-sm-12">
    {!! Form::label('stripe_public_key', 'Public/Publishable Key:') !!}
    {!! Form::text('stripe_public_key', $settings->stripe_public_key, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('stripe_secret_key', 'Secret Key:') !!}
    {!! Form::text('stripe_secret_key', $settings->stripe_secret_key, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('stripe_webhooks_key', 'Webhook Signing Key:') !!}
    {!! Form::text('stripe_webhooks_key', $settings->stripe_webhooks_key, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('stripe_display_name', 'Display Name:') !!}
    {!! Form::text('stripe_display_name', $settings->stripe_display_name, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('stripe_webhooks_url', 'Webhook URL:') !!}
    {!! Form::text('stripe_webhooks_url', route('landlord.admin-stripe-webhooks'), ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('stripe_status', 'Enable:') !!}
    {!! Form::checkbox('stripe_status', $settings->stripe_status=='enabled'?'on':null , $settings->stripe_status=='enabled') !!}
</div>
