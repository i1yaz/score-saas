<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="mock-tests-table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Location Id</th>
                <th>Proctor Id</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mockTests as $mockTest)
                <tr>
                    <td>{{ $mockTest->date }}</td>
                    <td>{{ $mockTest->location_id }}</td>
                    <td>{{ $mockTest->proctor_id }}</td>
                    <td>{{ $mockTest->start_time }}</td>
                    <td>{{ $mockTest->end_time }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['mock-tests.destroy', $mockTest->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('mock_test-show')
                            <a href="{{ route('mock-tests.show', [$mockTest->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('mock_test-edit')
                            <a href="{{ route('mock-tests.edit', [$mockTest->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('mock_test-destroy')
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endpermission
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
            @include('adminlte-templates::common.paginate', ['records' => $mockTests])
        </div>
    </div>
</div>
