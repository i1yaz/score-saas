<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="student-tutoring-packages-table">
            <thead>
            <tr>
                <th>Student Id</th>
                <th>Package Type Id</th>
                <th>Tutor Id</th>
                <th>Notes</th>
                <th>Internal Noted</th>
                <th>Hours</th>
                <th>Hourly Rate</th>
                <th>Tutoring Location Id</th>
                <th>Discount</th>
                <th>Discount Type</th>
                <th>Start Date</th>
                <th>Tutor Hourly Rate</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($studentTutoringPackages as $studentTutoringPackage)
                <tr>
                    <td>{{ $studentTutoringPackage->student_id }}</td>
                    <td>{{ $studentTutoringPackage->package_type_id }}</td>
                    <td>{{ $studentTutoringPackage->tutor_id }}</td>
                    <td>{{ $studentTutoringPackage->notes }}</td>
                    <td>{{ $studentTutoringPackage->internal_noted }}</td>
                    <td>{{ $studentTutoringPackage->hours }}</td>
                    <td>{{ $studentTutoringPackage->hourly_rate }}</td>
                    <td>{{ $studentTutoringPackage->tutoring_location_id }}</td>
                    <td>{{ $studentTutoringPackage->discount }}</td>
                    <td>{{ $studentTutoringPackage->discount_type }}</td>
                    <td>{{ $studentTutoringPackage->start_date }}</td>
                    <td>{{ $studentTutoringPackage->tutor_hourly_rate }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['student-tutoring-packages.destroy', $studentTutoringPackage->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('student-tutoring-packages.show', [$studentTutoringPackage->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('student-tutoring-packages.edit', [$studentTutoringPackage->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $studentTutoringPackages])
        </div>
    </div>
</div>
