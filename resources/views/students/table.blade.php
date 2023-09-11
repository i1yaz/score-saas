<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="students-table">
            <thead>
            <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Official Baseline Act Score</th>
                <th>Official Baseline Sat Score</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->first_name }}</td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->official_baseline_act_score }}</td>
                    <td>{{ $student->official_baseline_sat_score }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['students.destroy', $student->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('students.show', [$student->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', [$student->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $students])
        </div>
    </div>
</div>
