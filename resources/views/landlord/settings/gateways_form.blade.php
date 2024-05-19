<div class="form-group col-sm-6">
    {!! Form::label('gateways_default_product_name', 'Default Product Name:') !!}
    {!! Form::text('gateways_default_product_name', $settings->gateways_default_product_name, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('gateways_default_product_description', 'Default Product Description:') !!}
    {!! Form::text('gateways_default_product_description', $settings->gateways_default_product_description, ['class' => 'form-control']) !!}
</div>
