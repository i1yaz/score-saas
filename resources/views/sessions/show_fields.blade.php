<div class="col-sm-6">
    {!! Form::label('student-name', 'Student Name:') !!}
    <p>{{ $session->student_name }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('scheduled-date', 'Schedule Date:') !!}
    <p>{{ $session->scheduled_date }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('start-time', 'Start Time:') !!}
    <p>{{ $session->start_time }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('end-time', 'End Time:') !!}
    <p>{{ $session->end_time }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('location', 'Location:') !!}
    <p>{{ $session->tutoring_location_name }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('session-completion-code', 'Session Completion Code:') !!}
    <p>{{ $session->completion_code }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('student-parent-session-notes', 'Student/parent Session notes:') !!}
    <p>{{ $session->student_parent_session_notes }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('homework', 'Homework:') !!}
    <p>{{ $session->homework }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('internal-notes', 'Internal Notes:') !!}
    <p>{{ $session->internal_notes }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('percent-homework-completed', '80% Homework Completed:') !!}
    <p>{{ $session->percent_homework_completed_80 }}</p>
</div>

