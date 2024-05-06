<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:',['class' => 'required']) !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:',['class' => 'required']) !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>
<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:',['class' => 'required']) !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Address2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address2', 'Address2:') !!}
    {!! Form::text('address2', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Alternate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone_alternate', 'Phone Alternate:') !!}
    {!! Form::text('phone_alternate', null, ['class' => 'form-control']) !!}
</div>

<!-- Referral Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('referral_source', 'Referral Source:') !!}
    {!! Form::text('referral_source', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', ['yes' =>'YES','no'=>'NO'],booleanSelect($parent->status??null), ['class' => 'form-control custom-select'])  !!}
</div>
<!-- Referral From Positive Experience With Tutor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('referral_from_positive_experience_with_tutor', 'Referral From Positive Experience With Tutor:') !!}
    {!! Form::select('referral_from_positive_experience_with_tutor', ['yes' =>'YES','no'=>'NO'],booleanSelect($parent->referral_from_positive_experience_with_tutor??null), ['class' => 'form-control custom-select'])  !!}
</div>
@push('after_third_party_scripts')
    <script type="text/javascript">
        ajaxSubmit = false;
    </script>
@endpush
