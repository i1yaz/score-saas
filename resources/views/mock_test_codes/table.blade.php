<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="mock-test-codes-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Test Type</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mockTestCodes as $mockTestCode)
                <tr>
                    <td>{{ $mockTestCode->name }}</td>
                    <td>{{ $mockTestCode->test_type }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['mock-test-codes.destroy', $mockTestCode->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('mock_test_code-show')
                            <a href="{{ route('mock-test-codes.show', [$mockTestCode->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('mock_test_code-edit')
                            <a href="{{ route('mock-test-codes.edit', [$mockTestCode->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('mock_test_code-destroy')
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
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
            @include('adminlte-templates::common.paginate', ['records' => $mockTestCodes])
        </div>
    </div>
</div>
