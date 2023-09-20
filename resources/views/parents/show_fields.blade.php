<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $parent->id }}</p>
</div>

<!-- User Id Field -->
<div class="col-sm-12">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $parent->user_id }}</p>
</div>

<!-- First Name Field -->
<div class="col-sm-12">
    {!! Form::label('first_name', 'First Name:') !!}
    <p>{{ $parent->first_name }}</p>
</div>

<!-- Last Name Field -->
<div class="col-sm-12">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{{ $parent->last_name }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $parent->status }}</p>
</div>

<!-- Phone Field -->
<div class="col-sm-12">
    {!! Form::label('phone', 'Phone:') !!}
    <p>{{ $parent->phone }}</p>
</div>

<!-- Address Field -->
<div class="col-sm-12">
    {!! Form::label('address', 'Address:') !!}
    <p>{{ $parent->address }}</p>
</div>

<!-- Address2 Field -->
<div class="col-sm-12">
    {!! Form::label('address2', 'Address2:') !!}
    <p>{{ $parent->address2 }}</p>
</div>

<!-- Phone Alternate Field -->
<div class="col-sm-12">
    {!! Form::label('phone_alternate', 'Phone Alternate:') !!}
    <p>{{ $parent->phone_alternate }}</p>
</div>

<!-- Referral Source Field -->
<div class="col-sm-12">
    {!! Form::label('referral_source', 'Referral Source:') !!}
    <p>{{ $parent->referral_source }}</p>
</div>


<!-- Referral From Positive Experience With Tutor Field -->
<div class="col-sm-12">
    {!! Form::label('referral_from_positive_experience_with_tutor', 'Referral From Positive Experience With Tutor:') !!}
    <p>{{ $parent->referral_from_positive_experience_with_tutor }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $parent->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $parent->updated_at }}</p>
</div>

