<!-- User Id Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('user_id', 'Family Code:') !!}
    <p>{{ $parent->family_code }}</p>
</div>

<!-- First Name Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('first_name', 'First Name:') !!}
    <p>{{ $parent->first_name }}</p>
</div>

<!-- Last Name Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{{ $parent->last_name }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('status', 'Status:') !!}
    <p>@include('partials.status_badge',['status' => $parent->status, 'text_success' => 'Active', 'text_danger' => 'Inactive'])</p>
</div>

<!-- Phone Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('phone', 'Phone:') !!}
    <p>{{ $parent->phone }}</p>
</div>

<!-- Address Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('address', 'Address:') !!}
    <p>{{ $parent->address }}</p>
</div>

<!-- Address2 Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('address2', 'Address2:') !!}
    <p>{{ $parent->address2 }}</p>
</div>

<!-- Phone Alternate Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('phone_alternate', 'Phone Alternate:') !!}
    <p>{{ $parent->phone_alternate }}</p>
</div>

<!-- Referral Source Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('referral_source', 'Referral Source:') !!}
    <p>{{ $parent->referral_source }}</p>
</div>


<!-- Referral From Positive Experience With Tutor Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('referral_from_positive_experience_with_tutor', 'Referral From Positive Experience With Tutor:') !!}
    <p>{{ booleanToYesNo($parent->referral_from_positive_experience_with_tutor) }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $parent->created_at }}</p>
</div>

