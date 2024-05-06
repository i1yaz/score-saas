<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $mockTest->id }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $mockTest->date }}</p>
</div>

<!-- Location Id Field -->
<div class="col-sm-12">
    {!! Form::label('location_id', 'Location Id:') !!}
    <p>{{ $mockTest->location_id }}</p>
</div>

<!-- Proctor Id Field -->
<div class="col-sm-12">
    {!! Form::label('proctor_id', 'Proctor Id:') !!}
    <p>{{ $mockTest->proctor_id }}</p>
</div>

<!-- Start Time Field -->
<div class="col-sm-12">
    {!! Form::label('start_time', 'Start Time:') !!}
    <p>{{ $mockTest->start_time }}</p>
</div>

<!-- End Time Field -->
<div class="col-sm-12">
    {!! Form::label('end_time', 'End Time:') !!}
    <p>{{ $mockTest->end_time }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $mockTest->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $mockTest->updated_at }}</p>
</div>

@dd($mockTest->students)

@if($mockTest->students->isNotEmpty())
    <div class="col-sm-12">
        <div class="card card-gray">
            <div class="card-header">
                <h5>Sessions</h5>
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
                        <th>Proctor's Notes</th>
                        <th>Attendance</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($studentTutoringPackage->students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->email }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endif
