<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:',['class' => 'required']) !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:',['class' => 'required']) !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>
<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

{{--<!-- password Field -->--}}
{{--<div class="form-group col-sm-6">--}}
{{--    {!! Form::label('password', 'Password:',['class' => 'required']) !!}--}}
{{--    {!! Form::password('password', ['class' => 'form-control']) !!}--}}
{{--</div>--}}
{{--<!-- password Field -->--}}
{{--<div class="form-group col-sm-6">--}}
{{--    {!! Form::label('confirm_password', 'Confirm Password:',['class' => 'required']) !!}--}}
{{--    {!! Form::password('confirm_password', ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', ['yes' => 'Activate','no'=>'Deactivate'],null ,['class' => 'form-control custom-select']) !!}
</div>
