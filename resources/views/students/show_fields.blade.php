<!-- User Id Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $student->user_id }}</p>
</div>

<!-- School Id Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('school_id', 'School Id:') !!}
    <p>{{ $student->school_name }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $student->student_email }}</p>
</div>

<!-- First Name Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('first_name', 'First Name:') !!}
    <p>{{ $student->first_name }}</p>
</div>

<!-- Last Name Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{{ $student->last_name }}</p>
</div>



<!-- Testing Accommodation Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('testing_accommodation', 'Testing Accommodation:') !!}
    <p>{{ $student->testing_accommodation }}</p>
</div>

<!-- Testing Accommodation Nature Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('testing_accommodation_nature', 'Testing Accommodation Nature:') !!}
    <p>{{ $student->testing_accommodation_nature }}</p>
</div>

<!-- Official Baseline Act Score Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('official_baseline_act_score', 'Official Baseline Act Score:') !!}
    <p>{{ $student->official_baseline_act_score }}</p>
</div>

<!-- Official Baseline Sat Score Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('official_baseline_sat_score', 'Official Baseline Sat Score:') !!}
    <p>{{ $student->official_baseline_sat_score }}</p>
</div>

<!-- Test Anxiety Challenge Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('test_anxiety_challenge', 'Test Anxiety Challenge:') !!}
    <p>{{ booleanToYesNo($student->test_anxiety_challenge) }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12 col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $student->student_created_at }}</p>
</div>
