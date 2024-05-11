<!-- Id Field -->
<div class="col-sm-12 col-md-4">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $mockTestDetail->first()->mock_test_id }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12 col-md-4">
    {!! Form::label('date', 'Mock Test Date:') !!}
    <p>{{ $mockTestDetail->first()->date->toDateString() }}</p>
</div>

<!-- Location Id Field -->
<div class="col-sm-12 col-md-4">
    {!! Form::label('location_id', 'Location:') !!}
    <p>{{ $mockTestDetail->first()->location }}</p>
</div>

<!-- Proctor Id Field -->
<div class="col-sm-12 col-md-4">
    {!! Form::label('proctor_id', 'Proctor:') !!}
    <p>{{ $mockTestDetail->first()->proctor_id }}</p>
</div>

<!-- Start Time Field -->
<div class="col-sm-12 col-md-4">
    {!! Form::label('start_time', 'Start Time:') !!}
    <p>{{ $mockTestDetail->first()->start_time }}</p>
</div>

<!-- End Time Field -->
<div class="col-sm-12 col-md-4">
    {!! Form::label('end_time', 'End Time:') !!}
    <p>{{ $mockTestDetail->first()->end_time }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12 col-md-4">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $mockTestDetail->first()->test_created_at }}</p>
</div>

@if($mockTestDetail->isNotEmpty())
    <div class="col-sm-12">
        <div class="card card-gray">
            <div class="card-header">
                <h5>Students</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped" id="student-tutoring-packages-table">
                    <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student</th>
                        <th>Extra Time</th>
                        <th>Test Code Taken</th>
                        <th>Notes To Proctor</th>
                        <th>Attendance</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($mockTestDetail as $student)
                        @if($student->student_id === null)
                            @continue
                        @endif
                        <tr>
                            <td>{{ $student->student_id }}</td>
                            <td>
                                {{ $student->first_name }} {{ $student->last_name }}<br>
                                {{ $student->student_email }}
                            </td>
                            <td>{{ booleanToYesNo($student->extra_time) }}</td>
                            <td>{{ $student->mock_test_code }}</td>
                            <td>{{ $student->notes_to_proctor }}</td>
                            <td>{{ $student->signup_status }}</td>
                            <td>
                                @permission('mock_test-score')
                                <a href="{{ route('mock-test-add-score.get', ['mock_test' => $student->mock_test_id,'student_id' => $student->student_id]) }}"
                                   class='btn btn-default'>
                                    <i class="far fa-edit">Add Score</i>
                                </a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endif
