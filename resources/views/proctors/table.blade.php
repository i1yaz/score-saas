<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="proctors-table">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($proctors as $proctor)
                <tr>
                    <td>{{ $proctor->first_name }}</td>
                    <td>{{ $proctor->last_name }}</td>
                    <td>{{ $proctor->email }}</td>
                    <td>{{ $proctor->phone }}</td>
                    <td>{!! booleanToActiveCheck($proctor->status) !!}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['proctors.destroy', $proctor->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('proctor-show')
                            <a href="{{ route('proctors.show', [$proctor->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('proctor-edit')
                            <a href="{{ route('proctors.edit', [$proctor->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('proctor-destroy')
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
            @include('adminlte-templates::common.paginate', ['records' => $proctors])
        </div>
    </div>
</div>
